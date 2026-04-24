<template lang="">
  <div class="d-flex align-items-center gap-1">
    <create />
    <export-all />
    <export-selected />
    <import />
    <active />
    <inactive />
    <trash />
    <bulk-actions />
  </div>
</template>
<script>
import { inject } from "vue";
import Create from "./action_buttons/Create.vue";
import ExportAll from "./action_buttons/ExportAll.vue";
import ExportSelected from "./action_buttons/ExportSelected.vue";
import Import from "./action_buttons/Import.vue";
import Active from "./action_buttons/Active.vue";
import Inactive from "./action_buttons/InActive.vue";
import BulkActions from "./action_buttons/BulkActions.vue";
import Trash from "./action_buttons/Trash.vue";

export default {
  components: {
    Create,
    ExportAll,
    ExportSelected,
    Import,
    Active,
    Inactive,
    BulkActions,
    Trash,
  },
  setup() {
    // Inject dependencies from parent All.vue
    const moduleSetup = inject("moduleSetup");
    const dataStoreConstructor = inject("dataStoreConstructor");

    return {
      moduleSetup,
      dataStoreConstructor,
    };
  },
  provide() {
    return {
      moduleSetup: this.moduleSetup,
      dataStoreConstructor: this.dataStoreConstructor,
    };
  },
};
</script>
<style lang=""></style>
