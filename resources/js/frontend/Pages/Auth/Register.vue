<template>
  <div class="register-container">
    <div class="register-card">
      <!-- Header Section -->
      <div class="register-header">
        <div class="shield-icon">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10.5V11.5C15.4,11.5 16,12.4 16,13V16C16,17.4 15.4,18 14.8,18H9.2C8.6,18 8,17.4 8,16V13C8,12.4 8.6,11.5 9.2,11.5V10.5C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,10.5V11.5H13.5V10.5C13.5,8.7 12.8,8.2 12,8.2Z"
            />
          </svg>
        </div>
        <h1 class="register-title">Create Account</h1>
        <p class="register-subtitle">Join MyDictionary today</p>
      </div>

      <!-- Form Section -->
      <div class="register-form-section">
        <form @submit.prevent="RegisterSubmitHandler" class="register-form">
          <!-- Name Field -->
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="label-icon">
                <path
                  d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"
                />
              </svg>
              Full Name
            </label>
            <input type="text" name="name" class="form-input" placeholder="Enter your full name" required @input="errorReset" />
            <div id="name-error" class="error-message"></div>
          </div>

          <!-- Email Field -->
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="label-icon">
                <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
              </svg>
              Email Address
            </label>
            <input type="email" name="email" class="form-input" placeholder="Enter your email" required @input="errorReset" />
            <div id="email-error" class="error-message"></div>
          </div>

          <!-- Password Field -->
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="label-icon">
                <path
                  d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"
                />
              </svg>
              Password
            </label>
            <div class="password-input-wrapper">
              <input
                :type="showPassword ? 'text' : 'password'"
                name="password"
                class="form-input"
                placeholder="Create a secure password"
                required
                @input="errorReset"
              />
              <button type="button" class="password-toggle-btn" @click="showPassword = !showPassword">
                <svg v-if="!showPassword" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"
                  />
                </svg>
                <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M11.83,9L15,12.16C15,12.11 15,12.05 15,12A3,3 0 0,0 12,9C11.94,9 11.89,9 11.83,9M7.53,9.8L9.08,11.35C9.03,11.56 9,11.77 9,12A3,3 0 0,0 12,15C12.22,15 12.44,14.97 12.65,14.92L14.2,16.47C13.53,16.8 12.79,17 12,17A5,5 0 0,1 7,12C7,11.21 7.2,10.47 7.53,9.8M2,4.27L4.28,6.55L4.73,7C3.08,8.3 1.78,10 1,12C2.73,16.39 7,19.5 12,19.5C13.55,19.5 15.03,19.2 16.38,18.66L16.81,19.09L19.73,22L21,20.73L3.27,3M12,7A5,5 0 0,1 17,12C17,12.64 16.87,13.26 16.64,13.82L19.57,16.75C21.07,15.5 22.27,13.86 23,12C21.27,7.61 17,4.5 12,4.5C10.6,4.5 9.26,4.75 8,5.2L10.17,7.35C10.76,7.13 11.37,7 12,7Z"
                  />
                </svg>
              </button>
            </div>
            <div id="password-error" class="error-message"></div>
            <div class="password-requirements">
              <small>Password must be at least 8 characters long</small>
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div class="form-group">
            <label class="form-label">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="label-icon">
                <path
                  d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"
                />
              </svg>
              Confirm Password
            </label>
            <div class="password-input-wrapper">
              <input
                :type="showConfirmPassword ? 'text' : 'password'"
                name="password_confirmation"
                class="form-input"
                placeholder="Confirm your password"
                required
                @input="errorReset"
              />
              <button type="button" class="password-toggle-btn" @click="showConfirmPassword = !showConfirmPassword">
                <svg v-if="!showConfirmPassword" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"
                  />
                </svg>
                <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M11.83,9L15,12.16C15,12.11 15,12.05 15,12A3,3 0 0,0 12,9C11.94,9 11.89,9 11.83,9M7.53,9.8L9.08,11.35C9.03,11.56 9,11.77 9,12A3,3 0 0,0 12,15C12.22,15 12.44,14.97 12.65,14.92L14.2,16.47C13.53,16.8 12.79,17 12,17A5,5 0 0,1 7,12C7,11.21 7.2,10.47 7.53,9.8M2,4.27L4.28,6.55L4.73,7C3.08,8.3 1.78,10 1,12C2.73,16.39 7,19.5 12,19.5C13.55,19.5 15.03,19.2 16.38,18.66L16.81,19.09L19.73,22L21,20.73L3.27,3M12,7A5,5 0 0,1 17,12C17,12.64 16.87,13.26 16.64,13.82L19.57,16.75C21.07,15.5 22.27,13.86 23,12C21.27,7.61 17,4.5 12,4.5C10.6,4.5 9.26,4.75 8,5.2L10.17,7.35C10.76,7.13 11.37,7 12,7Z"
                  />
                </svg>
              </button>
            </div>
            <div id="password_confirmation-error" class="error-message"></div>
          </div>

          <!-- Terms Agreement -->
          <div class="terms-agreement">
            <label class="terms-checkbox">
              <input type="checkbox" name="terms" required />
              <span class="checkmark"></span>
              <span class="terms-text">
                I agree to the
                <a href="/terms" class="terms-link">Terms of Service</a>
                and
                <a href="/privacy" class="terms-link">Privacy Policy</a>
              </span>
            </label>
            <div id="terms-error" class="error-message"></div>
          </div>

          <!-- Register Button -->
          <button type="submit" class="register-btn" :disabled="loading">
            <div v-if="loading" class="loading-content">
              <div class="spinner"></div>
              Creating Account...
            </div>
            <div v-else class="button-content">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
              </svg>
              Create Account
            </div>
          </button>
        </form>

        <!-- Form Footer -->
        <div class="form-footer">
          <p class="signin-prompt">
            Already have an account?
            <router-link to="/login" class="signin-link">Sign In</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Register",
  data() {
    return {
      loading: false,
      showPassword: false,
      showConfirmPassword: false,
    };
  },
  methods: {
    async RegisterSubmitHandler() {
      try {
        this.loading = true;
        let formData = new FormData(event.target);
        let response = await axios.post("/register", formData);

        console.log("aaa", response);

        if (response.data?.data?.access_token && response.data?.data?.user) {
          let access_token = response.data.data.access_token;
          let user = response.data.data.user;
          // Store authentication data
          localStorage.setItem("auth_token", access_token);
          localStorage.setItem("auth_role", user.role_id);

          // Clear any previous URL after successful login
          const prevUrl = window.sessionStorage.getItem("prevurl");
          window.sessionStorage.removeItem("prevurl");

          // Show success message
          window.s_alert("Registration Successfully");

          // Redirect based on role
          let redirectUrl = "/profile";

          if (redirectUrl) {
            // Small delay to ensure token is saved before redirect
            setTimeout(() => {
              window.location.href = redirectUrl;
            }, 100);
          }
        } else {
          window.s_alert("Registration failed. Invalid response from server.", "error");
        }
      } catch (error) {
        if (error.response?.data?.errors) {
          this.displayErrors(error.response.data.errors);
        } else {
          window.s_alert("Registration failed. Please try again.", "error");
        }
        console.error("Registration error", error);
      } finally {
        this.loading = false;
      }
    },

    errorReset(event) {
      // Reset all error states
      const fieldName = event.target.name;
      const errorElement = document.getElementById(`${fieldName}-error`);
      if (errorElement) {
        errorElement.style.display = "none";
        errorElement.textContent = "";
      }

      event.target.classList.remove("error");
    },

    displayErrors(errors) {
      // Clear existing errors first
      document.querySelectorAll(".error-message").forEach((element) => {
        element.style.display = "none";
        element.textContent = "";
      });

      document.querySelectorAll(".form-input").forEach((element) => {
        element.classList.remove("error");
      });

      // Display new errors
      Object.keys(errors).forEach((fieldName) => {
        const errorElement = document.getElementById(`${fieldName}-error`);
        const inputElement = document.querySelector(`[name="${fieldName}"]`);

        if (errorElement && errors[fieldName][0]) {
          errorElement.textContent = errors[fieldName][0];
          errorElement.style.display = "block";
        }

        if (inputElement) {
          inputElement.classList.add("error");
        }
      });
    },
  },
};
</script>

