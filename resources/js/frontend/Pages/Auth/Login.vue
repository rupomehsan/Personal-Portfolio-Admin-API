<template>
  <title>Login |</title>
  <div class="login-root">
    <!-- Video Background -->
    <video class="bg-video" autoplay muted loop playsinline>
      <source src="/frontend/encryption-bg.webm" type="video/webm" />
    </video>
    <div class="bg-overlay"></div>

    <!-- Glass Effect Layer -->
    <div class="bg-glass" aria-hidden="true"></div>

    <!-- Binary Effect Canvas -->
    <canvas ref="rainCanvas" class="binary-canvas" aria-hidden="true"></canvas>

    <!-- Login Panel -->
    <div class="terminal-panel">
      <!-- Terminal Top Bar -->
      <div class="terminal-topbar">
        <div class="terminal-dots">
          <span class="dot dot-red"></span>
          <span class="dot dot-yellow"></span>
          <span class="dot dot-green"></span>
        </div>
        <span class="terminal-title">root@sys:~# AUTH_INIT.SH</span>
        <span class="blink-cursor">▮</span>
      </div>

      <!-- Header -->
      <div class="terminal-header">
        <div class="lock-icon">
          <svg
            width="36"
            height="36"
            viewBox="0 0 24 24"
            fill="#00ffe0"
            style="filter: drop-shadow(0 0 8px #00ffe0)"
          >
            <path
              d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M12,17c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S13.1,17,12,17z M15.1,8H8.9V6c0-1.71,1.39-3.1,3.1-3.1s3.1,1.39,3.1,3.1V8z"
            />
          </svg>
        </div>
        <h1 class="sys-title">SECURE_ACCESS</h1>
        <p class="sys-subtitle">
          <span class="prompt">$&gt;</span> Authenticate to continue<span
            class="animated-dots"
            ><span>.</span><span>.</span><span>.</span></span
          >
        </p>
      </div>

      <!-- Form Body -->
      <form @submit.prevent="LoginSubmitHandler" class="terminal-form">
        <!-- Email -->
        <div class="field-group">
          <label class="field-label">[ USR_EMAIL ]</label>
          <div class="input-wrap">
            <span class="input-prefix">&#9658;</span>
            <input
              v-model="email"
              type="email"
              name="email"
              class="field-input"
              placeholder="user@domain.com"
              autocomplete="email"
              required
            />
          </div>
        </div>

        <!-- Password -->
        <div class="field-group">
          <label class="field-label">[ PASSWD_HASH ]</label>
          <div class="input-wrap">
            <span class="input-prefix">&#9658;</span>
            <input
              :type="showPassword ? 'text' : 'password'"
              v-model="password"
              name="password"
              class="field-input"
              placeholder="••••••••••••"
              autocomplete="current-password"
              required
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="eye-btn"
            >
              <svg
                v-if="!showPassword"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"
                />
              </svg>
              <svg
                v-else
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M11.83,9L15,12.16C15,12.11 15,12.05 15,12A3,3 0 0,0 12,9C11.94,9 11.89,9 11.83,9M7.53,9.8L9.08,11.35C9.03,11.56 9,11.77 9,12A3,3 0 0,0 12,15C12.22,15 12.44,14.97 12.65,14.92L14.2,16.47C13.53,16.8 12.79,17 12,17A5,5 0 0,1 7,12C7,11.21 7.2,10.47 7.53,9.8M2,4.27L4.28,6.55L4.73,7C3.08,8.3 1.78,10 1,12C2.73,16.39 7,19.5 12,19.5C13.55,19.5 15.03,19.2 16.38,18.66L16.81,19.09L19.73,22L21,20.73L3.27,3M12,7A5,5 0 0,1 17,12C17,12.64 16.87,13.26 16.64,13.82L19.57,16.75C21.07,15.5 22.27,13.86 23,12C21.27,7.61 17,4.5 12,4.5C10.6,4.5 9.26,4.75 8,5.2L10.17,7.35C10.76,7.13 11.38,7 12,7Z"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- Options -->
        <div class="form-options">
          <label class="remember-label">
            <input type="checkbox" v-model="rememberMe" class="cb-input" />
            <span class="cb-custom"></span>
            <span class="cb-text">--remember-me</span>
          </label>
          <Link href="forgot-password" class="forgot-link">forgot_passwd?</Link>
        </div>

        <!-- Submit -->
        <button type="submit" class="transmit-btn" :disabled="loading">
          <span v-if="!loading" class="btn-inner">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
            </svg>
            TRANSMIT_
          </span>
          <span v-else class="btn-inner">
            <span class="spinner"></span>
            PROCESSING...
          </span>
        </button>
      </form>

      <!-- Footer -->
      <div class="terminal-footer">
        <span class="footer-text">No account?</span>
        <Link href="register" class="register-link">./register --new</Link>
      </div>

      <!-- Corner decorations -->
      <span class="corner corner-tl"></span>
      <span class="corner corner-tr"></span>
      <span class="corner corner-bl"></span>
      <span class="corner corner-br"></span>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { auth_store } from "../../GlobalStore/auth_store";

