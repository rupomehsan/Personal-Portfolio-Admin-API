<template>
    <a href="" @click.prevent="set_import_csv_modal(true)"
        class="btn action_btn btn-sm btn-secondary d-flex align-items-center ">
        <i class="fa fa-download mr-1"></i> Import
    </a>
</template>
<script>
import { inject } from 'vue';

export default {
    setup() {
        // Inject the dataStoreConstructor from parent
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            set_import_csv_modal(value) {
                store.set_import_csv_modal(value);
            }
        };
    }
}
</script>
<style></style>
