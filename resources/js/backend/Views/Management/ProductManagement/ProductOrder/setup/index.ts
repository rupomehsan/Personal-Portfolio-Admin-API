import app_config from "@config/app_config";

const prefix = "ProductOrder";

const setup = {
  prefix,
  module_name: "product_order",
  store_prefix: "product_order",
  route_prefix: "ProductOrder",
  route_path: "product-order",

  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "digital-product-orders",

  select_fields: ["id", "digital_product_id", "first_name", "last_name", "email", "phone", "trx_number", "payment_status", "created_at"],
  sort_by_cols:  ["id", "digital_product_id", "email", "trx_number", "payment_status", "created_at"],

  table_header_data: ["id", "digital_product", "customer", "email", "trx_number", "payment_status", "created_at"],
  table_row_data:    ["id", "digital_product", "customer", "email", "trx_number", "payment_status", "created_at"],
  quick_view_data:   ["id", "digital_product_id", "first_name", "last_name", "email", "phone", "trx_number", "payment_status", "created_at"],

  layout_title:        prefix + " Management",
  page_title:          `${prefix} Management`,
  all_page_title:      "All " + prefix,
  details_page_title:  "View " + prefix,
  create_page_title:   "Create " + prefix,
  edit_page_title:     "Edit " + prefix,
};

export default setup;
