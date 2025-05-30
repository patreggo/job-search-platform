<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-700">Регистрация</h2>

    <!-- Выбор роли -->
    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Выберите роль:</label>
      <select v-model="form.roles" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        <option v-for="role in roles" :key="role.id" :value="role.id">
          {{ role.display_name }}
        </option>
      </select>
    </div>

    <!-- Email -->
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input v-model="form.email" id="email" type="email" placeholder="Email"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
    </div>

    <!-- Пароль -->
    <div class="mb-4">
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
      <input v-model="form.password" id="password" type="password" placeholder="Пароль"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
    </div>

    <!-- Имя -->
    <div class="mb-4">
      <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
      <input v-model="form.firstName" id="firstName" type="text" placeholder="Имя"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
    </div>

    <!-- Фамилия -->
    <div class="mb-4">
      <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
      <input v-model="form.lastName" id="lastName" type="text" placeholder="Фамилия"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
    </div>

    <!-- Телефон -->
    <div class="mb-4">
      <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
      <input v-model="form.phone" id="phone" type="tel" placeholder="Телефон"
             class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
    </div>

    <!-- Кнопка регистрации -->
    <div class="mt-6">
      <button type="submit"
              @click="register"
              class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Зарегистрироваться
      </button>
    </div>
  </div>

  <!-- Ссылка на вход -->
  <p class="mt-4 text-center text-sm text-gray-600">
    Уже есть аккаунт?
    <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">Войдите</router-link>
  </p>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'
import router from '../router.js'

// Данные формы
const form = ref({
  email: '',
  password: '',
  firstName: '',
  lastName: '',
  phone: '',
  roles: ''
})

// Список ролей
const roles = ref([])

// Получение ролей с бэкенда
onMounted(async () => {
  try {
    const response = await api.get('/user_roles')
    roles.value = response.data
    if (roles.value.length > 0) {
      form.value.role = roles.value[0].code // устанавливаем первую роль по умолчанию
    }
  } catch (e) {
    alert('Не удалось загрузить роли')
    console.error(e)
  }
})

// Отправка формы
const register = async () => {
  try {
    await api.post('/registration', form.value)
    alert('Вы успешно зарегистрировались!')
    router.push('/login') // перенаправление на страницу входа
  } catch (error) {
    alert('Ошибка регистрации. Попробуйте снова.')
    console.error(error)
  }
}
</script>