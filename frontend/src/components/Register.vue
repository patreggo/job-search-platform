<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-700">Регистрация</h2>

    <form @submit.prevent="register" class="space-y-6">

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="form.email" type="email" placeholder="Email"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700" />
      </div>

      <!-- Пароль -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Пароль</label>
        <input v-model="form.password" type="password" placeholder="Пароль"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700" />
      </div>

      <!-- Имя -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Имя</label>
        <input v-model="form.firstName" type="text" placeholder="Имя"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700" />
      </div>

      <!-- Фамилия -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Фамилия</label>
        <input v-model="form.lastName" type="text" placeholder="Фамилия"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700" />
      </div>

      <!-- Телефон -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Телефон</label>
        <input v-model="form.phone" type="tel" placeholder="Телефон"
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700" />
      </div>

      <!-- Кнопка -->
      <div>
        <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          Зарегистрироваться
        </button>
      </div>

    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      Уже есть аккаунт?
      <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">Войдите</router-link>
    </p>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import api from '../api.js'
import router from '../router.js'

const form = reactive({
  email: '',
  password: '',
  firstName: '',
  lastName: '',
  phone: '',
})

const register = async () => {
  try {
    await api.post('/registration', form)
    router.push('/login')
  } catch (e) {
    alert('Ошибка регистрации')
    console.error(e)
  }
}
</script>

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
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

button:hover {
  background-color: #16a34a; /* Темнее зелёный при наведении */
}
</style>