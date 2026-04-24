<template>
  <div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center pb-2 section-title">
      <h5 class="m-0">Add Contributors</h5>
      <button class="btn btn-sm btn-outline-success" @click.prevent="add_row('contributor')">Add row</button>
    </div>

    <div class="row align-items-center" v-for="(contributor, index) in contributor_data" :key="index">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">name</label>
          <div class="mt-1 mb-3">
            <input
              class="form-control form-control-square mb-2"
              type="text"
              :name="`contributors[${index}][name]`"
              v-model="contributor.name"
              id="name"
              :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].name,
              }"
            />
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].name" class="text-danger small">
            {{ errors["contributor"][index].name }}
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="">Age</label>
          <div class="mt-1 mb-3">
            <select
              class="form-control form-control-square mb-2"
              :name="`contributors[${index}][age]`"
              v-model="contributor.age"
              :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].age,
              }"
            >
              <option value="">-- select --</option>
              <option v-for="(age, i) in 50" :key="i" :value="age">
                {{ age }}
              </option>
            </select>
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].age" class="text-danger small">
            {{ errors["contributor"][index].age }}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="">email</label>
          <div class="mt-1 mb-3">
            <input
              class="form-control form-control-square mb-2"
              type="text"
              :name="`contributors[${index}][email]`"
              v-model="contributor.email"
              id="email"
              :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].email,
              }"
            />
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].email" class="text-danger small">
            {{ errors["contributor"][index].email }}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <div>
            <label for="">image</label>
            <a :href="contributor_data[index].image" data-lightbox="image-1" data-title="My caption">
              <img class="image_preview" v-if="contributor_data[index].image" :src="contributor_data[index].image" />
            </a>
          </div>

          <div class="mt-1 mb-3">
            <input
              class="form-control form-control-square mb-2"
              type="file"
              @change="onImageChange($event, index)"
              :name="`contributors[${index}][image]`"
              id="image"
              :class="{
                custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].image,
              }"
            />
          </div>
          <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].image" class="text-danger small">
            {{ errors["contributor"][index].image }}
          </div>
        </div>
      </div>
      <div class="col-md-1 d-flex align-items-center justify-content-center">
        <button
          class="btn btn-sm btn-outline-danger"
          :style="{
            width: '50%',
            marginTop: !errors['contributor']?.[index]?.title ? '30px' : '0',
          }"
          @click.prevent="delete_row('contributor', index)"
        >
          <i class="fa fa-trash"></i>
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../../store";

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
        name: "",
        age: "",
        email: "",
        image: "",
      },
    ],

    //----------- for floor_plan list input ----------
  }),
  created: function () {
    this.$watch("item", (newValue) => {
      console.log("newValue", newValue?.contributors);

      if (newValue) {
        this.contributor_data = newValue?.contributors.map((contributor) => ({
          name: contributor.name,
          email: contributor.email,
          age: contributor.age,
          image: contributor.image,
        }));
      }
    });
  },

  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    add_row() {
      this.contributor_data.push({
        name: "",
        age: "",
        email: "",
        image: "",
      });
    },
    onImageChange(event, index) {
      const file = event.target.files[0];
      if (file) {
        // store the file for submission
        this.contributor_data[index].image = file;

        // create base64 preview
        const reader = new FileReader();
        reader.onload = (e) => {
          this.contributor_data[index].image = e.target.result;
        };
        reader.readAsDataURL(file);
      }
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
