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
                  <i class="fa fa-comments mr-2" style="color: #6f42c1"></i>All
                  Project Comments
                </h5>
              </div>

              <div class="col-12 col-md-3 mb-2 mb-md-0">
                <input
                  class="form-control form-control-sm"
                  v-model="search_key"
                  @keyup="on_search"
                  placeholder="Search comments..."
                />
              </div>

              <!-- Project Filter Dropdown -->
              <div class="col-12 col-md-3 mb-2 mb-md-0">
                <select
                  class="form-control form-control-sm"
                  v-model="selected_project_id"
                  @change="on_project_filter"
                >
                  <option value="">-- All Projects --</option>
                  <option
                    v-for="project in projects"
                    :key="project.id"
                    :value="project.id"
                  >
                    {{ project.name }}
                  </option>
                </select>
              </div>

              <div
                class="col-12 col-md-3 text-md-right d-flex justify-content-end"
                style="gap: 6px"
              >
                <button
                  class="btn btn-outline-primary btn-sm"
                  @click="load_comments"
                  title="Reload"
                >
                  <i class="fa fa-refresh"></i>
                </button>
                <router-link
                  :to="{ name: 'ProjectWiseComments' }"
                  class="btn btn-sm"
                  style="background: #6f42c1; color: #fff; font-size: 0.82rem"
                >
                  <i class="fa fa-list mr-1"></i>Project Wise
                </router-link>
              </div>
            </div>
          </div>

          <!-- Body -->
          <div class="card-body p-0">
            <div v-if="loading" class="text-center py-5">
              <i class="fa fa-spinner fa-spin fa-2x" style="color: #6f42c1"></i>
              <p class="mt-2 text-muted">Loading comments...</p>
            </div>

            <div v-else class="table-responsive">
              <table class="table table-hover table-bordered mb-0 text-center">
                <thead style="background: #f1f3f5">
                  <tr>
                    <th style="width: 50px">#</th>
                    <th style="min-width: 220px; text-align: left">Comment</th>
                    <th>Project</th>
                    <th>User / Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width: 120px">Actions</th>
                  </tr>
                </thead>

                <tbody v-if="comments.length">
                  <template v-for="comment in comments" :key="comment.id">
                    <tr>
                      <td>{{ comment.id }}</td>
                      <td
                        class="text-left text-wrap"
                        style="max-width: 300px; word-break: break-word"
                      >
                        <div v-html="truncate(comment.comment, 80)"></div>
                      </td>
                      <td :title="comment.project ? comment.project.name : ''">
                        {{
                          truncate(
                            comment.project
                              ? comment.project.name
                              : String(comment.project_id),
                            50,
                          )
                        }}
                      </td>
                      <td>
                        {{
                          comment.user ? comment.user.name : comment.name || "-"
                        }}
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
                            comment.status === 'active'
                              ? 'background:#d4edda;color:#155724;'
                              : 'background:#fff3cd;color:#856404;'
                          "
                          >{{ comment.status }}</span
                        >
                      </td>
                      <td style="font-size: 0.82rem">
                        {{ format_date(comment.created_at) }}
                      </td>
                      <td>
                        <div class="d-flex flex-column" style="gap: 4px">
                          <button
                            class="btn btn-sm"
                            style="font-size: 0.73rem"
                            :style="
                              active_reply_id === comment.id
                                ? 'background:#6c757d;color:#fff;'
                                : 'background:#6f42c1;color:#fff;'
                            "
                            @click="toggle_reply_form(comment.id)"
                          >
                            <i class="fa fa-reply mr-1"></i>
                            {{
                              active_reply_id === comment.id
                                ? "Cancel"
                                : "Reply"
                            }}
                          </button>
                          <button
                            class="btn btn-sm"
                            style="
                              font-size: 0.73rem;
                              background: #17a2b8;
                              color: #fff;
                            "
                            @click="go_to_replies(comment)"
                          >
                            <i class="fa fa-comments mr-1"></i>Replies
                          </button>
                          <button
                            class="btn btn-sm"
                            style="
                              font-size: 0.73rem;
                              background: #dc3545;
                              color: #fff;
                            "
                            @click="delete_comment(comment.id)"
                          >
                            <i class="fa fa-trash mr-1"></i>Delete
                          </button>
                        </div>
                      </td>
                    </tr>

                    <!-- Inline Reply Form -->
                    <tr v-if="active_reply_id === comment.id">
                      <td colspan="7" style="padding: 0">
                        <div
                          class="p-3"
                          style="
                            background: #f3eeff;
                            border-left: 4px solid #6f42c1;
                            text-align: left;
                          "
                        >
                          <p
                            class="mb-2"
                            style="font-size: 0.85rem; color: #555"
                          >
                            <i
                              class="fa fa-reply mr-1"
                              style="color: #6f42c1"
                            ></i>
                            Replying to:
                            <em style="color: #333"
                              >"{{
                                strip_html(comment.comment).substring(0, 100)
                              }}{{
                                strip_html(comment.comment).length > 100
                                  ? "..."
                                  : ""
                              }}"</em
                            >
                          </p>
                          <div class="mb-2">
                            <input
                              class="form-control form-control-sm mb-2"
                              v-model="reply_name"
                              placeholder="Your name (optional)"
                            />
                          </div>
                          <div class="mb-2">
                            <textarea
                              class="form-control"
                              rows="3"
                              v-model="reply_text"
                              placeholder="Write your reply here..."
                              style="resize: vertical; font-size: 0.9rem"
                            ></textarea>
                            <small
                              v-if="reply_error"
                              class="text-danger"
                              style="font-size: 0.8rem"
                              >{{ reply_error }}</small
                            >
                          </div>
                          <div class="d-flex" style="gap: 8px">
                            <button
                              class="btn btn-sm"
                              style="
                                background: #6f42c1;
                                color: #fff;
                                font-size: 0.8rem;
                              "
                              @click="submit_reply(comment)"
                              :disabled="reply_loading"
                            >
                              <i class="fa fa-paper-plane mr-1"></i>
                              {{
                                reply_loading ? "Submitting..." : "Submit Reply"
                              }}
                            </button>
                            <button
                              class="btn btn-outline-secondary btn-sm"
                              @click="cancel_reply"
                              style="font-size: 0.8rem"
                            >
                              <i class="fa fa-times mr-1"></i>Cancel
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>

                <tbody v-else>
                  <tr>
                    <td colspan="7">
                      <div class="d-flex flex-column align-items-center py-5">
                        <div
                          style="
                            width: 120px;
                            height: 120px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: linear-gradient(
                              135deg,
                              #6f42c1,
                              #a78bfa
                            );
                            border-radius: 12px;
                            opacity: 0.6;
                          "
                        >
                          <i
                            class="fa fa-comments"
                            style="font-size: 60px; color: #fff"
                          ></i>
                        </div>
                        <h5 class="text-muted mt-3">No Comments Found</h5>
                        <p class="text-secondary">
                          Try adjusting your search or project filter.
                        </p>
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
                Showing {{ pagination.from || 0 }}&ndash;{{
                  pagination.to || 0
                }}
                of {{ pagination.total || 0 }} comments
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
                      : 'background:#6f42c1;color:#fff;'
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
                      ? 'background:#6f42c1;color:#fff;'
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
                      : 'background:#6f42c1;color:#fff;'
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
import debounce from "@/shared/helpers/debounce";

