<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <!-- Header -->
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12 col-md-6 mb-2 mb-md-0">
                <div
                  class="d-flex align-items-center flex-wrap"
                  style="gap: 10px"
                >
                  <h5 class="mb-0" style="font-size: 0.95rem; font-weight: 700">
                    <i class="fa fa-comments mr-2" style="color: #17a2b8"></i>
                    Replies — Comment #{{ comment_id }}
                  </h5>
                  <span
                    v-if="commenter_name"
                    style="
                      display: inline-flex;
                      align-items: center;
                      gap: 5px;
                      padding: 3px 10px;
                      border-radius: 20px;
                      background: #e8f4fd;
                      border: 1px solid #bee5f5;
                      font-size: 0.8rem;
                      font-weight: 600;
                      color: #0c6f9f;
                    "
                  >
                    <i class="fa fa-user-circle" style="font-size: 0.9rem"></i
                    >{{ commenter_name }}
                  </span>
                </div>
                <p
                  v-if="comment_preview"
                  class="mb-0 mt-1"
                  style="font-size: 0.8rem; color: #666; font-style: italic"
                >
                  "{{ comment_preview }}"
                </p>
              </div>
              <div
                class="col-12 col-md-6 text-md-right d-flex justify-content-end"
                style="gap: 6px"
              >
                <button
                  class="btn btn-outline-primary btn-sm"
                  @click="load_replies"
                  title="Reload"
                >
                  <i class="fa fa-refresh"></i>
                </button>
                <router-link
                  :to="{ name: 'AllProjectComment' }"
                  class="btn btn-outline-secondary btn-sm"
                  style="font-size: 0.82rem"
                >
                  <i class="fa fa-arrow-left mr-1"></i>All Comments
                </router-link>
              </div>
            </div>
          </div>

          <!-- New Reply Form -->
          <div class="card-body" style="border-bottom: 1px solid #dee2e6">
            <h6
              style="font-size: 0.88rem; font-weight: 600; margin-bottom: 10px"
            >
              <i class="fa fa-plus-circle mr-1" style="color: #6f42c1"></i>Add
              New Reply
            </h6>
            <div class="mb-2">
              <input
                class="form-control form-control-sm mb-2"
                v-model="new_reply_name"
                placeholder="Your name (optional)"
              />
            </div>
            <div class="mb-2">
              <textarea
                class="form-control"
                rows="3"
                v-model="new_reply_text"
                placeholder="Write a reply to this comment..."
                style="resize: vertical; font-size: 0.9rem"
              ></textarea>
              <small
                v-if="new_reply_error"
                class="text-danger"
                style="font-size: 0.8rem"
                >{{ new_reply_error }}</small
              >
            </div>
            <button
              class="btn btn-sm"
              style="background: #6f42c1; color: #fff; font-size: 0.82rem"
              @click="submit_new_reply"
              :disabled="new_reply_loading"
            >
              <i class="fa fa-paper-plane mr-1"></i>
              {{ new_reply_loading ? "Submitting..." : "Submit Reply" }}
            </button>
          </div>

          <!-- Replies List -->
          <div class="card-body p-0">
            <div
              v-if="!loading && total_replies > 0"
              style="
                padding: 8px 16px;
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
                font-size: 0.82rem;
              "
            >
              <strong>Total Replies:</strong>
              <span
                style="
                  padding: 1px 8px;
                  border-radius: 10px;
                  background: #d1ecf1;
                  color: #0c5460;
                  font-weight: 600;
                  margin-left: 4px;
                "
                >{{ total_replies }}</span
              >
            </div>

            <div v-if="loading" class="text-center py-5">
              <i class="fa fa-spinner fa-spin fa-2x" style="color: #17a2b8"></i>
              <p class="mt-2 text-muted">Loading replies...</p>
            </div>

            <div v-else class="table-responsive">
              <table class="table table-hover table-bordered mb-0 text-center">
                <thead style="background: #f1f3f5">
                  <tr>
                    <th style="width: 50px">#</th>
                    <th>User / Name</th>
                    <th style="min-width: 300px; text-align: left">Reply</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width: 120px">Actions</th>
                  </tr>
                </thead>

                <tbody v-if="replies.length">
                  <tr v-for="reply in replies" :key="reply.id">
                    <td>{{ reply.id }}</td>
                    <td>
                      {{ reply.user ? reply.user.name : reply.name || "-" }}
                    </td>
                    <td class="text-left" style="word-break: break-word">
                      <div v-html="reply.comment"></div>
                    </td>
                    <td>
                      <span
                        style="
                          padding: 2px 10px;
                          border-radius: 12px;
                          font-size: 0.78rem;
                          font-weight: 600;
                        "
                        :style="
                          reply.status === 'active'
                            ? 'background:#d4edda;color:#155724;'
                            : 'background:#fff3cd;color:#856404;'
                        "
                        >{{ reply.status || "active" }}</span
                      >
                    </td>
                    <td style="font-size: 0.82rem">
                      {{ format_date(reply.created_at) }}
                    </td>
                    <td>
                      <button
                        class="btn btn-sm"
                        style="
                          font-size: 0.73rem;
                          background: #dc3545;
                          color: #fff;
                        "
                        @click="delete_comment(reply.id)"
                      >
                        <i class="fa fa-trash mr-1"></i>Delete
                      </button>
                    </td>
                  </tr>
                </tbody>

                <tbody v-else>
                  <tr>
                    <td colspan="6">
                      <div class="d-flex flex-column align-items-center py-5">
                        <div
                          style="
                            width: 100px;
                            height: 100px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: linear-gradient(
                              135deg,
                              #6f42c1,
                              #a78bfa
                            );
                            border-radius: 12px;
                            opacity: 0.55;
                          "
                        >
                          <i
                            class="fa fa-comments"
                            style="font-size: 50px; color: #fff"
                          ></i>
                        </div>
                        <h5 class="text-muted mt-3">No Replies Yet</h5>
                        <p class="text-secondary">
                          Be the first to reply using the form above.
                        </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="card-footer py-2" v-if="!loading">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap"
              style="gap: 8px"
            >
              <span class="text-muted" style="font-size: 0.82rem">
                Showing {{ pagination.from || 0 }}&ndash;{{
                  pagination.to || 0
                }}
                of {{ pagination.total || 0 }} replies
              </span>
              <div
                v-if="pagination.last_page > 1"
                class="d-flex flex-wrap"
                style="gap: 4px"
              >
                <button
                  class="btn btn-sm"
                  :disabled="pagination.current_page === 1"
                  :style="
                    pagination.current_page === 1
                      ? 'background:#e9ecef;color:#aaa;cursor:not-allowed;'
                      : 'background:#17a2b8;color:#fff;'
                  "
                  style="min-width: 32px"
                  @click="go_to_page(pagination.current_page - 1)"
                >
                  &laquo;
                </button>
                <button
                  v-for="page in visible_pages"
                  :key="page"
                  class="btn btn-sm"
                  :style="
                    pagination.current_page === page
                      ? 'background:#17a2b8;color:#fff;'
                      : 'background:#e9ecef;color:#333;'
                  "
                  style="min-width: 32px"
                  @click="go_to_page(page)"
                >
                  {{ page }}
                </button>
                <button
                  class="btn btn-sm"
                  :disabled="pagination.current_page === pagination.last_page"
                  :style="
                    pagination.current_page === pagination.last_page
                      ? 'background:#e9ecef;color:#aaa;cursor:not-allowed;'
                      : 'background:#17a2b8;color:#fff;'
                  "
                  style="min-width: 32px"
                  @click="go_to_page(pagination.current_page + 1)"
                >
                  &raquo;
                </button>
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

