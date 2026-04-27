//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//UserRoutes
import UserRoutes from "../Management/UserManagement/User/setup/routes.js";
//routes
import DigitalProductRoutes from "../Management/ProductManagement/DigitalProduct/setup/routes.js";

import TodoListRoutes from "../Management/TodoListManagement/TodoList/setup/routes.js";
import PersonalNoteRoutes from "../Management/PersonalNoteManagement/PersonalNote/setup/routes.js";
import CredentialRoutes from "../Management/CredentialManagement/Credential/setup/routes.js";
import ProjectRoutes from "../Management/ProjectManagement/Project/setup/routes.js";
import BlogTagRoutes from "../Management/BlogManagement/BlogTag/setup/routes.js";
import BlogWriterRoutes from "../Management/BlogManagement/BlogWriter/setup/routes.js";
import BlogRoutes from "../Management/BlogManagement/Blog/setup/routes.js";
import BlogCategoryRoutes from "../Management/BlogManagement/BlogCategory/setup/routes.js";
import BlogCommentRoutes from "../Management/BlogManagement/BlogComment/setup/routes.js";
import ProjectCommentRoutes from "../Management/ProjectManagement/ProjectComment/setup/routes.js";

import ContactRoutes from "../Management/Contact/setup/routes.js";
import UserRoleRoutes from "../Management/UserManagement/Role/setup/routes.js";

const routes = {
  path: "",
  component: Layout,
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      name: "adminDashboard",
    },
    //management routes

    DigitalProductRoutes,

    TodoListRoutes,
    PersonalNoteRoutes,
    CredentialRoutes,
    ProjectRoutes,
    BlogTagRoutes,
    BlogWriterRoutes,
    BlogRoutes,
    BlogCategoryRoutes,
    BlogCommentRoutes,
    ProjectCommentRoutes,

    ContactRoutes,

    //user routes
    UserRoutes,
    UserRoleRoutes,
    //settings
    SettingsRoutes,
  ],
};

export default routes;
