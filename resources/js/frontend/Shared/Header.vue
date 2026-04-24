<template>
  <!-- header start -->
  <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <!-- Brand/Logo -->
      <Link class="navbar-brand fw-bold text-primary d-flex align-items-center" href="/">
        <img src="/logo.png" alt="Dictionary Logo" class="logo-img me-2" />
        <span class="brand-text">E ⇄ B Dictionary</span>
      </Link>

      <!-- Mobile menu button -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation menu -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <Link class="nav-link" aria-current="page" href="/">Home</Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link" href="/vocabulary">Vocabulary</Link>
          </li>

          <li class="nav-item">
            <Link class="nav-link" href="/story">Story</Link>
          </li>
          
          <!-- Show Login link when not authenticated -->
          <li v-if="!is_auth" class="nav-item">
            <Link class="nav-link" href="/login">Login</Link>
          </li>
          
          <!-- Show User dropdown when authenticated -->
          <li v-if="is_auth" class="nav-item dropdown position-relative">
            <a 
              class="nav-link user-dropdown" 
              href="#" 
              role="button" 
              :aria-expanded="showDropdown"
              @click.prevent="toggleDropdown"
            >
              <img 
                :src="auth_info.avatar || auth_info.image || '/avatar.png'" 
                :alt="auth_info.name || 'User'" 
                class="user-avatar me-2"
              />
              <span class="user-name">{{ auth_info.name || 'User' }}</span>
              <i class="fas fa-chevron-down ms-1" :class="{ 'rotated': showDropdown }"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" :class="{ show: showDropdown }">
              <li class="dropdown-header">
                <div class="user-info">
                  <img 
                    :src="auth_info.avatar || auth_info.image || '/avatar.png'" 
                    :alt="auth_info.name || 'User'" 
                    class="dropdown-avatar"
                  />
                  <div class="user-details">
                    <div class="user-name-large">{{ auth_info.name || 'User' }}</div>
                    <div class="user-email">{{ auth_info.email || 'user@example.com' }}</div>
                  </div>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <Link class="dropdown-item" href="/profile" @click="closeDropdown">
                  <i class="fas fa-user me-2"></i>
                  My Profile
                </Link>
              </li>
              <li>
                <a class="dropdown-item" href="/profile#revision" @click="closeDropdown">
                  <i class="fas fa-heart me-2"></i>
                  My revision
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/profile#stories" @click="closeDropdown">
                  <i class="fas fa-book-open me-2"></i>
                  My Stories
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/profile#tests" @click="closeDropdown">
                  <i class="fas fa-brain me-2"></i>
                  Test History
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="/profile#settings" @click="closeDropdown">
                  <i class="fas fa-cog me-2"></i>
                  Settings
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="#" @click.prevent="logout">
                  <i class="fas fa-sign-out-alt me-2"></i>
                  Logout
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <!-- header end -->
</template>

<script>
import { mapActions, mapState } from "pinia";
import { auth_store } from "../GlobalStore/auth_store";

export default {
  data: () => ({
    is_home: true,
    search: "",
    showDropdown: false,
  }),
  
  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      is_auth: "is_auth",
    }),
  },
  
  methods: {
    ...mapActions(auth_store, {
      check_is_auth: "check_is_auth",
      log_out: "log_out",
    }),
    
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
      console.log('Dropdown toggled:', this.showDropdown);
    },
    
    closeDropdown() {
      this.showDropdown = false;
    },
    
    logout() {
      this.closeDropdown();
      this.log_out();
    },
    
    // Close dropdown when clicking outside
    handleClickOutside(event) {
      if (!this.showDropdown) return;
      
      // Get the dropdown elements using document.querySelector instead of this.$el
      const dropdown = document.querySelector('.user-dropdown-menu');
      const trigger = document.querySelector('.user-dropdown');
      
      if (dropdown && trigger && 
          !dropdown.contains(event.target) && 
          !trigger.contains(event.target)) {
        this.showDropdown = false;
      }
    }
  },
  
  async mounted() {
    console.log('Header component mounted');
    
    // Check authentication status when component mounts
    const token = localStorage.getItem('auth_token');
    if (token) {
      try {
        await this.check_is_auth();
        console.log('Auth check completed in header:', this.is_auth);
      } catch (error) {
        console.error('Auth check failed in header:', error);
      }
    }
    
    // Add click outside listener
    document.addEventListener('click', this.handleClickOutside);
  },
  
  beforeUnmount() {
    // Remove click outside listener
    document.removeEventListener('click', this.handleClickOutside);
  }
};
</script>

