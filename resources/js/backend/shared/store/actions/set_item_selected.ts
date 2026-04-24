function set_item_selectd(item, event){
    // This function is deprecated - use SelectSingle.vue's toggleSelect method instead
    // Keeping for backward compatibility
    let temp_selected = this.selected ? [...this.selected] : [];

    let isChecked = event.target.checked
    if (isChecked) {
        if (!temp_selected.find(i => i.id === item.id)) {
            temp_selected.push(item);
        }
    } else {
        temp_selected = temp_selected.filter(i => i.id != item.id);
    }

    // Use splice instead of direct assignment to maintain reactivity
    if (this.selected && Array.isArray(this.selected)) {
        this.selected.splice(0, this.selected.length, ...temp_selected);
    }

    let select_all_checkbox = document.querySelector('.select_all_checkbox');
    if(select_all_checkbox){
        (select_all_checkbox as HTMLInputElement).checked = false;
    }
}

export default set_item_selectd;
