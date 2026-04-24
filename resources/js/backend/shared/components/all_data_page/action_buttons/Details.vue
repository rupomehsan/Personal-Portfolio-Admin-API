<template lang="">
    <router-link
        :to="{
            name: `Details${moduleSetup.route_prefix}`,
            params: { id: item.slug || item.uuid || item.id }
        }"
        class="border-secondary">
        <i class="fa fa-eye text-secondary"></i>
        Show
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
        }),
    }
});
</script>
<style lang="">

</style>
