<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-5xl mx-auto px-4">
      <!-- Заголовок -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          {{ isEdit ? 'Редактировать компанию' : 'Создать компанию' }}
        </h1>
        <p class="text-gray-600">
          Заполните информацию о компании для публикации вакансий
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Информация о компании -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-2m-6 0H7m2-8h6m-6 4h6"></path>
              </svg>
              Информация о компании
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Название компании</label>
                <input v-model="form.name" type="text" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input v-model="form.email" type="email"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Контактный телефон</label>
                <input v-model="form.contactPhone" type="text"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors text-gray-900 bg-white">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <textarea v-model="form.description" rows="4"
                          placeholder="Опишите вашу компанию..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors resize-none text-gray-900 bg-white placeholder-gray-500"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Кнопки -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="flex justify-end space-x-3">
            <router-link to="/company/user/personal"
                         class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
              Отмена
            </router-link>
            <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
              {{ isEdit ? 'Сохранить изменения' : 'Создать компанию' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
label {
  font-weight: 500;
  color: #374151;
}
input,
textarea,
select {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.25rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease-in-out;
}
input:focus,
textarea:focus,
select:focus {
  outline: none;
  border-color: #14b8a6;
  box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.2);
}
button:hover {
  background-color: #0d9488;
}
</style>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api.js'

const route = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)

const form = ref({
  name: '',
  email: '',
  contactPhone: '',
  description: ''
})

onMounted(async () => {
  if (isEdit.value) {
    try {
      const { data } = await api.get(`/company/${route.params.id}`)
      form.value = {
        name: data.name,
        email: data.email || '',
        contactPhone: data.contact_phone || '',
        description: data.description || ''
      }
    } catch (e) {
      console.error('Ошибка загрузки данных компании:', e)
      alert('Не удалось загрузить данные компании')
    }
  }
})

const submit = async () => {
  try {
    if (isEdit.value) {
      await api.put(`/company/${route.params.id}`, form.value)
    } else {
      await api.post('/company/new', form.value)
    }
    router.push('/company/user/personal')
  } catch (e) {
    console.error('Ошибка сохранения компании:', e)
    alert('Не удалось сохранить компанию')
  }
}
</script>