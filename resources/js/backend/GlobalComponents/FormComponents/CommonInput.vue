<template>
  <div :class="class" v-if="is_visible">
    <div class="form-group">
      <label for="">
        {{ label || name }}
      </label>
      <div
        v-if="
          [
            'text',
            'number',
            'password',
            'email',
            'date',
            'time',
            'datetime-local',
          ].includes(type)
        "
        class="mt-1 mb-3"
      >
        <input
          class="form-control form-control-square mb-2"
          :type="type"
          :name="name"
          :id="name"
          :value="value"
          @change="errorReset"
        />
      </div>

      <div v-if="type === 'textarea'" class="mt-1 mb-3">
        <!-- <textarea class="form-control form-control-square" rows="10"  type="text" :name="name" :value="value"
                @change="errorReset"></textarea> -->
        <!-- <div :id="name"></div> -->
        <text-editor :name="name" />
      </div>

      <div v-if="type === 'select'" class="mt-1 mb-3">
        <select
          :name="name"
          class="form-control form-control-square"
          :id="name"
          @change="errorReset"
        >
          <option value="">Select item</option>
          <option
            v-for="data in data_list"
            :key="data"
            :value="data.value"
            :selected="data.value == value"
          >
            {{ data.label }}
          </option>
        </select>
      </div>
      <div v-if="type === 'file'" class="mt-1 mb-3">
        <image-component
          :name="name"
          :accept="`.jpg,.jpeg,.png`"
          :value="value"
          :item="item"
          :api_url="api_url"
        ></image-component>
      </div>
    </div>
  </div>
</template>

<script>
import TextEditor from "./TextEditor.vue";
import ImageComponent from "./ImageComponent.vue";
import { mapActions, mapState } from "pinia";
/**
 * props:
 */
export default {
  components: { TextEditor, ImageComponent },
  data: () => ({
    tag_input_value: "",
  }),
  props: {
    is_visible: {
      type: [Boolean, String],
      default: true,
    },
    name: {
      required: true,
      type: String,
    },
    label: {
      required: true,
      type: String,
    },
    type: {
      required: true,
      type: [String, Array, Object],
    },
    multiple: {
      required: false,
      type: [Boolean, String],
    },
    value: {
      required: false,
      type: [String, Number],
    },
    data_list: {
      required: false,
      type: Array,
    },
    images_list: {
      required: false,
      type: Array,
    },
    item: {
      required: false,
      type: Object,
      default: null,
    },
    class: {
      required: false,
      type: String,
      default: "col-md-6",
    },
    onchange: {
      required: false,
      type: Function,
      default: () => "",
    },
    onchangeAction: {
      required: false,
      type: String,
      default: null,
    },
    api_url: {
      required: false,
      type: String,
      default: null,
    },
  },

  methods: {
    errorReset(event) {
      let currentElement = event.target;
      let nextElement = currentElement.nextElementSibling;
      if (nextElement) {
        currentElement.classList.remove("border-warning");
        nextElement.remove();
      }
      if (this.onchange) {
        if (this.onchangeAction) {
          this.onchange(this.onchangeAction, event, this);
        } else {
          this.onchange(event);
        }
      }
    },

    removeTag: function (item) {
      this.remove_tag(item);
    },
  },
  computed: {
    // Resolve item: prefer explicit prop, otherwise fallback to parent form's item
    resolvedItem() {
      if (this.item) return this.item;
      try {
        // parent may be the Form page which has computed `item`
        if (this.$parent && this.$parent.item) return this.$parent.item;
      } catch (e) {
        // ignore
      }
      return null;
    }
  },
  created: async function () {},
};
</script>

<style></style>
