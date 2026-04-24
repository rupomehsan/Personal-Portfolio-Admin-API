<template>
  <!-- {{ item }} -->

  <template
    v-for="(row_item, index) in moduleSetup.table_row_data"
    :key="index"
  >
    <tr>
      <th>{{ row_item }}</th>
      <th class="text-center">:</th>
      <th class="text-trim">
        <template v-if="row_item === 'thumbnail_image' && item[row_item]">
          <a
            :href="item[row_item]"
            data-lightbox="blog-image"
            data-title="Preview"
          >
            <img
              :src="item[row_item]"
              style="width: 120px; height: 80px; object-fit: cover"
              alt="image"
            />
          </a>
        </template>
        <template v-else>
          {{ trim_content(item[row_item], row_item) }}
        </template>
      </th>
    </tr>
  </template>
</template>

<script setup>
import { inject } from "vue";

// Accept setup via dependency injection
const moduleSetup = inject("moduleSetup");

defineProps(["item"]);

// Methods
const trim_content = (content, row_item = null) => {
  if (typeof content == "string") {
    if (row_item == "created_at" || row_item == "updated_at") {
      return new Date(content).toLocaleString();
    }
    return content;
  }
  if (content && typeof content === "object") {
    for (const key of Object.keys(content)) {
      if (key === "title" && content.title) {
        return content.title;
      }
      if (key === "name" && content.name) {
        return content.name;
      }
    }
  }

  return content || "";
};
</script>

<style scoped>
.max-w-120 {
  max-width: 120px;
}
</style>
