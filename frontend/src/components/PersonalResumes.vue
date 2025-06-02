<template>
  <div class="container mx-auto p-4">
    <div class="mb-6">
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Мои резюме</h1>
        <router-link
            to="/resume/new"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Создать новое резюме
        </router-link>
      </div>
      <p class="text-gray-600 mt-2">Управляйте своими резюме и отслеживайте их эффективность</p>
    </div>

    <!-- Фильтры и сортировка -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
      <div class="flex flex-wrap gap-4 items-center">
        <div class="flex-1 min-w-64">
          <input
              v-model="searchQuery"
              type="text"
              placeholder="Поиск по названию или специализации..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
        </div>
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-700">Показать:</label>
          <select
              v-model="statusFilter"
              class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="all">Все резюме</option>
            <option value="active">Активные</option>
            <option value="hidden">Скрытые</option>
          </select>
        </div>
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-700">Сортировать:</label>
          <select
              v-model="sortBy"
              class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="created_at">По дате создания</option>
            <option value="updated_at">По дате обновления</option>
            <option value="first_name">По имени</option>
            <option value="desired_salary">По зарплате</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Загрузка -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-gray-600">Загрузка резюме...</p>
    </div>

    <!-- Список резюме -->
    <div v-else-if="filteredResumes.length > 0" class="space-y-6">
      <div
          v-for="resume in filteredResumes"
          :key="resume.id"
          class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border"
          :class="{ 'opacity-60': resume.is_hidden }"
      >
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center space-x-4">
              <!-- Аватар -->
              <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-semibold text-xl">
                  {{ getInitials(resume.first_name, resume.last_name) }}
                </span>
              </div>

              <!-- Основная информация -->
              <div>
                <h3 class="text-xl font-semibold text-gray-900">
                  {{ resume.first_name }} {{ resume.last_name }}
                </h3>
                <p class="text-gray-600">{{ resume.specialization?.name || 'Специализация не указана' }}</p>
                <div class="flex items-center mt-1 space-x-4 text-sm text-gray-500">
                  <span>{{ getAge(resume.birthday) }} лет</span>
                  <span>{{ resume.residence_city?.name || 'Город не указан' }}</span>
                </div>
              </div>
            </div>

            <!-- Статус -->
            <div class="flex items-center space-x-2">
              <span
                  :class="[
                  'px-3 py-1 rounded-full text-xs font-medium',
                  resume.is_hidden
                    ? 'bg-gray-100 text-gray-800'
                    : 'bg-green-100 text-green-800'
                ]"
              >
                {{ resume.is_hidden ? 'Скрыто' : 'Активно' }}
              </span>
            </div>
          </div>

          <!-- Дополнительная информация -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
              <span class="text-sm font-medium text-gray-500">Желаемая зарплата:</span>
              <p class="text-green-600 font-medium">
                {{ resume.desired_salary || 0 }} {{ resume.salaryCurrency?.name || 'RUB' }}
              </p>
            </div>
            <div>
              <span class="text-sm font-medium text-gray-500">Образование:</span>
              <p class="text-gray-900">{{ resume.education?.name || 'Не указано' }}</p>
            </div>
            <div>
              <span class="text-sm font-medium text-gray-500">Обновлено:</span>
              <p class="text-gray-900">{{ formatDate(resume.updated_at) }}</p>
            </div>
          </div>

          <!-- Краткое описание -->
          <div v-if="resume.about" class="mb-4">
            <span class="text-sm font-medium text-gray-500">О себе:</span>
            <p class="text-gray-700 mt-1 line-clamp-2">{{ resume.about }}</p>
          </div>

          <!-- Кнопки действий -->
          <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            <router-link
                :to="`/resume/${resume.id}`"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm"
            >
              Просмотреть
            </router-link>
            <router-link
                :to="`/resume/edit/${resume.id}`"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm"
            >
              Редактировать
            </router-link>
            <button
                @click="toggleVisibility(resume)"
                :class="[
                'px-4 py-2 rounded-md transition-colors text-sm',
                resume.is_hidden
                  ? 'bg-green-600 text-white hover:bg-green-700'
                  : 'bg-yellow-600 text-white hover:bg-yellow-700'
              ]"
            >
              {{ resume.is_hidden ? 'Показать' : 'Скрыть' }}
            </button>
            <button
                @click="confirmDelete(resume)"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm"
            >
              Удалить
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Пустое состояние -->
    <div v-else class="text-center py-12">
      <div class="text-gray-400 text-6xl mb-4">📄</div>
      <h3 class="text-xl font-medium text-gray-900 mb-2">
        {{ resumes.length === 0 ? 'У вас пока нет резюме' : 'Резюме не найдены' }}
      </h3>
      <p class="text-gray-600 mb-4">
        {{ resumes.length === 0 ? 'Создайте свое первое резюме' : 'Попробуйте изменить параметры поиска' }}
      </p>
      <router-link
          v-if="resumes.length === 0"
          to="/resume/new"
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
      >
        Создать резюме
      </router-link>
      <button
          v-else
          @click="clearFilters"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
      >
        Сбросить фильтры
      </button>
    </div>
  </div>

  <!-- Модальное окно подтверждения удаления -->
  <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 backdrop-blur-sm bg-black/30" @click="closeDeleteModal"></div>
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md z-10">
      <h3 class="text-lg font-semibold mb-4 text-gray-900">Подтвердите удаление</h3>
      <p class="text-gray-600 mb-6">
        Вы уверены, что хотите удалить резюме "{{ resumeToDelete?.first_name }} {{ resumeToDelete?.last_name }}"?
        Это действие нельзя отменить.
      </p>
      <div class="flex justify-end space-x-2">
        <button
            @click="closeDeleteModal"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition"
        >
          Отмена
        </button>
        <button
            @click="deleteResume"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
        >
          Удалить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api.js'
