import { anyObject } from "@/common_types/object";
import fetchDataAndUpdateCache from "../helpers/http";
import { buildApiUrl } from "../createStore";

let execute = async function (this: any) {

  // Build query parameters from store state
  let qparams: anyObject = {
    params: {
      page: this.page,
      paginate: this.paginate,
      limit: this.paginate,
      search_key: this.search_key,
      search: this.search_key,
      sort_by_col: this.sort_by_col,
      sort_type: this.sort_type,
      status: this.status,
      start_date: this.start_date,
      end_date: this.end_date,
    },
  };

  let response: anyObject = {};
  let url = buildApiUrl(this.api_host, this.api_version, this.api_end_point);
  let full_url: URL = new URL(url);
  let fetch_only_latest: boolean = true;

  // Add query parameters to URL
  for (let param in qparams.params) {
    full_url.searchParams.set(param, qparams.params[param]);
  }

  // Add select fields
  this.select_fields.forEach(function (el: any, index: number) {
    full_url.searchParams.set(`selected_fields[${index}]`, el);
    full_url.searchParams.set(`fields[${index}]`, el);
  });

  // Add filter criteria
  let index = 0;
  for (let param in this.filter_criteria) {
    let value = this.filter_criteria[param];
    if (value) {
      full_url.searchParams.set(`filter_criterias[${index}][key]`, param);
      full_url.searchParams.set(`filter_criterias[${index}][value]`, value);
      index++;
      full_url.searchParams.set(param, value);
    }
  }

  this.is_loading = true;
  this.loading_text = "loading..";

  // Fetch data
  if (
    this.url &&
    this.search_key.length === 0 &&
    Object.keys(this.filter_criteria).length
  ) {
    url = this.url;
    response = await fetchDataAndUpdateCache(url, fetch_only_latest);
  } else {
    response = await fetchDataAndUpdateCache(full_url.href, fetch_only_latest);
  }

  // Update store state with response
  this.cached = response.totalStorage;
  this.all = response.data;
  this.all_data_count = response.data.total;
  this.active_data_count = response.data.active_data_count;
  this.inactive_data_count = response.data.inactive_data_count;
  this.trased_data_count = response.data.trased_data_count;

  this.is_loading = false;
  this.loading_text = "";

  return response.data;
};

export default execute;
