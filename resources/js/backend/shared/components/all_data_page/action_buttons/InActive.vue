<template>
    <div class="">
        <a href="" @click.prevent="change_status(`inactive`)"
            class="btn action_btn btn-sm btn-warning d-flex align-items-center">
            <i class="fa fa fa-eye-slash mr-2"></i> Inactive
            ({{ inactive_data_count }})
        </a>
    </div>
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
            inactive_data_count: computed(() => store.inactive_data_count),
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