export default {
  data() {
    return {
      comments: [],
      projects: [],
      loading: false,
      search_key: "",
      selected_project_id: "",
      pagination: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },
      current_page: 1,
      per_page: 10,
      active_reply_id: null,
      reply_name: "",
      reply_text: "",
      reply_error: "",
      reply_loading: false,
    };
  },

  computed: {
    api_base() {
      return `${app_config.api_host}/${app_config.api_version}`;
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
    this.load_projects();
    this.load_comments();
  },

  methods: {
    async load_projects() {
      try {
        const res = await axios.get(`${this.api_base}/projects`, {
          params: { get_all: 1, limit: 200 },
        });
        if (res.data?.data) {
          this.projects = Array.isArray(res.data.data)
            ? res.data.data
            : res.data.data.data || [];
        }
      } catch (_) {}
    },

    async load_comments() {
      this.loading = true;
      try {
        const url = this.selected_project_id
          ? `${this.api_base}/project-comments/project/${this.selected_project_id}`
          : `${this.api_base}/project-comments`;
        const params = { page: this.current_page, limit: this.per_page };
        if (this.search_key.trim()) params.search = this.search_key.trim();
        const res = await axios.get(url, { params });
        const payload = res.data?.data;
        if (payload) {
          this.comments = payload.data || [];
          this.pagination = {
            current_page: payload.current_page || 1,
            last_page: payload.last_page || 1,
            from: payload.from || 0,
            to: payload.to || 0,
            total: payload.total || 0,
          };
        }
      } catch (_) {
        window.s_warning && window.s_warning("Failed to load comments.");
      } finally {
        this.loading = false;
      }
    },

    on_search: debounce(function () {
      this.current_page = 1;
      this.load_comments();
    }, 500),
    on_project_filter() {
      this.current_page = 1;
      this.load_comments();
    },
    go_to_page(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.current_page = page;
      this.load_comments();
    },
    on_per_page_change() {
      this.current_page = 1;
      this.load_comments();
    },

    toggle_reply_form(comment_id) {
      if (this.active_reply_id === comment_id) {
        this.cancel_reply();
      } else {
        this.active_reply_id = comment_id;
        this.reply_name = "";
        this.reply_text = "";
        this.reply_error = "";
      }
    },

    cancel_reply() {
      this.active_reply_id = null;
      this.reply_name = "";
      this.reply_text = "";
      this.reply_error = "";
    },

    async submit_reply(comment) {
      this.reply_error = "";
      const text = this.reply_text.trim();
      if (!text) {
        this.reply_error = "Reply is required.";
        return;
      }
      if (text.length < 5) {
        this.reply_error = "Reply must be at least 5 characters.";
        return;
      }

      this.reply_loading = true;
      try {
        const payload = {
          parent_id: comment.id,
          project_id: comment.project_id,
          comment: text,
        };
        if (this.reply_name.trim()) payload.name = this.reply_name.trim();

        const res = await axios.post(
          `${this.api_base}/project-comments/reply`,
          payload,
        );
        if (res.data?.status === "success") {
          window.s_alert &&
            window.s_alert(res.data.message || "Reply submitted successfully.");
          this.cancel_reply();
        } else {
          window.s_warning &&
            window.s_warning(res.data?.message || "Failed to submit reply.");
        }
      } catch (e) {
        if (e.response?.data?.errors) {
          this.reply_error = Object.values(e.response.data.errors)
            .flat()
            .join(" ");
        } else {
          window.s_warning && window.s_warning("An error occurred.");
        }
      } finally {
        this.reply_loading = false;
      }
    },

    go_to_replies(comment) {
      this.$router.push({
        name: "ProjectCommentReplies",
        params: { comment_id: comment.id },
        query: {
          preview: comment.comment
            ? this.strip_html(comment.comment).substring(0, 120)
            : "",
          commenter: comment.user ? comment.user.name : comment.name || "",
        },
      });
    },

    truncate(str, len) {
      if (!str) return "-";
      return str.length > len ? str.substring(0, len) + "…" : str;
    },

    strip_html(str) {
      if (!str) return "";
      return str.replace(/<[^>]*>/g, "");
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
      if (
        !confirm(
          "Are you sure you want to delete this comment? Its replies will also be deleted.",
        )
      )
        return;
      try {
        const res = await axios.post(
          `${this.api_base}/project-comments/destroy/${id}`,
        );
        if (res.data.status === "success" || res.status === 200) {
          window.s_alert("Comment deleted successfully", "success");
          this.load_comments();
        }
      } catch (err) {
        console.error("Error deleting comment:", err);
        window.s_alert("Failed to delete comment", "error");
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
