<template>
    <a
        v-if="!is_trashed_data"
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
        restore_data: async function (item) {
            let con = await window.s_confirm("Are you sure want to restore ?");
            if (con) {
                const store = this.dataStoreConstructor();
                store.set_item(item);
                store.set_only_latest_data(true);

                let response = await store.restore();
                if (response.data.status === "success") {
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
};
</script>
<style lang=""></style>