import { useRouter } from 'vue-router'

const router = useRouter()

// Реактивные данные
const resumes = ref([])
const loading = ref(true)
const searchQuery = ref('')
const statusFilter = ref('all')
const sortBy = ref('created_at')
const showDeleteModal = ref(false)
const resumeToDelete = ref(null)

// Загрузка резюме
const loadResumes = async () => {
  try {
    loading.value = true
    const { data } = await api.get('/resume/user/personal')
    resumes.value = data
  } catch (error) {
    console.error('Ошибка загрузки резюме:', error)
    // Здесь можно добавить уведомление об ошибке
  } finally {
    loading.value = false
  }
}

// Фильтрация и сортировка резюме
const filteredResumes = computed(() => {
  let filtered = resumes.value

  // Фильтр по статусу
  if (statusFilter.value === 'active') {
    filtered = filtered.filter(resume => !resume.is_hidden)
  } else if (statusFilter.value === 'hidden') {
    filtered = filtered.filter(resume => resume.is_hidden)
  }

  // Поиск
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(resume =>
        resume.first_name?.toLowerCase().includes(query) ||
        resume.last_name?.toLowerCase().includes(query) ||
        resume.specialization?.name?.toLowerCase().includes(query)
    )
  }

  // Сортировка
  filtered.sort((a, b) => {
    const aValue = a[sortBy.value]
    const bValue = b[sortBy.value]

    if (sortBy.value === 'created_at' || sortBy.value === 'updated_at') {
      return new Date(bValue) - new Date(aValue) // Новые сначала
    } else if (sortBy.value === 'desired_salary') {
      return (bValue || 0) - (aValue || 0) // Большие зарплаты сначала
    } else {
      return (aValue || '').localeCompare(bValue || '')
    }
  })

  return filtered
})

// Переключение видимости резюме
const toggleVisibility = async (resume) => {
  try {
    await api.patch(`/resume/${resume.id}/visibility`, {
      is_hidden: !resume.is_hidden
    })
    resume.is_hidden = !resume.is_hidden
  } catch (error) {
    console.error('Ошибка изменения видимости:', error)
  }
}

// Подтверждение удаления
const confirmDelete = (resume) => {
  resumeToDelete.value = resume
  showDeleteModal.value = true
}

// Закрытие модального окна
const closeDeleteModal = () => {
  showDeleteModal.value = false
  resumeToDelete.value = null
}

// Удаление резюме
const deleteResume = async () => {
  try {
    await api.delete(`/resume/${resumeToDelete.value.id}`)
    resumes.value = resumes.value.filter(r => r.id !== resumeToDelete.value.id)
    closeDeleteModal()
  } catch (error) {
    console.error('Ошибка удаления резюме:', error)
  }
}

// Очистка фильтров
const clearFilters = () => {
  searchQuery.value = ''
  statusFilter.value = 'all'
  sortBy.value = 'created_at'
}

// Вспомогательные функции
const getInitials = (firstName, lastName) => {
  return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`.toUpperCase()
}

const getAge = (birthday) => {
  if (!birthday) return 'Не указан'
  const today = new Date()
  const birthDate = new Date(birthday)
  let age = today.getFullYear() - birthDate.getFullYear()
  const monthDiff = today.getMonth() - birthDate.getMonth()
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }
  return age
}

const formatDate = (dateString) => {
  if (!dateString) return 'Не указано'
  return new Date(dateString).toLocaleDateString('ru-RU')
}

// Монтирование
onMounted(() => {
  loadResumes()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>