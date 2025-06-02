<template>
  <div class="container mx-auto p-4">
    <div class="flex gap-6">
      <!-- Левая боковая панель с фильтрами -->
      <div class="w-1/4 bg-white p-6 rounded-lg shadow-lg h-fit sticky top-4">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Фильтры поиска</h2>

        <!-- Поиск по имени -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Поиск по имени или специализации
          </label>
          <input
              v-model="searchQuery"
              type="text"
              placeholder="Введите имя или специализацию..."
              class="text-black w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
              @input="debouncedSearch"
          >
        </div>

        <!-- Желаемая зарплата -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Желаемая зарплата
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
            Город проживания
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

        <!-- Возраст -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Возраст
          </label>
          <div class="flex gap-2">
            <input
                v-model="filters.ageFrom"
                type="number"
                placeholder="От"
                min="16"
                max="70"
                class="text-black w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
            <input
                v-model="filters.ageTo"
                type="number"
                placeholder="До"
                min="16"
                max="70"
                class="text-black w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
          </div>
        </div>

        <!-- Пол -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Пол
          </label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input
                  type="radio"
                  value=""
                  v-model="filters.gender"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">Любой</span>
            </label>
            <label class="flex items-center">
              <input
                  type="radio"
                  value="male"
                  v-model="filters.gender"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">Мужской</span>
            </label>
            <label class="flex items-center">
              <input
                  type="radio"
                  value="female"
                  v-model="filters.gender"
                  class="mr-2 text-blue-600"
                  @change="applyFilters"
              >
              <span class="text-sm text-gray-700">Женский</span>
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
          Найдено: {{ resumes.length }} резюме
        </div>
      </div>

      <!-- Основной контент -->
      <div class="flex-1">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-black">Все резюме</h1>

          <!-- Сортировка -->
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700">Сортировать по:</label>
            <select
                v-model="sortBy"
                class="text-black px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                @change="applyFilters"
            >
              <option value="created_at">Дате создания</option>
              <option value="first_name">Имени</option>
              <option value="desired_salary">Желаемой зарплате</option>
              <option value="age">Возрасту</option>
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
              Зарплата: {{ filters.salaryFrom || 0 }} - {{ filters.salaryTo || '∞' }}
              <button @click="filters.salaryFrom = ''; filters.salaryTo = ''; applyFilters()" class="ml-2 text-green-600 hover:text-green-800">&times;</button>
            </span>

            <span v-if="filters.cityId" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
              Город: {{ getCityName(filters.cityId) }}
              <button @click="filters.cityId = ''; applyFilters()" class="ml-2 text-purple-600 hover:text-purple-800">&times;</button>
            </span>

            <span v-if="filters.ageFrom || filters.ageTo" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
              Возраст: {{ filters.ageFrom || 0 }} - {{ filters.ageTo || '∞' }}
              <button @click="filters.ageFrom = ''; filters.ageTo = ''; applyFilters()" class="ml-2 text-yellow-600 hover:text-yellow-800">&times;</button>
            </span>

            <span v-if="filters.gender" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
              Пол: {{ filters.gender === 'male' ? 'Мужской' : 'Женский' }}
              <button @click="filters.gender = ''; applyFilters()" class="ml-2 text-pink-600 hover:text-pink-800">&times;</button>
            </span>
          </div>
        </div>

        <!-- Загрузка -->
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          <p class="mt-2 text-gray-600">Загрузка резюме...</p>
        </div>

        <!-- Список резюме -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <router-link
              v-for="resume in resumes"
              :key="resume.id"
              :to="`/resume/${resume.id}`"
              class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 block no-underline"
          >
            <!-- Аватар или инициалы -->
            <div class="flex justify-center mb-4">
              <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-semibold text-xl">
                  {{ getInitials(resume.first_name, resume.last_name) }}
                </span>
              </div>
            </div>

            <!-- Имя и фамилия -->
            <h2 class="text-xl font-semibold text-blue-600 mb-2 text-center line-clamp-2">
              {{ resume.first_name }} {{ resume.last_name }}
            </h2>

            <!-- Возраст и пол -->
            <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
              <span>{{ getAge(resume.birthday) }} лет</span>
              <span>{{ getGenderText(resume.gender) }}</span>
            </div>

            <!-- Желаемая зарплата -->
            <div class="text-green-600 font-medium mb-4 text-center">
              {{ resume.desired_salary || 0 }} {{ resume.salaryCurrency?.name || 'RUB' }}
            </div>

            <!-- Город проживания -->
            <div class="text-gray-700 mb-2">
              <span class="font-medium">Город:</span> {{ resume.residence_city?.name || 'Не указан' }}
            </div>

            <!-- Специализация -->
            <div class="text-gray-600 text-sm mb-2">
              <span class="font-medium">Специализация:</span>
              {{ resume.specialization?.name || 'Не указана' }}
            </div>

            <!-- Образование -->
            <div class="text-gray-600 text-sm mb-4">
              <span class="font-medium">Образование:</span>
              {{ resume.education?.name || 'Не указано' }}
            </div>

            <!-- Кнопка связаться -->
            <button
                class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition-colors duration-200"
                @click.stop.prevent="contactCandidate(resume.id)"
            >
              Связаться
            </button>
          </router-link>
        </div>

        <!-- Сообщение об отсутствии результатов -->
        <div v-if="!loading && !resumes.length" class="text-center py-12">
          <div class="text-gray-400 text-6xl mb-4">👤</div>
          <h3 class="text-xl font-medium text-gray-900 mb-2">Резюме не найдены</h3>
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
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api.js'

