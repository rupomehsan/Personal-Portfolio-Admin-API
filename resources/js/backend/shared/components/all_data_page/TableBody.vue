<template>
  <tr
    v-for="(item, dataindex) in data"
    :key="item.id"
    :class="`table_rows table_row_${item.id}`"
  >
    <td>
      <table-row-action :item="item"></table-row-action>
    </td>
    <td>
      <select-single :data="item" />
    </td>
    <template
      v-for="(row_item, index) in moduleSetup.table_row_data"
      :key="index"
    >
      <td v-if="row_item == 'id'">
        {{ dataindex + 1 }}
      </td>
      <td
        v-else-if="row_item === 'thumbnail_image'"
        class="text-wrap max-w-120"
      >
        <a
          :href="item[row_item]"
          data-lightbox="blog-image"
          data-title="Preview"
        >
          <img
            :src="item[row_item]"
            style="width: 60px; height: 40px; object-fit: cover"
            alt="image"
          />
        </a>
      </td>
      <td v-else class="text-wrap max-w-120">
        {{ trim_content(item[row_item], row_item) }}
      </td>
    </template>
  </tr>
</template>

<script setup>
import { inject } from "vue";
import SelectAll from "./select_data/SelectAll.vue";
import TableRowAction from "./TableRowAction.vue";
import SelectSingle from "./select_data/SelectSingle.vue";

// Accept setup via dependency injection
const moduleSetup = inject("moduleSetup");

defineProps(["data"]);

// Methods
const trim_content = (content, row_item = null) => {
  if (typeof content == "string") {
    if (
      row_item == "created_at" ||
      row_item == "updated_at" ||
      row_item == "deleted_at"
    ) {
      // Show full date and time (e.g., 3/20/2026, 7:37:43 PM)
      return new Date(content).toLocaleString();
    }
    return content.length > 20 ? content.slice(0, 20) + "..." : content;
  }
  return content;
};
</script>
