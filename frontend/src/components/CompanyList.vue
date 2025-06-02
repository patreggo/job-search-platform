<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Мои компании</h2>

      <!-- Загрузка -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Список компаний -->
      <div v-else-if="companies.length" class="space-y-4">
        <div v-for="company in companies" :key="company.id" class="bg-white rounded-xl shadow-lg p-6">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ company.name }}</h3>
              <p class="text-gray-600">{{ company.description || 'Описание отсутствует' }}</p>
              <p class="text-sm text-gray-500 mt-1">Email: {{ company.email || 'Не указан' }}</p>
              <p class="text-sm text-gray-500">Телефон: {{ company.contact_phone || 'Не указан' }}</p>
              <p class="text-sm text-gray-500">Статус: {{ company.is_confirmed ? 'Подтверждена' : 'Ожидает подтверждения' }}</p>
            </div>
            <div class="flex space-x-2">
              <router-link :to="`/company/edit/${company.id}`"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Редактировать
              </router-link>
              <button @click="deleteCompany(company.id)"
                      class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                Удалить
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Нет компаний -->
      <div v-else class="bg-white rounded-xl shadow-lg p-6 text-center">
        <p class="text-gray-600">У вас пока нет компаний. <router-link to="/company/new" class="text-teal-600 hover:underline">Создать компанию</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'
import router from '../router.js'

const companies = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await api.get('/company/user/personal')
    companies.value = data
  } catch (e) {
    console.error('Ошибка загрузки компаний:', e)
  } finally {
    loading.value = false
  }
})

const deleteCompany = async (id) => {
  if (confirm('Вы уверены, что хотите удалить эту компанию?')) {
    try {
      await api.delete(`/company/${id}`)
      companies.value = companies.value.filter(company => company.id !== id)
    } catch (e) {
      console.error('Ошибка удаления компании:', e)
      alert('Не удалось удалить компанию')
    }
  }
}
</script>