<template>
  <div class="role-based-home">
    <!-- Контент для пользователей (соискателей) -->
    <div v-if="isUser" class="user-dashboard">
      <div class="dashboard-header">
        <h1>Добро пожаловать, {{ userName }}!</h1>
        <p class="subtitle">Найдите идеальную работу для себя</p>
      </div>

      <!-- Список вакансий для пользователей -->
      <VacanciesList />
    </div>

    <!-- Контент для работодателей -->
    <div v-else-if="isEmployer" class="employer-dashboard">
      <div class="dashboard-header">
        <h1>Панель работодателя</h1>
        <p class="subtitle">Найдите лучших кандидатов для вашей компании</p>
      </div>

      <!-- Список резюме для работодателей -->
      <div class="resumes-section">
        <h2>Доступные резюме</h2>
        <ResumeList />
      </div>
    </div>

    <!-- Загрузка или ошибка -->
    <div v-else class="loading-state">
      <p>Загрузка...</p>
    </div>
  </div>
</template>

<script>
import VacanciesList from './VacanciesList.vue'
import ResumeList from './ResumeList.vue'

export default {
  name: 'RoleBasedHome',
  components: {
    VacanciesList,
    ResumeList
  },
  data() {
    return {
      user: null
    }
  },
  computed: {
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
    }
  },
  mounted() {
    this.loadUserData()
  },
  methods: {
    loadUserData() {
      const userData = localStorage.getItem('user')
      if (userData) {
        this.user = JSON.parse(userData)
      }
    }
  }
}
</script>

<style scoped>
.role-based-home {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.dashboard-header {
  text-align: center;
  margin-bottom: 30px;
  padding: 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 12px;
}

.dashboard-header h1 {
  margin: 0 0 10px 0;
  font-size: 2.5rem;
  font-weight: 600;
}

.subtitle {
  margin: 0;
  font-size: 1.1rem;
  opacity: 0.9;
}

.quick-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.action-btn {
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  display: inline-block;
  text-align: center;
}

.action-btn.primary {
  background-color: #4f46e5;
  color: white;
}

.action-btn.primary:hover {
  background-color: #4338ca;
  transform: translateY(-2px);
}

.action-btn.secondary {
  background-color: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
}

.action-btn.secondary:hover {
  background-color: #e5e7eb;
  transform: translateY(-2px);
}

.user-dashboard,
.employer-dashboard {
  animation: fadeIn 0.5s ease-in;
}

.resumes-section {
  margin-top: 30px;
}

.resumes-section h2 {
  font-size: 1.8rem;
  margin-bottom: 20px;
  color: #1f2937;
  text-align: center;
}

.loading-state {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .dashboard-header h1 {
    font-size: 2rem;
  }

  .quick-actions {
    flex-direction: column;
    align-items: center;
  }

  .action-btn {
    width: 200px;
  }
}
</style>