<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <!-- Header -->
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12 col-md-3 mb-2 mb-md-0">
                <h5 class="mb-0" style="font-size: 0.95rem; font-weight: 700">
                  <i class="fa fa-shopping-cart mr-2" style="color: #28a745"></i>All Orders
                </h5>
              </div>

              <div class="col-12 col-md-3 mb-2 mb-md-0">
                <input
                  class="form-control form-control-sm"
                  v-model="search_key"
                  @keyup="on_search"
                  placeholder="Search by email, name, trx..."
                />
              </div>

              <!-- Payment Status Filter -->
              <div class="col-12 col-md-3 mb-2 mb-md-0">
                <select
                  class="form-control form-control-sm"
                  v-model="selected_status"
                  @change="on_status_filter"
                >
                  <option value="">-- All Status --</option>
                  <option value="pending">Pending</option>
                  <option value="due">Due</option>
                  <option value="compelete">Completed</option>
                </select>
              </div>

              <div
                class="col-12 col-md-3 text-md-right d-flex justify-content-end"
                style="gap: 6px"
              >
                <button
                  class="btn btn-outline-success btn-sm"
                  @click="load_orders"
                  title="Reload"
                >
                  <i class="fa fa-refresh"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Body -->
          <div class="card-body p-0">
            <div v-if="loading" class="text-center py-5">
              <i class="fa fa-spinner fa-spin fa-2x" style="color: #28a745"></i>
              <p class="mt-2 text-muted">Loading orders...</p>
            </div>

            <div v-else class="table-responsive">
              <table class="table table-hover table-bordered mb-0 text-center">
                <thead style="background: #f1f3f5">
                  <tr>
                    <th style="width: 50px">#</th>
                    <th style="min-width: 100px">Product</th>
                    <th style="min-width: 150px; text-align: left">Customer</th>
                    <th style="min-width: 150px">Email</th>
                    <th style="min-width: 120px">Phone</th>
                    <th style="min-width: 120px">Transaction ID</th>
                    <th>Status</th>
                    <th style="min-width: 100px">Date</th>
                    <th style="width: 100px">Actions</th>
                  </tr>
                </thead>

                <tbody v-if="orders.length">
                  <tr v-for="order in orders" :key="order.id">
                    <td>{{ order.id }}</td>
                       <td style="font-size: 0.85rem" :title="order.digital_product ? order.digital_product.title : ''">
                      {{
                        truncate(
                          order.digital_product
                            ? order.digital_product.title
                            : String(order.digital_product_id),
                          30,
                        )
                      }}
                    </td>
                    <td class="text-left text-wrap">
                      <strong>{{ order.first_name }} {{ order.last_name }}</strong>
                    </td>
                    <td  style="font-size: 0.85rem">{{ order.email }}</td>
                    <td style="font-size: 0.85rem">{{ order.phone }}</td>
                 
                    <td style="font-size: 0.85rem; font-weight: 600; color: #0066cc">
                      {{ order.trx_number }}
                    </td>
                    <td>
                      <span
                        style="padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600;"
                        :style="getStatusStyle(order.payment_status)"
                      >
                        {{ order.payment_status }}
                      </span>
                    </td>
                    <td style="font-size: 0.82rem">
                      {{ format_date(order.created_at) }}
                    </td>
                    <td>
                      <div class="d-flex flex-column" style="gap: 4px">
                        <router-link
                          :to="{
                            name: 'DetailsProductOrder',
                            params: { order_id: order.id },
                          }"
                          class="btn btn-sm"
                          style="font-size: 0.73rem; background: #28a745; color: #fff;"
                        >
                          <i class="fa fa-eye mr-1"></i>View
                        </router-link>
                        <button
                          class="btn btn-sm"
                          style="font-size: 0.73rem; background: #dc3545; color: #fff;"
                          @click="delete_order(order.id)"
                        >
                          <i class="fa fa-trash mr-1"></i>Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>

                <tbody v-else>
                  <tr>
                    <td colspan="9">
                      <div class="d-flex flex-column align-items-center py-5">
                        <div
                          style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #28a745, #66bb6a); border-radius: 12px; opacity: 0.6;"
                        >
                          <i class="fa fa-shopping-cart" style="font-size: 60px; color: #fff"></i>
                        </div>
                        <h5 class="text-muted mt-3">No Orders Found</h5>
                        <p class="text-secondary">Try adjusting your search or status filter.</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="card-footer py-2">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap"
              style="gap: 8px"
            >
              <span class="text-muted" style="font-size: 0.82rem">
                Showing {{ pagination.from || 0 }}&ndash;{{ pagination.to || 0 }}
                of {{ pagination.total || 0 }} orders
              </span>
              <div v-if="pagination.last_page > 1" class="d-flex flex-wrap" style="gap: 4px">
                <button
                  class="btn btn-sm"
                  :disabled="pagination.current_page === 1"
                  :style="pagination.current_page === 1 ? 'background:#e9ecef;color:#aaa;cursor:not-allowed;' : 'background:#28a745;color:#fff;'"
                  style="min-width: 32px"
                  @click="go_to_page(pagination.current_page - 1)"
                >&laquo;</button>
                <button
                  v-for="page in visible_pages"
                  :key="page"
                  class="btn btn-sm"
                  :style="pagination.current_page === page ? 'background:#28a745;color:#fff;' : 'background:#e9ecef;color:#333;'"
                  style="min-width: 32px"
                  @click="go_to_page(page)"
                >{{ page }}</button>
                <button
                  class="btn btn-sm"
                  :disabled="pagination.current_page === pagination.last_page"
                  :style="pagination.current_page === pagination.last_page ? 'background:#e9ecef;color:#aaa;cursor:not-allowed;' : 'background:#28a745;color:#fff;'"
                  style="min-width: 32px"
                  @click="go_to_page(pagination.current_page + 1)"
                >&raquo;</button>
              </div>
              <select
                class="form-control form-control-sm"
                style="width: auto"
                v-model="per_page"
                @change="on_per_page_change"
              >
                <option value="10">10/page</option>
                <option value="25">25/page</option>
                <option value="50">50/page</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import app_config from "@config/app_config";
