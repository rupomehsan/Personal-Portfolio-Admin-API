<template lang="">
    <a href=""
        @click.prevent="export_all_csv_handler()"
        class="btn action_btn mr-1 btn-sm btn-primary d-flex align-content-center align-items-center">
        <i class="fa fa-print mr-1"></i>
        Export All
    </a>
</template>
<script>
import { inject } from 'vue';
import export_all_csv from "../../../helpers/export_all_csv"

export default {
    setup() {
        // Inject moduleSetup from parent
        const moduleSetup = inject('moduleSetup');
        
        return {
            export_all_csv_handler() {
                // Call the helper with context containing moduleSetup
                return export_all_csv.call({ setup: moduleSetup });
            }
        };
    }
}
</script>
<style lang="">

</style>
