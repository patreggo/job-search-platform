<template>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-black mb-6">Все вакансии</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <router-link
          v-for="vacancy in vacancies"
          :key="vacancy.id"
          :to="`/vacancy/${vacancy.id}`"
          class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-2 border border-gray-200 block no-underline"
      >
        <div class="flex justify-around-l mb-4">
          <img
              :src="vacancy.logoUrl || '/default-logo.png'"
              alt="Company Logo"
              class="h-30 w-40"
              @error="setDefaultLogo"
          />
        </div>

        <!-- Заголовок вакансии -->
        <h2 class="text-xl font-semibold text-blue-600 mb-2">{{ vacancy.name }}</h2>

        <!-- Компания и дата -->
        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
          <span>{{ vacancy.company?.name || 'Компания не указана' }}</span>
          <span>{{ formatDate(vacancy.created_at) }}</span>
        </div>

        <!-- Зарплата -->
        <div class="text-green-600 font-medium mb-4">
          ${{ vacancy.income_min || 0 }} - ${{ vacancy.income_max || 0 }}
        </div>

        <!-- Город -->
        <div class="text-gray-700 mb-2">Город: {{ vacancy.city?.name || 'Не указан' }}</div>

        <!-- Фильтры -->
        <div class="text-gray-600 text-sm mb-4">
          <span>Специализации: {{ vacancy.specializations?.map(s => s.name).join(', ') || 'Не указаны' }}</span>
          <br />
          <span>Тип трудоустройства: {{ vacancy.employment_type?.map(t => t.name).join(', ') || 'Не указаны' }}</span>
        </div>

        <!-- Кнопка Apply -->
        <button
            class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition"
            @click.stop.prevent="openModal(vacancy.id)"
        >
          Apply
        </button>
      </router-link>
    </div>
    <p v-if="!vacancies.length" class="text-black text-center mt-4">Загрузка...</p>
  </div>

  <!-- Модальное окно выбора резюме -->
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 backdrop-blur-sm bg-black/30" @click.self="closeModal"></div>
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md z-10">
      <h3 class="text-lg font-semibold mb-4 text-black">Выберите резюме</h3>

      <div v-if="loadingResumes" class="text-gray-500 mb-4">Загрузка...</div>
      <div v-else-if="resumes.length === 0" class="text-gray-500 mb-4">Нет активных резюме</div>

      <div v-else class="space-y-3 max-h-80 overflow-y-auto mb-4">
        <div
            v-for="resume in resumes"
            :key="resume.id"
            @click="selectResume(resume.id)"
            :class="[
              'p-4 border rounded cursor-pointer transition-all',
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
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm"
        >
          Отмена
        </button>
        <button
            @click="sendApplication"
            :disabled="!selectedResumeId"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm disabled:opacity-50"
        >
          Отправить
        </button>
      </div>
    </div>
  </div>
</template>

 <script setup>
 import { ref, onMounted } from 'vue'
 import { useRoute, useRouter } from 'vue-router'
 import api from '../api.js'

 const setDefaultLogo = (event) => {
   event.target.src = '/default-logo.png'
 }

 // Список вакансий
 const vacancies = ref([])

 // Модальное окно
 const showModal = ref(false)
 const loadingResumes = ref(true)
 const resumes = ref([])
 const selectedResumeId = ref(null)
 const modalError = ref(null)

 // Текущая вакансия для отправки
 const selectedVacancyId = ref(null)

 // Роутер и текущий route
 const route = useRoute()
 const router = useRouter()

 // Форматирование даты
 const formatDate = (dateStr) => {
   if (!dateStr) return '-'
   const date = new Date(dateStr)
   return isNaN(date.getTime()) ? '-' : date.toLocaleDateString()
 }

 // Загрузка всех вакансий
 onMounted(async () => {
   try {
     const response = await api.get('/many_vacancies')
     vacancies.value = response.data
   } catch (error) {
     console.error('Ошибка при загрузке вакансий:', error)
   }
 })

 // Открытие модального окна для выбора резюме
 const openModal = async (vacancyId) => {
   selectedVacancyId.value = vacancyId
   selectedResumeId.value = null
   modalError.value = null
   showModal.value = true
   loadingResumes.value = true

   try {
     const response = await api.get('resume/user/personal') // должен возвращать список резюме: [{ id, name, first_name, last_name }]
     resumes.value = response.data
   } catch (error) {
     modalError.value = 'Не удалось загрузить резюме'
   } finally {
     loadingResumes.value = false
   }
 }

 // Выбор резюме
 const selectResume = (id) => {
   selectedResumeId.value = id
 }

 // Закрытие модального окна
 const closeModal = () => {
   showModal.value = false
   selectedVacancyId.value = null
   selectedResumeId.value = null
   modalError.value = null
   resumes.value = []
 }

 // Отправка отклика
 const sendApplication = async () => {
   if (!selectedResumeId.value || !selectedVacancyId.value) return

   try {
     const payload = {
       resume: selectedResumeId.value,
       vacancy: selectedVacancyId.value
     }

     const response = await api.post(`/vacancy_response/new`, payload, {
       headers: { 'Content-Type': 'application/json' }
     })

     alert('Резюме успешно отправлено!')
     closeModal()
   } catch (e) {
     modalError.value = 'Ошибка при отправке резюме'
     console.error(e.response?.data || e)
   }
 }
 </script>

<style scoped>
.backdrop-blur-sm {
  -webkit-backdrop-filter: blur(4px);
  backdrop-filter: blur(4px);
}
.container {
  max-width: 1200px;
}
.grid {
  display: grid;
}
.card {
  position: relative;
  transition: all 0.3s ease;
}
.card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
</style>