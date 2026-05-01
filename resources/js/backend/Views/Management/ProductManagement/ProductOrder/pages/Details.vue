<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <!-- Back Button -->
        <div class="mb-3">
          <router-link :to="{ name: 'AllProductOrder' }" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-2"></i>Back to Orders
          </router-link>
        </div>

        <div class="card">
          <!-- Header -->
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12 col-md-6">
                <h5 class="mb-0" style="font-size: 0.95rem; font-weight: 700">
                  <i class="fa fa-receipt mr-2" style="color: #28a745"></i>Order #{{ order_id }}
                </h5>
              </div>
              <div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
                <span v-if="order" class="badge"
                  :style="getStatusStyle(order.payment_status)"
                  style="font-size: 0.85rem; padding: 6px 12px; border-radius: 12px;">
                  {{ order.payment_status ? order.payment_status.toUpperCase() : '-' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Body -->
          <div class="card-body text-center py-5" v-if="loading" >
            <i class="fa fa-spinner fa-spin fa-2x" style="color: #28a745"></i>
            <p class="mt-2 text-muted">Loading order details...</p>
          </div>

          <div class="card-body" v-else-if="order">
            <div class="row">
              <!-- Customer Information -->
              <div class="col-md-6">
                <h6 class="mb-3" style="font-weight: 700; color: #333">
                  <i class="fa fa-user mr-2" style="color: #28a745"></i>Customer Information
                </h6>
                <div class="info-block">
                  <div class="info-row">
                    <label>Name:</label>
                    <span>{{ order.first_name }} {{ order.last_name }}</span>
                  </div>
                  <div class="info-row">
                    <label>Email:</label>
                    <span><a :href="`mailto:${order.email}`">{{ order.email }}</a></span>
                  </div>
                  <div class="info-row">
                    <label>Phone:</label>
                    <span><a :href="`tel:${order.phone}`">{{ order.phone }}</a></span>
                  </div>
                </div>
              </div>

              <!-- Order Information -->
              <div class="col-md-6">
                <h6 class="mb-3" style="font-weight: 700; color: #333">
                  <i class="fa fa-info-circle mr-2" style="color: #28a745"></i>Order Information
                </h6>
                <div class="info-block">
                  <div class="info-row">
                    <label>Product:</label>
                    <span>{{ order.digital_product ? order.digital_product.title : 'N/A' }}</span>
                  </div>
                  <div class="info-row">
                    <label>Transaction ID:</label>
                    <span style="font-weight: 600; color: #0066cc">{{ order.trx_number }}</span>
                  </div>
                  <div class="info-row">
                    <label>Order Date:</label>
                    <span>{{ format_date(order.created_at) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Payment Details -->
            <div class="row mt-4">
              <div class="col-md-6">
                <h6 class="mb-3" style="font-weight: 700; color: #333">
                  <i class="fa fa-credit-card mr-2" style="color: #28a745"></i>Payment Details
                </h6>
                <div class="info-block">
                  <div class="info-row" v-if="order.payment_details">
                    <label>Status:</label>
                    <span :style="getStatusStyle(order.payment_status)" 
                      style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem;">
                      {{ order.payment_status }}
                    </span>
                  </div>
                  <div class="info-row" v-if="order.payment_details && order.payment_details.timestamp">
                    <label>Timestamp:</label>
                    <span>{{ format_date(order.payment_details.timestamp) }}</span>
                  </div>
                </div>
              </div>

              <!-- Update Status -->
              <div class="col-md-6">
                <h6 class="mb-3" style="font-weight: 700; color: #333">
                  <i class="fa fa-edit mr-2" style="color: #28a745"></i>Update Status
                </h6>
                <div class="form-group">
                  <select v-model="new_status" class="form-control form-control-sm">
                    <option value="">-- Select Status --</option>
                    <option value="pending">Pending</option>
                    <option value="due">Due</option>
                    <option value="compelete">Completed</option>
                  </select>
                </div>
                <button
                  @click="update_status"
                  :disabled="!new_status || updating_status"
                  class="btn btn-sm btn-primary"
                  style="background: #28a745; border-color: #28a745; color: #fff;"
                >
                  <i class="fa fa-save mr-1"></i>{{ updating_status ? "Updating..." : "Update Status" }}
                </button>
              </div>
            </div>

            <!-- Document Provision (After Order Completion) -->
            <div class="row mt-4" v-if="order.payment_status === 'compelete'">
              <div class="col-md-12">
                <div class="card" style="background: #f0f8ff; border-color: #0066cc;">
                  <div class="card-header" style="background: #0066cc; color: #fff;">
                    <h6 class="mb-0" style="font-weight: 700;">
                      <i class="fa fa-file-download mr-2"></i>Provide Download Access
                    </h6>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label style="font-weight: 600;">License Key / Download Link</label>
                      <textarea
                        v-model="license_key"
                        class="form-control"
                        rows="4"
                        placeholder="Paste the license key, download link, or access credentials here..."
                      ></textarea>
                      <small class="form-text text-muted mt-2">
                        This will be sent to the customer along with the order confirmation.
                      </small>
                    </div>

                    <div class="form-group">
                      <label style="font-weight: 600;">Documentation (Optional)</label>
                      <textarea
                        v-model="documentation"
                        class="form-control"
                        rows="4"
                        placeholder="Add installation guides, usage documentation, or support information..."
                      ></textarea>
                    </div>

                    <div class="form-group">
                      <label style="font-weight: 600;">Support Email / Contact</label>
                      <input
                        v-model="support_contact"
                        type="email"
                        class="form-control form-control-sm"
                        placeholder="support@example.com"
                      />
                    </div>

                    <div class="alert alert-info" role="alert" style="font-size: 0.9rem;">
                      <i class="fa fa-info-circle mr-2"></i>
                      After saving, the customer will receive an email with the license key and documentation.
                    </div>

                    <div class="d-flex" style="gap: 8px;">
                      <button
                        @click="save_download_access"
                        :disabled="!license_key || saving_access"
                        class="btn btn-sm btn-success"
                      >
                        <i class="fa fa-save mr-1"></i>{{ saving_access ? "Saving..." : "Save & Send to Customer" }}
                      </button>
                      <button
                        @click="send_reminder_email"
                        class="btn btn-sm btn-info"
                        :disabled="sending_email"
                      >
                        <i class="fa fa-envelope mr-1"></i>{{ sending_email ? "Sending..." : "Send Reminder" }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order Notes / Comments -->
            <div class="row mt-4">
              <div class="col-md-12">
                <h6 class="mb-3" style="font-weight: 700; color: #333">
                  <i class="fa fa-comments mr-2" style="color: #28a745"></i>Order Notes
                </h6>

                <!-- Display Notes -->
                <div v-if="order_notes && order_notes.length" class="mb-3">
                  <div v-for="note in order_notes" :key="note.id" class="alert alert-light" style="border-left: 4px solid #28a745;">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <strong>{{ note.admin_name || 'Admin' }}</strong>
                        <small class="text-muted ml-2">{{ format_date(note.created_at) }}</small>
                      </div>
                    </div>
                    <p class="mt-2 mb-0">{{ note.note }}</p>
                  </div>
                </div>

                <!-- Add Note Form -->
                <div class="form-group">
                  <textarea
                    v-model="new_note"
                    class="form-control"
                    rows="3"
                    placeholder="Add a note to this order..."
                  ></textarea>
                </div>
                <button
                  @click="add_note"
                  :disabled="!new_note || adding_note"
                  class="btn btn-sm btn-primary"
                  style="background: #28a745; border-color: #28a745; color: #fff;"
                >
                  <i class="fa fa-plus mr-1"></i>{{ adding_note ? "Adding..." : "Add Note" }}
                </button>
              </div>
            </div>
          </div>

          <div class="card-body text-center py-5" v-else >
            <h5 class="text-muted">Order not found</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import app_config from "@config/app_config";

export default {
  data() {
    return {
      order_id: null,
      order: null,
      loading: false,
      new_status: "",
      updating_status: false,
      license_key: "",
      documentation: "",
      support_contact: "",
      saving_access: false,
      sending_email: false,
      order_notes: [],
      new_note: "",
      adding_note: false,
    };
  },

  created() {
    this.order_id = this.$route.params.order_id;
    this.load_order();
  },

  methods: {
    load_order() {
      this.loading = true;
      axios
        .get(
          `digital-product-orders/${this.order_id}`
        )
        .then((response) => {
          const data = response.data.data;
          this.order = data;
          this.new_status = data.payment_status;
          
          // Load order notes if available
          if (data.notes) {
            this.order_notes = data.notes;
          }
        })
        .catch((error) => {
          console.error("Error loading order:", error);
          this.$alertMessage.error("Failed to load order details");
        })
        .finally(() => {
          this.loading = false;
        });
    },

    update_status() {
      if (!this.new_status) return;

      this.updating_status = true;
      axios
        .patch(
          `${app_config.api_host}/api/${app_config.api_version}/digital-product-orders/${this.order_id}/status`,
          { payment_status: this.new_status }
        )
        .then(() => {
          this.$alertMessage.success("Order status updated successfully");
          this.load_order();
        })
        .catch((error) => {
          console.error("Error updating status:", error);
          this.$alertMessage.error("Failed to update status");
        })
        .finally(() => {
          this.updating_status = false;
        });
    },

    save_download_access() {
      if (!this.license_key) {
        this.$alertMessage.error("License key is required");
        return;
      }

      this.saving_access = true;
      axios
        .post(
          `${app_config.api_host}/api/${app_config.api_version}/digital-product-orders/${this.order_id}/provide-access`,
          {
            license_key: this.license_key,
            documentation: this.documentation,
            support_contact: this.support_contact,
          }
        )
        .then(() => {
          this.$alertMessage.success("Download access saved and email sent to customer");
          this.license_key = "";
          this.documentation = "";
          this.support_contact = "";
          this.load_order();
        })
        .catch((error) => {
          console.error("Error saving access:", error);
          this.$alertMessage.error("Failed to save download access");
        })
        .finally(() => {
          this.saving_access = false;
        });
    },

    send_reminder_email() {
      this.sending_email = true;
      axios
        .post(
          `${app_config.api_host}/api/${app_config.api_version}/digital-product-orders/${this.order_id}/send-reminder`
        )
        .then(() => {
          this.$alertMessage.success("Reminder email sent to customer");
        })
        .catch((error) => {
          console.error("Error sending email:", error);
          this.$alertMessage.error("Failed to send reminder email");
        })
        .finally(() => {
          this.sending_email = false;
        });
    },

    add_note() {
      if (!this.new_note) return;

      this.adding_note = true;
      axios
        .post(
          `${app_config.api_host}/api/${app_config.api_version}/digital-product-orders/${this.order_id}/notes`,
          { note: this.new_note }
        )
        .then(() => {
          this.$alertMessage.success("Note added successfully");
          this.new_note = "";
          this.load_order();
        })
        .catch((error) => {
          console.error("Error adding note:", error);
          this.$alertMessage.error("Failed to add note");
        })
        .finally(() => {
          this.adding_note = false;
        });
    },

    format_date(date) {
      if (!date) return "-";
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
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

<style scoped>
.info-block {
  background: #f8f9fa;
  padding: 12px;
  border-radius: 6px;
  border-left: 4px solid #28a745;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row label {
  font-weight: 600;
  color: #555;
  min-width: 120px;
}

.info-row span {
  color: #333;
  text-align: right;
  flex: 1;
}

.info-row a {
  color: #0066cc;
  text-decoration: none;
}

.info-row a:hover {
  text-decoration: underline;
}
</style>