<style scoped>
/* Container */
.register-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

/* Main Card */
.register-card {
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
.register-header {
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

.register-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0 0 8px 0;
  letter-spacing: -0.025em;
}

.register-subtitle {
  font-size: 1rem;
  opacity: 0.9;
  margin: 0;
  font-weight: 400;
}

/* Form Section */
.register-form-section {
  padding: 40px 30px;
}

/* Form Groups */
.form-group {
  margin-bottom: 20px;
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

/* Error Messages */
.error-message {
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px;
  display: none;
}

.form-input.error {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.password-requirements {
  margin-top: 6px;
}

.password-requirements small {
  color: #6b7280;
  font-size: 12px;
}

/* Terms Agreement */
.terms-agreement {
  margin-bottom: 24px;
}

.terms-checkbox {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
  line-height: 1.4;
}

.terms-checkbox input[type="checkbox"] {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  display: inline-block;
  position: relative;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.terms-checkbox input[type="checkbox"]:checked + .checkmark {
  background: #667eea;
  border-color: #667eea;
}

.terms-checkbox input[type="checkbox"]:checked + .checkmark::after {
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

.terms-text {
  line-height: 1.4;
}

.terms-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.terms-link:hover {
  color: #764ba2;
}

/* Register Button */
.register-btn {
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

.register-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.register-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.register-btn:active {
  transform: translateY(0);
}

.loading-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.spinner {
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.button-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Form Footer */
.form-footer {
  text-align: center;
  border-top: 1px solid #e5e7eb;
  padding-top: 24px;
}

.signin-prompt {
  color: #6b7280;
  font-size: 14px;
  margin: 0;
}

.signin-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.signin-link:hover {
  color: #764ba2;
}

/* Responsive Design */
@media (max-width: 480px) {
  .register-container {
    padding: 10px;
  }

  .register-header {
    padding: 30px 20px;
  }

  .register-title {
    font-size: 1.5rem;
  }

  .register-form-section {
    padding: 30px 20px;
  }

  .shield-icon {
    width: 70px;
    height: 70px;
  }

  .form-group {
    margin-bottom: 18px;
  }
}
</style>
