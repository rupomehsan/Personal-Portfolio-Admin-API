<template>
  <div class="login-container">
    <div class="login-card">
      <!-- Header Section -->
      <div class="login-header">
        <div class="shield-icon">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V18H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"
            />
          </svg>
        </div>
        <h1 class="login-title">Login Account</h1>
      </div>

      <!-- Form Section -->
      <div class="login-form-section">
        <div class="form-wrapper">
          <!-- Login Form -->
          <form @submit.prevent="LoginSubmitHandler" class="login-form">
            <!-- Email Field -->
            <div class="form-group">
              <label class="form-label">
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="label-icon"
                >
                  <path
                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
                  />
                </svg>
                Email Address
              </label>
              <input
                v-model="email"
                type="email"
                name="email"
                class="form-input"
                placeholder="test@gmail.com"
                required
              />
            </div>

            <!-- Password Field -->
            <div class="form-group">
              <label class="form-label">
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="label-icon"
                >
                  <path
                    d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M12,17c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S13.1,17,12,17z M15.1,8H8.9V6c0-1.71,1.39-3.1,3.1-3.1s3.1,1.39,3.1,3.1V8z"
                  />
                </svg>
                Password
              </label>
              <div class="password-input-wrapper">
                <input
                  :type="showPassword ? 'text' : 'password'"
                  name="password"
                  class="form-input"
                  placeholder="••••••••"
                  value=""
                  required
                  v-model="password"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="password-toggle-btn"
                >
                  <svg
                    v-if="!showPassword"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                  >
                    <path
                      d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"
                    />
                  </svg>
                  <svg
                    v-else
                    width="20"
                    height="20"
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

            <!-- Remember Me and Forgot Password -->
            <div class="form-options">
              <label class="remember-checkbox">
                <input type="checkbox" v-model="rememberMe" />

                Remember me
              </label>
              <Link href="forgot-password" class="forgot-link"
                >Forgot password?</Link
              >
            </div>

            <!-- Sign In Button -->
            <button type="submit" class="signin-btn">
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path d="M10,17L15,12L10,7V10H4V14H10V17Z" />
              </svg>
              Sign In
            </button>
          </form>
          <!-- Footer -->
          <div class="form-footer">
            <p class="signup-prompt">
              Don't have an account?
              <Link href="register" class="signup-link">Register</Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import Layout from "./Layout/Layout.vue";
