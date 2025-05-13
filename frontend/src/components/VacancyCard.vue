<!-- src/components/VacancyCard.vue -->
<template>
  <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-start mb-4">
      <!-- Логотип компании -->

      <!-- Кнопка избранное -->
      <button @click="toggleFavorite" class="text-gray-500 hover:text-red-500 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.35 2.71.753-1.583 2.415-2.71 4.35-2.71 2.591 0 4.688 2.015 4.688 4.5zM8.25 4.5c-2.485 0-4.5 2.099-4.5 4.5 0 1.935 1.126 3.597 2.71 4.35-.753-1.583-2.71 2.415-2.71 4.35 0 2.485 2.099 4.5 4.5 4.5 1.935 0 3.597-1.126 4.35-2.71-.753 1.583-2.415 2.71-4.35 2.71-2.591 0-4.5-2.015-4.5-4.5zm0 9.75c1.59 0 2.945-1.258 3.22-2.874.133-1.138-.56-2.157-1.765-2.35a4.438 4.438 0 00-1.715-.12V18.5l.714-.714a1 1 0 011.414 0l.714 .714V13.818c0-.999.603-1.765 1.536-1.94c.933-.174 1.972.242 2.366 1.178a4.436 4.436 0 01-.393 2.423c0 3.328-2.672 6-6 6s-6-2.672-6-6z" />
        </svg>
      </button>
    </div>

    <!-- Название вакансии -->
    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ name }}</h2>

    <!-- Название компании -->
    <p class="text-gray-600 mb-2">{{ company }}</p>

    <!-- Зарплата -->
    <p class="text-green-600 font-semibold mb-2">
      ${{ income_min }} - ${{ income_max }}
    </p>

    <!-- Дата публикации -->
    <p class="text-sm text-gray-500">
      {{ created_at }}
    </p>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue'

const props = defineProps({
  vacancy: {
    type: Object,
    required: true
  }
})

const isFavorite = ref(false)

const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
}

// Форматирование даты
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>