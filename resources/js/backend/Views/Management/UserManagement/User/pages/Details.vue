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
            <div class="col-lg-12">
              <h6 class="text-info mb-3">User Details</h6>
              <table class="table quick_modal_table table-bordered">
                <tbody>
                  <tr>
                    <th>First Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item.name }}</th>
                  </tr>
                  <tr>
                    <th>Last Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item.name }}</th>
                  </tr>
                  <tr>
                    <th>User Name</th>
                    <th class="text-center">:</th>
                    <th>{{ item.user_name }}</th>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <th class="text-center">:</th>
                    <th>{{ item.email }}</th>
                  </tr>
                  <tr>
                    <th>Role</th>
                    <th class="text-center">:</th>
                    <th>{{ item.role?.name || "N/A" }}</th>
                  </tr>
                  <tr>
                    <th>Image</th>
                    <th class="text-center">:</th>
                    <th>
                      <img
                        height="100px"
                        width="100px"
                        :src="item.image"
                        alt="User Image"
                        style="object-fit: cover; border-radius: 5px"
                      />
                    </th>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <th class="text-center">:</th>
                    <th>
                      <span
                        :class="`badge ${
                          item.status === 'active'
                            ? 'badge-success'
                            : 'badge-danger'
                        }`"
                      >
                        {{ item.status }}
                      </span>
                    </th>
                  </tr>
                  <tr>
                    <th>Created At</th>
                    <th class="text-center">:</th>
                    <th>
                      {{ new Date(item.created_at).toLocaleDateString() }}
                    </th>
                  </tr>
                </tbody>
              </table>

              <!-- Address Information -->
              <div class="mt-4" v-if="item.address">
                <h6 class="text-info mb-3">Address Information</h6>
                <table class="table quick_modal_table table-bordered">
                  <tbody>
                    <tr>
                      <th>Phone Numbers</th>
                      <th class="text-center">:</th>
                      <th>
                        <div v-if="phoneNumbers.length">
                          <span
                            v-for="(phone, index) in phoneNumbers"
                            :key="index"
                            class="badge badge-info mr-1"
                            >{{ phone }}</span
                          >
                        </div>
                        <span v-else class="text-muted">No phone numbers</span>
                      </th>
                    </tr>
                    <tr>
                      <th>State</th>
                      <th class="text-center">:</th>
                      <th>{{ item.address.state || "N/A" }}</th>
                    </tr>
                    <tr>
                      <th>City</th>
                      <th class="text-center">:</th>
                      <th>{{ item.address.city || "N/A" }}</th>
                    </tr>
                    <tr>
                      <th>Post Code</th>
                      <th class="text-center">:</th>
                      <th>{{ item.address.post || "N/A" }}</th>
                    </tr>
                    <tr>
                      <th>Country</th>
                      <th class="text-center">:</th>
                      <th>{{ item.address.country || "N/A" }}</th>
                    </tr>
                    <tr>
                      <th>Full Address</th>
                      <th class="text-center">:</th>
                      <th>{{ item.address.address || "N/A" }}</th>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Social Links -->
              <div
                class="mt-4"
                v-if="item.social_links && item.social_links.length"
              >
                <h6 class="text-info mb-3">Social Media Links</h6>
                <table class="table quick_modal_table table-bordered">
                  <tbody>
                    <tr v-for="(link, index) in item.social_links" :key="index">
                      <th>{{ link.media_name }}</th>
                      <th class="text-center">:</th>
                      <th>
                        <span
                          v-if="
                            link.link &&
                            (link.link.startsWith('http://') ||
                              link.link.startsWith('https://'))
                          "
                        >
                          <a :href="link.link" target="_blank">
                            {{ link.link }}
                          </a>
                        </span>
                        <span v-else-if="link.link">
                          <a :href="`https://${link.link}`" target="_blank">
                            {{ link.link }}
                          </a>
                        </span>
                        <span v-else class="text-muted">N/A</span>
                      </th>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Activity Logs -->
              <!-- <div class="mt-4" v-if="item.logs && item.logs.length">
                <h6 class="text-info mb-3">Recent Activity (Last 5)</h6>
                <table class="table quick_modal_table table-bordered">
                  <thead>
                    <tr>
                      <th>Activity</th>
                      <th>Date & Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(log, index) in item.logs.slice(0, 5)" :key="index">
                      <th>User Activity</th>
                      <th>{{ new Date(log.last_seen).toLocaleString() }}</th>
                    </tr>
                  </tbody>
                </table>
              </div> -->
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

          <a
            href=""
            v-if="item.prev_slug"
            @click.prevent="get_data(item.prev_slug)"
            class="btn btn-secondary btn-sm ml-2"
          >
            <i class="fa fa-angle-left"></i>
            Previous {{ setup.route_prefix }} ({{ item.prev_count }})
          </a>

          <a
            href=""
            v-if="item.next_slug"
            @click.prevent="get_data(item.next_slug)"
            class="btn btn-secondary btn-sm ml-2"
          >
            Next {{ setup.route_prefix }} ({{ item.next_count }})
            <i class="fa fa-angle-right"></i>
          </a>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";

export default {
  data: () => ({
    setup,
  }),
  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    await this.get_data(id);
  },
  methods: {
    ...mapActions(store, {
      details: "details",
    }),
    get_data: async function (slug) {
      this.item = {};
      await this.details(slug);
    },
  },
  computed: {
    ...mapWritableState(store, {
      item: "item",
    }),
    phoneNumbers() {
      if (this.item.address && this.item.address.phone_number) {
        try {
          return JSON.parse(this.item.address.phone_number);
        } catch (error) {
          console.error("Error parsing phone numbers:", error);
          return [];
        }
      }
      return [];
    },
  },
};
</script>

<style>
tr th {
  text-align: left !important;
}
</style>
