<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Вход</h2>

    <input v-model="email" type="email" placeholder="Email" class="w-full p-2 border rounded mb-4 text-black" />
    <input v-model="password" type="password" placeholder="Пароль" class="w-full p-2 border rounded mb-4 text-black" />

    <button type="submit" @click="login" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
      Войти
    </button>

    <p class="mt-4 text-center text-black">
      Нет аккаунта?
      <router-link to="/register" class="text-green-600 hover:underline">Зарегистрироваться</router-link>
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../api.js'
import router from '../router.js'

const email = ref('')
const password = ref('')

const login = async () => {
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value
    })

    const token = response.data.token
    localStorage.setItem('token', token)
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`

    const { data } = await api.get('/auth_user')
    localStorage.setItem('user', JSON.stringify(data))

    router.push('/profile') // Редирект на профиль
  } catch (e) {
    alert('Ошибка входа')
    console.error(e)
  }
}
</script>
