function reset_filter_criteria() {
  // Reset all filter-related state to default values
  this.filter_criteria = {};
  this.start_date = "";
  this.end_date = "";
  this.search_key = "";
  this.sort_by_col = "id";
  this.sort_type = "DESC";
  this.page = 1;
  this.status = "active";
}

export default reset_filter_criteria;
