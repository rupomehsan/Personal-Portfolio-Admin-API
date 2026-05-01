import app_config from "@config/app_config";

const prefix = "DigitalProductComment";

const setup = {
  prefix,
  module_name: "digital_product_comment",
  store_prefix: "digital_product_comment",
  route_prefix: "DigitalProductComment",
  route_path: "digital-product-comment",

  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "digital-product-comments",

  select_fields: ["id", "digital_product_id", "user_id", "name", "comment", "status", "created_at"],
  sort_by_cols:  ["id", "digital_product_id", "user_id", "comment", "status", "created_at"],

  table_header_data: ["id", "digital_product", "user", "comment", "status", "created_at"],
  table_row_data:    ["id", "digital_product", "user", "comment", "status", "created_at"],
  quick_view_data:   ["id", "digital_product_id", "user_id", "name", "comment", "status", "created_at"],

  layout_title:        prefix + " Management",
  page_title:          `${prefix} Management`,
  all_page_title:      "All " + prefix,
  details_page_title:  "Details " + prefix,
  create_page_title:   "Create " + prefix,
  edit_page_title:     "Edit " + prefix,
};

export default setup;
