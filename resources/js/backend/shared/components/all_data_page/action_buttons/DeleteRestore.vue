<template>
    <a
        v-if="!item.deleted_at"
        @click.prevent="softDelete(item)"
        href=""
        class="border-danger"
    >
        <i class="fa fa-ban text-warning"></i>
        Soft Delete
    </a>

    <a
        v-if="item.deleted_at"
        @click.prevent="restore_data(item)"
        href=""
        class="border-danger"
    >
        <i class="fa fa-ban text-warning"></i>
        Restore data
    </a>
</template>
<script>
export default {
    props: {
        item: {
            slug: "",
        },
    },
    data: () => ({
        is_trashed_data: false,
    }),
    methods: {
        softDelete: async function (item) {
            let con = await window.s_confirm(
                "Are you sure want to soft delete ?"
            );
            if (con) {
                const store = this.dataStoreConstructor();
                store.set_item(item);
                store.set_only_latest_data(true);

                let response = await store.soft_delete();
                if (response.data.status === "success") {
                    // Update the item prop with the deleted_at field from response
                    if (response.data.data) {
                        Object.assign(this.item, response.data.data);
                    }
                    await store.get_all();
                    window.s_alert(response.data?.message);
                    store.set_only_latest_data(true);
                } else {
                    window.s_warning(response.data?.message);
                }
            }
        },
        restore_data: async function (item) {
            let con = await window.s_confirm("Restore");
            if (con) {
                const store = this.dataStoreConstructor();
                store.set_item(item);
                store.set_only_latest_data(true);
                let response = await store.restore();
                if (response.data.status === "success") {
                    // Update the item prop by removing deleted_at field
                    if (response.data.data) {
                        Object.assign(this.item, response.data.data);
                    }
                    await store.get_all();
                    window.s_alert("Permanently deleted.");
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
<style lang=""></style>
