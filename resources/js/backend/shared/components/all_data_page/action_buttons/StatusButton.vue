<template>
    <a v-if="item.status == 'active'" href="" @click.prevent="updateStatus(item)" class="border-warning">
        <i class="fa fa-eye-slash text-warning"></i>
        Inactive
    </a>
    <a v-if="item.status == 'inactive'" href="" @click.prevent="updateStatus(item)" class="border-warning">
        <i class="fa fa-eye text-warning"></i>
        Active
    </a>
</template>
<script>
export default {
    props: {
        item: {
            slug: "",
        }
    },
    methods: {
        updateStatus: async function (item) {
            // Ensure item has required fields
            if (!item.slug) {
                window.s_alert('Item slug is missing', 'error');
                return;
            }

            let action = item.status == 'active' ? 'deactive' : 'active';
            let con = await window.s_confirm('Are you sure want to ' + action + ' ?');
            if (con) {
                // Inject the store function and call it to get the store instance
                const store = this.dataStoreConstructor();
                
                // Ensure the item has all required fields including slug
                const completeItem = {
                    ...item,
                    slug: item.slug || item.id, // Fallback to id if slug missing
                    status: item.status
                };
                
                store.set_item(completeItem);
                store.set_only_latest_data(true);
                let response = await store.update_status();
                if (response.data.status === "success") {
                    // Update the item's status reactively
                    Object.assign(item, response.data.data);
                    await store.get_all();
                    window.s_alert(response.data?.message);
                    store.set_only_latest_data(true);
                } else {
                    window.s_warning(response.data?.message);
                }
            }
        },
    },
    inject: ['dataStoreConstructor']
}
</script>
<style lang="">

</style>
