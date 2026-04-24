import app_config from "@config/app_config";
import setup_type from "./setup_type";

const prefix: string = "Shared";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "shared",

  module_name: "shared",
  store_prefix: "shared",
  route_prefix: "shared",
  route_path: "shared",

  select_fields: ["id", "name", "status", "created_at", "updated_at"],

  sort_by_cols: ["id", "name", "status", "created_at"],

  table_header_data: ["id", "name", "status", "created_at"],

  table_row_data: ["id", "name", "status", "created_at"],

  layout_title: "Shared Management",

  page_title: "Shared",
  all_page_title: "All Shared",
  details_page_title: "Shared Details",
  create_page_title: "Create Shared",
  edit_page_title: "Edit Shared",
};

export default setup;
