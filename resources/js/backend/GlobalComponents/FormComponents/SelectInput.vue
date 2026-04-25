<template>
  <div class="ss-wrapper" ref="wrapper" :class="{ 'ss-open': isOpen, 'ss-multiple': multiple }">

    <!-- ── Control box ─────────────────────────────────────────────── -->
    <div class="ss-control" :class="{ 'ss-control--open': isOpen }" @click="onControlClick">

      <!-- Tags (multiple mode) -->
      <span v-if="multiple" class="ss-tags">
        <span v-for="item in selected" :key="item.value" class="ss-tag">
          {{ item.label }}
          <button type="button" class="ss-tag-remove" @click.stop="deselect(item)">×</button>
        </span>
      </span>

      <!-- Single mode: selected label sits behind the search input -->
      <span v-if="!multiple && singleItem && !isOpen && !query" class="ss-single-label">
        {{ singleItem.label }}
      </span>

      <!-- Search / filter input -->
      <input
        ref="searchRef"
        v-model="query"
        class="ss-search"
        autocomplete="off"
        spellcheck="false"
        :placeholder="inputPlaceholder"
        @mousedown.stop
        @focus="onFocus"
        @input="onQueryInput"
        @keydown.delete="onBackspace"
        @keydown.escape.prevent="close"
        @keydown.enter.prevent="pickHighlighted"
        @keydown.up.prevent="moveHighlight(-1)"
        @keydown.down.prevent="moveHighlight(1)"
        @keydown.tab="close"
      />

      <span class="ss-icons">
        <button
          v-if="!multiple && singleItem"
          type="button"
          class="ss-btn-clear"
          @click.stop="clearSingle"
          title="Clear"
        >×</button>
        <span class="ss-chevron" :class="{ 'ss-chevron--up': isOpen }">
          <svg width="10" height="6" viewBox="0 0 10 6" fill="none">
            <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5"
                  stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </span>
    </div>

    <!-- ── Dropdown ────────────────────────────────────────────────── -->
    <div class="ss-dropdown" v-show="isOpen" ref="dropdownRef">
      <div v-if="filtered.length === 0" class="ss-empty">No results found</div>
      <div
        v-for="(opt, idx) in filtered"
        :key="opt.value"
        class="ss-option"
        :class="{
          'ss-option--selected'    : isSelected(opt),
          'ss-option--highlighted' : idx === highlighted,
        }"
        @click.stop="pick(opt)"
        @mouseenter="highlighted = idx"
      >
        <span v-if="multiple" class="ss-tick">
          <svg v-if="isSelected(opt)" width="11" height="9" viewBox="0 0 11 9" fill="none">
            <path d="M1 4.5L4 7.5L10 1" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
        <span class="ss-opt-label">{{ opt.label }}</span>
      </div>
    </div>

    <!-- ── Hidden inputs for form serialisation ───────────────────── -->
    <template v-if="multiple">
      <input v-if="selected.length === 0" type="hidden" :name="name" value="" />
      <input
        v-for="item in selected"
        :key="'h-' + item.value"
        type="hidden"
        :name="name + '[]'"
        :value="item.value"
      />
    </template>
    <template v-else>
      <input type="hidden" :name="name" :value="singleItem ? singleItem.value : ''" />
    </template>

  </div>
</template>

