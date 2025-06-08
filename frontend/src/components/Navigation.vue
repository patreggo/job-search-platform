<template>
  <nav class="navigation">
    <div class="nav-container">
      <!-- Логотип -->
      <div class="nav-brand">
        <router-link to="/" class="brand-link">
          <div class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
              <rect width="32" height="32" rx="8" fill="#4f46e5"/>
              <path d="M8 12h16M8 16h16M8 20h12" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <span class="brand-text">JobPortal</span>
        </router-link>
      </div>

      <!-- Основная навигация -->
      <div class="nav-menu" :class="{ 'nav-menu-open': isMobileMenuOpen }">
        <!-- Навигация для неавторизованных пользователей -->
        <div v-if="!isAuthenticated" class="nav-links">
          <router-link to="/" class="nav-link" @click="closeMobileMenu">
            Главная
          </router-link>
        </div>

        <!-- Навигация для соискателей -->
        <div v-else-if="isUser" class="nav-links">
          <router-link to="/main" class="nav-link" @click="closeMobileMenu">
            <i class="icon">🏠</i>
            Главная
          </router-link>

          <div class="nav-dropdown">
            <button class="nav-link dropdown-trigger">
              <i class="icon">📄</i>
              Резюме
              <i class="dropdown-arrow">▼</i>
            </button>
            <div class="dropdown-menu">
              <router-link to="/resume/new" class="dropdown-item" @click="closeMobileMenu">
                Создать резюме
              </router-link>
              <router-link to="/resume/user/personal" class="dropdown-item" @click="closeMobileMenu">
                Мои резюме
              </router-link>
            </div>
          </div>

          <router-link to="/vacancy_response/user/personal" class="nav-link" @click="closeMobileMenu">
            <i class="icon">📨</i>
            Мои отклики
          </router-link>
        </div>

        <!-- Навигация для работодателей -->
        <div v-else-if="isEmployer" class="nav-links">
          <router-link to="/main" class="nav-link" @click="closeMobileMenu">
            <i class="icon">🏠</i>
            Главная
          </router-link>

          <div class="nav-dropdown">
            <button class="nav-link dropdown-trigger">
              <i class="icon">💼</i>
              Вакансии
              <i class="dropdown-arrow">▼</i>
            </button>
            <div class="dropdown-menu">
              <router-link to="/vacancy/new" class="dropdown-item" @click="closeMobileMenu">
                Создать вакансию
              </router-link>
              <router-link to="/vacancy/user/personal" class="dropdown-item" @click="closeMobileMenu">
                Мои вакансии
              </router-link>
              <router-link to="/vacancies" class="dropdown-item" @click="closeMobileMenu">
                Все вакансии
              </router-link>
            </div>
          </div>

          <div class="nav-dropdown">
            <button class="nav-link dropdown-trigger">
              <i class="icon">🏢</i>
              Компания
              <i class="dropdown-arrow">▼</i>
            </button>
            <div class="dropdown-menu">
              <router-link to="/company/new" class="dropdown-item" @click="closeMobileMenu">
                Создать компанию
              </router-link>
              <router-link to="/company/user/personal" class="dropdown-item" @click="closeMobileMenu">
                Мои компании
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Правая часть навигации -->
      <div class="nav-actions">
        <div v-if="!isAuthenticated" class="auth-buttons">
          <router-link to="/login" class="btn btn-outline">
            Войти
          </router-link>
          <router-link to="/register" class="btn btn-primary">
            Регистрация
          </router-link>
        </div>

        <div v-else class="user-menu">
          <div class="nav-dropdown user-dropdown">
            <button class="user-trigger">
              <div class="user-avatar">
                <span>{{ userInitials }}</span>
              </div>
              <span class="user-name">{{ userName }}</span>
              <i class="dropdown-arrow">▼</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="user-info">
                <div class="user-role">{{ userRoleText }}</div>
                <div class="user-email">{{ userEmail }}</div>
              </div>
              <div class="dropdown-divider"></div>
              <router-link to="/profile" class="dropdown-item" @click="closeMobileMenu">
                <i class="icon">👤</i>
                Профиль
              </router-link>
              <button @click="logout" class="dropdown-item logout-btn">
                <i class="icon">🚪</i>
                Выйти
              </button>
            </div>
          </div>
        </div>

        <!-- Мобильное меню -->
        <button
            class="mobile-menu-btn"
            @click="toggleMobileMenu"
            :class="{ 'mobile-menu-btn-open': isMobileMenuOpen }"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  data() {
    return {
      user: null,
      isMobileMenuOpen: false
    }
  },
  computed: {
    isAuthenticated() {
      return this.user !== null
    },
    isUser() {
      return this.user?.roles?.name?.toLowerCase() === 'user' ||
          this.user?.roles?.name === 'ROLE_USER'
    },
    isEmployer() {
      return this.user?.roles?.name?.toLowerCase() === 'employer' ||
          this.user?.roles?.name === 'ROLE_EMPLOYER'
    },
    userName() {
      return this.user?.name || this.user?.email || 'Пользователь'
    },
    userEmail() {
      return this.user?.email || ''
    },
    userInitials() {
      if (this.user?.name) {
        return this.user.name.split(' ').map(word => word[0]).join('').toUpperCase()
      }
      return this.user?.email?.[0]?.toUpperCase() || 'U'
    },
    userRoleText() {
      if (this.isEmployer) return 'Работодатель'
      if (this.isUser) return 'Соискатель'
      return 'Пользователь'
    }
  },
  mounted() {
    this.loadUserData()
    // Слушаем изменения в localStorage
    window.addEventListener('storage', this.handleStorageChange)
  },
  beforeUnmount() {
    window.removeEventListener('storage', this.handleStorageChange)
  },
  methods: {
    loadUserData() {
      const userData = localStorage.getItem('user')
      if (userData) {
        try {
          this.user = JSON.parse(userData)
        } catch (e) {
          console.error('Error parsing user data:', e)
          this.user = null
        }
      }
    },
    handleStorageChange() {
      this.loadUserData()
    },
    logout() {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      this.user = null
      this.closeMobileMenu()
      this.$router.push('/')
    },
    toggleMobileMenu() {
      this.isMobileMenuOpen = !this.isMobileMenuOpen
    },
    closeMobileMenu() {
      this.isMobileMenuOpen = false
    }
  }
}
</script>

