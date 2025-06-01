<template>
  <div class="container mx-auto p-4">
    <div class="flex gap-6">
      <!-- Левая боковая панель с фильтрами -->
      <div class="w-1/4 bg-white p-6 rounded-lg shadow-lg h-fit sticky top-4">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Фильтры поиска</h2>

        <!-- Поиск по названию -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Поиск по названию или компании
          </label>
          <input
              v-model="searchQuery"
              type="text"
              placeholder="Введите название вакансии..."
              class="text-black w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
              @input="debouncedSearch"
          >
        </div>

        <!-- Зарплата -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Зарплата ($)
          </label>
          <div class="flex gap-2">
            <input
                v-model="filters.salaryFrom"
                type="number"
                placeholder="От"
                class="text-black w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
            <input
                v-model="filters.salaryTo"
                type="number"
                placeholder="До"
                class="text-black w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
          </div>
        </div>

        <!-- Город -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Город
          </label>
          <select
              v-model="filters.cityId"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
              @change="applyFilters"
          >
            <option value="">Все города</option>
            <option v-for="city in cities" :key="city.id" :value="city.id">
              {{ city.name }}
            </option>
          </select>
        </div>

        <!-- Образование -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Образование
          </label>
          <div class="space-y-2">
            <label v-for="education in educationOptions" :key="education.id" class="flex items-center">
              <input
                  type="radio"
                  :value="education.id"
                  v-model="filters.education"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">{{ education.name }}</span>
            </label>
          </div>
        </div>

        <!-- Специализации -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Специализации
          </label>
          <div class="space-y-2 max-h-40 overflow-y-auto">
            <label v-for="spec in specializations" :key="spec.id" class="flex items-center">
              <input
                  type="checkbox"
                  :value="spec.id"
                  v-model="filters.selectedSpecializations"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">{{ spec.name }}</span>
            </label>
          </div>
        </div>

        <!-- Тип занятости -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Тип занятости
          </label>
          <div class="space-y-2">
            <label v-for="employment in employmentTypes" :key="employment.id" class="flex items-center">
              <input
                  type="checkbox"
                  :value="employment.id"
                  v-model="filters.selectedEmploymentTypes"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">{{ employment.name }}</span>
            </label>
          </div>
        </div>

        <!-- Кнопки управления фильтрами -->
        <div class="flex flex-col gap-2">
          <button
              @click="clearFilters"
              class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm"
          >
            Сбросить фильтры
          </button>
          <button
              @click="applyFilters"
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm"
          >
            Применить фильтры
          </button>
        </div>

        <!-- Счетчик результатов -->
        <div class="mt-4 text-sm text-gray-600 text-center">
          Найдено: {{ vacancies.length }} вакансий
        </div>
      </div>

      <!-- Основной контент -->
      <div class="flex-1">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-black">Все вакансии</h1>

          <!-- Сортировка -->
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700">Сортировать по:</label>
            <select
                v-model="sortBy"
                class="text-black px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
              <option value="created_at">Дате создания</option>
              <option value="name">Названию</option>
              <option value="income_min">Зарплате (мин)</option>
              <option value="income_max">Зарплате (макс)</option>
            </select>
            <select
                v-model="sortOrder"
                class="text-black px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
              <option value="desc">По убыванию</option>
              <option value="asc">По возрастанию</option>
            </select>
          </div>
        </div>

        <!-- Активные фильтры (теги) -->
        <div v-if="hasActiveFilters" class="mb-6">
          <div class="flex flex-wrap gap-2">
            <span v-if="searchQuery" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              Поиск: "{{ searchQuery }}"
              <button @click="searchQuery = ''; applyFilters()" class="ml-2 text-blue-600 hover:text-blue-800">&times;</button>
            </span>

            <span v-if="filters.salaryFrom || filters.salaryTo" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
              Зарплата: ${{ filters.salaryFrom || 0 }} - ${{ filters.salaryTo || '∞' }}
              <button @click="filters.salaryFrom = ''; filters.salaryTo = ''; applyFilters()" class="ml-2 text-green-600 hover:text-green-800">&times;</button>
            </span>

            <span v-if="filters.cityId" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
              Город: {{ getCityName(filters.cityId) }}
              <button @click="filters.cityId = ''; applyFilters()" class="ml-2 text-purple-600 hover:text-purple-800">&times;</button>
            </span>
          </div>
        </div>

        <!-- Загрузка -->
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          <p class="mt-2 text-gray-600">Загрузка вакансий...</p>
        </div>

        <!-- Список вакансий -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <router-link
              v-for="vacancy in vacancies"
              :key="vacancy.id"
              :to="`/vacancy/${vacancy.id}`"
              class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 block no-underline"
          >
            <div class="flex justify-center mb-4">
              <img
                  :src="vacancy.logoUrl || '/default-logo.png'"
                  alt="Company Logo"
                  class="h-16 w-20 object-contain"
                  @error="setDefaultLogo"
              />
            </div>

            <!-- Заголовок вакансии -->
            <h2 class="text-xl font-semibold text-blue-600 mb-2 line-clamp-2">{{ vacancy.name }}</h2>

            <!-- Компания и дата -->
            <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
              <span class="truncate">{{ vacancy.company?.name || 'Компания не указана' }}</span>
              <span class="whitespace-nowrap ml-2">{{ formatDate(vacancy.created_at) }}</span>
            </div>

            <!-- Зарплата -->
            <div class="text-green-600 font-medium mb-4">
              ${{ vacancy.income_min || 0 }} - ${{ vacancy.income_max || 0 }}
            </div>

            <!-- Город -->
            <div class="text-gray-700 mb-2">
              <span class="font-medium">Город:</span> {{ vacancy.city?.name || 'Не указан' }}
            </div>

            <!-- Специализации -->
            <div class="text-gray-600 text-sm mb-2">
              <span class="font-medium">Специализации:</span>
              {{ vacancy.specializations?.map(s => s.name).join(', ') || 'Не указаны' }}
            </div>

            <!-- Тип трудоустройства -->
            <div class="text-gray-600 text-sm mb-4">
              <span class="font-medium">Тип занятости:</span>
              {{ vacancy.employment_type?.map(t => t.name).join(', ') || 'Не указаны' }}
            </div>

            <!-- Кнопка Apply -->
            <button
                class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition-colors duration-200"
                @click.stop.prevent="openModal(vacancy.id)"
            >
              Откликнуться
            </button>
          </router-link>
        </div>

        <!-- Сообщение об отсутствии результатов -->
        <div v-if="!loading && !vacancies.length" class="text-center py-12">
          <div class="text-gray-400 text-6xl mb-4">🔍</div>
          <h3 class="text-xl font-medium text-gray-900 mb-2">Вакансии не найдены</h3>
          <p class="text-gray-600 mb-4">Попробуйте изменить параметры поиска</p>
          <button
              @click="clearFilters"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
          >
            Сбросить все фильтры
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Модальное окно выбора резюме (без изменений) -->
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 backdrop-blur-sm bg-black/30" @click.self="closeModal"></div>
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md z-10">
      <h3 class="text-lg font-semibold mb-4 text-black">Выберите резюме</h3>

      <div v-if="loadingResumes" class="text-gray-500 mb-4">Загрузка...</div>
      <div v-else-if="resumes.length === 0" class="text-gray-500 mb-4">Нет активных резюме</div>

      <div v-else class="space-y-3 max-h-80 overflow-y-auto mb-4">
        <div
            v-for="resume in resumes"
            :key="resume.id"
            @click="selectResume(resume.id)"
            :class="[
            'p-4 border rounded-lg cursor-pointer transition-all',
            selectedResumeId === resume.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'
          ]"
        >
          <div class="font-medium text-black">{{ resume.first_name }} {{ resume.last_name }}</div>
          <div class="text-sm text-gray-500" v-if="resume.specialization">
            {{ resume.specialization.name }}
          </div>
          <div v-else class="text-sm text-gray-400">Без специализации</div>
        </div>
      </div>

      <div v-if="modalError" class="text-red-500 mb-2 text-sm">{{ modalError }}</div>

      <div class="flex justify-end space-x-2 mt-4">
        <button
            @click="closeModal"
            class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 text-sm transition"
        >
          Отмена
        </button>
        <button
            @click="sendApplication"
            :disabled="!selectedResumeId"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm disabled:opacity-50 transition"
        >
          Отправить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api.js'

