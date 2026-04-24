<template lang="">
    <a href="" @click.prevent="change_status(`active`)"
        class="btn action_btn btn-sm btn-success d-flex align-items-center mx-1">
        <i class="fa fa fa fa-eye mr-2"></i> Active
        ({{ active_data_count }})
    </a>
</template>
<script>
import { inject, computed } from 'vue';

export default {
    props: {
        item: {
            slug: "",
        }
    },
    setup() {
        // Inject the dataStoreConstructor from parent
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            active_data_count: computed(() => store.active_data_count),
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
                store.set_only_latest_data(true);
            }
        };
    }
}
</script>
<style lang="">

</style>
