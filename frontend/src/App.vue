<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from './api'

const route = useRoute()
const router = useRouter()

const user = ref(null)
const storedUser = localStorage.getItem('user')
if (storedUser) {
  user.value = JSON.parse(storedUser)
}

const hiddenRoutes = ['/profile', '/company/new', '/resume', '/my-vacancies', '/login']
const showUserPanel = computed(() => !hiddenRoutes.includes(route.path))

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  delete api.defaults.headers.common['Authorization']
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Шапка -->
    <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center relative">
      <!-- Логотип слева -->
      <h1 @click="router.push('/')" class="text-2xl font-bold text-blue-600 cursor-pointer">
        JobBoard
      </h1>

      <!-- Панель справа -->
      <div v-if="showUserPanel" class="flex items-center space-x-4">
        <template v-if="user">
          <router-link to="/profile" class="text-gray-700 hover:underline">{{ user.email }}</router-link>
          <button @click="logout" class="text-red-600 hover:underline">Выйти</button>
        </template>
        <template v-else>
          <router-link to="/login" class="text-blue-600 hover:underline">Войти</router-link>
          <router-link to="/register" class="text-green-600 hover:underline">Регистрация</router-link>
        </template>
      </div>
    </nav>

    <!-- Контент -->
    <main class="container mx-auto p-6">
      <router-view />
    </main>
  </div>
</template>