const setDefaultLogo = (event) => {
  event.target.src = '/default-logo.png'
}

// Основные данные
const vacancies = ref([])
const loading = ref(false)

// Данные для фильтров
const cities = ref([])
const educationOptions = ref([])
const specializations = ref([])
const employmentTypes = ref([])

// Поиск и фильтры
const searchQuery = ref('')
const filters = ref({
  salaryFrom: '',
  salaryTo: '',
  cityId: '',
  education: '',
  selectedSpecializations: [],
  selectedEmploymentTypes: []
})

// Сортировка
const sortBy = ref('created_at')
const sortOrder = ref('desc')

// Модальное окно
const showModal = ref(false)
const loadingResumes = ref(true)
const resumes = ref([])
const selectedResumeId = ref(null)
const modalError = ref(null)
const selectedVacancyId = ref(null)

// Роутер
const route = useRoute()
const router = useRouter()

// Computed свойства
const hasActiveFilters = computed(() => {
  return searchQuery.value ||
      filters.value.salaryFrom ||
      filters.value.salaryTo ||
      filters.value.cityId ||
      filters.value.education ||
      filters.value.selectedSpecializations.length > 0 ||
      filters.value.selectedEmploymentTypes.length > 0
})

// Утилиты
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  return isNaN(date.getTime()) ? '-' : date.toLocaleDateString('ru-RU')
}

const getCityName = (cityId) => {
  const city = cities.value.find(c => c.id === parseInt(cityId))
  return city ? city.name : ''
}

// Debounced поиск
let searchTimeout = null
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