<style scoped>
.navbar-brand {
  font-size: 1.5rem;
}

.logo-img {
  height: 40px;
  width: auto;
}

.brand-text {
  white-space: nowrap;
}

@media (max-width: 768px) {
  .navbar-brand {
    font-size: 1.2rem;
  }

  .logo-img {
    height: 32px;
  }

  .brand-text {
    font-size: 1rem;
  }
}

@media (max-width: 576px) {
  .brand-text {
    display: none;
  }

  .logo-img {
    height: 36px;
  }
}

.nav-link {
  font-weight: 500;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: var(--bs-primary) !important;
}

/* User Dropdown Styles */
.user-dropdown {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  border-radius: 25px;
  transition: all 0.3s ease;
  text-decoration: none;
  color: #333;
  background: rgba(0, 123, 255, 0.1);
  border: 1px solid rgba(0, 123, 255, 0.2);
  cursor: pointer;
}

.user-dropdown:hover {
  background: rgba(0, 123, 255, 0.15);
  color: var(--bs-primary);
  text-decoration: none;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(0, 123, 255, 0.3);
}

.user-name {
  font-weight: 600;
  font-size: 0.9rem;
  max-width: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.user-dropdown i {
  font-size: 0.8rem;
  transition: transform 0.3s ease;
}

.user-dropdown i.rotated {
  transform: rotate(180deg);
}

/* Dropdown positioning */
.dropdown.position-relative {
  position: relative;
}

/* Dropdown Menu Styles */
.user-dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  min-width: 280px;
  border: none;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  border-radius: 12px;
  padding: 0;
  margin-top: 8px;
  overflow: hidden;
  z-index: 1000;
  opacity: 0;
  transform: translateY(-10px);
  transition: all 0.3s ease;
  pointer-events: none;
  display: block;
}

.user-dropdown-menu.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

.dropdown-header {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  padding: 20px;
  border: none;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.dropdown-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255, 255, 255, 0.3);
}

.user-details {
  flex: 1;
}

.user-name-large {
  font-weight: 600;
  font-size: 1.1rem;
  margin-bottom: 2px;
}

.user-email {
  font-size: 0.85rem;
  opacity: 0.9;
}

.dropdown-item {
  padding: 12px 20px;
  font-weight: 500;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
}

.dropdown-item:hover {
  background: #f8f9fa;
  color: var(--bs-primary);
  padding-left: 24px;
}

.dropdown-item i {
  width: 18px;
  font-size: 0.9rem;
  color: #6c757d;
}

.dropdown-item:hover i {
  color: var(--bs-primary);
}

.dropdown-item.text-danger:hover {
  background: #fff5f5;
  color: #dc3545;
}

.dropdown-item.text-danger:hover i {
  color: #dc3545;
}

.dropdown-divider {
  margin: 8px 0;
  border-color: #e9ecef;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .user-name {
    display: none;
  }
  
  .user-dropdown {
    padding: 6px 8px;
    min-width: auto;
  }
  
  .user-dropdown-menu {
    min-width: 260px;
    margin-right: 10px;
  }
}

@media (max-width: 576px) {
  .user-dropdown-menu {
    min-width: 240px;
    margin-right: 5px;
  }
  
  .dropdown-header {
    padding: 15px;
  }
  
  .user-name-large {
    font-size: 1rem;
  }
  
  .user-email {
    font-size: 0.8rem;
  }
}
</style>
