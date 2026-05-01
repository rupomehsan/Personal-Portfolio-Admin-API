import setup from ".";
import All from "../pages/All.vue";
import Layout from "../pages/Layout.vue";
import ProductWiseComments from "../pages/ProductWiseComments.vue";
import CommentReplies from "../pages/CommentReplies.vue";

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
      path: "product-wise",
      name: "ProductWiseComments",
      component: ProductWiseComments,
    },
    {
      path: "replies/:comment_id",
      name: "DigitalProductCommentReplies",
      component: CommentReplies,
    },
  ],
};

export default routes;