import debounce from "@/shared/helpers/debounce";

export default {
  data() {
    return {
      orders: [],
      loading: false,
      search_key: "",
      selected_status: "",
      pagination: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },
      current_page: 1,
      per_page: 10,
    };
  },

  computed: {
    visible_pages() {
      let pages = [];
      let start = Math.max(1, this.pagination.current_page - 2);
      let end = Math.min(this.pagination.last_page, this.pagination.current_page + 2);

      if (start > 1) pages.push(1);
      if (start > 2) pages.push("...");

      for (let i = start; i <= end; i++) {
        pages.push(i);
      }

      if (end < this.pagination.last_page - 1) pages.push("...");
      if (end < this.pagination.last_page) pages.push(this.pagination.last_page);

      return pages;
    },
  },

  created() {
    this.load_orders();
  },

  methods: {
    load_orders() {
      this.loading = true;
      const params = {
        page: this.current_page,
        per_page: this.per_page,
        search: this.search_key,
        status: this.selected_status,
      };

      axios
        .get(
          `digital-product-orders`,
          { params }
        )
        .then((response) => {
          const data = response.data?.data?.data;
          
          this.orders = data.data || [];
          this.pagination = {
            current_page: data.current_page,
            last_page: data.last_page,
            from: data.from,
            to: data.to,
            total: data.total,
          };
        })
        .catch((error) => {
          console.error("Error loading orders:", error);
          this.$alertMessage.error("Failed to load orders");
        })
        .finally(() => {
          this.loading = false;
        });
    },

    on_search: debounce(function () {
      this.current_page = 1;
      this.load_orders();
    }, 300),

    on_status_filter() {
      this.current_page = 1;
      this.load_orders();
    },

    go_to_page(page) {
      if (page === "..." || page < 1 || page > this.pagination.last_page) return;
      this.current_page = page;
      this.load_orders();
    },

    on_per_page_change() {
      this.current_page = 1;
      this.load_orders();
    },

    delete_order(order_id) {
      if (!confirm("Are you sure you want to delete this order?")) return;

      axios
        .delete(
          `${app_config.api_host}/api/${app_config.api_version}/digital-product-orders/${order_id}`
        )
        .then(() => {
          this.$alertMessage.success("Order deleted successfully");
          this.load_orders();
        })
        .catch((error) => {
          console.error("Error deleting order:", error);
          this.$alertMessage.error("Failed to delete order");
        });
    },

    truncate(text, length) {
      if (!text) return "";
      return text.length > length ? text.substring(0, length) + "..." : text;
    },

    format_date(date) {
      if (!date) return "-";
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    },

    getStatusStyle(status) {
      const styles = {
        pending: "background:#fff3cd;color:#856404;",
        due: "background:#f8d7da;color:#721c24;",
        compelete: "background:#d4edda;color:#155724;",
      };
      return styles[status] || "background:#e9ecef;color:#333;";
    },
  },
};
</script>

<style scoped></style>
