<template lang="">
    <div class="off_canvas data_filter" :class="{active: show_filter_canvas,}">
        <div class="off_canvas_body">
            <div class="header">
                <h3 class="heading_text">Filter</h3>
                <button @click.prevent="set_show_filter_canvas(false)" class="close_button">
                    <span class="fa fa-close"></span>
                </button>
            </div>
            <hr class="m-0 p-0">
            <div class="data_content">
                <div class="filter_item">
                    <label for="start_date">Start Date</label>
                    <label for="start_date" class="text-capitalize d-block date_custom_control">
                        <input v-model="start_date" type="date" id="start_date" name="start_date" class="form-control">
                    </label>
                </div>
                <div class="filter_item">
                    <label for="end_date">End Date</label>
                    <label for="end_date" class="text-capitalize d-block date_custom_control">
                        <input v-model="end_date" type="date" id="end_date" name="end_date" class="form-control">
                    </label>
                </div>
                <div class="filter_item">
                    <label for="sort_by_col">Sort By Col</label>
                    <label for="sort_by_col" class="text-capitalize d-block date_custom_control">
                        <select v-model="sort_by_col" class="form-control">
                            <option v-for="col in sort_by_cols" :key="col">
                                {{ col }}
                            </option>
                        </select>
                    </label>
                </div>
                <div class="filter_item">
                    <label for="sort_by_col">Sort Type</label>
                    <label for="sort_by_col" class="text-capitalize d-block date_custom_control">
                        <select v-model="sort_type" class="form-control">
                            <option v-for="col in ['ASC', 'DESC']" :key="col">
                                {{ col }}
                            </option>
                        </select>
                    </label>
                </div>
                <div class="filter_item d-flex justify-content-between align-items-center">
                    <button @click.prevent="applyFilters()" type="button" class="btn btn-sm btn-outline-info">Apply Filters</button>
                    <button class="btn btn-outline-danger btn-sm" @click="reset_filters">Reset</button>
                </div>
            </div>
        </div>
        <div class="off_canvas_overlay"></div>
    </div>
</template>
<script>
import { inject, computed } from 'vue';

export default {
    setup() {
        // Inject dependencies from parent
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            show_filter_canvas: computed({
                get: () => store.show_filter_canvas,
                set: (val) => store.set_show_filter_canvas(val)
            }),
            start_date: computed({
                get: () => store.start_date,
                set: (val) => store.start_date = val
            }),
            end_date: computed({
                get: () => store.end_date,
                set: (val) => store.end_date = val
            }),
            sort_by_col: computed({
                get: () => store.sort_by_col,
                set: (val) => store.sort_by_col = val
            }),
            sort_type: computed({
                get: () => store.sort_type,
                set: (val) => store.sort_type = val
            }),
            sort_by_cols: computed(() => store.sort_by_cols),
            set_show_filter_canvas() {
                store.set_show_filter_canvas(false);
            },
            async applyFilters() {
                await store.get_all();
            },
            async reset_filters() {
                store.start_date = '';
                store.end_date = '';
                store.sort_by_col = 'id';
                store.sort_type = 'ASC';
                await store.get_all();
            }
        };
    }
}
</script>
  
<style lang="">

</style>
