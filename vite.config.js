import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/sass/app.scss",
        "resources/js/frontend/app.js",
        "resources/js/backend/Views/app.js",
      ],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
      "@config": path.resolve(__dirname, "resources/js/backend/Config"),
      "@": path.resolve(__dirname, "resources/js/backend"),
    },
  },
  server: {
    watch: {
      ignored: ["**/vendor/**", "**/node_modules/**", "**/storage/**"],
    },
  },
});
