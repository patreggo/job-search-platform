<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-black mb-6">Мои отклики</h1>

    <!-- Список откликов -->
    <div v-if="applications.length" class="space-y-6">
      <div
          v-for="app in applications"
          :key="app.id"
          class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition border border-gray-200"
      >
        <!-- Информация о резюме -->
        <div class="mb-4">
          <h2 class="text-xl font-semibold text-blue-600">{{ app.resume.first_name }} {{ app.resume.last_name }}</h2>
          <p class="text-gray-600">{{ app.resume.specialization?.name || 'Без специализации' }}</p>
        </div>

        <!-- Вакансия -->
        <div class="mb-4">
          <strong class="text-black">Вакансия:</strong>
          <p class="text-black">{{ app.vacancy.name }}</p>
          <p class="text-sm text-gray-500">{{ app.vacancy.company?.name || 'Компания не указана' }}</p>
        </div>

        <!-- Статус -->
        <div class="mb-4">
          <strong class="text-black">Статус:</strong>
          <span
              class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
          >{{ app.status.name }}</span>
        </div>
      </div>
    </div>

    <!-- Сообщение если нет откликов -->
    <p v-else class="text-center text-gray-500 mt-6">Нет откликов</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'

const applications = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/vacancy_response/user/personal')
    applications.value = response.data
  } catch (error) {
    console.error('Ошибка при загрузке откликов:', error)
  }
})
</script>

<style scoped>
.container {
  max-width: 1200px;
}
.card {
  transition: all 0.3s ease;
}
.card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
</style>