export default {
  data() {
    return {
      loading: false,
      checkingAuth: true,
      showPassword: false,
      email: "superadmin@gmail.com",
      password: "12345678",
      rememberMe: false,
      _rainId: null,
    };
  },

  async created() {
    this.checkingAuth = true;
    const token = localStorage.getItem("auth_token");
    const role = localStorage.getItem("auth_role");

    if (token && role) {
      try {
        await this.check_is_auth();
        if (this.is_auth) {
          window.location.href = "/admin#/dashboard";
        } else {
          this.clearAuthData();
        }
      } catch {
        this.clearAuthData();
      }
    }
    this.checkingAuth = false;
  },

  mounted() {
    this.loadRememberedCredentials();
    this.startBinaryRain();
  },

  beforeUnmount() {
    if (this._rainId) cancelAnimationFrame(this._rainId);
  },

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      is_auth: "is_auth",
    }),
  },

  methods: {
    ...mapActions(auth_store, { check_is_auth: "check_is_auth" }),

    startBinaryRain() {
      const canvas = this.$refs.rainCanvas;
      if (!canvas) return;
      const ctx = canvas.getContext("2d");
      const fontSize = 18;
      let frame = 0;
      let mouseX = canvas.width / 2;
      let mouseY = canvas.height / 2;

      const resize = () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
      };
      resize();
      window.addEventListener("resize", resize);

      // Track mouse movement for hover effect
      document.addEventListener("mousemove", (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
      });

      // Generate static binary grid pattern
      let rows = Math.ceil(canvas.height / fontSize);
      let cols = Math.ceil(canvas.width / fontSize);
      let binaryGrid = Array.from({ length: rows }, () =>
        Array.from({ length: cols }, () => (Math.random() > 0.5 ? "1" : "0")),
      );

      const draw = () => {
        frame++;

        // Very light clear - transparent to show video
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw static binary grid with hover effect
        ctx.font = `${fontSize}px "Courier New", monospace`;

        // Determine which single cell the cursor is currently on
        const hoverCol = Math.floor(mouseX / fontSize);
        const hoverRow = Math.floor(mouseY / fontSize);

        for (let row = 0; row < rows; row++) {
          for (let col = 0; col < cols; col++) {
            const bit = binaryGrid[row][col];
            const x = col * fontSize;
            const y = row * fontSize + fontSize;

            // Base opacity with subtle pulsing
            let opacity =
              0.12 + 0.06 * Math.sin((row + col + frame * 0.02) * 0.1);

            // Light up ONLY the specific bit directly under the cursor
            if (row === hoverRow && col === hoverCol) {
              opacity = 1;
            }

            ctx.fillStyle = `rgba(0, 255, 210, ${opacity})`;
            ctx.fillText(bit, x, y);
          }
        }

        // Occasionally refresh some bits for subtle animation
        if (frame % 120 === 0) {
          const randRow = Math.floor(Math.random() * rows);
          const randCol = Math.floor(Math.random() * cols);
          binaryGrid[randRow][randCol] = Math.random() > 0.5 ? "1" : "0";
        }

        this._rainId = requestAnimationFrame(draw);
      };

      draw();
    },

    async LoginSubmitHandler() {
      try {
        this.loading = true;
        if (this.rememberMe) {
          this.saveCredentials();
        } else {
          this.clearSavedCredentials();
        }

        let formData = new FormData();
        formData.append("email", this.email);
        formData.append("password", this.password);
        formData.append("remember", this.rememberMe);

        let response = await axios.post("/login", formData);

        if (response.data?.status === "success") {
          let data = response.data?.data;
          if (data.access_token && data.user) {
            localStorage.setItem("auth_token", data.access_token);
            localStorage.setItem("auth_role", data.user.role_id);
            const prevUrl = window.sessionStorage.getItem("prevurl");
            window.sessionStorage.removeItem("prevurl");
            window.s_alert("Login Successfully");
            let redirectUrl = "";
            if (data.user.role_id) {
              redirectUrl = "/admin#/dashboard";
            }
            if (redirectUrl) {
              setTimeout(() => {
                window.location.href = redirectUrl;
              }, 100);
            }
          } else {
            window.s_alert(
              "Login failed. Invalid response from server.",
              "error",
            );
          }
        } else {
          window.s_alert(
            response.data?.message || "Login failed. Please try again.",
            "error",
          );
        }
      } catch (error) {
        if (error.response?.data?.message) {
          window.s_alert(error.response.data.message, "error");
        } else if (error.response?.status === 401) {
          window.s_alert(
            "Invalid credentials. Please check your email and password.",
            "error",
          );
        } else {
          window.s_alert(
            "Login failed. Please check your connection and try again.",
            "error",
          );
        }
      } finally {
        this.loading = false;
      }
    },

    saveCredentials() {
      localStorage.setItem(
        "rememberedCredentials",
        JSON.stringify({
          email: this.email,
          password: this.password,
          rememberMe: this.rememberMe,
        }),
      );
    },

    clearSavedCredentials() {
      localStorage.removeItem("rememberedCredentials");
    },

    clearAuthData() {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_role");
      window.sessionStorage.removeItem("prevurl");
    },

    loadRememberedCredentials() {
      const saved = localStorage.getItem("rememberedCredentials");
      if (saved) {
        try {
          const c = JSON.parse(saved);
          this.email = c.email || "";
          this.password = c.password || "";
          this.rememberMe = c.rememberMe || false;
        } catch {
          this.clearSavedCredentials();
        }
      }
    },
  },
};
</script>

