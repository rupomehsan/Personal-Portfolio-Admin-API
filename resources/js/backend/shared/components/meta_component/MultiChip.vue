<template>
  <div class="col-md-6">
    <div class="form-group">
      <label for="">Enter your tag</label>
      <div class="mb-3">
        <div class="bootstrap-tagsinput" style="min-height: 40px">
          <template v-for="item in set_blog_tags" :key="item">
            <span class="tag badge badge-light">{{ item }}<span data-role="remove" @click="removeTag(item)"></span></span>
          </template>
          <input type="hidden" :name="name" :value="set_blog_tags.join(',')" />
          <input type="text" placeholder="" v-on:keydown.enter="onEnter" v-model="tag_input_value" />
        </div>
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
    set_blog_tags: [],
    tag_input_value: "",
    tags: "",
  }),
  created: function () {
    this.$watch(
      "item",
      (newValue) => {
        if (newValue) {
          this.set_blog_tags = newValue.tags ? newValue.tags.split(",").filter((tag) => tag) : [];
        }
      },
      { immediate: true }
    );
  },

  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    set_tags: async function (item) {
      if (item == "empty") {
        this.set_blog_tags = [];
        return false;
      }

      let is_exist = this.set_blog_tags.some((data) => data === item);
      if (!is_exist) {
        this.set_blog_tags.push(item);
      }
    },
    remove_tag: async function (item) {
      this.set_blog_tags = this.set_blog_tags.filter((data) => data != item);
    },
    onEnter: function () {
      event.preventDefault();
      this.set_tags(this.tag_input_value);
      this.tag_input_value = "";
    },
    removeTag: function (item) {
      this.remove_tag(item);
    },
  },
  computed: {
    ...mapState(store, ["item"]),
  },
};
</script>
<style scoped>
.bootstrap-tagsinput {
  background-color: rgba(255, 255, 255, 0.2);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  width: 100%;
  line-height: 22px;
  cursor: text;
}

.bootstrap-tagsinput input {
  border: none;
  box-shadow: none;
  outline: none;
  background-color: transparent;
  padding: 0 6px;
  margin: 0;
  color: #fff;
  width: auto;
  max-width: inherit;
}

.bootstrap-tagsinput.form-control input::-moz-placeholder {
  color: #777;
  opacity: 1;
}

.bootstrap-tagsinput.form-control input:-ms-input-placeholder {
  color: #777;
}

.bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
  color: #777;
}

.bootstrap-tagsinput input:focus {
  border: none;
  box-shadow: none;
}

.bootstrap-tagsinput .tag {
  margin-right: 2px;
}

.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}

.bootstrap-tagsinput .tag [data-role="remove"]:after {
  content: "x";
  padding: 0px 2px;
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
</style>
