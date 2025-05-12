<template>
  <div>
    <h1 class="text-black text-10">Все вакансии</h1>
    <ul v-if="vacancies.length" class="space-y-2">
      <li v-for="vac in vacancies" :key="vac.id" class="p-4 border rounded hover:bg-gray-50 transition">
        <router-link
            :to="`/vacancy/${vac.id}`"
            class="text-blue-600 hover:underline font-medium"
        >
          {{ vac.name }} — {{ vac.income_min }} ₽
        </router-link>
      </li>
    </ul>
    <p v-else class="text-black">Загрузка...</p>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import api from '../api.js'

const vacancies = ref([])

onMounted(async () => {
  const { data } = await api.get('/many_vacancies')
  vacancies.value = data
})
</script>

<style scoped>
ul {
  list-style: none;
  padding-left: 0;
}
li {
  border-bottom: 1px solid #e5e7eb;
}
li:last-child {
  border-bottom: none;
}
</style>
