<template>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-black mb-6">Все резюме</h1>

    <!-- Список резюме -->
    <div v-if="resumes.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <router-link
          v-for="resume in resumes"
          :key="resume.id"
          :to="`/resume/${resume.id}`"
          class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-2 border border-gray-200 block no-underline"
      >
        <!-- Имя и фамилия -->
        <h2 class="text-xl font-semibold text-blue-600 mb-2">
          {{ resume.first_name }} {{ resume.last_name }}
        </h2>

        <!-- Специализация -->
        <div class="text-gray-700 mb-2">
          <strong>Специализация:</strong>
          {{ resume.specialization?.name || 'Не указана' }}
        </div>

        <!-- Желаемая зарплата -->
        <div class="text-green-600 font-medium mb-2">
          {{ resume.desired_salary }} {{ resume.salaryCurrency?.name || 'RUB' }}
        </div>

        <!-- Город -->
        <div class="text-gray-600 text-sm mb-2">
          <span v-if="resume.residence_city">{{ resume.residence_city.name }}</span>
          <span v-else>Город не указан</span>
        </div>
      </router-link>
    </div>

    <!-- Сообщение если нет резюме -->
    <p v-else class="text-gray-500 text-center mt-4">Нет активных резюме</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'

const resumes = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/resume/user/personal')
    resumes.value = response.data
  } catch (error) {
    console.error('Ошибка при загрузке резюме:', error)
  }
})
</script>

<style scoped>
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