// Основные методы
const loadInitialData = async () => {
  try {
    // Загружаем справочники для фильтров используя ваши реальные эндпоинты
    const [citiesRes, educationRes, specializationsRes, employmentRes] = await Promise.all([
      api.get('/city/list'),
      api.get('/many_vacancy_parameters/education'),
      api.get('/many_vacancy_parameters/specializations'),
      api.get('/many_vacancy_parameters/employmentType')
    ])

    cities.value = citiesRes.data
    educationOptions.value = educationRes.data
    specializations.value = specializationsRes.data
    employmentTypes.value = employmentRes.data

    console.log('Загружены справочники:', {
      cities: cities.value.length,
      education: educationOptions.value.length,
      specializations: specializations.value.length,
      employmentTypes: employmentTypes.value.length
    })
  } catch (error) {
    console.error('Ошибка при загрузке справочников:', error)
  }
}

const applyFilters = async () => {
  loading.value = true

  try {
    const params = {
      // Поиск по названию
      search: searchQuery.value || undefined,

      // Зарплата (диапазон)
      salary: {},

      // Город
      city_id: filters.value.cityId || undefined,

      // Образование
      education_id: filters.value.education || undefined,

      // Специализации (массив)
      specializations: filters.value.selectedSpecializations.length > 0
          ? filters.value.selectedSpecializations.join(',')
          : undefined,

      // Типы занятости (массив)
      employmentType: filters.value.selectedEmploymentTypes.length > 0
          ? filters.value.selectedEmploymentTypes.join(',')
          : undefined,

      // Сортировка
      sort_by: sortBy.value,
      sort_order: sortOrder.value
    }

    // Формируем диапазон зарплат
    if (filters.value.salaryFrom || filters.value.salaryTo) {
      if (filters.value.salaryFrom) params.salary.from = filters.value.salaryFrom
      if (filters.value.salaryTo) params.salary.to = filters.value.salaryTo
    } else {
      delete params.salary
    }

    // Убираем пустые параметры
    Object.keys(params).forEach(key => {
      if (params[key] === undefined || params[key] === '') {
        delete params[key]
      }
    })

    console.log('Параметры запроса:', params)

    const response = await api.get('/many_vacancies', { params })

    // Правильно извлекаем данные из ответа API
    if (response.data && typeof response.data === 'object') {
      // Если API возвращает объект с полем data
      vacancies.value = response.data.data || []
      console.log('Структура ответа API:', response.data)
      console.log('Получено вакансий:', vacancies.value.length)
    } else {
      // Если API возвращает массив напрямую
      vacancies.value = Array.isArray(response.data) ? response.data : []
      console.log('Прямой массив вакансий:', vacancies.value.length)
    }

    console.log('Итоговый массив вакансий:', vacancies.value)
  } catch (error) {
    console.error('Ошибка при поиске вакансий:', error)
    console.error('Детали ошибки:', error.response?.data)
  } finally {
    loading.value = false
  }
}

const clearFilters = () => {
  searchQuery.value = ''
  filters.value = {
    salaryFrom: '',
    salaryTo: '',
    cityId: '',
    education: '',
    selectedSpecializations: [],
    selectedEmploymentTypes: []
  }
  sortBy.value = 'created_at'
  sortOrder.value = 'desc'
  applyFilters()
}

// Модальное окно (без изменений)
const openModal = async (vacancyId) => {
  selectedVacancyId.value = vacancyId
  selectedResumeId.value = null
  modalError.value = null
  showModal.value = true
  loadingResumes.value = true

  try {
    const response = await api.get('resume/user/personal')
    resumes.value = response.data
  } catch (error) {
    modalError.value = 'Не удалось загрузить резюме'
  } finally {
    loadingResumes.value = false
  }
}

const selectResume = (id) => {
  selectedResumeId.value = id
}

const closeModal = () => {
  showModal.value = false
  selectedVacancyId.value = null
  selectedResumeId.value = null
  modalError.value = null
  resumes.value = []
}

const sendApplication = async () => {
  if (!selectedResumeId.value || !selectedVacancyId.value) return

  try {
    const payload = {
      resume: selectedResumeId.value,
      vacancy: selectedVacancyId.value
    }

    await api.post(`/vacancy_response/new`, payload, {
      headers: { 'Content-Type': 'application/json' }
    })

    alert('Резюме успешно отправлено!')
    closeModal()
  } catch (e) {
    modalError.value = 'Ошибка при отправке резюме'
    console.error(e.response?.data || e)
  }
}

// Инициализация
onMounted(async () => {
  // Явно очищаем массив вакансий при загрузке
  vacancies.value = []
  console.log('Инициализация: очищен массив вакансий')

  await loadInitialData()
  await applyFilters()
})
</script>

<style scoped>
.backdrop-blur-sm {
  -webkit-backdrop-filter: blur(4px);
  backdrop-filter: blur(4px);
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.container {
  max-width: 1400px;
}

/* Стили для чекбоксов и радиокнопок */
input[type="checkbox"], input[type="radio"] {
  width: 16px;
  height: 16px;
  accent-color: #2563eb;
}

/* Анимация для карточек */
.grid > * {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Скролл для списков фильтров */
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>