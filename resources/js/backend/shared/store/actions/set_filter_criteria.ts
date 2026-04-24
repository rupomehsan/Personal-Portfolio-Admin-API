async function set_filter_criteria(data = {}) {
    // Filter out date parameters - they should be set directly on store.start_date and store.end_date
    const filteredData = {};
    for (const key in data) {
        if (key !== 'start_date' && key !== 'end_date') {
            filteredData[key] = data[key];
        }
    }
    
    this.filter_criteria = {
        ...this.filter_criteria,
        ...filteredData
    }
}

export default set_filter_criteria;
