import app_config from "@config/app_config";

const prefix = "ProjectComment";

const setup = {
  prefix,
  module_name: "project_comment",
  store_prefix: "project_comment",
  route_prefix: "ProjectComment",
  route_path: "project-comment",

  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "project-comments",

  select_fields: ["id", "project_id", "user_id", "name", "comment", "status", "created_at"],
  sort_by_cols:  ["id", "project_id", "user_id", "comment", "status", "created_at"],

  table_header_data: ["id", "project", "user", "comment", "status", "created_at"],
  table_row_data:    ["id", "project", "user", "comment", "status", "created_at"],
  quick_view_data:   ["id", "project_id", "user_id", "name", "comment", "status", "created_at"],

  layout_title:        prefix + " Management",
  page_title:          `${prefix} Management`,
  all_page_title:      "All " + prefix,
  details_page_title:  "Details " + prefix,
  create_page_title:   "Create " + prefix,
  edit_page_title:     "Edit " + prefix,
};

export default setup;
