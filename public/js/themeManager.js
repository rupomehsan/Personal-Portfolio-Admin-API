/**
 * Theme Management System
 * Handles switching between light and dark themes
 * Persists theme preference to localStorage
 */

class ThemeManager {
  constructor() {
    this.LIGHT_THEME = 'light-theme';
    this.DARK_THEME = 'dark-theme';
    this.STORAGE_KEY = 'app-theme';
    this.SYSTEM_PREFERENCE = 'system-preference';
    this.init();
  }

  /**
   * Initialize theme system
   * Load saved theme or detect system preference
   */
  init() {
    const savedTheme = this.getSavedTheme();
    
    if (savedTheme) {
      this.setTheme(savedTheme);
    } else if (this.shouldUseSystemPreference()) {
      const systemTheme = this.getSystemPreference();
      this.setTheme(systemTheme);
    } else {
      // Default to light theme
      this.setTheme(this.LIGHT_THEME);
    }
  }

  /**
   * Get the saved theme from localStorage
   * @returns {string|null} The saved theme name
   */
  getSavedTheme() {
    return localStorage.getItem(this.STORAGE_KEY);
  }

  /**
   * Check if system preference is enabled
   * @returns {boolean}
   */
  shouldUseSystemPreference() {
    return localStorage.getItem('use-system-theme') === 'true';
  }

  /**
   * Get system color scheme preference
   * @returns {string} Either 'dark-theme' or 'light-theme'
   */
  getSystemPreference() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      return this.DARK_THEME;
    }
    return this.LIGHT_THEME;
  }

  /**
   * Set the theme
   * @param {string} theme - Either 'light-theme' or 'dark-theme'
   */
  setTheme(theme) {
    const body = document.body;
    const htmlElement = document.documentElement;

    // Guard against null body (when script loads before DOM is ready)
    if (!body || !htmlElement) {
      console.warn('ThemeManager: DOM not ready yet, deferring theme application');
      return;
    }

    // Remove existing theme classes
    body.classList.remove(this.LIGHT_THEME, this.DARK_THEME);
    htmlElement.classList.remove(this.LIGHT_THEME, this.DARK_THEME);

    // Add the new theme class
    body.classList.add(theme);
    htmlElement.classList.add(theme);

    // Save to localStorage
    localStorage.setItem(this.STORAGE_KEY, theme);

    // Emit custom event for other components
    this.dispatchThemeChangeEvent(theme);
  }

  /**
   * Toggle between light and dark themes
   */
  toggleTheme() {
    const currentTheme = this.getCurrentTheme();
    const newTheme = currentTheme === this.LIGHT_THEME ? this.DARK_THEME : this.LIGHT_THEME;
    this.setTheme(newTheme);
  }

  /**
   * Get the current active theme
   * @returns {string} Either 'light-theme' or 'dark-theme'
   */
  getCurrentTheme() {
    if (!document.body) {
      // Return default before DOM is ready
      return this.LIGHT_THEME;
    }
    if (document.body.classList.contains(this.DARK_THEME)) {
      return this.DARK_THEME;
    }
    return this.LIGHT_THEME;
  }

  /**
   * Check if dark theme is enabled
   * @returns {boolean}
   */
  isDarkTheme() {
    return this.getCurrentTheme() === this.DARK_THEME;
  }

  /**
   * Check if light theme is enabled
   * @returns {boolean}
   */
  isLightTheme() {
    return this.getCurrentTheme() === this.LIGHT_THEME;
  }

  /**
   * Dispatch custom event when theme changes
   * Other components can listen to this event
   * @param {string} theme - The new theme
   */
  dispatchThemeChangeEvent(theme) {
    const event = new CustomEvent('themechange', {
      detail: { theme: theme }
    });
    window.dispatchEvent(event);
  }

  /**
   * Listen to theme changes
   * @param {Function} callback - Function to call when theme changes
   */
  onThemeChange(callback) {
    window.addEventListener('themechange', (event) => {
      callback(event.detail.theme);
    });
  }

  /**
   * Listen to system preference changes
   */
  watchSystemPreference() {
    if (!window.matchMedia) return;

    const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
    darkModeQuery.addEventListener('change', (e) => {
      if (this.shouldUseSystemPreference()) {
        const newTheme = e.matches ? this.DARK_THEME : this.LIGHT_THEME;
        this.setTheme(newTheme);
      }
    });
  }

  /**
   * Enable system preference detection
   */
  enableSystemPreference() {
    localStorage.setItem('use-system-theme', 'true');
    const systemTheme = this.getSystemPreference();
    this.setTheme(systemTheme);
    this.watchSystemPreference();
  }

  /**
   * Disable system preference detection
   */
  disableSystemPreference() {
    localStorage.setItem('use-system-theme', 'false');
  }
}

// Defer instantiation until DOM is ready
function initializeThemeManager() {
  if (document.body && document.documentElement) {
    window.themeManager = new ThemeManager();
  }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeThemeManager);
} else {
  // DOM is already loaded
  initializeThemeManager();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ThemeManager;
}
