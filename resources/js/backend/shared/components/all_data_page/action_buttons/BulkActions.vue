<template>
    <div class="" v-if="selected && selected.length > 0">
        <select @change="bulkActions" class="form-select bulk-action-select">
            <option disabled selected>Select action</option>
            <option value="inactive">Inactive</option>
            <option value="active">Active</option>
            <option value="soft_delete">Soft Delete</option>
            <option value="restore">Restore</option>
            <option value="destroy">Destroy</option>
        </select>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            slug: "",
        }
    },
    data() {
        return {
            selected: [],
            store: null
        };
    },
    methods: {
        bulkActions: async function (event) {
            let action = event.target.value;
            let con = await window.s_confirm('Are you sure want to ' + action + ' items ?');
            if (con) {
                const store = this.dataStoreConstructor();
                
                // Get the selected IDs
                let selected_ids = this.selected.map((item) => item.id || item.slug);
                
                store.set_only_latest_data(true);
                let response = await store.bulk_action(action, selected_ids);
                
                if (response?.data?.status === "success") {
                    await store.get_all();
                    
                    // Clear the select checkbox
                    const selectAllCheckbox = document.querySelector('.select_all_checkbox');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                    }
                    
                    store.clear_selected();
                    store.set_only_latest_data(false);
                    window.s_alert('You have ' + action + ' items');
                } else {
                    window.s_warning(response?.data?.message || 'Action failed');
                }
                
                // Reset select dropdown
                event.target.value = '';
            } else {
                // Reset select dropdown if cancelled
                event.target.value = '';
            }
        },
    },
    watch: {
        // Watch for changes in the selected array from the store
        selected: {
            handler(newVal) {
                this.selected = newVal || [];
            },
            deep: true
        }
    },
    mounted() {
        // Initialize the store reference
        if (this.dataStoreConstructor) {
            this.store = this.dataStoreConstructor();
            // Get initial selected value
            this.selected = this.store.selected || [];
            
            // Watch store.selected for changes
            const unwatchSelected = this.store.$subscribe((mutation, state) => {
                if (state.selected !== undefined) {
                    this.selected = state.selected;
                }
            });
        }
    },
    inject: ['dataStoreConstructor']
}
</script>
<style>
.bulk-action-select {
    width: 150px;
    padding: 5px;
    margin-left: 10px;
}
</style>
