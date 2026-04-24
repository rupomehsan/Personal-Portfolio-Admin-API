<template>
  <!--Start topbar header-->
  <header class="topbar-nav">
    <nav class="navbar navbar-expand fixed-top">
      <div class="toggle-menu">
        <i @click.prevent="toggle_menu" class="zmdi zmdi-menu"></i>
      </div>

      <a
        href="/"
        target="_blank"
        title="Visit Website"
        class="d-flex align-items-center justify-content-center mx-2"
        style="background: var(--bg-hover); width: 30px; height: 30px"
      >
        <i class="zmdi zmdi-globe mt-1"></i>
      </a>

      <div class="search-bar flex-grow-1"></div>

      <!-- Theme Toggle Button -->
      <div class="theme-toggle-header me-3">
        <button
          class="btn btn-sm theme-switch-btn"
          :class="{
            'light-mode': isLightTheme,
            'dark-mode': isDarkTheme,
          }"
          @click="toggleTheme"
          :title="isLightTheme ? 'Switch to Dark Mode' : 'Switch to Light Mode'"
        >
          <i v-if="isDarkTheme" class="zmdi zmdi-sun"></i>
          <i v-else class="zmdi zmdi-brightness-2"></i>
        </button>
      </div>

      <!-- Old Theme Toggle (commented out) -->
      <!-- <theme-toggle></theme-toggle> -->

      <ul class="navbar-nav align-items-center right-nav-link ml-auto">
        <!-- <li
                    class="nav-item dropdown dropdown-lg"
                    @click="toggle_notification('show_notification')"
                >
                    <a
                        class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        data-toggle="dropdown"
                        href="javascript:void();"
                        aria-expanded="false"
                    >
                        <i class="zmdi zmdi-comment-outline align-middle"></i
                        ><span class="bg-danger text-white badge-up"
                            >12</span
                        ></a
                    >
                    <div
                        class="dropdown-menu dropdown-menu-right"
                        :class="{ show: show_notification }"
                    >
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center"
                            >
                                New Messages
                                <a
                                    href="javascript:void();"
                                    class="extra-small-font"
                                    >Clear All</a
                                >
                            </li>
                            <li class="list-group-item">
                                <a href="javaScript:void();">
                                    <div class="media">
                                        <div class="avatar">
                                            <img
                                                class="align-self-start mr-3"
                                                src="avatar.png"
                                                alt="user avatar"
                                            />
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 msg-title">
                                                Jhon Deo
                                            </h6>
                                            <p class="msg-info">
                                                Lorem ipsum dolor sit amet...
                                            </p>
                                            <small>Today, 4:10 PM</small>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li class="list-group-item text-center">
                                <a href="javaScript:void();"
                                    >See All Messages</a
                                >
                            </li>
                        </ul>
                    </div>
                </li> -->

        <li
          class="nav-item dropdown dropdown-lg"
          @click="toggle_notification('show_message')"
        >
          <a
            role="button"
            class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
          >
            <i class="zmdi zmdi-notifications-active align-middle"></i>
            <span class="bg-info text-white badge-up">{{ unseen_vouchers.length }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" :class="{ show: show_message }">
            <ul class="list-group list-group-flush">
              <!-- <li
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                New Notifications
                <a href="javascript:void();" class="extra-small-font"
                  >Clear All</a
                >
              </li> -->
              <li class="list-group-item" v-for="voucher in unseen_vouchers" :key="voucher.id || voucher.title">
                <a @click.prevent="mark_as_seen(voucher)" class="cursor-pointer">
                  <div class="media">
                    <div class="media-body">
                      <h6 class="mt-0 msg-title">
                        {{ voucher.title.substring(0, 30) }}
                      </h6>
                      <p class="msg-info">Amount : {{ voucher.amount }}</p>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item dropdown" @click="toggle_notification('show_profile')">
          <a
            class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
          >
            <span class="user-profile">
              <img
                :src="`${auth_info.image ?? 'avatar.png'}`"
                class="img-circle"
                alt="user avatar"
              />
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-right" :class="{ show: show_profile }">
            <li class="dropdown-item user-details">
              <a href="javaScript:void();">
                <div class="media">
                  <div class="avatar">
                    <img
                      class="align-self-start mr-3"
                      :src="`${auth_info.image ?? 'avatar.png'}`"
                      alt="user avatar"
                    />
                  </div>
                  <div class="media-body">
                    <h6 class="mt-2 user-title">
                      {{ auth_info.name }}
                    </h6>
                    <p class="user-subtitle">
                      {{ auth_info.email }}
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li class="dropdown-divider"></li>

            <li class="dropdown-divider"></li>

            <li class="dropdown-divider"></li>
            <li>
              <router-link class="dropdown-item" :to="{ name: 'AdminProfileSettings' }">
                <i class="zmdi zmdi-accounts mr-3"></i>Profile
              </router-link>
            </li>
            <li>
              <router-link class="dropdown-item" :to="{ name: 'AdminSiteSettings' }">
                <i class="zmdi zmdi-settings mr-3"></i>Settings
              </router-link>
            </li>
            <li class="dropdown-divider"></li>
            <li class="dropdown-item" @click="logout()" role="button">
              <i class="zmdi zmdi-power mr-3"></i>Logout
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>
  <!--End topbar header-->
</template>

<script>
//auth_store
import { auth_store } from "../../../../GlobalStore/auth_store";
import { mapState } from "pinia";

export default {
  data: () => ({
    show_notification: 0,
    show_message: 0,
    show_profile: 0,
    unseen_vouchers: [],
    currentTheme: "light-theme",
  }),

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
    }),
    isLightTheme() {
      return this.currentTheme === "light-theme";
    },
    isDarkTheme() {
      return this.currentTheme === "dark-theme";
    },
  },

  created: async function () {},

  mounted() {
    // Initialize current theme
    this.initializeTheme();

    // Listen to theme changes from other components
    this.themeChangeHandler = this.onThemeChange.bind(this);
    window.addEventListener("themechange", this.themeChangeHandler);

    // Add a small delay to check body class after page fully loads
    setTimeout(() => {
      this.syncThemeFromDOM();
    }, 100);

    const navItems = document.querySelectorAll(".nav-item");

    navItems.forEach((element) => {
      element.addEventListener("click", () => {
        navItems.forEach((item) => {
          if (element !== item) {
            if (item.childNodes[1]?.classList?.contains("show")) {
              item.childNodes[1]?.classList?.remove("show");
            }
          }
        });
        element.childNodes[1].classList.toggle("show");
      });
    });
  },

  beforeUnmount() {
    // Clean up event listener
    if (this.themeChangeHandler) {
      window.removeEventListener("themechange", this.themeChangeHandler);
    }
  },

  methods: {
    initializeTheme() {
      // Try multiple ways to get the theme
      let theme = "light-theme";

      // Method 1: Check if themeManager is available and initialized
      if (window.themeManager && typeof window.themeManager.getCurrentTheme === "function") {
        theme = window.themeManager.getCurrentTheme();
      }
      // Method 2: Check body class
      else if (document.body.classList.contains("dark-theme")) {
        theme = "dark-theme";
      }
      // Method 3: Check localStorage
      else {
        theme = localStorage.getItem("app-theme") || "light-theme";
      }

      this.currentTheme = theme;
    },
    syncThemeFromDOM() {
      // Sync component state with actual DOM theme
      if (document.body.classList.contains("dark-theme")) {
        this.currentTheme = "dark-theme";
      } else if (document.body.classList.contains("light-theme")) {
        this.currentTheme = "light-theme";
      } else {
        // Default to light if no class found
        this.currentTheme = "light-theme";
      }
    },
    toggleTheme() {
      const newTheme = this.isLightTheme ? "dark-theme" : "light-theme";
      
      if (window.themeManager && typeof window.themeManager.toggleTheme === "function") {
        window.themeManager.toggleTheme();
      } else {
        // Fallback: toggle manually
        const body = document.body;
        const html = document.documentElement;
        
        if (body && html) {
          body.classList.remove("light-theme", "dark-theme");
          html.classList.remove("light-theme", "dark-theme");
          body.classList.add(newTheme);
          html.classList.add(newTheme);
          localStorage.setItem("app-theme", newTheme);
        }
      }
      
      // Update local state immediately
      this.currentTheme = newTheme;
    },
    onThemeChange(event) {
      // Handle theme change event from other components
      // The event might come as event.detail.theme or just check DOM
      if (event.detail && event.detail.theme) {
        this.currentTheme = event.detail.theme;
      } else {
        // Fallback: sync from DOM
        this.syncThemeFromDOM();
      }
    },
    toggle_menu: function () {
      document.getElementById("wrapper").classList.toggle("toggled");
    },
    logout: async function () {
      let con = await window.s_confirm("Are you sure want to logout?");
      if (con) {
        localStorage.removeItem("auth_token");
        window.location.href = "/";
      }
    },

    toggle_notification: function (type) {
      if (type == "show_notification") {
        this.show_notification = this.show_notification ? 0 : 1;
        this.show_message = 0;
        this.show_profile = 0;
      } else if (type == "show_message") {
        this.show_message = this.show_message ? 0 : 1;
        this.show_notification = 0;
        this.show_profile = 0;
      } else if (type == "show_profile") {
        this.show_profile = this.show_profile ? 0 : 1;
        this.show_notification = 0;
        this.show_message = 0;
      }
    },
  },
};
</script>

