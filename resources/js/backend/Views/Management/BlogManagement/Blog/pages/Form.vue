<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{
              param_id
                ? `${setup.edit_page_title}`
                : `${setup.create_page_title}`
            }}
          </h5>
          <div>
            <router-link
              v-if="item.slug"
              class="btn btn-outline-info mr-2 btn-sm"
              :to="{
                name: `Details${setup.route_prefix}`,
                params: { id: item.slug },
              }"
            >
              {{ setup.details_page_title }}
            </router-link>
            <router-link
              class="btn btn-outline-warning btn-sm"
              :to="{ name: `All${setup.route_prefix}` }"
            >
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div class="row">
            <template
              v-for="(form_field, index) in form_fields"
              v-bind:key="index"
            >
              <common-input
                :label="form_field.label"
                :type="form_field.type"
                :name="form_field.name"
                :placeholder="form_field.placeholder"
                :multiple="form_field.multiple"
                :value="form_field.value"
                :data_list="form_field.data_list"
                :is_visible="form_field.is_visible"
                :class="form_field.class"
              />
            </template>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            Submit
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import form_fields from "../setup/form_fields";

export default {
  data: () => ({
    setup,
    form_fields,
    param_id: null,
  }),

  created: async function () {
    const id = (this.param_id = this.$route.params.id);
    this.reset_fields();
    // load all relation selects in parallel, then populate edit values
    await Promise.all([
      this.loadBlogCategories(),
    ]);
    if (id) {
      await this.set_fields(id);
    }
  },

  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      set_only_latest_data: "set_only_latest_data",
    }),

    // ── Relation loaders ───────────────────────────────────────────────
    async loadBlogCategories() {
      try {
        const { data } = await axios.get("blog-categories", {
          params: { limit: 1000, status: "active" },
        });
        // handle both paginated ({ data: { data: [...] } }) and plain ({ data: [...] })
        const items = data?.data?.data ?? data?.data ?? [];
        const field = this.form_fields.find((f) => f.name === "blog_category_id");
        if (field) {
          field.data_list = items.map((cat) => ({
            label: cat.title,
            value: cat.id,
          }));
        }
      } catch (e) {
        console.error("Failed to load blog categories:", e);
      }
    },

    // ── Form helpers ───────────────────────────────────────────────────
    reset_fields() {
      this.form_fields.forEach((field) => (field.value = ""));
    },

    async set_fields(id) {
      await this.details(id);
      if (!this.item) return;
      this.form_fields.forEach((field, index) => {
        if (field.name in this.item) {
          this.form_fields[index].value = this.item[field.name];
        }
        if (field.name === "description") {
          try { $("#description").summernote("code", this.item.description); }
          catch { /* editor not mounted yet */ }
        }
      });
    },

    // ── Submit ─────────────────────────────────────────────────────────
    async submitHandler($event) {
      this.setSummerEditor();
      this.set_only_latest_data(true);
      const response = this.param_id
        ? await this.update($event)
        : await this.create($event);

      if ([200, 201].includes(response?.status)) {
        window.s_alert(
          this.param_id ? "Data successfully updated" : "Data successfully created"
        );
        this.$router.push({
          name: this.param_id
            ? `Details${this.setup.route_prefix}`
            : `All${this.setup.route_prefix}`,
        });
      }
    },

    setSummerEditor() {
      const el = document.getElementById("description");
      if (!el) return;
      try {
        const input = document.createElement("input");
        input.setAttribute("name", "description");
        input.value = $("#description").summernote("code");
        el.appendChild(input);
      } catch (e) {
        console.warn("Summernote not available:", e);
      }
    },
  },

  computed: {
    ...mapState(store, { item: "item" }),
  },
};
</script>

<style scoped></style>
