<template>
  <form @submit.prevent="register">
    <input v-model="form.email" placeholder="Email" />
    <input v-model="form.password" type="password" placeholder="Пароль" />
    <input v-model="form.firstName" placeholder="Имя" />
    <input v-model="form.lastName" placeholder="Фамилия" />
    <input v-model="form.phone" placeholder="Телефон" />
    <button type="submit">Зарегистрироваться</button>
  </form>
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
  }
}
</script>
