<template>
    <tr>
        <th class="w-10 text-center">
            <span class="icon"></span>
        </th>
        <th class="w-10 text-center">
            <select-all />
        </th>
        <template v-for="(item, index) in moduleSetup.table_header_data" :key="index">
            <th :class="index == 0 ? 'w-10' : ''"> {{ item }} </th>
        </template>
    </tr>
</template>

<script setup>
import { inject } from 'vue';
import SelectAll from './select_data/SelectAll.vue';

// Accept setup via dependency injection (provided by parent component)
const moduleSetup = inject('moduleSetup');
</script>

<style scoped>
.icon {
    cursor: pointer;
    position: relative;
    width: 20px;
    height: 10px;
}

.icon::after {
    content: "\f085";
    font-family: 'FontAwesome';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
}
</style>
