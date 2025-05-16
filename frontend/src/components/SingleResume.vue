<template>
  <div v-if="resume" class="max-w-4xl mx-auto p-6 bg-white rounded shadow-md">
    <h1 class="text-2xl font-bold mb-6">Резюме: {{ resume.first_name }} {{ resume.last_name }}</h1>

    <div class="mb-4">
      <strong>Дата рождения:</strong>
      <p class="text-black">{{ formatDate(resume.birth_date) }}</p>
    </div>

    <div class="mb-4">
      <strong>Желаемая зарплата:</strong>
      <p class="text-black">{{ resume.desired_salary }} ₽</p>
    </div>

    <div class="mb-4">
      <strong>Пол:</strong>
      <p class="text-black">{{ resume.gender?.name }}</p>
    </div>

    <div class="mb-4">
      <strong>Город проживания:</strong>
      <p class="text-black">{{ resume.residence_city?.name }}</p>
    </div>

    <div class="mb-4">
      <strong>Гражданство:</strong>
      <p class="text-black">{{ resume.citizenship?.map(c => c.name).join(', ') }}</p>
    </div>

    <div class="mb-4">
      <strong>Специализация:</strong>
      <p class="text-black">{{ resume.specialization?.name }}</p>
    </div>

    <div class="mb-4">
      <strong>Тип занятости:</strong>
      <p class="text-black">{{ resume.employment_type?.map(t => t.name).join(', ') }}</p>
    </div>

    <div class="mb-4">
      <strong>График работы:</strong>
      <p class="text-black">{{ resume.work_schedule?.name }}</p>
    </div>

    <div class="mb-4">
      <strong>Личный автомобиль:</strong>
      <p class="text-black">{{ resume.havePersonalCar ? 'Да' : 'Нет' }}</p>
    </div>

    <div class="mb-4">
      <strong>Резюме активно:</strong>
      <p class="text-black">{{ resume.isActive ? 'Да' : 'Нет' }}</p>
    </div>

    <div class="mb-6 text-black">
      <h2 class="text-xl font-semibold mb-2">Опыт работы</h2>
      <div v-if="resume.work_place?.length">
        <div v-for="(wp, index) in resume.work_place" :key="index" class="mb-4 border-b pb-2">
          <p class="text-black"><strong>Организация:</strong> {{ wp.organization_name }}</p>
          <p class="text-black"><strong>Должность:</strong> {{ wp.profession_name }}</p>
          <p class="text-black"><strong>Период:</strong> {{ formatDate(wp.start_date) }} — {{ formatDate(wp.end_date) || 'н.в.' }}</p>
        </div>
      </div>
      <p v-else class="text-gray-500">Нет данных</p>
    </div>

    <div class="mb-6 text-black">
      <h2 class="text-xl font-semibold mb-2">Образование</h2>
      <div v-if="resume.education?.length">
        <div v-for="(edu, index) in resume.education" :key="index" class="mb-4 border-b pb-2">
          <p class="text-black"><strong>Уровень:</strong> {{ edu.level_education?.name }}</p>
          <p class="text-black"><strong>Университет:</strong> {{ edu.university }}</p>
          <p class="text-black"><strong>Факультет:</strong> {{ edu.faculty }}</p>
          <p class="text-black"><strong>Специальность:</strong> {{ edu.speciality }}</p>
          <p class="text-black"><strong>Год окончания:</strong> {{ edu.graduation_year }}</p>
        </div>
      </div>
      <p v-else class="text-gray-500">Нет данных</p>
    </div>

    <div class="mb-6 text-black">
      <h2 class="text-xl font-semibold mb-2">Награды и достижения</h2>
      <div v-if="resume.awardAndAchievement?.length">
        <ul class="list-disc list-inside">
          <li v-for="(award, index) in resume.awardAndAchievement" :key="index">{{ award.description }}</li>
        </ul>
      </div>
      <p v-else class="text-gray-500">Нет данных</p>
    </div>
  </div>

  <div v-else class="text-center text-gray-500 mt-10">
    Загрузка...
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api.js'

const route = useRoute()
const resume = ref(null)

onMounted(async () => {
  const { data } = await api.get(`/resume/${route.params.id}`)
  resume.value = data
})

// Форматирование даты
function formatDate(dateString) {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return isNaN(date.getTime()) ? '-' : date.toLocaleDateString()
}

// Форматирование массивов (например, список специализаций)
function formatArray(arr) {
  if (!arr || !Array.isArray(arr)) return '-'
  return arr.map(item => item.name).join(', ') || '-'
}
</script>

<style scoped>
h1 {
  color: #1f2937;
}
strong {
  display: inline-block;
  width: 120px;
  color: #4b5563;
}
</style>