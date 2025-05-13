<!-- Profile.vue -->
<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-black">Профиль</h2>
    <div v-if="user" class="space-y-3 text-black">
      <p><strong>Имя:</strong> {{ user.first_name }}</p>
      <p><strong>Фамилия:</strong> {{ user.last_name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Телефон:</strong> {{ user.phone }}</p>

      <!-- Кнопка перехода на форму создания вакансии -->
      <router-link to="/new" class="mt-4 w-full inline-block text-center bg-green-600 text-black py-2 px-4 rounded hover:bg-green-700">
        Создать вакансию
      </router-link>

      <button @click="logout" class="mt-2 w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
        Выйти
      </button>
    </div>
    <div v-else>
      Загрузка...
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'
import router from '../router.js'

const user = ref(null)

onMounted(async () => {
  try {
    const { data } = await api.get('/auth_user')
    user.value = data
    localStorage.setItem('user', JSON.stringify(data))
  } catch (e) {
    // Если нет токена или он невалиден — редиректим на login
    console.error('Ошибка получения данных:', e)
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    delete api.defaults.headers.common['Authorization']
    router.push('/login')
  }
})

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  delete api.defaults.headers.common['Authorization']
  router.push('/login')
}
</script>