<style scoped>
.navigation {
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 70px;
}

/* Логотип */
.nav-brand {
  flex-shrink: 0;
}

.brand-link {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  color: #1f2937;
  font-weight: 600;
  font-size: 1.2rem;
}

.logo {
  display: flex;
  align-items: center;
}

.brand-text {
  font-weight: 700;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Основная навигация */
.nav-menu {
  display: flex;
  align-items: center;
  flex: 1;
  justify-content: center;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  color: #6b7280;
  font-weight: 500;
  transition: all 0.2s ease;
  cursor: pointer;
  background: none;
  border: none;
  font-size: 14px;
}

.nav-link:hover {
  color: #4f46e5;
  background-color: #f3f4f6;
}

.nav-link.router-link-active {
  color: #4f46e5;
  background-color: #eef2ff;
}

.icon {
  font-size: 16px;
}

/* Выпадающие меню */
.nav-dropdown {
  position: relative;
}

.dropdown-trigger {
  display: flex;
  align-items: center;
  gap: 8px;
}

.dropdown-arrow {
  font-size: 10px;
  transition: transform 0.2s ease;
}

.nav-dropdown:hover .dropdown-arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 200px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.2s ease;
  z-index: 1001;
}

.dropdown-menu-right {
  right: 0;
  left: auto;
}

.nav-dropdown:hover .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  color: #374151;
  text-decoration: none;
  transition: background-color 0.2s ease;
  cursor: pointer;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  font-size: 14px;
}

.dropdown-item:hover {
  background-color: #f9fafb;
}

.dropdown-item:first-child {
  border-radius: 8px 8px 0 0;
}

.dropdown-item:last-child {
  border-radius: 0 0 8px 8px;
}

.dropdown-divider {
  height: 1px;
  background-color: #e5e7eb;
  margin: 8px 0;
}

/* Правая часть */
.nav-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.auth-buttons {
  display: flex;
  gap: 12px;
}

.btn {
  padding: 8px 16px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.2s ease;
  cursor: pointer;
  border: none;
}

.btn-outline {
  background: transparent;
  color: #6b7280;
  border: 1px solid #d1d5db;
}

.btn-outline:hover {
  background-color: #f9fafb;
  color: #374151;
}

.btn-primary {
  background-color: #4f46e5;
  color: white;
}

.btn-primary:hover {
  background-color: #4338ca;
}

/* Пользовательское меню */
.user-trigger {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 6px 12px;
  border-radius: 8px;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.user-trigger:hover {
  background-color: #f9fafb;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f46e5, #7c3aed);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 14px;
}

.user-name {
  font-weight: 500;
  color: #374151;
  max-width: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.user-info {
  padding: 12px 16px;
  border-bottom: 1px solid #e5e7eb;
}

.user-role {
  font-weight: 600;
  color: #4f46e5;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.user-email {
  color: #6b7280;
  font-size: 14px;
  margin-top: 4px;
}

.logout-btn {
  color: #dc2626 !important;
}

.logout-btn:hover {
  background-color: #fef2f2 !important;
}

/* Мобильное меню */
.mobile-menu-btn {
  display: none;
  flex-direction: column;
  gap: 4px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.mobile-menu-btn span {
  width: 24px;
  height: 2px;
  background-color: #374151;
  transition: all 0.3s ease;
}

.mobile-menu-btn-open span:nth-child(1) {
  transform: rotate(45deg) translate(6px, 6px);
}

.mobile-menu-btn-open span:nth-child(2) {
  opacity: 0;
}

.mobile-menu-btn-open span:nth-child(3) {
  transform: rotate(-45deg) translate(6px, -6px);
}

/* Адаптивность */
@media (max-width: 768px) {
  .mobile-menu-btn {
    display: flex;
  }

  .nav-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-20px);
    transition: all 0.3s ease;
  }

  .nav-menu-open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }

  .nav-links {
    flex-direction: column;
    padding: 20px;
    gap: 0;
  }

  .nav-link {
    width: 100%;
    justify-content: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .nav-dropdown .dropdown-menu {
    position: static;
    opacity: 1;
    visibility: visible;
    transform: none;
    box-shadow: none;
    background: #f9fafb;
    margin-left: 20px;
    margin-top: 8px;
    border-radius: 6px;
  }

  .user-name {
    display: none;
  }

  .auth-buttons {
    flex-direction: column;
    gap: 8px;
  }
}

@media (max-width: 480px) {
  .nav-container {
    padding: 0 16px;
  }

  .brand-text {
    display: none;
  }
}
</style>