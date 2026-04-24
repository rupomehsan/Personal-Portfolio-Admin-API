<template lang="">
    <input
        @change="toggleSelect(data, $event)"
        :checked="isSelected"
        class="form-check-input ml-0"
        type="checkbox">
</template>
<script>
import { inject } from 'vue';

export default {
    props: ['data'],
    setup(props) {
        const dataStoreConstructor = inject('dataStoreConstructor');
        return { dataStoreConstructor };
    },
    methods: {
        toggleSelect(data, event) {
            const store = this.dataStoreConstructor();
            const isChecked = event.target.checked;
            
            if (isChecked) {
                // Add item to selected
                if (!store.selected.find(i => i.id === data.id)) {
                    store.selected.push(data);
                }
            } else {
                // Remove item from selected
                const index = store.selected.findIndex(i => i.id === data.id);
                if (index > -1) {
                    store.selected.splice(index, 1);
                }
            }
            
            // Uncheck select all checkbox
            const selectAllCheckbox = document.querySelector('.select_all_checkbox');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
        }
    },
    computed: {
        isSelected() {
            if (!this.dataStoreConstructor) return false;
            const store = this.dataStoreConstructor();
            return store.selected && store.selected.some(i => i.id === this.data.id);
        }
    }
}
</script>
<style lang="">

</style>