export default {
  data() {
    return {
      replies: [],
      total_replies: 0,
      loading: false,
      pagination: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },
      current_page: 1,
      per_page: 10,
      new_reply_name: "",
      new_reply_text: "",
      new_reply_error: "",
      new_reply_loading: false,
    };
  },

  computed: {
    api_base() {
      return `${app_config.api_host}/${app_config.api_version}`;
    },
    comment_id() {
      return this.$route.params.comment_id;
    },
    comment_preview() {
      return this.$route.query.preview || "";
    },
    commenter_name() {
      return this.$route.query.commenter || "";
    },
    visible_pages() {
      const total = this.pagination.last_page || 1;
      const current = this.pagination.current_page || 1;
      const pages = [];
      for (
        let i = Math.max(1, current - 2);
        i <= Math.min(total, current + 2);
        i++
      )
        pages.push(i);
      return pages;
    },
  },

  created() {
    this.load_replies();
  },

  methods: {
    async load_replies() {
      this.loading = true;
      try {
        const res = await axios.get(
          `${this.api_base}/project-comments/${this.comment_id}/replies`,
          { params: { page: this.current_page, limit: this.per_page } },
        );
        const payload = res.data?.data;
        if (payload) {
          this.replies = payload.data || [];
          this.total_replies = payload.total_replies ?? payload.total ?? 0;
          this.pagination = {
            current_page: payload.current_page || 1,
            last_page: payload.last_page || 1,
            from: payload.from || 0,
            to: payload.to || 0,
            total: payload.total || 0,
          };
        }
      } catch (_) {
        window.s_warning && window.s_warning("Failed to load replies.");
      } finally {
        this.loading = false;
      }
    },

    go_to_page(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.current_page = page;
      this.load_replies();
    },
    on_per_page_change() {
      this.current_page = 1;
      this.load_replies();
    },

    async submit_new_reply() {
      this.new_reply_error = "";
      const text = this.new_reply_text.trim();
      if (!text) {
        this.new_reply_error = "Reply is required.";
        return;
      }
      if (text.length < 5) {
        this.new_reply_error = "Reply must be at least 5 characters.";
        return;
      }

      this.new_reply_loading = true;
      try {
        const payload = { parent_id: this.comment_id, comment: text };
        if (this.new_reply_name.trim())
          payload.name = this.new_reply_name.trim();
        const res = await axios.post(
          `${this.api_base}/project-comments/reply`,
          payload,
        );
        if (res.data?.status === "success") {
          window.s_alert &&
            window.s_alert(res.data.message || "Reply submitted successfully.");
          this.new_reply_name = "";
          this.new_reply_text = "";
          this.current_page = 1;
          await this.load_replies();
        } else {
          window.s_warning &&
            window.s_warning(res.data?.message || "Failed to submit reply.");
        }
      } catch (e) {
        if (e.response?.data?.errors)
          this.new_reply_error = Object.values(e.response.data.errors)
            .flat()
            .join(" ");
        else window.s_warning && window.s_warning("An error occurred.");
      } finally {
        this.new_reply_loading = false;
      }
    },

    format_date(date) {
      if (!date) return "-";
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    },

    async delete_comment(id) {
      if (!confirm("Are you sure you want to delete this reply?")) return;
      try {
        const res = await axios.post(
          `${this.api_base}/project-comments/destroy/${id}`,
        );
        if (res.data.status === "success" || res.status === 200) {
          window.s_alert("Reply deleted successfully", "success");
          this.load_replies();
        }
      } catch (err) {
        console.error("Error deleting reply:", err);
        window.s_alert("Failed to delete reply", "error");
      }
    },
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
  font-size: 0.85rem;
}
</style>
