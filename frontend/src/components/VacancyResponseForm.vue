<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  resume: null,
  vacancy: null,
})

const resumes = ref([])
const error = ref(null)
const loading = ref(false)

// Получение активных резюме пользователя
const fetchResumes = async () => {
  try {
    const res = await axios.get('/resume/list')
    resumes.value = res.data
  } catch (e) {
    error.value = 'Ошибка при загрузке резюме'
  }
}

const submit = async () => {
  error.value = null
  loading.value = true
  try {
    await axios.post('/vacancy_response/new', {
      resume: form.value.resume,
      vacancy: form.value.vacancy
    })
    router.push('/profile')
  } catch (e) {
    error.value = e.response?.data || 'Ошибка при отправке отклика'
  } finally {
    loading.value = false
  }
}

fetchResumes()
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-6 border rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-4">Отклик на вакансию</h2>

    <div v-if="error" class="text-red-500 mb-3">{{ error }}</div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Выберите резюме</label>
        <select v-model="form.resume" class="w-full p-2 border rounded" required>
          <option value="" disabled selected>-- Выберите --</option>
          <option v-for="r in resumes" :key="r.id" :value="r.id">
            {{ r.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="block mb-1">ID вакансии</label>
        <input v-model="form.vacancy" type="number" class="w-full p-2 border rounded" required />
      </div>

      <button type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              :disabled="loading">
        {{ loading ? 'Отправка...' : 'Откликнуться' }}
      </button>
    </form>
  </div>
</template>
