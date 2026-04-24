<template>
  <div id="wrapper" v-if="is_auth">
    <top-header></top-header>
    <div class="clearfix"></div>
    <div class="content-wrapper">
      <router-view></router-view>
    </div>
    <!--End content-wrapper-->

    <!--start color switcher-->
    <Footer></Footer>
    <!--end color switcher-->
  </div>
</template>

<script>
import TopHeader from "../Layouts/Partials/Header/Index.vue";
import Footer from "../Layouts/Partials/Footer/Index.vue";
//auth_store
import { auth_store } from "../../GlobalStore/auth_store";
import { site_settings_store } from "../../GlobalStore/site_settings_store";
import { mapActions, mapState } from "pinia";

export default {
  components: { TopHeader, Footer },
  data: () => ({
    rightToggle: false,
    isInitialized: false,
  }),
  created: async function () {
    // Initialize theme system before other operations

    // Prevent multiple initialization calls
    if (this.isInitialized) {
      return;
    }
    this.isInitialized = true;

    try {
      await this.check_is_auth();
      await this.get_all_website_settings();

      if (this.is_auth) {
        let prev_url = window.sessionStorage.getItem("prevurl");
        window.location.href = "/admin#/dashboard";
        if (this.$route.path === "/admin#") {
          this.$router.push("/dashboard");
        }
        window.location.hash = prev_url || "/admin#/dashboard";
      } else {
        window.location.href = "login";
      }
    } catch (error) {
      console.error("Initialization error:", error);
      this.isInitialized = false; // Reset flag on error
    }
  },
  methods: {
    ...mapActions(auth_store, {
      check_is_auth: "check_is_auth",
    }),
    ...mapActions(site_settings_store, {
      get_all_website_settings: "get_all_website_settings",
    }),
    changeTheme(id) {
      const totalThemes = Array.from({ length: 15 }, (_, i) => i + 1);
      const newThemeNo = "bg-theme" + id;
      const body = document.getElementById("body");

      totalThemes.forEach((item) => {
        const currentThemeClass = "bg-theme" + item;
        if (body.classList.contains(currentThemeClass)) {
          body.classList.remove(currentThemeClass);
        }
      });

      body.classList.add(newThemeNo);
    },
  },

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      is_auth: "is_auth",
    }),
  },
};
</script>

<style></style>
