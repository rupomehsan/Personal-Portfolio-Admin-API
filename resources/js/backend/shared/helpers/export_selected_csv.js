import CsvBuilder from "./filify";
// Removed hardcoded imports - now uses component context (this.setup, this.$pinia)

function export_selected_csv(data) {
    // Access setup from component context
    const setup = this.setup;
    let store_prefix = setup.store_prefix;

    const excludedColumns = ["deleted_at", "created_at", "updated_at"];
    let col = Object.keys(data[0]).filter(
        (key) => !excludedColumns.includes(key)
    );
    let values = data.map(
        (row) => col.map((key) => row[key])
    );

    new CsvBuilder(`${store_prefix}_list.csv`)
        .setColumns(col)
        .addRows(values)
        .exportFile();

    // Clear selection using store action
    this.clear_selected();
    document.querySelector('.select_all_checkbox').checked = false;
}

export default export_selected_csv;
