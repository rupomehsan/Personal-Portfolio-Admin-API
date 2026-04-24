<template lang="">
    <div class="">
        <a href="" @click.prevent="change_status(`trased`)"
            class="btn action_btn btn-sm btn-danger d-flex align-items-center mx-1">
            <i class="fa fa-trash mr-2"></i> Trased
            ({{ trashed_data_count }})
        </a>
    </div>
</template>
<script>
import { inject, computed } from 'vue';

export default {
    setup() {
        // Inject the dataStoreConstructor from parent
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            trashed_data_count: computed(() => store.trashed_data_count),
            change_status(status = 'active') {
                if (status == 'trashed') {
                    store.set_only_latest_data(true);
                } else {
                    store.set_only_latest_data(false);
                }
                store.set_only_latest_data(true);
                store.set_status(status);
                store.set_page(1);
                store.get_all();
                store.set_only_latest_data(false);
            }
        };
    }
}
</script>
<style lang="">

</style>
