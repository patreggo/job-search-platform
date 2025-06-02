<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
      <!-- Загрузка -->
      <div v-if="!resume" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Основной контент -->
      <div v-else class="space-y-8">
        <!-- Заголовок -->
        <div class="text-center">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ resume.first_name }} {{ resume.last_name }}
          </h1>
          <p class="text-gray-600">{{ resume.specialization?.name }}</p>
        </div>

        <!-- Основная информация -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Основная информация
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Дата рождения</label>
                <p class="text-gray-900 font-medium">{{ formatDate(resume.birth_date) }}</p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Желаемая зарплата</label>
                <p class="text-gray-900 font-medium text-green-600">{{ resume.desired_salary }} ₽</p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Пол</label>
                <p class="text-gray-900 font-medium">{{ resume.gender?.name || 'Не указан' }}</p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Город проживания</label>
                <p class="text-gray-900 font-medium">{{ resume.residence_city?.name || 'Не указан' }}</p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Гражданство</label>
                <p class="text-gray-900 font-medium">{{ formatArray(resume.citizenship) }}</p>
              </div>

              <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">График работы</label>
                <p class="text-gray-900 font-medium">{{ resume.work_schedule?.name || 'Не указан' }}</p>
              </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200">
              <div class="flex flex-wrap gap-3">
                <div class="flex items-center">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                        :class="resume.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ resume.is_active ? 'Активно' : 'Неактивно' }}
                  </span>
                </div>

                <div v-if="resume.havePersonalCar" class="flex items-center">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    Есть автомобиль
                  </span>
                </div>

                <div v-if="resume.employment_type?.length" class="flex items-center">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    {{ formatArray(resume.employment_type) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Опыт работы -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
              </svg>
              Опыт работы
            </h2>
          </div>

          <div class="p-6">
            <div v-if="resume.work_place?.length" class="space-y-4">
              <div v-for="(wp, index) in resume.work_place" :key="index"
                   class="border-l-4 border-green-400 pl-4 py-3 bg-gray-50 rounded-r-lg">
                <h3 class="font-semibold text-lg text-gray-900">{{ wp.profession_name }}</h3>
                <p class="text-gray-700 font-medium">{{ wp.organization_name }}</p>
                <p class="text-sm text-gray-500 mt-1">
                  {{ formatDate(wp.start_date) }} — {{ formatDate(wp.end_date) || 'настоящее время' }}
                </p>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
              </svg>
              <p class="text-gray-500">Опыт работы не указан</p>
            </div>
          </div>
        </div>

        <!-- Образование -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
              </svg>
              Образование
            </h2>
          </div>

          <div class="p-6">
            <div v-if="resume.education?.length" class="space-y-4">
              <div v-for="(edu, index) in resume.education" :key="index"
                   class="border-l-4 border-blue-400 pl-4 py-3 bg-gray-50 rounded-r-lg">
                <h3 class="font-semibold text-lg text-gray-900">{{ edu.university }}</h3>
                <p class="text-gray-700 font-medium">{{ edu.faculty }}</p>
                <p class="text-gray-600">{{ edu.speciality }}</p>
                <div class="flex items-center mt-2 space-x-4">
                  <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                    {{ edu.level_education?.name }}
                  </span>
                  <span class="text-sm text-gray-500">{{ edu.graduation_year }}</span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
              </svg>
              <p class="text-gray-500">Образование не указано</p>
            </div>
          </div>
        </div>

        <!-- Награды и достижения -->
        <div v-if="resume.awardAndAchievement?.length" class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
              </svg>
              Награды и достижения
            </h2>
          </div>

          <div class="p-6">
            <div class="space-y-3">
              <div v-for="(award, index) in resume.awardAndAchievement" :key="index"
                   class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-gray-800">{{ award.description }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Кнопка назад -->
        <div class="flex justify-center">
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
const resume = ref(null)

onMounted(async () => {
  try {
    const { data } = await api.get(`/resume/${route.params.id}`)
    resume.value = data
  } catch (error) {
    console.error('Ошибка загрузки резюме:', error)
  }
})

// Форматирование даты
function formatDate(dateString) {
  if (!dateString) return 'Не указана'
  const date = new Date(dateString)
  return isNaN(date.getTime()) ? 'Не указана' : date.toLocaleDateString('ru-RU')
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
</script>