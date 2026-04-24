<template>
  <div :class="class">
    <div class="form-group">
      <label for="">
        {{ label || name }}
      </label>

      <input
        @change="preview"
        class="form-control form-control-square"
        type="file"
        ref="input_files"
        :accept="accept"
        :class="classNames"
        :name="name + '[]'"
        :multiple="true"
      />

      <div class="uploaded_image_preview my-2 d-flex gap-1 flex-wrap">
        <!-- {{ component_images }} -->
        <template v-for="(image, index) in component_images" :key="image">
          <div class="position-relative my-1">
            <a
              :href="image.url ? image.url : image"
              data-lightbox="images-preview"
              data-title="Preview"
            >
              <img
                :src="image.url ? image.url : image"
                :class="image != '' ? 'border' : ''"
                style="width: 200px; height: 80px; object-fit: contain"
                alt="image"
                target="_black"
                class="mx-1"
              />
            </a>
            <button
              class="btn btn-outline-warning btn-roun btn-sm"
              :class="`id-${image.id} index-${index}`"
              style="position: absolute; top: 3px; right: 7px"
              @click.prevent="remove({ field: name, index: index })"
            >
              X
            </button>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../../store";
export default {
  props: {
    name: {
      required: true,
      default: "selected_images",
    },
    classNames: {
      default: "form-control",
    },
    accept: {
      required: true,
    },
    images: {
      default: [],
      required: false,
    },
    class: {
      type: String,
      default: "col-6",
    },
  },
  data: () => ({
    component_images: [],
    value: null,
  }),
  watch: {
    images: function (v) {
      // console.log(v);
      this.component_images = this.images;
      if (!this.multiple) {
        this.value = this.images;
      }
    },
  },
  created() {
    this.value = this.images;
    if (this.multiple) {
      this.component_images = this.images;
    }
  },
  methods: {
    preview: function () {
      let that = this;
      that.component_images = [...that.component_images];
      [...that.$refs.input_files.files].forEach((el) => {
        const reader = new FileReader();
        reader.onload = function (e) {
          const base64String = e.target.result;
          that.component_images.push(base64String);
          that.value = base64String;
        };
        reader.readAsDataURL(el);
      });
    },

    remove: async function (data) {
      if (this.item.slug) {
        const parsedData = { field: this.name, index: data.index };
        const confirmed = await window.s_confirm();
        if (!confirmed) return;
        const response = await axios.post(
          `properties/proprerty-image-delete/${this.item.slug}?data=${JSON.stringify(parsedData)}`,
        );
        window.s_alert(response.data.message);
      }

      this.component_images.splice(data.index, 1);
      const dt = new DataTransfer();
      const input = this.$refs.input_files;

      [...input.files].forEach((file, idx) => {
        if (idx !== data.index) {
          dt.items.add(file);
        }
      });
      input.files = dt.files; // Assign the new FileList
    },
  },
  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>

<style></style>