<style scoped>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

:global(body),
:global(html) {
  overflow: hidden !important;
  height: 100%;
}

.login-root {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  font-family: "Courier New", Courier, monospace;
  background: #020408;
}

/* Video BG — highly visible */
.bg-video {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 100vw;
  height: 100vh;
  min-width: 100vw;
  min-height: 100vh;
  object-fit: cover;
  object-position: center center;
  transform: translate(-50%, -50%) scale(1.6);
  transform-origin: center center;
  z-index: 0;
  opacity: 1;
}

/* Very light overlay to show video clearly */
.bg-overlay {
  position: fixed;
  inset: 0;
  background: rgba(2, 4, 8, 0.08);
  z-index: 1;
}

/* Frosted Glass Effect Layer */
.bg-glass {
  position: fixed;
  inset: 0;
  z-index: 2;
  pointer-events: none;
  backdrop-filter: blur(6px) saturate(140%);
  -webkit-backdrop-filter: blur(6px) saturate(140%);
  background: linear-gradient(
    135deg,
    rgba(2, 8, 14, 0.25) 0%,
    rgba(0, 255, 210, 0.03) 50%,
    rgba(2, 8, 14, 0.3) 100%
  );
  border-top: 1px solid rgba(255, 255, 255, 0.04);
  box-shadow: inset 0 0 120px rgba(0, 0, 0, 0.35);
}

/* Binary Canvas */
.binary-canvas {
  position: fixed;
  inset: 0;
  z-index: 3;
  pointer-events: none;
  width: 100%;
  height: 100%;
}

/* Terminal Panel */
.terminal-panel {
  position: relative;
  z-index: 10;
  width: calc(100% - 32px);
  max-width: 460px;
  background: rgba(4, 10, 18, 0.9);
  border: 1px solid rgba(0, 255, 200, 0.3);
  border-radius: 6px;
  box-shadow:
    0 0 0 1px rgba(0, 255, 200, 0.08),
    0 0 50px rgba(0, 255, 200, 0.12),
    0 30px 60px rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(14px);
  overflow: hidden;
  animation: panelIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
}

