<template>
  <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-500">Создание новой вакансии</h2>

    <form @submit.prevent="submit" class="space-y-6">

      <!-- Название вакансии -->
      <div>
        <label class="block text-sm font-medium text-black-700">Название вакансии</label>
        <input v-model="form.name" type="text" placeholder="Название вакансии"
               class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
      </div>

      <!-- Минимальная и максимальная зарплата -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-black-700">Мин. зарплата</label>
          <input v-model.number="form.incomeMin" type="number" placeholder="Мин. зарплата"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Макс. зарплата</label>
          <input v-model.number="form.incomeMax" type="number" placeholder="Макс. зарплата"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
      </div>

      <!-- Адрес работы -->
      <div>
        <label class="block text-sm font-medium text-black-700">Адрес работы</label>
        <input v-model="form.workAddress" type="text" placeholder="Адрес работы"
               class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
      </div>

      <!-- Описание -->
      <div>
        <label class="block text-sm font-medium text-black-700">Описание</label>
        <textarea v-model="form.description" placeholder="Описание"
                  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"></textarea>
      </div>

      <!-- Требования -->
      <div>
        <label class="block text-sm font-medium text-black-700">Требования</label>
        <textarea v-model="form.requirements" placeholder="Требования"
                  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"></textarea>
      </div>

      <!-- Обязанности -->
      <div>
        <label class="block text-sm font-medium text-black-700">Обязанности</label>
        <textarea v-model="form.responsibilities" placeholder="Обязанности"
                  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"></textarea>
      </div>

      <!-- Тип занятости -->
      <div>
        <label class="block text-sm font-medium text-black-700">Тип занятости</label>
        <select v-model="form.employmentType" multiple
                class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
          <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{ type.tech_name }}</option>
        </select>
      </div>

      <!-- Специализации -->
      <div>
        <label class="block text-sm font-medium text-black-700">Специализации</label>
        <select v-model="form.specializations" multiple
                class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
          <option v-for="spec in specializations" :key="spec.id" :value="spec.id">{{ spec.tech_name }}</option>
        </select>
      </div>

      <!-- Чекбоксы -->
      <div class="flex flex-col sm:flex-row gap-4">
        <label class="inline-flex items-center">
          <input v-model="form.archived" type="checkbox"
                 class="rounded border-black-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
          <span class="ml-2">Архивная</span>
        </label>
        <label class="inline-flex items-center">
          <input v-model="form.isActive" type="checkbox"
                 class="rounded border-black-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
          <span class="ml-2">Активна</span>
        </label>
      </div>

      <!-- Кнопка -->
      <div>
        <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Создать
        </button>
      </div>

    </form>
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
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}
button:hover {
  background-color: #2563eb;
}
</style>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import api from '../api.js'

const form = reactive({
  name: '',
  incomeMin: null,
  incomeMax: null,
  workAddress: '',
  description: '',
  requirements: '',
  responsibilities: '',

  employmentType: [],
  specializations: [],

  archived: false,
  isActive: true,
})

// Состояния для параметров, полученных из API
const employmentTypes = ref([])
const specializations = ref([])

// Функция для загрузки параметров из API
const loadParameters = async (vacancyParameter, targetArray) => {
  try {
    const { data } = await api.get(`/many_vacancy_parameters/${vacancyParameter}`)
    console.log(`Данные для ${vacancyParameter}:`, data)
    targetArray.value = data
  } catch (e) {
    console.error('Ошибка при загрузке данных:', e)
  }
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadParameters('employment_type', employmentTypes)
  loadParameters('specializations', specializations)
})

const submit = async () => {
  try {
    const payload = JSON.parse(JSON.stringify(form)) // очищаем от Vue Proxy
    const { data } = await api.post('/new_vacancy', payload, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
    alert(`Вакансия создана с ID ${data.id}`)
  } catch (e) {
    console.error(e.response?.data || e)
    alert('Ошибка при создании вакансии')
  }
}
</script>