<script>
export default {
  name: 'SelectInput',
  props: {
    name:        { type: String,                  required: true  },
    multiple:    { type: [Boolean, String],        default: false  },
    value:       { type: [String, Number, Array],  default: null   },
    data_list:   { type: Array,                    default: () => [] },
    placeholder: { type: String,                   default: 'Search or select...' },
  },
  emits: ['change'],
  data() {
    return { isOpen: false, query: '', selected: [], highlighted: 0 };
  },
  computed: {
    singleItem() {
      return this.multiple ? null : (this.selected[0] ?? null);
    },
    inputPlaceholder() {
      if (this.multiple) return this.selected.length === 0 ? this.placeholder : '';
      // Always show placeholder when nothing is selected (open or closed)
      return !this.singleItem ? this.placeholder : '';
    },
    filtered() {
      const q = this.query.trim().toLowerCase();
      if (!q) return this.data_list;
      return this.data_list.filter(o =>
        String(o.label).toLowerCase().includes(q) ||
        String(o.value).toLowerCase().includes(q)
      );
    },
  },
  watch: {
    value:     { immediate: true, handler(v) { this.syncFromValue(v); } },
    data_list: {                  handler()  { this.syncFromValue(this.value); } },
  },
  mounted()       { document.addEventListener('click', this.onClickOutside); },
  beforeUnmount() { document.removeEventListener('click', this.onClickOutside); },
  methods: {
    syncFromValue(val) {
      if (val === null || val === undefined || val === '') { this.selected = []; return; }
      if (this.multiple) {
        const vals = Array.isArray(val) ? val.map(String) : String(val).split(',').map(v => v.trim());
        this.selected = this.data_list.filter(o => vals.includes(String(o.value)));
      } else {
        const found = this.data_list.find(o => String(o.value) === String(val));
        this.selected = found ? [found] : [];
      }
    },
    isSelected(opt) {
      return this.selected.some(s => String(s.value) === String(opt.value));
    },
    pick(opt) {
      if (this.multiple) {
        this.isSelected(opt) ? this.deselect(opt) : (this.selected = [...this.selected, opt]);
        this.query = '';
        this.$nextTick(() => this.$refs.searchRef?.focus());
      } else {
        this.selected = [opt]; this.query = ''; this.close();
      }
      this.notify();
    },
    deselect(opt) {
      this.selected = this.selected.filter(s => String(s.value) !== String(opt.value));
      this.notify();
    },
    clearSingle() { this.selected = []; this.query = ''; this.notify(); },
    pickHighlighted() { const opt = this.filtered[this.highlighted]; if (opt) this.pick(opt); },
    open() {
      if (this.isOpen) return;
      this.isOpen = true; this.highlighted = 0;
      this.$nextTick(() => { this.$refs.searchRef?.focus(); this.scrollHighlightedIntoView(); });
    },
    close() { if (!this.isOpen) return; this.isOpen = false; this.query = ''; },
    onControlClick(e) {
      // Input mousedown is stopped — only outer-div clicks reach here
      this.isOpen ? this.close() : this.open();
    },
    onFocus() { this.open(); },
    onQueryInput() { this.highlighted = 0; if (!this.isOpen) this.open(); },
    onBackspace() {
      if (this.query === '' && this.multiple && this.selected.length > 0)
        this.deselect(this.selected[this.selected.length - 1]);
    },
    onClickOutside(e) {
      if (this.$refs.wrapper && !this.$refs.wrapper.contains(e.target)) this.close();
    },
    moveHighlight(dir) {
      if (!this.isOpen) { this.open(); return; }
      const max = this.filtered.length - 1;
      this.highlighted = Math.max(0, Math.min(this.highlighted + dir, max));
      this.scrollHighlightedIntoView();
    },
    scrollHighlightedIntoView() {
      this.$nextTick(() => {
        const el = this.$refs.dropdownRef?.querySelectorAll('.ss-option')[this.highlighted];
        el?.scrollIntoView({ block: 'nearest' });
      });
    },
    notify() {
      const value = this.multiple
        ? this.selected.map(s => s.value)
        : (this.singleItem?.value ?? '');
      this.$emit('change', value);
    },
  },
};
</script>

<style scoped>
/* ── Root ──────────────────────────────────────────────────────────── */
.ss-wrapper {
  position: relative;
  width: 100%;
  font-size: 0.875rem;
}

