import axios from "axios";
import { defineStore } from "pinia";

export const global_store = defineStore("global_store", {
  state: () => ({
    unseen_vouchers: [],
  }),
  getters: {
    get_auth_info: function () {
      return this.auth_info;
    },
  },
  actions: {
   
  },
});
