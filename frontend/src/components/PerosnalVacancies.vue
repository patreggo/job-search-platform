<template>
  <div class="container mx-auto p-4">
    <div class="mb-6">
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Мои вакансии</h1>
        <router-link
            to="/vacancy/new"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
          </svg>
          Создать вакансию
        </router-link>
      </div>
      <p class="text-gray-600 mt-2">Управляйте своими вакансиями и отслеживайте отклики</p>
    </div>

    <!-- Фильтры -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
      <div class="flex flex-wrap gap-4 items-center">
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Поиск по названию, городу или описанию..."
            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
        >
        <select
            v-model="statusFilter"
            class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
        >
          <option value="all">Все</option>
          <option value="active">Активные</option>
          <option value="hidden">Скрытые</option>
        </select>
      </div>
    </div>

    <!-- Загрузка -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-2 text-gray-600">Загрузка вакансий...</p>
    </div>

    <!-- Список вакансий -->
    <div v-else-if="filteredVacancies.length" class="space-y-6">
      <div
          v-for="vacancy in filteredVacancies"
          :key="vacancy.id"
          class="bg-white p-6 rounded-lg shadow-md border hover:shadow-lg transition"
          :class="{ 'opacity-60': vacancy.is_hidden }"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ vacancy.title }}</h2>
            <p class="text-gray-600">{{ vacancy.city?.name || 'Город не указан' }}</p>
            <p class="text-gray-500 text-sm mt-1">{{ vacancy.company?.name || 'Компания не указана' }}</p>
          </div>
          <span
              :class="[
              'px-3 py-1 text-xs font-medium rounded-full',
              vacancy.is_hidden ? 'bg-gray-200 text-gray-800' : 'bg-green-100 text-green-800'
            ]"
          >
            {{ vacancy.is_hidden ? 'Скрыта' : 'Активна' }}
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <span class="text-sm text-gray-500">Зарплата:</span>
            <p class="text-green-600 font-medium">
              {{ vacancy.income_min || 'Не указана' }} {{ vacancy.salary_currency?.name || 'RUB' }}
            </p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Тип занятости:</span>
            <p class="text-gray-900">{{ vacancy.employment_type?.name || 'Не указано' }}</p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Обновлено:</span>
            <p class="text-gray-900">{{ formatDate(vacancy.updated_at) }}</p>
          </div>
        </div>

        <div v-if="vacancy.description" class="text-gray-700 line-clamp-2 mb-4">
          {{ vacancy.description }}
        </div>

        <div class="flex gap-2 border-t pt-4">
          <router-link
              :to="`/vacancy/${vacancy.id}`"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm"
          >
            Просмотреть
          </router-link>
          <router-link
              :to="`/vacancy/edit/${vacancy.id}`"
              class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm"
          >
            Редактировать
          </router-link>
          <button
              @click="toggleVisibility(vacancy)"
              :class="[
              'px-4 py-2 rounded-md text-sm',
              vacancy.is_hidden ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-yellow-600 text-white hover:bg-yellow-700'
            ]"
          >
            {{ vacancy.is_hidden ? 'Показать' : 'Скрыть' }}
          </button>
          <button
              @click="confirmDelete(vacancy)"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm"
          >
            Удалить
          </button>
        </div>
      </div>
    </div>

    <!-- Пусто -->
    <div v-else class="text-center py-12">
      <div class="text-6xl text-gray-300 mb-4">📄</div>
      <h3 class="text-xl text-gray-900 font-medium mb-2">
        У вас пока нет вакансий
      </h3>
      <router-link
          to="/vacancy/new"
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
      >
        Создать первую вакансию
      </router-link>
    </div>
  </div>

  <!-- Модалка удаления -->
  <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black/30" @click="closeDeleteModal"></div>
    <div class="bg-white p-6 rounded-lg shadow-xl z-10 max-w-md w-full">
      <h3 class="text-lg font-semibold mb-4">Удаление вакансии</h3>
      <p class="text-gray-600 mb-6">
        Вы уверены, что хотите удалить вакансию "{{ vacancyToDelete?.name }}"?
      </p>
      <div class="flex justify-end gap-2">
        <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-300 rounded-md">Отмена</button>
        <button @click="deleteVacancy" class="px-4 py-2 bg-red-600 text-white rounded-md">Удалить</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api'

const vacancies = ref([])
const loading = ref(true)
const searchQuery = ref('')
const statusFilter = ref('all')
const showDeleteModal = ref(false)
const vacancyToDelete = ref(null)

const loadVacancies = async () => {
  try {
    loading.value = true
    const { data } = await api.get('/user/personal')
    vacancies.value = data
  } catch (err) {
    console.error('Ошибка загрузки вакансий', err)
  } finally {
    loading.value = false
  }
}

const formatDate = date => new Date(date).toLocaleDateString('ru-RU')

const filteredVacancies = computed(() => {
  let filtered = [...vacancies.value]

  if (statusFilter.value === 'active') {
    filtered = filtered.filter(v => !v.is_hidden)
  } else if (statusFilter.value === 'hidden') {
    filtered = filtered.filter(v => v.is_hidden)
  }

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    filtered = filtered.filter(v =>
        v.title?.toLowerCase().includes(q) ||
        v.description?.toLowerCase().includes(q) ||
        v.city?.name?.toLowerCase().includes(q)
    )
  }

  return filtered
})

const toggleVisibility = async (vacancy) => {
  try {
    await api.patch(`/vacancy/${vacancy.id}`, {
      is_hidden: !vacancy.is_hidden
    })
    vacancy.is_hidden = !vacancy.is_hidden
  } catch (e) {
    console.error('Ошибка смены статуса', e)
  }
}

const confirmDelete = (vacancy) => {
  vacancyToDelete.value = vacancy
  showDeleteModal.value = true
}

const deleteVacancy = async () => {
  try {
    await api.delete(`/vacancy/${vacancyToDelete.value.id}`)
    vacancies.value = vacancies.value.filter(v => v.id !== vacancyToDelete.value.id)
    closeDeleteModal()
  } catch (e) {
    console.error('Ошибка удаления', e)
  }
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  vacancyToDelete.value = null
}

onMounted(loadVacancies)
</script>
