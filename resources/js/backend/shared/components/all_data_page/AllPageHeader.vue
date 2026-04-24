<template lang="">
    <h5 class="text-capitalize mb-0"> {{ moduleSetup.all_page_title }} </h5>
    <div>
        <search/>
    </div>
    <div>
        <button @click.prevent="$emit('showFilter', true)"
            class="btn btn-outline-success btn-sm">
            <i class="fa fa-gear"></i>
        </button>
    </div>
</template>
<script setup>
import { inject } from 'vue';
import Search from './Search.vue';

// Accept setup via dependency injection
const moduleSetup = inject('moduleSetup');

// Emit event for parent to handle
defineEmits(['showFilter']);
</script>