@keyframes panelIn {
  from {
    opacity: 0;
    transform: translateY(32px) scale(0.97);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Corner decorations */
.corner {
  position: absolute;
  width: 14px;
  height: 14px;
  border-color: #00ffe0;
  border-style: solid;
  opacity: 0.7;
  pointer-events: none;
}
.corner-tl {
  top: 0;
  left: 0;
  border-width: 2px 0 0 2px;
}
.corner-tr {
  top: 0;
  right: 0;
  border-width: 2px 2px 0 0;
}
.corner-bl {
  bottom: 0;
  left: 0;
  border-width: 0 0 2px 2px;
}
.corner-br {
  bottom: 0;
  right: 0;
  border-width: 0 2px 2px 0;
}

/* Top bar */
.terminal-topbar {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(0, 0, 0, 0.55);
  border-bottom: 1px solid rgba(0, 255, 200, 0.15);
  padding: 10px 16px;
}

.terminal-dots {
  display: flex;
  gap: 6px;
}
.dot {
  width: 11px;
  height: 11px;
  border-radius: 50%;
}
.dot-red {
  background: #ff5f57;
  box-shadow: 0 0 6px #ff5f5780;
}
.dot-yellow {
  background: #febc2e;
  box-shadow: 0 0 6px #febc2e80;
}
.dot-green {
  background: #28c840;
  box-shadow: 0 0 6px #28c84080;
}

.terminal-title {
  flex: 1;
  font-size: 11px;
  color: #00ffe0;
  letter-spacing: 0.08em;
  opacity: 0.85;
}

.blink-cursor {
  color: #00ffe0;
  animation: blink 1s step-end infinite;
  font-size: 14px;
}
@keyframes blink {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0;
  }
}

/* Header */
.terminal-header {
  padding: 28px 30px 20px;
  text-align: center;
  border-bottom: 1px solid rgba(0, 255, 200, 0.1);
}

.lock-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 68px;
  height: 68px;
  border-radius: 50%;
  border: 2px solid rgba(0, 255, 200, 0.75);
  background: rgba(0, 255, 200, 0.1);
  color: #00ffe0;
  margin-bottom: 14px;
  box-shadow:
    0 0 28px rgba(0, 255, 200, 0.4),
    inset 0 0 14px rgba(0, 255, 200, 0.1);
}

.sys-title {
  font-size: 1.5rem;
  font-weight: 700;
  letter-spacing: 0.18em;
  color: #ffffff;
  text-shadow:
    0 0 12px rgba(0, 255, 200, 0.9),
    0 0 30px rgba(0, 255, 200, 0.5);
  margin-bottom: 8px;
}

/* Subtitle — bright and readable */
.sys-subtitle {
  font-size: 13px;
  color: #ffffff;
  letter-spacing: 0.05em;
  text-shadow: 0 0 10px rgba(0, 255, 200, 0.6);
}

.prompt {
  color: #a78bfa;
  margin-right: 4px;
}

/* Animated bouncing dots */
.animated-dots {
  display: inline-flex;
  gap: 1px;
}
.animated-dots span {
  color: #00ffe0;
  font-weight: 700;
  animation: dotBounce 1.4s infinite ease-in-out;
}
.animated-dots span:nth-child(1) {
  animation-delay: 0s;
}
.animated-dots span:nth-child(2) {
  animation-delay: 0.2s;
}
.animated-dots span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes dotBounce {
  0%,
  80%,
  100% {
    opacity: 0.25;
    transform: translateY(0);
  }
  40% {
    opacity: 1;
    transform: translateY(-4px);
  }
}

/* Form */
.terminal-form {
  padding: 26px 30px;
}

.field-group {
  margin-bottom: 20px;
}

.field-label {
  display: block;
  font-size: 11px;
  color: #00ffe0;
  letter-spacing: 0.12em;
  margin-bottom: 8px;
  opacity: 0.8;
}

.input-wrap {
  display: flex;
  align-items: center;
  background: rgba(0, 255, 200, 0.04);
  border: 1px solid rgba(0, 255, 200, 0.2);
  border-radius: 4px;
  transition:
    border-color 0.25s,
    box-shadow 0.25s;
  overflow: hidden;
}

