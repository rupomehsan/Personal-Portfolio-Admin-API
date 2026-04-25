<template>
  <div :class="class" v-if="is_visible">
    <div class="form-group">
      <label :for="name">{{ label || name }}</label>

      <!-- ── Standard text-like inputs ──────────────────────────────── -->
      <div
        v-if="['text','number','password','email','date','time','datetime-local','url','tel','color'].includes(type)"
        class="mt-1 mb-3"
      >
        <input
          class="form-control form-control-square mb-2"
          :type="type"
          :name="name"
          :id="name"
          :value="value"
          :placeholder="placeholder || label"
          @change="errorReset"
        />
      </div>

      <!-- ── Textarea / rich-text editor ────────────────────────────── -->
      <div v-if="type === 'textarea' || type === 'editor'" class="mt-1 mb-3">
        <text-editor :name="name" />
      </div>

      <!-- ── Searchable select (single & multiple) ──────────────────── -->
      <div v-if="type === 'select'" class="mt-1 mb-3">
        <select-input
          :name="name"
          :multiple="!!multiple"
          :value="value"
          :data_list="data_list || []"
          @change="onSelectChange"
        />
      </div>

      <!-- ── Multi-chip tag input ────────────────────────────────────── -->
      <div v-if="type === 'multichip'" class="mt-1 mb-3">
        <multi-chip-input
          :name="name"
          :value="value"
          :placeholder="placeholder || 'Add a tag and press Enter...'"
          @change="onChipChange"
        />
      </div>

      <!-- ── File / image upload ─────────────────────────────────────── -->
      <div v-if="type === 'file'" class="mt-1 mb-3">
        <image-component
          :name="name"
          :accept="accept || 'image/*'"
          :multiple="!!multiple"
          :value="value"
          :item="item"
          :api_url="api_url"
        />
      </div>

      <!-- ── Range slider ────────────────────────────────────────────── -->
      <div v-if="type === 'range'" class="mt-1 mb-3">
        <div class="d-flex align-items-center gap-2">
          <input
            class="form-range flex-grow-1"
            type="range"
            :name="name"
            :id="name"
            :min="min || 0"
            :max="max || 100"
            :step="step || 1"
            :value="value"
            @change="errorReset"
          />
          <span class="ci-range-val">{{ value || min || 0 }}</span>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import TextEditor      from "./TextEditor.vue";
import ImageComponent  from "./ImageComponent.vue";
import SelectInput     from "./SelectInput.vue";
import MultiChipInput  from "./MultiChipInput.vue";

export default {
  components: { TextEditor, ImageComponent, SelectInput, MultiChipInput },

  props: {
    is_visible:     { type: [Boolean, String],        default: true        },
    name:           { type: String,                   required: true       },
    label:          { type: String,                   required: true       },
    type:           { type: [String, Array, Object],  required: true       },
    placeholder:    { type: String,                   default: null        },
    multiple:       { type: [Boolean, String],        default: false       },
    value:          { type: [String, Number, Array],  default: null        },
    data_list:      { type: Array,                    default: null        },
    images_list:    { type: Array,                    default: null        },
    item:           { type: Object,                   default: null        },
    class:          { type: String,                   default: 'col-md-6'  },
    onchange:       { type: Function,                 default: null        },
    onchangeAction: { type: String,                   default: null        },
    api_url:        { type: String,                   default: null        },
    accept:         { type: String,                   default: null        },
    min:            { type: [String, Number],         default: null        },
    max:            { type: [String, Number],         default: null        },
    step:           { type: [String, Number],         default: null        },
  },

  methods: {
    // Native inputs
    errorReset(event) {
      const el = event.target;
      const next = el.nextElementSibling;
      if (next) { el.classList.remove('border-warning'); next.remove(); }
      this.fireOnchange(event);
    },

    // SelectInput emits the selected value(s)
    onSelectChange(value) {
      const ctrl = this.$el.querySelector('.ss-control');
      if (ctrl) {
        ctrl.classList.remove('border-warning');
        const next = ctrl.nextElementSibling;
        if (next && !next.classList.contains('ss-dropdown')) next.remove();
      }
      this.fireOnchange({ target: { name: this.name, value } });
    },

    // MultiChipInput emits the comma-separated string
    onChipChange(value) {
      const box = this.$el.querySelector('.mc-box');
      if (box) box.classList.remove('border-warning');
      this.fireOnchange({ target: { name: this.name, value } });
    },

    fireOnchange(event) {
      if (!this.onchange) return;
      this.onchangeAction
        ? this.onchange(this.onchangeAction, event, this)
        : this.onchange(event);
    },
  },

  computed: {
    resolvedItem() {
      if (this.item) return this.item;
      try { if (this.$parent?.item) return this.$parent.item; } catch { /**/ }
      return null;
    },
  },
};
</script>

<style scoped>
.ci-range-val {
  min-width: 32px;
  text-align: right;
  font-size: .8rem;
  color: var(--text-light);
}
</style>
