<template lang="">
    <router-link
        :to="{
            name: `Edit${moduleSetup.route_prefix}`,
            params: { id: item.slug || item.uuid || item.id }
        }"
        class="border-secondary">
        <i class="fa fa-pencil-square-o text-info"></i>
        Edit
    </router-link>
</template>
<script setup>
import { inject } from 'vue';

// Accept setup via dependency injection
const moduleSetup = inject('moduleSetup');

defineProps({
    item: {
        type: Object,
        default: () => ({
            slug: 1,
        })
    }
});
</script>
<style lang="">

</style>