// Основные данные
const resumes = ref([])
const loading = ref(false)

// Данные для фильтров
const cities = ref([])
const educationOptions = ref([])
const specializations = ref([])

// Поиск и фильтры
const searchQuery = ref('')
const filters = ref({
  salaryFrom: '',
  salaryTo: '',
  cityId: '',
  education: '',
  selectedSpecializations: [],
  ageFrom: '',
  ageTo: '',
  gender: ''
})

// Сортировка
const sortBy = ref('created_at')
const sortOrder = ref('desc')

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
      filters.value.ageFrom ||
      filters.value.ageTo ||
      filters.value.gender
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

const getInitials = (firstName, lastName) => {
  const first = firstName ? firstName.charAt(0).toUpperCase() : ''
  const last = lastName ? lastName.charAt(0).toUpperCase() : ''
  return first + last || '??'
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

const getGenderText = (gender) => {
  switch (gender) {
    case 'male': return 'Мужской'
    case 'female': return 'Женский'
    default: return 'Не указан'
  }
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
    // Загружаем справочники для фильтров
    const [citiesRes, educationRes, specializationsRes] = await Promise.all([
      api.get('/city/list'),
      api.get('/many_vacancy_parameters/education'),
      api.get('/many_vacancy_parameters/specializations')
    ])

    cities.value = citiesRes.data
    educationOptions.value = educationRes.data
    specializations.value = specializationsRes.data

    console.log('Загружены справочники для резюме:', {
      cities: cities.value.length,
      education: educationOptions.value.length,
      specializations: specializations.value.length
    })
  } catch (error) {
    console.error('Ошибка при загрузке справочников:', error)
  }
}

const applyFilters = async () => {
  loading.value = true

  try {
    const params = {
      // Поиск по имени или специализации
      search: searchQuery.value || undefined,

      // Желаемая зарплата (диапазон)
      salary: {},

      // Город проживания
      city_id: filters.value.cityId || undefined,

      // Образование
      education_id: filters.value.education || undefined,

      // Специализации (массив)
      specializations: filters.value.selectedSpecializations.length > 0
          ? filters.value.selectedSpecializations.join(',')
          : undefined,

      // Возраст (диапазон)
      age: {},

      // Пол
      gender: filters.value.gender || undefined,

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

    // Формируем диапазон возрастов
    if (filters.value.ageFrom || filters.value.ageTo) {
      if (filters.value.ageFrom) params.age.from = filters.value.ageFrom
      if (filters.value.ageTo) params.age.to = filters.value.ageTo
    } else {
      delete params.age
    }

    // Убираем пустые параметры
    Object.keys(params).forEach(key => {
      if (params[key] === undefined || params[key] === '') {
        delete params[key]
      }
    })

    console.log('Параметры запроса резюме:', params)

    // Здесь используем эндпоинт для поиска резюме
    const response = await api.get('/resume/list', { params })

    // Правильно извлекаем данные из ответа API
    if (response.data && typeof response.data === 'object') {
      // Если API возвращает объект с полем data
      resumes.value = response.data.data || []
      console.log('Структура ответа API резюме:', response.data)
      console.log('Получено резюме:', resumes.value.length)
    } else {
      // Если API возвращает массив напрямую
      resumes.value = Array.isArray(response.data) ? response.data : []
      console.log('Прямой массив резюме:', resumes.value.length)
    }

    console.log('Итоговый массив резюме:', resumes.value)
  } catch (error) {
    console.error('Ошибка при поиске резюме:', error)
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
    ageFrom: '',
    ageTo: '',
    gender: ''
  }
  sortBy.value = 'created_at'
  sortOrder.value = 'desc'
  applyFilters()
}

const contactCandidate = (resumeId) => {
  // Здесь можно добавить логику для связи с кандидатом
  // Например, открыть модальное окно или перейти на страницу чата
  console.log('Связаться с кандидатом:', resumeId)
  alert('Функция связи с кандидатом будет реализована позже')
}

// Инициализация
onMounted(async () => {
  // Явно очищаем массив резюме при загрузке
  resumes.value = []
  console.log('Инициализация: очищен массив резюме')

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