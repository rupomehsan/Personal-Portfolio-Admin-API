/**
 * Form Fields Configuration
 *
 * Auto-generated form field definitions.
 * Each field includes type, validation, and display properties.
 */

export default [
  {
    name: "name",
    label: "Enter Name",
    type: "text",
    value: "",
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "link",
    label: "Enter Link",
    type: "text",
    value: "",
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "is_featured",
    label: "Select Is Featured",
    type: "select",
    value: "",
    is_visible: true,
    class: "col-md-6",
    data_list: [
      {
        label: "Yes",
        value: "1",
      },
      {
        label: "No",
        value: "0",
      },
    ],
  },

  // {
  // 	name: "is_top",
  // 	label: "Enter Link",
  // 	type: "text",
  // 	value: "",
  // 	is_visible: true,
  // 	class: "col-md-6",
  // },

  {
    name: "thumb_image",
    label: "Enter Thumb Image",
    type: "file",
    value: "",
    multiple: false,
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "images",
    label: "Upload Images",
    type: "file",
    multiple: true,
    rows: 6,
    value: "",
    is_visible: true,
    class: "col-md-6",
  },
  {
    name: "description",
    label: "Enter Description",
    type: "textarea",
    rows: 4,
    value: "",
    is_visible: true,
    class: "col-md-12",
  },
];
