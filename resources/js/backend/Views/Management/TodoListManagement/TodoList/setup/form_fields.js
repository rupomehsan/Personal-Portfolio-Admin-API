/**
 * Form Fields Configuration
 *
 * Auto-generated form field definitions.
 * Each field includes type, validation, and display properties.
 */

export default [
	{
		name: "title",
		label: "Enter Title",
		type: "text",
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
		class: "col-md-6",
	},
	{
		name: "category_id",
		label: "Enter Category Id",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "priority",
		label: "Enter Priority",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "is_complete",
		label: "Enter Is Complete",
		type: "select",
		multiple: false,
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
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "progress",
		label: "Enter Progress",
		type: "text",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
];
