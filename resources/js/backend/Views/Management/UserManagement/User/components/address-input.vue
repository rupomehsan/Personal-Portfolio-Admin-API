<template>
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center pb-2 section-title">
            <h5 class="m-0">Address Information</h5>
        </div>
        
        <div class="row">
            <div v-for="field in fields" :key="field.name" class="col-md-6">
                <div class="form-group">
                    <label :for="field.name">{{ field.label }}</label>
                    <div class="mt-1 mb-3">
                        <input
                            :type="field.type"
                            class="form-control form-control-square"
                            :id="field.name"
                            :name="`${field.name}`"
                            v-model="form[field.name]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "pinia";
import { store } from "../store";

export default {
    name: "AddressForm",
    data() {
        return {
            fields: [
                { name: "state", label: "State", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "post", label: "Post Code", type: "text" },
                { name: "country", label: "Country", type: "text" },
            ],
            form: {
                state: "",
                city: "",
                post: "",
                country: "",
            },
        };
    },
    created: function () {
        this.$watch("item", (newValue) => {
            if (newValue && newValue.address) {
                this.form.state = newValue.address.state || "";
                this.form.city = newValue.address.city || "";
                this.form.post = newValue.address.post || "";
                this.form.country = newValue.address.country || "";
            }
        });
    },
    computed: {
        ...mapState(store, ["item"]),
    },
};
</script>

<style scoped>
.section-title {
  border: 1px solid #dddddd78;
  padding: 10px;
  margin: 10px 0;
  border-radius: 5px;
  font-weight: 500;
  color: #343a40;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>