<style scoped>
.theme-toggle-header {
  display: flex;
  align-items: center;
  justify-content: center;
}

.theme-switch-btn {
  position: relative;
  width: 40px;
  height: 40px;
  padding: 0;
  border: 1px solid var(--border-light, #e2e8f0);
  border-radius: 8px;
  background-color: var(--bg-card, #ffffff);
  color: var(--text-primary, #0f172a);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.theme-switch-btn:hover {
  background-color: var(--bg-hover, #f1f5f9);
  border-color: var(--border-color, #cbd5e1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

.theme-switch-btn.light-mode {
  color: #f59e0b;
}

.theme-switch-btn.dark-mode {
  color: #3b82f6;
}

.theme-switch-btn i {
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: rotate-icon 0.3s ease;
}

@keyframes rotate-icon {
  from {
    transform: rotate(0deg);
    opacity: 0.7;
  }
  to {
    transform: rotate(360deg);
    opacity: 1;
  }
}

.theme-switch-btn:active {
  transform: scale(0.95);
}

/* Dark theme adjustments */
:global(body.dark-theme) .theme-switch-btn {
  background-color: var(--bg-card, rgb(15, 23, 42));
  border-color: var(--border-dark, rgb(71, 85, 105));
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .theme-switch-btn:hover {
  background-color: var(--bg-hover, rgb(24, 33, 49));
  border-color: var(--border-color, rgb(113, 126, 159));
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* =====================================
   NAVBAR RIGHT NAV ITEMS STYLING
   ===================================== */

.right-nav-link {
  gap: 0.35rem;
  padding: 0 0.35rem;
}

.right-nav-link .nav-item {
  display: flex;
  align-items: center;
}

.right-nav-link .nav-link {
  padding: 0.35rem 0.6rem;
  border-radius: 8px;
  color: var(--text-secondary, #475569);
  transition: background-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
  position: relative;
}

.right-nav-link .nav-link:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
}

.right-nav-link .nav-link:focus-visible {
  outline: 2px solid var(--primary-color, #3b82f6);
  outline-offset: 2px;
}

/* Badge styling */
.right-nav-link .badge-up {
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  font-size: 11px;
  font-weight: 600;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: -4px;
  right: -4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

/* Dropdown menu styling */
.right-nav-link .dropdown-menu {
  border: 1px solid var(--border-color, #e2e8f0);
  border-radius: 10px;
  background-color: var(--dropdown-bg, #ffffff);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  margin-top: 0.55rem;
  padding: 0.5rem 0;
  min-width: 280px;
}

.right-nav-link .list-group-flush {
  border-radius: 10px;
}

.right-nav-link .list-group-item {
  border-color: var(--border-light, #f1f5f9);
  background-color: var(--dropdown-bg, #ffffff);
  color: var(--text-secondary, #475569);
  padding: 0.75rem 0.9rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.right-nav-link .list-group-item:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
}

.right-nav-link .list-group-item.user-details {
  padding: 0.9rem;
  border-bottom: 1px solid var(--border-light, #f1f5f9);
}

.right-nav-link .dropdown-item {
  color: var(--text-secondary, #475569);
  background-color: var(--dropdown-bg, #ffffff);
  padding: 0.75rem 0.9rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.right-nav-link .dropdown-item:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
}

.right-nav-link .dropdown-divider {
  border-top: 1px solid var(--border-light, #f1f5f9);
  margin: 0.35rem 0;
}

/* User profile image */
.user-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 999px;
  overflow: hidden;
  border: 2px solid var(--border-color, #e2e8f0);
  transition: border-color 0.25s ease;
}

.right-nav-link .nav-link:hover .user-profile {
  border-color: var(--primary-color, #3b82f6);
}

.user-profile .img-circle {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* User title/subtitle text styling */
.user-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
  margin-bottom: 0.15rem;
}

.user-subtitle {
  font-size: 0.85rem;
  color: var(--text-tertiary, #64748b);
  margin: 0;
}

.msg-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
}

.msg-info {
  font-size: 0.82rem;
  color: var(--text-tertiary, #64748b);
  margin: 0.25rem 0 0;
}

/* Dark theme nav items */
:global(body.dark-theme) .right-nav-link .nav-link {
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .right-nav-link .nav-link:hover {
  background-color: var(--bg-hover, rgb(51, 65, 85));
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .right-nav-link .dropdown-menu {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(30, 41, 59));
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

:global(body.dark-theme) .right-nav-link .list-group-item,
:global(body.dark-theme) .right-nav-link .dropdown-item {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(30, 41, 59));
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .right-nav-link .list-group-item:hover,
:global(body.dark-theme) .right-nav-link .dropdown-item:hover {
  background-color: var(--bg-hover, rgb(51, 65, 85));
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .right-nav-link .dropdown-divider {
  border-top-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .user-profile {
  border-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .user-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .user-subtitle {
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .msg-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .msg-info {
  color: var(--text-tertiary, #94a3b8);
}
</style>
