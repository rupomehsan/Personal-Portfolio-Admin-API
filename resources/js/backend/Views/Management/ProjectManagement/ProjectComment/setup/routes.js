import setup from ".";
import All from "../pages/All.vue";
import Layout from "../pages/Layout.vue";
import ProjectWiseComments from "../pages/ProjectWiseComments.vue";
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
      path: "project-wise",
      name: "ProjectWiseComments",
      component: ProjectWiseComments,
    },
    {
      path: "replies/:comment_id",
      name: "ProjectCommentReplies",
      component: CommentReplies,
    },
  ],
};

export default routes;
