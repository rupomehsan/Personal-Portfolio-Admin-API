import { defineStore } from "pinia";
import { initialState } from "./initial_state.ts";
import { actions } from "./actions";

export const store = defineStore("home_page_store", {
    state: () => ({ ...initialState }),
    getters: {},
    actions: {
        ...actions
    },
});


