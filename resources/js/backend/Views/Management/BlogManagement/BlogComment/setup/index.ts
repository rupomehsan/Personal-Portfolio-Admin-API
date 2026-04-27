/**
 * BlogComment Module Setup Configuration
 *
 * This file contains all configuration for the BlogComment module including:
 * - API endpoints and versioning (uses Blog module endpoints)
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * API Endpoints are nested under Blog module:
 * - GET    /v1/blog-comments              (getAllComments)
 * - GET    /v1/blog-comments/blog/{id}    (getBlogComments)
 * - POST   /v1/blog-comments/store        (submitComment)
 * - POST   /v1/blog-comment-replies/store (submitCommentReply)
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix = "BlogComment";

const setup = {
  // Module Identity
  prefix,
  module_name: "blog_comment",
  store_prefix: "blog_comment",
  route_prefix: "BlogComment",
  route_path: "blog-comment",

  // Permission Configuration
  permission: ["admin", "super_admin"],

  // API Configuration (uses Blog module endpoints)
  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "blog-comments",

  // Field Selection for API requests
  select_fields: [
    "id",
    "blog_id",
    "user_id",
    "comment",
    "creator",
    "slug",
    "status",
    "created_at",
    "deleted_at",
  ],

  // Available columns for sorting
  sort_by_cols: ["id", "blog_id", "user_id", "comment", "status", "created_at"],

  // Table header columns (shown in list view)
  table_header_data: ["id", "blog", "user", "comment", "status", "created_at"],

  // Table row data fields (rendered in list view)
  table_row_data: ["id", "blog", "user", "comment", "status", "created_at"],

  // Quick view modal data fields
  quick_view_data: [
    "id",
    "blog_id",
    "user_id",
    "comment",
    "status",
    "created_at",
  ],

  // UI Labels and Titles
  layout_title: prefix + " Management",
  page_title: `${prefix} Management`,
  all_page_title: "All " + prefix,
  details_page_title: "Details " + prefix,
  create_page_title: "Create " + prefix,
  edit_page_title: "Edit " + prefix,
};

export default setup;