.input-wrap:focus-within {
  border-color: rgba(0, 255, 200, 0.6);
  box-shadow:
    0 0 0 3px rgba(0, 255, 200, 0.08),
    0 0 14px rgba(0, 255, 200, 0.12);
}

.input-prefix {
  padding: 0 10px;
  color: #00ffe0;
  font-size: 12px;
  opacity: 0.55;
  flex-shrink: 0;
}

.field-input {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  color: #e2e8f0;
  font-family: "Courier New", monospace;
  font-size: 14px;
  padding: 13px 12px 13px 0;
  caret-color: #00ffe0;
}

.field-input::placeholder {
  color: rgba(255, 255, 255, 0.2);
}

.eye-btn {
  background: none;
  border: none;
  color: rgba(0, 255, 200, 0.45);
  cursor: pointer;
  padding: 0 12px;
  display: flex;
  align-items: center;
  transition: color 0.2s;
  flex-shrink: 0;
}
.eye-btn:hover {
  color: #00ffe0;
}

/* Options row */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.remember-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.cb-input {
  display: none;
}

.cb-custom {
  width: 14px;
  height: 14px;
  border: 1px solid rgba(0, 255, 200, 0.45);
  border-radius: 2px;
  background: transparent;
  display: inline-block;
  position: relative;
  transition:
    background 0.2s,
    border-color 0.2s;
  flex-shrink: 0;
}

.cb-input:checked + .cb-custom {
  background: #00ffe0;
  border-color: #00ffe0;
}
.cb-input:checked + .cb-custom::after {
  content: "";
  position: absolute;
  left: 3px;
  top: 1px;
  width: 5px;
  height: 8px;
  border: solid #000;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.cb-text {
  font-size: 12px;
  color: rgba(0, 255, 200, 0.65);
  letter-spacing: 0.04em;
}

.forgot-link {
  font-size: 12px;
  color: #a78bfa;
  text-decoration: none;
  letter-spacing: 0.04em;
  transition:
    color 0.2s,
    text-shadow 0.2s;
}
.forgot-link:hover {
  color: #c4b5fd;
  text-shadow: 0 0 8px rgba(167, 139, 250, 0.5);
}

/* Transmit Button */
.transmit-btn {
  width: 100%;
  background: transparent;
  border: 1px solid rgba(0, 255, 200, 0.55);
  border-radius: 4px;
  color: #00ffe0;
  font-family: "Courier New", monospace;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.2em;
  padding: 14px 24px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.transmit-btn::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(0, 255, 200, 0.1),
    transparent
  );
  transform: translateX(-100%);
  transition: transform 0.5s ease;
}

.transmit-btn:hover::before {
  transform: translateX(100%);
}

.transmit-btn:hover {
  background: rgba(0, 255, 200, 0.08);
  border-color: #00ffe0;
  box-shadow:
    0 0 24px rgba(0, 255, 200, 0.25),
    inset 0 0 20px rgba(0, 255, 200, 0.05);
  text-shadow: 0 0 8px rgba(0, 255, 200, 0.7);
}

.transmit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-inner {
  display: flex;
  align-items: center;
  gap: 10px;
  position: relative;
  z-index: 1;
}

/* Spinner */
.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(0, 255, 200, 0.2);
  border-top-color: #00ffe0;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  display: inline-block;
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Footer */
.terminal-footer {
  padding: 16px 30px 22px;
  text-align: center;
  border-top: 1px solid rgba(0, 255, 200, 0.1);
}

.footer-text {
  font-size: 12px;
  color: rgba(255, 255, 255, 0.35);
  margin-right: 6px;
  letter-spacing: 0.04em;
}

.register-link {
  font-size: 12px;
  color: #a78bfa;
  text-decoration: none;
  letter-spacing: 0.04em;
  transition:
    color 0.2s,
    text-shadow 0.2s;
}
.register-link:hover {
  color: #c4b5fd;
  text-shadow: 0 0 8px rgba(167, 139, 250, 0.5);
}

/* Responsive */
@media (max-width: 480px) {
  .terminal-form {
    padding: 20px;
  }
  .terminal-header {
    padding: 20px 20px 16px;
  }
  .sys-title {
    font-size: 1.2rem;
  }
}
</style>