//auth_store
import { auth_store } from "../../GlobalStore/auth_store";
export default {
  components: { Layout },

  data() {
    return {
      loading: false,
      checkingAuth: true, // Add loading state for auth check
      showPassword: false,
      passwordError: false,
      email: "superadmin@gmail.com",
      password: "12345678",
      rememberMe: false,
    };
  },

  created: async function () {
    this.checkingAuth = true;

    // Check if we have both token and role
    const token = localStorage.getItem("auth_token");
    const role = localStorage.getItem("auth_role");

    if (token && role) {
      try {
        // Verify authentication with server before redirecting
        await this.check_is_auth();

        // Only redirect if authentication is successful
        if (this.is_auth) {
          const prevUrl = window.sessionStorage.getItem("prevurl");
          const userRole = parseInt(role);

          console.log("User already authenticated, redirecting...", {
            role: userRole,
            prevUrl,
          });

          if (userRole === 1 || role === "1") {
            const redirectUrl =
              prevUrl && prevUrl.startsWith("/admin")
                ? "/admin" + prevUrl
                : "/admin#/dashboard";
            window.location.href = redirectUrl;
            return;
          }
        } else {
          // If auth check fails, clear invalid tokens
          console.log("Auth check failed, clearing invalid tokens");
          this.clearAuthData();
        }
      } catch (error) {
        console.error("Auth verification failed:", error);
        // Clear potentially invalid tokens
        this.clearAuthData();
      }
    }

    // If no token, invalid auth, or auth check failed, user stays on login page
    this.checkingAuth = false;
  },

  mounted() {
    this.loadRememberedCredentials();
  },

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      is_auth: "is_auth",
    }),
  },

  methods: {
    ...mapActions(auth_store, {
      check_is_auth: "check_is_auth",
    }),

    LoginSubmitHandler: async function () {
      try {
        this.loading = true;

        // Handle remember me functionality
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
            // Store authentication data
            localStorage.setItem("auth_token", data.access_token);
            localStorage.setItem("auth_role", data.user.role_id);

            // Clear any previous URL after successful login
            const prevUrl = window.sessionStorage.getItem("prevurl");
            window.sessionStorage.removeItem("prevurl");

            // Show success message
            window.s_alert("Login Successfully");

            // Redirect based on role
            let redirectUrl = "";
            if (data.user.role_id === 1) {
              redirectUrl =
                prevUrl && prevUrl.startsWith("/admin")
                  ? prevUrl
                  : "/admin#/dashboard";
            }

            if (redirectUrl) {
              // Small delay to ensure token is saved before redirect
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
        console.error("Login error", error);

        // Handle specific error responses
        if (
          error.response &&
          error.response.data &&
          error.response.data.message
        ) {
          window.s_alert(error.response.data.message, "error");
        } else if (error.response && error.response.status === 401) {
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
      const credentials = {
        email: this.email,
        password: this.password,
        rememberMe: this.rememberMe,
      };
      localStorage.setItem(
        "rememberedCredentials",
        JSON.stringify(credentials),
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
      const savedCredentials = localStorage.getItem("rememberedCredentials");
      if (savedCredentials) {
        try {
          const credentials = JSON.parse(savedCredentials);
          this.email = credentials.email || "";
          this.password = credentials.password || "";
          this.rememberMe = credentials.rememberMe || false;
        } catch (error) {
          console.error("Error loading remembered credentials:", error);
          this.clearSavedCredentials();
        }
      }
    },

    setPassword(email) {
      this.email = email;
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

<style scoped>
/* Main Container */
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-family:
    "Inter",
    -apple-system,
    BlinkMacSystemFont,
    sans-serif;
}

/* Login Card */
.login-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 420px;
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Header Section */
.login-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px 30px;
  text-align: center;
  color: white;
}

.shield-icon {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  color: white;
}
.shield-icon svg {
  color: white !important;
}

.login-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0 0 8px 0;
  letter-spacing: -0.025em;
  color: white !important;
}

.login-subtitle {
  font-size: 1rem;
  opacity: 0.9;
  margin: 0;
  font-weight: 400;
}

/* Form Section */
.login-form-section {
  padding: 40px 30px;
}

/* Form Groups */
.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.label-icon {
  color: #6b7280;
}

.form-input {
  width: 100%;
  padding: 16px 18px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: #f9fafb;
  color: #1f2937;
  outline: none;
  box-sizing: border-box;
}

.form-input::placeholder {
  color: #9ca3af;
  font-weight: 400;
}

.form-input:focus {
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Password Input Wrapper */
.password-input-wrapper {
  position: relative;
}

.password-input-wrapper .form-input {
  padding-right: 50px;
}

.password-toggle-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.password-toggle-btn:hover {
  color: #667eea;
  background: rgba(102, 126, 234, 0.1);
}

/* Form Options */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.remember-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
}

.remember-checkbox input[type="checkbox"] {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  cursor: pointer;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  display: inline-block;
  position: relative;
  transition: all 0.3s ease;
}

.remember-checkbox input[type="checkbox"]:checked + .checkmark {
  background: #667eea;
  border-color: #667eea;
}

.remember-checkbox input[type="checkbox"]:checked + .checkmark::after {
  content: "";
  position: absolute;
  left: 5px;
  top: 2px;
  width: 6px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.forgot-link {
  color: #667eea;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: color 0.3s ease;
}

.forgot-link:hover {
  color: #764ba2;
}

/* Sign In Button */
.signin-btn {
  width: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 16px 24px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 24px;
}

.signin-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.signin-btn:active {
  transform: translateY(0);
}

/* Form Footer */
.form-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 24px;
}

.signup-prompt {
  color: #6b7280;
  font-size: 14px;
  margin: 0;
}

.signup-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.signup-link:hover {
  color: #764ba2;
}

/* Responsive Design */
@media (max-width: 480px) {
  .login-container {
    padding: 10px;
  }

  .login-header {
    padding: 30px 20px;
  }

  .login-title {
    font-size: 1.5rem;
  }

  .login-form-section {
    padding: 30px 20px;
  }

  .shield-icon {
    width: 70px;
    height: 70px;
  }
}
</style>
