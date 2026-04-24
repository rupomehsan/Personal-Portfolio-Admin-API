<template lang="">
    <router-link
        :to="{name: `Create${moduleSetup.route_prefix}`}"
        class="btn action_btn mr-1 btn-sm btn-info d-flex align-content-center align-items-center">
        <i class="fa fa-edit mr-1"></i>
        Create
    </router-link>
</template>
<script setup>
import { inject } from 'vue';

// Accept setup via dependency injection
const moduleSetup = inject('moduleSetup');
</script>
<style lang="">

</style>
