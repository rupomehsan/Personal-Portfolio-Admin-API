<template>
  <div class="right-sidebar" :class="rightToggle ? 'right-toggled' : ''">
    <div class="switcher-icon d-none" @click="rightToggle = !rightToggle">
      <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
    </div>
    <div class="right-sidebar-content">
      <!-- Light/Dark Mode Toggle Section -->
      <div class="theme-toggle-section mb-3">
        <p class="mb-2"><strong>Theme Mode</strong></p>
        <div class="d-flex gap-2">
          <button
            class="btn btn-sm theme-toggle-btn"
            :class="{
              'btn-primary': isLightTheme,
              'btn-outline-secondary': !isLightTheme,
            }"
            @click="toggleLightTheme"
            title="Switch to Light Mode"
          >
            <i class="zmdi zmdi-sun"></i> Light
          </button>
          <button
            class="btn btn-sm theme-toggle-btn"
            :class="{ 'btn-primary': isDarkTheme, 'btn-outline-secondary': !isDarkTheme }"
            @click="toggleDarkTheme"
            title="Switch to Dark Mode"
          >
            <i class="zmdi zmdi-moon"></i> Dark
          </button>
        </div>
      </div>
      <hr />

      <p class="mb-0">Gaussion Texture</p>
      <hr />

      <ul class="switcher">
        <li id="theme1" @click="changeTheme('1')"></li>
        <li id="theme2" @click="changeTheme('2')"></li>
        <li id="theme3" @click="changeTheme('3')"></li>
        <li id="theme4" @click="changeTheme('4')"></li>
        <li id="theme5" @click="changeTheme('5')"></li>
        <li id="theme6" @click="changeTheme('6')"></li>
      </ul>

      <p class="mb-0">Gradient Background</p>
      <hr />

      <ul class="switcher">
        <li id="theme7" @click="changeTheme('7')"></li>
        <li id="theme8" @click="changeTheme('8')"></li>
        <li id="theme9" @click="changeTheme('9')"></li>
        <li id="theme10" @click="changeTheme('10')"></li>
        <li id="theme11" @click="changeTheme('11')"></li>
        <li id="theme12" @click="changeTheme('12')"></li>
        <li id="theme13" @click="changeTheme('13')"></li>
        <li id="theme14" @click="changeTheme('14')"></li>
        <li id="theme15" @click="changeTheme('15')"></li>
      </ul>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  data: () => ({
    rightToggle: false,
    currentTheme: "light-theme",
  }),
  computed: {
    isLightTheme() {
      return this.currentTheme === "light-theme";
    },
    isDarkTheme() {
      return this.currentTheme === "dark-theme";
    },
  },
  created() {
    // Import theme manager on component creation
    this.initializeThemeManager();

    // Load saved theme preferences
    if (localStorage.getItem("theme_id")) {
      this.changeTheme(localStorage.getItem("theme_id"));
    }
  },
  mounted() {
    // Listen to theme changes
    window.addEventListener("themechange", this.onThemeChange);
  },
  beforeUnmount() {
    // Clean up event listener
    window.removeEventListener("themechange", this.onThemeChange);
  },
  methods: {
    initializeThemeManager() {
      // Initialize theme manager if not already done

      // Set current theme state
      this.currentTheme = window.themeManager
        ? window.themeManager.getCurrentTheme()
        : "light-theme";
    },
    toggleLightTheme() {
      if (window.themeManager) {
        window.themeManager.setTheme("light-theme");
        this.currentTheme = "light-theme";
      }
    },
    toggleDarkTheme() {
      if (window.themeManager) {
        window.themeManager.setTheme("dark-theme");
        this.currentTheme = "dark-theme";
      }
    },
    onThemeChange(event) {
      this.currentTheme = event.detail.theme;
    },
    changeTheme(id) {
      localStorage.setItem("theme_id", id);
      const totalThemes = Array.from({ length: 15 }, (_, i) => i + 1);
      const newThemeNo = "bg-theme" + id;
      const body = document.body;

      totalThemes.forEach((item) => {
        body.classList.remove(this.LIGHT_THEME, this.DARK_THEME);
        const currentThemeClass = "bg-theme" + item;
        if (body.classList.contains(currentThemeClass)) {
          body.classList.remove(currentThemeClass);
        }
      });

      body.classList.add(newThemeNo);
    },
  },
});
</script>

<style scoped>
.theme-toggle-section {
  padding: 12px 0;
}

.theme-toggle-section p {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 10px;
  color: inherit;
}

.d-flex {
  display: flex;
}

.gap-2 {
  gap: 8px;
}

.theme-toggle-btn {
  flex: 1;
  font-size: 12px;
  padding: 6px 12px;
  transition: all 0.3s ease;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  font-weight: 500;
  cursor: pointer;
}

.theme-toggle-btn i {
  font-size: 14px;
}

.btn-primary {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-primary:hover {
  background-color: var(--primary-hover);
  border-color: var(--primary-hover);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  transform: translateY(-1px);
}

.btn-outline-secondary {
  border-color: var(--border-color);
  color: var(--text-primary);
  background-color: transparent;
}

.btn-outline-secondary:hover {
  background-color: var(--bg-hover);
  color: var(--text-primary);
  border-color: var(--text-secondary);
}

.mb-2 {
  margin-bottom: 0.5rem;
}

.mb-3 {
  margin-bottom: 1rem;
}

.mb-0 {
  margin-bottom: 0;
}

/* Right Sidebar Styling */
.right-sidebar {
  background-color: var(--bg-card);
  color: var(--text-primary);
  border-left: 1px solid var(--border-color);
}

.switcher-icon {
  background-color: var(--primary-color);
  color: #ffffff;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.switcher-icon:hover {
  background-color: var(--primary-hover);
  transform: scale(1.05);
}

.right-sidebar-content {
  background-color: var(--bg-card);
  color: var(--text-primary);
  padding: 15px;
}

.right-sidebar-content p {
  color: var(--text-primary);
  margin-bottom: 10px;
  font-weight: 500;
}

.right-sidebar-content hr {
  border-color: var(--border-color);
  opacity: 0.5;
}

.switcher li {
  cursor: pointer;
  transition: all 0.2s ease;
  border-radius: 4px;
  padding: 2px;
  margin: 4px 2px;
}

.switcher li:hover {
  transform: scale(1.1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}
</style>
