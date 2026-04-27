<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ setup.details_page_title }}
          </h5>
          <div>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div class="row">
            <div class="col-lg-8">
              <table class="table quick_modal_table table-bordered">
                <tbody>
                  <tr
                    v-for="(value, key) in item"
                    :key="key"
                    v-if="setup.quick_view_data.includes(key)"
                  >
                    <td style="font-weight: 600; width: 30%">{{ key }}</td>
                    <td>{{ value }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <router-link
            class="btn btn-outline-warning btn-sm"
            :to="{
              name: `Edit${setup.route_prefix}`,
              params: { id: item.slug },
            }"
          >
            {{ setup.edit_page_title }}
          </router-link>

          <router-link
            class="btn btn-outline-danger btn-sm ml-2"
            :to="{ name: `All${setup.route_prefix}` }"
          >
            Go Back
          </router-link>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";

export default {
  data: () => ({
    setup,
  }),

  computed: {
    ...mapState(store, ["item"]),
  },

  created: function () {
    const id = this.$route.params.id;
    this.get_data(id);
  },

  methods: {
    ...mapActions(store, {
      get_data: "details",
    }),

    async submitHandler($event) {
      // Prevent form submission on details page
      $event.preventDefault();
    },
  },
};
</script>

<style></style>
