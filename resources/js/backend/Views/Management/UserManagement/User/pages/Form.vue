<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{
              param_id
                ? `${setup.edit_page_title}`
                : `${setup.create_page_title}`
            }}
          </h5>
          <div>
            <router-link
              v-if="item.slug"
              class="btn btn-outline-info mr-2 btn-sm"
              :to="{
                name: `Details${setup.route_prefix}`,
                params: { id: item.slug },
              }"
            >
              {{ setup.details_page_title }}
            </router-link>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div
            class="d-flex justify-content-between align-items-center pb-2 section-title"
          >
            <h5 class="m-0">User Information</h5>
          </div>
          <div class="row">
            <template
              v-for="(form_field, index) in form_fields"
              v-bind:key="index"
            >
              <common-input
                :label="form_field.label"
                :type="form_field.type"
                :name="form_field.name"
                :multiple="form_field.multiple"
                :value="form_field.value"
                :data_list="form_field.data_list"
                :is_visible="form_field.is_visible"
                :class="form_field.class"
              />
            </template>
            <address-input :name="`phone_number`" />
            <multi-chip :name="`phone_number`" />
            <multiple-input-field :name="'social_link'" />
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            Submit
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import form_fields from "../setup/form_fields";
import axios from "axios";

import MultiChip from "../components/MultiChip.vue";
import MultipleInputField from "../components/MultipleInputField.vue";
import addressInput from "../components/address-input.vue";

export default {
  components: {
    MultiChip,
    MultipleInputField,
    addressInput,
  },
  data: () => ({
    setup,
    form_fields,
    param_id: null,
  }),
  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    this.reset_fields();
    if (id) {
      this.set_fields(id);
    }
    this.get_all_role();
  },
  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),
    reset_fields: function () {
      this.form_fields.forEach((item) => {
        item.value = "";
      });
    },
    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);
      if (this.item) {
        this.form_fields.forEach((field, index) => {
          Object.entries(this.item).forEach((value) => {
            if (field.name == value[0]) {
              this.form_fields[index].value = value[1];
            }
          });
        });

        // if (this.item.role_id == 2) {
        //   this.form_fields[9].is_visible = true;
        //   this.form_fields[10].is_visible = true;
        //   this.form_fields[11].is_visible = true;
        // }
      }
    },

    get_all_role: async function () {
      let response = await axios.get("roles");
      if (response.data.status == "success") {
        const roles = response.data?.data?.data || [];
        console.log(roles);

        this.form_fields[0].data_list = roles.map((role) => ({
          label: role.name,
          value: role.id,
        }));
      }
    },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
      if (this.param_id) {
        let response = await this.update($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({
            name: `Details${this.setup.route_prefix}`,
          });
        }
      } else {
        let response = await this.create($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data Successfully Created");
          this.$router.push({
            name: `All${this.setup.route_prefix}`,
          });
        }
      }
    },

    // changeAction: function ($event) {
    //   if (event.target.name == "role_id") {
    //     let role_id = event.target.value;
    //     if (role_id == 2) {
    //       this.form_fields[9].is_visible = true;
    //       this.form_fields[10].is_visible = true;
    //       this.form_fields[11].is_visible = true;
    //     } else {
    //       this.form_fields[9].is_visible = false;
    //       this.form_fields[10].is_visible = false;
    //       this.form_fields[11].is_visible = false;
    //     }
    //   }
    // },
  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>

<style scoped>
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
