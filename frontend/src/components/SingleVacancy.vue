<template>
  <div v-if="vacancy" class="max-w-2xl mx-auto p-6 bg-white rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">{{ vacancy.name }}</h1>

    <div class="mb-4">
      <strong>Описание:</strong>
      <p class="text-black">{{ vacancy.description }}</p>
    </div>

    <div class="mb-4">
      <strong>Зарплата:</strong>
      <p class="text-black">от {{ vacancy.income_min }} до {{ vacancy.income_max }} ₽</p>
    </div>

    <div class="mb-4">
      <strong>Адрес работы:</strong>
      <p class="text-black">{{ vacancy.work_address }}</p>
    </div>

    <div class="mb-4">
      <strong>Требования:</strong>
      <p class="text-black">{{ vacancy.requirements }}</p>
    </div>

    <div>
      <strong>Обязанности:</strong>
      <p class="text-black">{{ vacancy.responsibilities }}</p>
    </div>
  </div>

  <div v-else>
    Загрузка...
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api.js'

const route = useRoute()
const vacancy = ref(null)

onMounted(async () => {
  const { data } = await api.get(`/single_vacancy/${route.params.id}`)
  vacancy.value = data
})
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
