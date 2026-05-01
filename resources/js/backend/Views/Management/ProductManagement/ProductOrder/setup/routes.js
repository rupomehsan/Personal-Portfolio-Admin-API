import setup from ".";
import All from "../pages/All.vue";
import Layout from "../pages/Layout.vue";
import Details from "../pages/Details.vue";

let route_prefix = setup.route_prefix;
let route_path   = setup.route_path;

const routes = {
  path: route_path,
  component: Layout,
  children: [
    {
      path: "all",
      name: "All" + route_prefix,
      component: All,
    },
    {
      path: "details/:order_id",
      name: "Details" + route_prefix,
      component: Details,
    },
  ],
};

export default routes;
