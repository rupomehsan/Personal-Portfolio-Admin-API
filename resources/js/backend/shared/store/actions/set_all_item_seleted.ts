function set_all_item_selectd(event){
    // This function is deprecated - use SelectAll.vue's selectAllItems method instead
    // Keeping for backward compatibility
    console.log(event.target.checked);

    if(event.target.checked){
        let temp_selected = this.selected ? [...this.selected] : [];
        
        if (this.all && this.all.data && Array.isArray(this.all.data)) {
            this.all.data.forEach(item => {
                if (!temp_selected.find(i => i.id === item.id)) {
                    temp_selected.push(item);
                }
            });
        }

        // Use splice to maintain reactivity
        if (this.selected && Array.isArray(this.selected)) {
            this.selected.splice(0, this.selected.length, ...temp_selected);
        }
    } else {
        // Clear selections using splice for reactivity
        if (this.selected && Array.isArray(this.selected)) {
            this.selected.splice(0, this.selected.length);
        }
    }
}

export default set_all_item_selectd;
