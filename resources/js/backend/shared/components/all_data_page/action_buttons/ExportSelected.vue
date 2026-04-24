<template lang="">
    <a v-if="selected.length" @click.prevent="export_selected_csv_handler(selected)" href=""
        class="btn action_btn mr-1 btn-sm btn-secondary d-flex align-content-center align-items-center">
        <i class="fa fa-sign-out mr-1"></i>
        Export ( {{ selected.length }} )
    </a>
</template>
<script>
import { inject, computed } from 'vue';
import export_selected_csv from "../../../helpers/export_selected_csv"

export default {
    setup() {
        // Inject dependencies from parent
        const moduleSetup = inject('moduleSetup');
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            moduleSetup,
            dataStoreConstructor,
            selected: computed(() => store.selected || []),
            export_selected_csv_handler(selected) {
                // Call the helper with this context containing moduleSetup
                return export_selected_csv.call({ setup: moduleSetup }, selected);
            }
        };
    }
}
</script>
<style lang="">

</style>
