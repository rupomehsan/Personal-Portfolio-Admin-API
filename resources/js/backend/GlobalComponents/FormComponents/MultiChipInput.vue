<template>
  <div class="mc-wrapper" :class="{ 'mc-focused': isFocused }">

    <!-- ── Chip box ────────────────────────────────────────────────── -->
    <div class="mc-box" @click="focusInput">
      <!-- Existing chips -->
      <span v-for="(chip, idx) in chips" :key="idx" class="mc-chip">
        <span class="mc-chip-label">{{ chip }}</span>
        <button type="button" class="mc-chip-remove" @click.stop="removeChip(idx)" title="Remove">×</button>
      </span>

      <!-- Text input -->
      <input
        ref="inputRef"
        v-model="draft"
        class="mc-input"
        autocomplete="off"
        spellcheck="false"
        :placeholder="chips.length === 0 ? placeholder : ''"
        @focus="isFocused = true"
        @blur="onBlur"
        @keydown.enter.prevent="commitDraft"
        @keydown.tab.prevent="commitDraft"
        @keydown="onKeydown"
        @paste="onPaste"
      />
    </div>

    <!-- ── Hint ────────────────────────────────────────────────────── -->
    <span class="mc-hint">Press <kbd>Enter</kbd> or <kbd>,</kbd> to add a tag</span>

    <!-- ── Hidden input for form serialisation ────────────────────── -->
    <input type="hidden" :name="name" :value="serialised" />

  </div>
</template>

<script>
export default {
  name: 'MultiChipInput',

  props: {
    name:        { type: String,               required: true  },
    value:       { type: [String, Array],       default: ''     },
    placeholder: { type: String,               default: 'Add a tag and press Enter...' },
    separator:   { type: String,               default: ','    },
  },

  emits: ['change'],

  data() {
    return {
      chips:    [],
      draft:    '',
      isFocused: false,
    };
  },

  computed: {
    serialised() {
      return this.chips.join(this.separator);
    },
  },

  watch: {
    value: {
      immediate: true,
      handler(val) {
        if (!val) { this.chips = []; return; }
        const raw = Array.isArray(val) ? val : String(val).split(',');
        this.chips = raw.map(v => v.trim()).filter(Boolean);
      },
    },
  },

  methods: {
    focusInput() {
      this.$refs.inputRef?.focus();
    },

    commitDraft() {
      const tag = this.draft.trim().replace(/,+$/, '');
      if (tag && !this.chips.includes(tag)) {
        this.chips = [...this.chips, tag];
        this.notify();
      }
      this.draft = '';
    },

    removeChip(idx) {
      this.chips = this.chips.filter((_, i) => i !== idx);
      this.notify();
    },

    onKeydown(e) {
      // Comma key → commit
      if (e.key === ',') {
        e.preventDefault();
        this.commitDraft();
        return;
      }
      // Backspace on empty draft → remove last chip
      if (e.key === 'Backspace' && this.draft === '' && this.chips.length > 0) {
        this.chips = this.chips.slice(0, -1);
        this.notify();
      }
    },

    onBlur() {
      // Commit any dangling draft when focus leaves
      if (this.draft.trim()) this.commitDraft();
      this.isFocused = false;
    },

    onPaste(e) {
      // Paste a comma-separated list → explode into chips
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData('text');
      const incoming = text.split(',').map(t => t.trim()).filter(Boolean);
      const merged = [...new Set([...this.chips, ...incoming])];
      this.chips = merged;
      this.draft = '';
      this.notify();
    },

    notify() {
      this.$emit('change', this.serialised);
    },
  },
};
</script>

<style scoped>
/* ── Root ──────────────────────────────────────────────────────────── */
.mc-wrapper {
  width: 100%;
  font-size: 0.875rem;
}

/* ── Box (looks like a form-control) ───────────────────────────────── */
.mc-box {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 5px;
  min-height: 38px;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: text;
  background: var(--bg-input);
  border: 1px solid var(--border-color);
  color: var(--text-primary);
  transition: border-color 0.15s, box-shadow 0.15s;
}
.mc-focused .mc-box {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
}

/* ── Chips ─────────────────────────────────────────────────────────── */
.mc-chip {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 2px 8px 2px 10px;
  border-radius: 20px;
  background: color-mix(in srgb, var(--primary-color) 15%, transparent);
  border: 1px solid color-mix(in srgb, var(--primary-color) 35%, transparent);
  color: var(--primary-color);
  font-size: 0.78rem;
  line-height: 1.6;
  white-space: nowrap;
  max-width: 200px;
  transition: background 0.15s;
}
.mc-chip:hover {
  background: color-mix(in srgb, var(--primary-color) 22%, transparent);
}
.mc-chip-label {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.mc-chip-remove {
  border: none;
  background: none;
  cursor: pointer;
  color: inherit;
  font-size: 1rem;
  line-height: 1;
  padding: 0;
  flex-shrink: 0;
  opacity: 0.65;
  transition: opacity 0.15s, color 0.15s;
}
.mc-chip-remove:hover { opacity: 1; color: var(--danger-color); }

/* ── Typing input ──────────────────────────────────────────────────── */
.mc-input {
  flex: 1 1 120px;
  min-width: 120px;
  border: none;
  outline: none;
  background: transparent;
  color: var(--text-primary);
  font-size: inherit;
  padding: 0;
  line-height: 1.5;
}
.mc-input::placeholder { color: var(--text-light); }

/* ── Hint ──────────────────────────────────────────────────────────── */
.mc-hint {
  display: block;
  margin-top: 4px;
  font-size: 0.72rem;
  color: var(--text-light);
}
.mc-hint kbd {
  display: inline-block;
  padding: 1px 5px;
  border-radius: 3px;
  font-size: 0.68rem;
  background: var(--bg-hover);
  border: 1px solid var(--border-color);
  color: var(--text-secondary);
  font-family: inherit;
}

/* ── Validation error state ────────────────────────────────────────── */
.mc-wrapper.border-warning .mc-box { border-color: var(--warning-color); }
</style>
