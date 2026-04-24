<template>
  <div class="col-md-6">
    <div class="form-group">
      <label for="">Enter phone number</label>
      <div class="mb-3">
        <div class="bootstrap-tagsinput" style="min-height: 40px">
          <template v-for="item in set_phone_numbers" :key="item">
            <span class="tag badge badge-light">{{ item }}<span data-role="remove" @click="removePhoneNumber(item)"></span></span>
          </template>
          <input type="hidden" :name="name" :value="set_phone_numbers.join(',')" />
          <input type="text" v-on:keydown.enter="onEnter" v-model="phone_input_value" />
        </div>
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
    set_phone_numbers: [],
    phone_input_value: "",
    phone_numbers: "",
  }),
  created: function () {
    this.$watch(
      "item",
      (newValue) => {
        if (newValue && newValue.address && newValue.address.phone_number) {
          try {
            // Parse the JSON string to get the array of phone numbers
            const phoneNumbers = JSON.parse(newValue.address.phone_number);
            this.set_phone_numbers = Array.isArray(phoneNumbers) ? phoneNumbers.filter((phone) => phone) : [];
          } catch (error) {
            console.error("Error parsing phone numbers:", error);
            this.set_phone_numbers = [];
          }
        } else {
          this.set_phone_numbers = [];
        }
      },
      { immediate: true }
    );
  },

  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    add_phone_number: async function (item) {
      if (item == "empty") {
        this.set_phone_numbers = [];
        return false;
      }

      // Basic phone number validation
      // Accepts numbers starting with 0 or +, followed by 10-15 digits
      const phoneRegex = /^(\+?\d{10,15}|0\d{9,14})$/;
      const cleanPhone = item.replace(/[\s\-\(\)]/g, '');
      if (!phoneRegex.test(cleanPhone)) {
        window.s_alert('Please enter a valid phone number','error');
        return false;
      }

      let is_exist = this.set_phone_numbers.some((data) => data === item);
      if (!is_exist) {
        this.set_phone_numbers.push(item);
      }
    },
    remove_phone_number: async function (item) {
      this.set_phone_numbers = this.set_phone_numbers.filter((data) => data != item);
    },
    onEnter: function () {
      event.preventDefault();
      if (this.phone_input_value.trim()) {
        this.add_phone_number(this.phone_input_value.trim());
        this.phone_input_value = "";
      }
    },
    removePhoneNumber: function (item) {
      this.remove_phone_number(item);
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
