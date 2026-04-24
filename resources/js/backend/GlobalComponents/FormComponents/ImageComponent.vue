<template>
  <div>
    <input @change="preview" class="form-control form-control-square" type="file" ref="input_files" :accept="accept"
      :class="classNames" :name="name" />

    <div v-if="image_preview && image_preview != ''" class="d-flex justify-content-start align-items-start">
      <a :href="image_preview" data-lightbox="image-preview" data-title="Preview">
        <img :src="image_preview" class="mt-2 image-preview-img" alt="image" target="_black" />
      </a>

      <button class="btn btn-warning btn-sm mt-2 p-1 image-remove-btn" @click.prevent="removeImage()">X</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      required: true,
      default: "image",
    },
    multiple: {
      default: false,
    },
    classNames: {
      default: "form-control",
    },
    accept: {
      required: true,
    },
    value: {
      type: String,
      default: null,
    },
    item: {
      type: Object,
      default: null,
    },
    api_url: {
      required: false,
      type: String,
      default: null,
    },
  },
  data: () => ({
    image_preview: null,
  }),

  created: function () {

    const currentRoute = this.$router && this.$route ? this.$route : null;
    // Dynamically get the first element after the base path in the route path
    this.moduleName = null;
    this.modalNames = [];
    if (currentRoute && currentRoute.path) {
      const segments = currentRoute.path.split('/').filter(Boolean);
      if (segments.length > 0) {
        // store pluralized module name (used for API endpoints)
        // Convert hyphens to underscores for moduleName
        this.moduleName = this.getPlural(segments[0].replace(/-/g, '_'));
        this.modalNames = segments;
        console.log('Module name detected:', this.moduleName);
      }
    }

    this.$watch(
      "value",
      (newValue) => {
        if (newValue) {
          this.image_preview = newValue;
        }
      },
      { immediate: true }
    );
  },

  methods: {
    getPlural: function (word) {
      if (!word || typeof word !== 'string') return word;

      const word_lower = word.toLowerCase();

      // Use Intl.PluralRules to determine if we need plural form
      const pluralRules = new Intl.PluralRules('en-US');
      const rule = pluralRules.select(2); // 2 means plural

      // Simple pluralization rules for English
      if (rule === 'other') {
        // Handle common irregular plurals
        const irregulars = {
          'child': 'children',
          'man': 'men',
          'woman': 'women',
          'tooth': 'teeth',
          'foot': 'feet',
          'mouse': 'mice',
          'person': 'people'
        };

        if (irregulars[word_lower]) {
          return irregulars[word_lower];
        }

        // Words ending in 'y' preceded by consonant
        if (word_lower.endsWith('y') && !/[aeiou]/.test(word_lower[word_lower.length - 2])) {
          return word_lower.slice(0, -1) + 'ies';
        }

        // Words ending in 's', 'sh', 'ch', 'x', 'z'
        if (/[sxz]$|[cs]h$/.test(word_lower)) {
          return word_lower + 'es';
        }

        // Words ending in 'f' or 'fe'
        if (word_lower.endsWith('f')) {
          return word_lower.slice(0, -1) + 'ves';
        }
        if (word_lower.endsWith('fe')) {
          return word_lower.slice(0, -2) + 'ves';
        }

        // Default: add 's'
        return word_lower + 's';
      }

      return word_lower;
    },

    preview: function () {
      const file = this.$refs.input_files.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        const base64String = e.target.result;
        this.image_preview = base64String;
      };
      reader.readAsDataURL(file);
    },

    removeImage: async function (data = {}) {
      // If the click event was accidentally forwarded, ignore it
      if (data && (data instanceof Event || data.type)) {
        data = {};
      }
      console.log(' image', { data, item: this.item });
      // If there's no item (new upload), just clear preview
      if (!this.item || !this.item.slug) {
        const confirmedLocal = await window.s_confirm();
        if (!confirmedLocal) return;
        this.remove();
        return;
      }

      // safe defaults
      const index = data.index ?? null;
      const parsedData = { field: this.name, index };

      console.log('Removing image', { parsedData, slug: this.item.slug, moduleName: this.moduleName });

      const confirmed = await window.s_confirm();
      if (!confirmed) return;

      const endpointBase = this.moduleName ? this.moduleName : (this.modalNames && this.modalNames[0]) ? this.modalNames[0] : '';
      // build URL; keep previous behavior of passing data via query string
      const url = this.api_url + `/${this.item.slug}?data=` + encodeURIComponent(JSON.stringify(parsedData));

      const response = await axios.post(url);
      if (response && response.data) {
        window.s_alert(response.data.message || 'Deleted');
        // optionally clear preview after successful delete
        this.remove();
      }
    },

    remove: function () {

      this.image_preview = null;
      this.$refs.input_files.value = null;
    },
  },
};
</script>

<style scoped>
.image-preview-img {
  width: 200px;
  height: 80px;
  object-fit: cover;
  object-position: center center;
  border: 1px solid #ffffff2e;
  padding: 2px;
}

.image-remove-btn {
  margin-left: -18px;
  border-radius: 0px;
  position: static;
}
</style>
