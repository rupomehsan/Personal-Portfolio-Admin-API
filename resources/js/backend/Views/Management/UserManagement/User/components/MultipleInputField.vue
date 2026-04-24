<template>
  <div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center pb-2 section-title">
      <h5 class="m-0">Add Social Media</h5>
      <button class="btn btn-sm btn-outline-success" @click.prevent="add_row('contributor')">Add row</button>
    </div>

    <div class="row align-items-center" v-for="(contributor, index) in contributor_data" :key="index">
      <div class="col-md-6">
        <div class="form-group">
          <label for="">media name</label>
          <div class="mt-1 mb-3">
            <select
              class="form-control form-control-square mb-2"
              :name="`social_media[${index}][media_name]`"
              v-model="contributor.media_name"
              id="media_name"
              :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].media_name,
              }"
            >
              <option value="">Select Platform</option>
              <option value="facebook">Facebook</option>
              <option value="twitter">Twitter</option>
              <option value="instagram">Instagram</option>
              <option value="linkedin">LinkedIn</option>
              <option value="youtube">YouTube</option>
              <option value="tiktok">TikTok</option>
              <option value="pinterest">Pinterest</option>
              <option value="snapchat">Snapchat</option>
              <option value="whatsapp">WhatsApp</option>
              <option value="telegram">Telegram</option>
              <option value="reddit">Reddit</option>
              <option value="tumblr">Tumblr</option>
              <option value="discord">Discord</option>
              <option value="slack">Slack</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].media_name"
            class="text-danger small">
            {{ errors["contributor"][index].media_name }}
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <label for="">media link</label>
          <div class="mt-1 mb-3">
            <input class="form-control form-control-square mb-2" type="text" :name="`social_media[${index}][media_link]`"
              v-model="contributor.media_link" id="media_link" :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].media_link,
              }" />
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].media_link"
            class="text-danger small">
            {{ errors["contributor"][index].media_link }}
          </div>
        </div>
      </div>

      <div class="col-md-1 d-flex align-items-center justify-content-center">
        <button class="btn btn-sm btn-outline-danger" :style="{
          width: '50%',
          marginTop: !errors['contributor']?.[index]?.media_name ? '30px' : '0',
        }" @click.prevent="delete_row('contributor', index)">
          <i class="fa fa-trash"></i>
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";

export default {
  props: {
    name: {
      type: String,
    },
    value: {
      type: Array,
      default: [],
    },
  },
  data: () => ({
    errors: [],
    //----------- for floor_plan list input ----------

    contributor_data: [
      {
        media_name: "",
        media_link: "",
      },
    ],

    //----------- for floor_plan list input ----------
  }),
  created: function () {
    this.$watch("item", (newValue) => {
      console.log("newValue", newValue?.social_links);

      if (newValue && newValue.social_links) {
        this.contributor_data = newValue.social_links.map((contributor) => ({
          media_name: contributor.media_name,
          media_link: contributor.link,
        }));
      }
    });
  },

  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    add_row() {
      this.contributor_data.push({
        media_name: "",
        media_link: "",
      });
    },
    delete_row(index) {
      if (this.contributor_data.length < 2) {
        console.error("cant delete first row");
        return;
      }
      this.contributor_data.splice(index, 1);
    },
  },
  computed: {
    ...mapState(store, ["item"]),
  },
};
</script>
<style scoped>
.image_preview {
  width: 40px;
  height: 40px;
  margin-left: 30px;
  object-fit: cover;
  border: 1px solid #dddddd35;
  padding: 3px;
}

.section-title {
  border: 1px solid #dddddd78;
  padding: 10px;
  margin: 10px 0;
  border-radius: 5px;
  font-weight: 500;
  color: #343a40;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
