<template lang="">
    <input class="form-check-input ml-0 select_all_checkbox"
        @change="selectAllItems"
        type="checkbox" />
</template>
<script>
import { inject } from 'vue';

export default {
    setup() {
        const dataStoreConstructor = inject('dataStoreConstructor');
        return { dataStoreConstructor };
    },
    methods: {
        selectAllItems(event) {
            const store = this.dataStoreConstructor();
            const isChecked = event.target.checked;
            
            if (isChecked) {
                // Add all items from the 'all' state to selected
                if (store.all && store.all.data && Array.isArray(store.all.data)) {
                    const newItems = store.all.data.filter(
                        item => !store.selected.some(s => s.id === item.id)
                    );
                    store.selected.push(...newItems);
                }
            } else {
                // Clear all selections
                store.selected.splice(0, store.selected.length);
            }
        }
    }
}
</script>
<style lang="">

</style>
