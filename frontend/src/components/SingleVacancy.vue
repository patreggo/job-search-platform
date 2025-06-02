<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
      <!-- Загрузка -->
      <div v-if="!vacancy" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Основной контент -->
      <div v-else class="space-y-8">
        <!-- Заголовок -->
        <div class="text-center">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ vacancy.name }}
          </h1>
          <p class="text-gray-600">{{ formatArray(vacancy.specializations) }}</p>
        </div>

        <!-- Основная информация о вакансии -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
              </svg>
              Информация о вакансии
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Заработная плата</label>
                <p class="text-gray-900 font-medium text-green-600 text-lg">
                  {{ formatSalary(vacancy.income_min, vacancy.income_max) }}
                </p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Тип занятости</label>
                <p class="text-gray-900 font-medium">{{ formatArray(vacancy.employment_type) }}</p>
              </div>
            </div>

            <div class="mt-4 space-y-3">
              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Адрес работы</label>
                <p class="text-gray-900 font-medium flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ vacancy.work_address || 'Не указан' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Описание вакансии -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Описание вакансии
            </h2>
          </div>

          <div class="p-6">
            <div class="prose max-w-none">
              <p class="text-gray-700 leading-relaxed text-base">
                {{ vacancy.description || 'Описание не указано' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Требования -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Требования к кандидату
            </h2>
          </div>

          <div class="p-6">
            <div v-if="vacancy.requirements" class="prose max-w-none">
              <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">
                <p class="text-gray-700 leading-relaxed">{{ vacancy.requirements }}</p>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-gray-500">Требования не указаны</p>
            </div>
          </div>
        </div>

        <!-- Обязанности -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
              </svg>
              Обязанности
            </h2>
          </div>

          <div class="p-6">
            <div v-if="vacancy.responsibilities" class="prose max-w-none">
              <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-400">
                <p class="text-gray-700 leading-relaxed">{{ vacancy.responsibilities }}</p>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <p class="text-gray-500">Обязанности не указаны</p>
            </div>
          </div>
        </div>

        <!-- Дополнительная информация -->
        <div v-if="vacancy.specializations?.length" class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              Специализации
            </h2>
          </div>

          <div class="p-6">
            <div class="flex flex-wrap gap-2">
              <span v-for="(spec, index) in vacancy.specializations"
                    :key="index"
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                {{ spec.name }}
              </span>
            </div>
          </div>
        </div>

        <!-- Кнопки действий -->
        <div class="flex justify-center space-x-4">
          <button @click="applyForJob"
                  class="flex items-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Откликнуться
          </button>

          <button @click="goBack"
                  class="flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Назад
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api.js'

const route = useRoute()
const router = useRouter()
const vacancy = ref(null)

onMounted(async () => {
  try {
    const { data } = await api.get(`/single_vacancy/${route.params.id}`)
    vacancy.value = data
  } catch (error) {
    console.error('Ошибка загрузки вакансии:', error)
  }
})

// Форматирование зарплаты
function formatSalary(min, max) {
  if (!min && !max) return 'Не указана'
  if (min && max) return `от ${min.toLocaleString()} до ${max.toLocaleString()} ₽`
  if (min) return `от ${min.toLocaleString()} ₽`
  if (max) return `до ${max.toLocaleString()} ₽`
  return 'Не указана'
}

// Форматирование массивов
function formatArray(arr) {
  if (!arr || !Array.isArray(arr) || arr.length === 0) return 'Не указано'
  return arr.map(item => item.name).join(', ')
}

// Возврат назад
function goBack() {
  router.go(-1)
}

// Откликнуться на вакансию
function applyForJob() {
  // Здесь будет логика отклика на вакансию
  console.log('Отклик на вакансию:', vacancy.value.id)
  // Например, переход на страницу отклика или открытие модального окна
}
</script>