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

        <div class="card order-card">
          <!-- Header -->
          <div class="card-header order-card-header">
            <div class="row align-items-center">
              <div class="col-12 col-md-6">
                <h5 class="mb-0 order-title">
                  <i class="fa fa-receipt mr-2 accent-icon"></i>Order #{{ order_id }}
                </h5>
              </div>
              <div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
                <span
                  v-if="order"
                  class="status-badge"
                  :class="'status-' + order.payment_status"
                >
                  Payment: {{ order.payment_status ? order.payment_status.toUpperCase() : "-" }}
                </span>
              </div>
            </div>
          </div>

          <!-- Loading -->
          <div class="card-body text-center py-5" v-if="loading">
            <i class="fa fa-spinner fa-spin fa-2x accent-icon"></i>
            <p class="mt-2 text-muted">Loading order details...</p>
          </div>

          <div class="card-body" v-else-if="order">

            <!-- Product Details Card -->
            <div class="section-card product-section mb-4" v-if="order.digital_product">
              <div class="section-header">
                <h6 class="section-title">
                  <i class="fa fa-box mr-2 accent-icon"></i>Product Details
                </h6>
              </div>
              <div class="product-details-body">
                <div class="product-thumb-wrap" v-if="order.digital_product.thumbnail">
                  <img :src="order.digital_product.thumbnail" :alt="order.digital_product.title" class="product-thumb" />
                </div>
                <div class="product-thumb-placeholder" v-else>
                  <i class="fa fa-cube"></i>
                </div>
                <div class="product-info-grid">
                  <div class="product-info-item">
                    <span class="info-label">Title</span>
                    <span class="info-value product-title-text">{{ order.digital_product.title || "N/A" }}</span>
                  </div>
                  <div class="product-info-item" v-if="order.digital_product.category">
                    <span class="info-label">Category</span>
                    <span class="info-value">{{ order.digital_product.category.name || order.digital_product.category }}</span>
                  </div>
                  <div class="product-info-item" v-if="order.digital_product.price !== undefined">
                    <span class="info-label">Price</span>
                    <span class="info-value price-text">${{ parseFloat(order.digital_product.price || 0).toFixed(2) }}</span>
                  </div>
                  <div class="product-info-item" v-if="order.digital_product.type">
                    <span class="info-label">Type</span>
                    <span class="info-value">
                      <span class="type-badge">{{ order.digital_product.type }}</span>
                    </span>
                  </div>
                  <div class="product-info-item" v-if="order.digital_product.slug">
                    <span class="info-label">Slug</span>
                    <span class="info-value slug-text">{{ order.digital_product.slug }}</span>
                  </div>
                  <div class="product-info-item full-width" v-if="order.digital_product.short_description || order.digital_product.description">
                    <span class="info-label">Description</span>
                    <span class="info-value description-text">{{ order.digital_product.short_description || truncate(order.digital_product.description, 200) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Customer Information -->
              <div class="col-md-6 mb-4">
                <div class="section-card h-100">
                  <div class="section-header">
                    <h6 class="section-title">
                      <i class="fa fa-user mr-2 accent-icon"></i>Customer Information
                    </h6>
                  </div>
                  <div class="section-body">
                    <div class="info-row">
                      <label class="info-label">Name</label>
                      <span class="info-value">{{ order.first_name }} {{ order.last_name }}</span>
                    </div>
                    <div class="info-row">
                      <label class="info-label">Email</label>
                      <span class="info-value"><a :href="`mailto:${order.email}`" class="info-link">{{ order.email }}</a></span>
                    </div>
                    <div class="info-row">
                      <label class="info-label">Phone</label>
                      <span class="info-value"><a :href="`tel:${order.phone}`" class="info-link">{{ order.phone }}</a></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Order Information -->
              <div class="col-md-6 mb-4">
                <div class="section-card h-100">
                  <div class="section-header">
                    <h6 class="section-title">
                      <i class="fa fa-info-circle mr-2 accent-icon"></i>Order Information
                    </h6>
                  </div>
                  <div class="section-body">
                    <div class="info-row">
                      <label class="info-label">Product</label>
                      <span class="info-value">{{ order.digital_product ? order.digital_product.title : "N/A" }}</span>
                    </div>
                    <div class="info-row">
                      <label class="info-label">Transaction ID</label>
                      <span class="info-value trx-id">{{ order.trx_number }}</span>
                    </div>
                    <div class="info-row">
                      <label class="info-label">Order Date</label>
                      <span class="info-value">{{ format_date(order.created_at) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Payment Details -->
              <div class="col-md-6 mb-4">
                <div class="section-card h-100">
                  <div class="section-header">
                    <h6 class="section-title">
                      <i class="fa fa-credit-card mr-2 accent-icon"></i>Payment Details
                    </h6>
                  </div>
                  <div class="section-body">
                    <div class="info-row" v-if="order.payment_details">
                      <label class="info-label">Status</label>
                      <span class="info-value">
                        <span class="status-badge" :class="'status-' + order.payment_status">
                          {{ order.payment_status }}
                        </span>
                      </span>
                    </div>
                    <div class="info-row" v-if="order.payment_details && order.payment_details.timestamp">
                      <label class="info-label">Timestamp</label>
                      <span class="info-value">{{ format_date(order.payment_details.timestamp) }}</span>
                    </div>
                    <div class="info-row" v-if="!order.payment_details">
                      <span class="info-value text-muted">No payment details available.</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Update Status -->
              <div class="col-md-6 mb-4">
                <div class="section-card h-100">
                  <div class="section-header">
                    <h6 class="section-title">
                      <i class="fa fa-edit mr-2 accent-icon"></i>Update Payment Status
                    </h6>
                  </div>
                  <div class="section-body">
                    <div class="form-group">
                      <select v-model="new_status" class="form-control form-control-sm themed-input">
                        <option value="">-- Select Status --</option>
                        <option value="pending">Pending</option>
                        <option value="due">Due</option>
                        <option value="compelete">Completed</option>
                      </select>
                    </div>
                    <button
                      @click="update_status"
                      :disabled="!new_status || updating_status"
                      class="btn btn-sm btn-accent"
                    >
                      <i class="fa fa-save mr-1"></i>{{ updating_status ? "Updating..." : "Update Status" }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Document Provision (After Order Completion) -->
            <div class="row" v-if="order.payment_status === 'compelete'">
              <div class="col-md-12 mb-4">
                <div class="section-card access-card">
                  <div class="section-header access-header">
                    <h6 class="section-title text-white mb-0">
                      <i class="fa fa-file-download mr-2"></i>Provide Download Access
                    </h6>
                  </div>
                  <div class="section-body">
                    <div class="form-group">
                      <label class="form-label-themed">License Key / Download Link</label>
                      <textarea
                        v-model="license_key"
                        class="form-control themed-input"
                        rows="4"
                        placeholder="Paste the license key, download link, or access credentials here..."
                      ></textarea>
                      <small class="form-text text-muted mt-2">This will be sent to the customer along with the order confirmation.</small>
                    </div>
                    <div class="form-group">
                      <label class="form-label-themed">Documentation (Optional)</label>
                      <textarea
                        v-model="documentation"
                        class="form-control themed-input"
                        rows="4"
                        placeholder="Add installation guides, usage documentation, or support information..."
                      ></textarea>
                    </div>
                    <div class="form-group">
                      <label class="form-label-themed">Support Email / Contact</label>
                      <input v-model="support_contact" type="email" class="form-control form-control-sm themed-input" placeholder="support@example.com" />
                    </div>
                    <div class="alert alert-info-themed" role="alert">
                      <i class="fa fa-info-circle mr-2"></i>
                      After saving, the customer will receive an email with the license key and documentation.
                    </div>
                    <div class="d-flex" style="gap: 8px; flex-wrap: wrap">
                      <button @click="save_download_access" :disabled="!license_key || saving_access" class="btn btn-sm btn-success-themed">
                        <i class="fa fa-save mr-1"></i>{{ saving_access ? "Saving..." : "Save & Send to Customer" }}
                      </button>
                      <button @click="send_reminder_email" class="btn btn-sm btn-info-themed" :disabled="sending_email">
                        <i class="fa fa-envelope mr-1"></i>{{ sending_email ? "Sending..." : "Send Reminder" }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order Notes -->
            <div class="row">
              <div class="col-md-12 mb-4">
                <div class="section-card">
                  <div class="section-header">
                    <h6 class="section-title">
                      <i class="fa fa-comments mr-2 accent-icon"></i>Order Notes
                    </h6>
                  </div>
                  <div class="section-body">
                    <div v-if="order_notes && order_notes.length" class="mb-3">
                      <div v-for="note in order_notes" :key="note.id" class="note-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                          <strong class="note-author">{{ note.admin_name || "Admin" }}</strong>
                          <small class="text-muted">{{ format_date(note.created_at) }}</small>
                        </div>
                        <p class="note-text mb-0">{{ note.note }}</p>
                      </div>
                    </div>
                    <div v-else class="text-muted mb-3" style="font-size: 0.88rem">No notes yet.</div>
                    <div class="form-group">
                      <textarea v-model="new_note" class="form-control themed-input" rows="3" placeholder="Add a note to this order..."></textarea>
                    </div>
                    <button
                      @click="add_note"
                      :disabled="!new_note || adding_note"
                      class="btn btn-sm btn-accent"
                    >
                      <i class="fa fa-plus mr-1"></i>{{ adding_note ? "Adding..." : "Add Note" }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="card-body text-center py-5" v-else>
            <i class="fa fa-inbox fa-2x text-muted mb-2"></i>
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
        .get(`digital-product-orders/${this.order_id}`)
        .then((response) => {
          const data = response.data.data.data;
          this.order = data;
          this.new_status = data.payment_status;
          if (data.notes) {
            this.order_notes = data.notes;
          }
        })
        .catch((error) => {
          console.error("Error loading order:", error);
          s_error("Failed to load order details");
        })
        .finally(() => {
          this.loading = false;
        });
    },

    update_status() {
      if (!this.new_status) return;
      this.updating_status = true;
      axios
        .patch(`digital-product-orders/${this.order_id}/status`, { payment_status: this.new_status })
        .then(() => {
          s_alert("Order status updated successfully");
          this.load_order();
        })
        .catch((error) => {
          console.error("Error updating status:", error);
          s_error("Failed to update status");
        })
        .finally(() => {
          this.updating_status = false;
        });
    },

    save_download_access() {
      if (!this.license_key) {
        s_error("License key is required");
        return;
      }
      this.saving_access = true;
      axios
        .post(`digital-product-orders/${this.order_id}/provide-access`, {
          license_key: this.license_key,
          documentation: this.documentation,
          support_contact: this.support_contact,
        })
        .then(() => {
          s_alert("Download access saved and email sent to customer");
          this.license_key = "";
          this.documentation = "";
          this.support_contact = "";
          this.load_order();
        })
        .catch((error) => {
          console.error("Error saving access:", error);
          s_error("Failed to save download access");
        })
        .finally(() => {
          this.saving_access = false;
        });
    },

    send_reminder_email() {
      this.sending_email = true;
      axios
        .post(`digital-product-orders/${this.order_id}/send-reminder`)
        .then(() => {
          s_alert("Reminder email sent to customer");
        })
        .catch((error) => {
          console.error("Error sending email:", error);
          s_error("Failed to send reminder email");
        })
        .finally(() => {
          this.sending_email = false;
        });
    },

    add_note() {
      if (!this.new_note) return;
      this.adding_note = true;
      axios
        .post(`digital-product-orders/${this.order_id}/notes`, { note: this.new_note })
        .then(() => {
          s_alert("Note added successfully");
          this.new_note = "";
          this.load_order();
        })
        .catch((error) => {
          console.error("Error adding note:", error);
          s_error("Failed to add note");
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

    truncate(text, length) {
      if (!text) return "";
      return text.length > length ? text.substring(0, length) + "..." : text;
    },
  },
};
</script>

<style scoped>
/* ── Base / shared ─────────────────────────────────────── */
.order-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  box-shadow: var(--shadow-sm);
}

.order-card-header {
  background: var(--bg-card);
  border-bottom: 1px solid var(--border-color);
  padding: 12px 16px;
}

.order-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--text-primary);
}

.accent-icon {
  color: var(--success-color);
}

/* ── Status badges ─────────────────────────────────────── */
.status-badge {
  display: inline-block;
  font-size: 0.8rem;
  font-weight: 600;
  padding: 4px 12px;
  border-radius: 20px;
}

.status-pending  { background: rgba(245, 158, 11, 0.15); color: var(--warning-color); }
.status-due      { background: rgba(239, 68,  68, 0.15); color: var(--danger-color);  }
.status-compelete{ background: rgba(16,  185,129, 0.15); color: var(--success-color); }

/* ── Section cards ─────────────────────────────────────── */
.section-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  overflow: hidden;
}

.section-header {
  padding: 10px 16px;
  border-bottom: 1px solid var(--border-color);
  background: var(--bg-hover);
}

.section-title {
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
}

.section-body {
  padding: 14px 16px;
}

/* ── Info rows ─────────────────────────────────────────── */
.info-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 8px 0;
  border-bottom: 1px solid var(--border-color);
  gap: 8px;
}

.info-row:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.info-label {
  font-weight: 600;
  font-size: 0.82rem;
  color: var(--text-secondary);
  min-width: 110px;
  flex-shrink: 0;
}

.info-value {
  font-size: 0.85rem;
  color: var(--text-primary);
  text-align: right;
  flex: 1;
  word-break: break-word;
}

.info-link {
  color: var(--primary-color);
  text-decoration: none;
}

.info-link:hover {
  text-decoration: underline;
}

.trx-id {
  font-weight: 600;
  color: var(--primary-color);
}

/* ── Product details section ───────────────────────────── */
.product-section {
  border-left: 4px solid var(--success-color);
}

.product-details-body {
  display: flex;
  gap: 16px;
  padding: 14px 16px;
  align-items: flex-start;
  flex-wrap: wrap;
}

.product-thumb-wrap {
  flex-shrink: 0;
  width: 100px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid var(--border-color);
  background: var(--bg-hover);
}

.product-thumb {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-thumb-placeholder {
  flex-shrink: 0;
  width: 80px;
  height: 80px;
  border-radius: 8px;
  background: var(--bg-hover);
  border: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: var(--text-tertiary);
}

.product-info-grid {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 10px 16px;
  min-width: 0;
}

.product-info-item {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.product-info-item.full-width {
  grid-column: 1 / -1;
}

.product-info-item .info-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--text-tertiary);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  min-width: unset;
}

.product-info-item .info-value {
  font-size: 0.88rem;
  color: var(--text-primary);
  text-align: left;
  flex: unset;
}

.product-title-text {
  font-weight: 700;
  font-size: 1rem !important;
}

.price-text {
  font-weight: 700;
  color: var(--success-color) !important;
  font-size: 1rem !important;
}

.slug-text {
  font-family: monospace;
  font-size: 0.82rem !important;
  color: var(--text-tertiary) !important;
}

.description-text {
  color: var(--text-secondary) !important;
  line-height: 1.5;
}

.type-badge {
  display: inline-block;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 4px;
  background: rgba(59, 130, 246, 0.15);
  color: var(--primary-color);
  text-transform: capitalize;
}

/* ── Themed form controls ──────────────────────────────── */
.themed-input {
  background: var(--bg-input);
  color: var(--text-primary);
  border-color: var(--border-color);
}

.themed-input:focus {
  background: var(--bg-input);
  color: var(--text-primary);
  border-color: var(--success-color);
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

.form-label-themed {
  font-weight: 600;
  font-size: 0.85rem;
  color: var(--text-primary);
}

/* ── Buttons ───────────────────────────────────────────── */
.btn-accent {
  background: var(--success-color);
  border-color: var(--success-color);
  color: #fff;
  font-size: 0.82rem;
}

.btn-accent:hover:not(:disabled) {
  opacity: var(--opacity-hover);
  color: #fff;
}

.btn-accent:disabled {
  opacity: var(--opacity-disabled);
  color: #fff;
}

.btn-success-themed {
  background: var(--success-color);
  border-color: var(--success-color);
  color: #fff;
  font-size: 0.82rem;
}

.btn-success-themed:hover:not(:disabled) {
  opacity: var(--opacity-hover);
  color: #fff;
}

.btn-success-themed:disabled {
  opacity: var(--opacity-disabled);
  color: #fff;
}

.btn-info-themed {
  background: var(--info-color);
  border-color: var(--info-color);
  color: #fff;
  font-size: 0.82rem;
}

.btn-info-themed:hover:not(:disabled) {
  opacity: var(--opacity-hover);
  color: #fff;
}

.btn-info-themed:disabled {
  opacity: var(--opacity-disabled);
  color: #fff;
}

/* ── Access card (blue) ────────────────────────────────── */
.access-card {
  border-color: var(--primary-color);
}

.access-header {
  background: var(--primary-color);
  border-bottom-color: var(--primary-color);
}

/* ── Alert info ────────────────────────────────────────── */
.alert-info-themed {
  background: rgba(8, 145, 178, 0.1);
  border: 1px solid rgba(8, 145, 178, 0.3);
  color: var(--info-color);
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 0.88rem;
  margin-bottom: 14px;
}

/* ── Notes ─────────────────────────────────────────────── */
.note-item {
  background: var(--bg-hover);
  border-left: 4px solid var(--success-color);
  border-radius: 0 6px 6px 0;
  padding: 10px 12px;
  margin-bottom: 10px;
}

.note-author {
  font-size: 0.85rem;
  color: var(--text-primary);
}

.note-text {
  font-size: 0.85rem;
  color: var(--text-secondary);
  line-height: 1.5;
}
</style>
