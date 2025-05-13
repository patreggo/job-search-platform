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
              class="h-30 w-40 "
              @error="setDefaultLogo"
          />
        </div>

        <!-- Заголовок вакансии -->
        <h2 class="text-xl font-semibold text-blue-600 mb-2">{{ vacancy.name }}</h2>

        <!-- Компания и дата -->
        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
          <span>{{ vacancy.company?.name || 'Компания не указана' }}</span>
          <span>{{ formatDate('05/05/2025') }}</span> <!-- Пример даты, замените на реальную -->
        </div>



        <!-- Зарплата -->
        <div class="text-green-600 font-medium mb-4">
          ${{ vacancy.income_min || 0 }} - ${{ vacancy.income_max || 0 }}
        </div>

        <!-- Город -->
        <div class="text-gray-700 mb-2">Город: {{ vacancy.city?.name || 'Не указан' }}</div>

        <!-- Фильтры (заглушка, замените на реальные данные) -->
        <div class="text-gray-600 text-sm mb-4">
          <span>Специализации: {{ vacancy.specializations?.map(s => s.name).join(', ') || 'Не указаны' }}</span>
          <br />
          <span>Тип трудоустройства: {{ vacancy.employment_type?.map(t => t.name).join(', ') || 'Не указаны' }}</span>
        </div>

        <!-- Кнопка "Apply" -->
        <button
            class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition"
            @click="applyVacancy(vacancy.id)"
        >
          Apply
        </button>
      </router-link>
    </div>
    <p v-if="!vacancies.length" class="text-black text-center mt-4">Загрузка...</p>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import api from '../api.js'

const vacancies = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/many_vacancies')
    vacancies.value = response.data
  } catch (error) {
    console.error('Error fetching vacancies:', error)
  }
})

// Вспомогательная функция для форматирования даты (замените на реальные данные)
function formatDate(dateStr) {
  return dateStr // Здесь можно добавить форматирование, например, через new Date(dateStr).toLocaleDateString()
}

// Методы для кнопок (нужна реализация)
function applyVacancy(id) {
  console.log('Apply for vacancy:', id)
  // Добавьте логику отправки заявки (например, POST-запрос)
}

function resetVacancy() {
  console.log('Reset vacancy')
  // Добавьте логику сброса
}
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