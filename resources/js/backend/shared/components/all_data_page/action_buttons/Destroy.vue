<template lang="">
    <a href="/destroy" @click.prevent="destroy_data" class="border-danger">
        <i class="fa fa-trash text-danger"></i>
        Destroy
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
        destroy_data: async function(){
            let con = await window.s_confirm('Permanently delete');
            if(con){
                const store = this.dataStoreConstructor();
                store.set_item(this.item);
                store.set_only_latest_data(true);

                let res = await store.destroy();
                await store.get_all();
                if(res.data.status == "success"){
                    window.s_alert('Permanently deleted.');
                }

                store.set_only_latest_data(false);
            }
        },
    },
    inject: ['dataStoreConstructor']
}


</script>
<style lang="">

</style>

