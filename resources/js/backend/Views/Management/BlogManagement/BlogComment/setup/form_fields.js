/**
 * BlogComment Form Fields Configuration
 * Edit data_list / class / is_visible as needed.
 */

export default [
  {
    name: "blog_id",
    label: "Select Blog",
    type: "select",
    multiple: false,
    data_list: [],
    value: "",
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "user_id",
    label: "Select User",
    type: "select",
    multiple: false,
    data_list: [],
    value: "",
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "comment",
    label: "Enter Comment",
    type: "textarea",
    rows: 4,
    value: "",
    is_visible: true,
    class: "col-md-12",
  },
  {
    name: "creator",
    label: "Creator ID",
    type: "number",
    step: "1",
    value: "",
    is_visible: false,
    class: "col-md-6",
  },
  {
    name: "slug",
    label: "Slug",
    type: "text",
    value: "",
    is_visible: false,
    class: "col-md-6",
  },
];
