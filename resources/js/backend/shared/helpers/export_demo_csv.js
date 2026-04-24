import CsvBuilder from "./filify";
// Uses component context (this.setup) to get fields to export

function export_demo_csv() {
    // Access setup from component context
    const setup = this.setup;
    let store_prefix = setup.store_prefix;

    // Use select_fields from setup - same as export_all
    // These are the fields that are actually shown in the API response
    const columns = setup.select_fields;
    
    // Exclude system fields from demo export
    const excludedColumns = ['created_at', 'slug', 'deleted_at', 'updated_at'];
    const filteredColumns = columns.filter(column => !excludedColumns.includes(column));

    // Generate placeholder values row with same number of columns
    const values = [Array(filteredColumns.length).fill("-")];

    new CsvBuilder(`${store_prefix}_demo_list.csv`)
        .setColumns(filteredColumns) // Use filtered columns (exclude system fields)
        .addRows(values)
        .exportFile();
}

export default export_demo_csv;
