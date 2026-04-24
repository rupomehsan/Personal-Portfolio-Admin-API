import { store } from "..";

function reset_filter_criteria() {
  const data_store = store();
  // Reset all filter-related state to default values
  data_store.filter_criteria = {};
  data_store.start_date = "";
  data_store.end_date = "";
  data_store.search_key = "";
  data_store.sort_by_col = "id";
  data_store.sort_type = "DESC";
  data_store.page = 1;
  data_store.status = "active";
}

export default reset_filter_criteria;