/* ── Control ───────────────────────────────────────────────────────── */
.ss-control {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 4px;
  min-height: 38px;
  padding: 4px 36px 4px 10px;
  position: relative;
  cursor: pointer;
  user-select: none;
  border-radius: 4px;
  background: var(--bg-input);
  border: 1px solid var(--border-color);
  color: var(--text-primary);
  transition: border-color 0.15s, box-shadow 0.15s;
}
.ss-control:hover         { border-color: var(--border-dark); }
.ss-control--open         { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(59,130,246,.15); }

/* ── Search input ──────────────────────────────────────────────────── */
.ss-search {
  flex: 1 1 60px;
  min-width: 60px;
  border: none;
  outline: none;
  background: transparent;
  color: var(--text-primary);
  font-size: inherit;
  padding: 0;
  line-height: 1.5;
}
.ss-search::placeholder { color: var(--text-light); }

/* ── Single selected label ─────────────────────────────────────────── */
.ss-single-label {
  position: absolute;
  left: 10px; right: 36px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  pointer-events: none;
  color: var(--text-primary);
  line-height: 1.5;
}

/* ── Tags ──────────────────────────────────────────────────────────── */
.ss-tags { display: contents; }
.ss-tag {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  padding: 1px 6px 1px 8px;
  border-radius: 12px;
  background: color-mix(in srgb, var(--primary-color) 15%, transparent);
  border: 1px solid color-mix(in srgb, var(--primary-color) 40%, transparent);
  color: var(--primary-color);
  font-size: 0.78rem;
  line-height: 1.6;
  white-space: nowrap;
}
.ss-tag-remove {
  border: none; background: none; cursor: pointer;
  color: inherit; font-size: 1rem; line-height: 1; padding: 0; opacity: .7;
}
.ss-tag-remove:hover { opacity: 1; color: var(--danger-color); }

/* ── Icons area ────────────────────────────────────────────────────── */
.ss-icons {
  display: flex; align-items: center; gap: 4px;
  position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
}
.ss-btn-clear {
  border: none; background: none; color: var(--text-light);
  font-size: 1rem; line-height: 1; cursor: pointer; padding: 0;
  transition: color .15s;
}
.ss-btn-clear:hover { color: var(--danger-color); }
.ss-chevron { color: var(--text-light); display: flex; transition: transform .2s; }
.ss-chevron--up { transform: rotate(180deg); }

/* ── Dropdown ──────────────────────────────────────────────────────── */
.ss-dropdown {
  position: absolute;
  top: calc(100% + 4px); left: 0; right: 0;
  z-index: 9999;
  max-height: 220px;
  overflow-y: auto;
  border-radius: 4px;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  box-shadow: var(--shadow-lg);
  scrollbar-width: thin;
  scrollbar-color: var(--border-color) transparent;
}
.ss-dropdown::-webkit-scrollbar       { width: 5px; }
.ss-dropdown::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

/* ── Options ───────────────────────────────────────────────────────── */
.ss-option {
  display: flex; align-items: center; gap: 8px;
  padding: 7px 12px;
  cursor: pointer;
  color: var(--text-primary);
  transition: background .1s;
}
.ss-option--highlighted              { background: var(--bg-hover); }
.ss-option--selected                 { color: var(--primary-color); }
.ss-option--selected.ss-option--highlighted { background: var(--bg-hover); }
.ss-opt-label { flex: 1; }

/* ── Checkbox tick ─────────────────────────────────────────────────── */
.ss-tick {
  width: 15px; height: 15px; flex-shrink: 0;
  border: 1.5px solid var(--border-color);
  border-radius: 3px;
  display: flex; align-items: center; justify-content: center;
  transition: border-color .15s, background .15s;
}
.ss-option--selected .ss-tick {
  background: var(--primary-color);
  border-color: var(--primary-color);
  color: #fff;
}

/* ── Empty ─────────────────────────────────────────────────────────── */
.ss-empty { padding: 10px 14px; color: var(--text-light); font-size: .8rem; text-align: center; }

/* ── Validation error state ────────────────────────────────────────── */
.ss-wrapper.border-warning .ss-control { border-color: var(--warning-color); }
</style>
