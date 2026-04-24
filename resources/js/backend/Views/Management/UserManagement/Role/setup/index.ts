import app_config from "@config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Role";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "roles",

  module_name: "role",
  store_prefix: "role",
  route_prefix: "Role",
  route_path: "role",

  select_fields: ["id", "name","slug", "status", "created_at", "updated_at","deleted_at"],

  sort_by_cols: ["id", "name", "status", "created_at"],
  table_header_data: ["id", "name", "status", "created_at"],
  table_row_data: ["id", "name", "status", "created_at"],

  layout_title: prefix + " Management",
  page_title: `${prefix} Management`,

  all_page_title: "All " + prefix + "s",
  details_page_title: "Details " + prefix,
  create_page_title: "Create " + prefix,
  edit_page_title: "Edit " + prefix,
};

export default setup;
