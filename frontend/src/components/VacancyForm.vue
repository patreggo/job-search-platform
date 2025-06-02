<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-5xl mx-auto px-4">
      <!-- Заголовок -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          {{ isEdit ? 'Редактировать вакансию' : 'Создать вакансию' }}
        </h1>
        <p class="text-gray-600">
          Заполните информацию о вакансии для поиска подходящих кандидатов
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Основная информация -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
              </svg>
              Основная информация
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Название вакансии</label>
                <input v-model="form.name" type="text" required placeholder="Название вакансии"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Компания</label>
                <select v-model="form.company" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
                  <option value="">Выберите компанию</option>
                  <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Мин. зарплата (₽)</label>
                <input v-model.number="form.incomeMin" type="number" min="0" placeholder="Мин. зарплата"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Макс. зарплата (₽)</label>
                <input v-model.number="form.incomeMax" type="number" min="0" placeholder="Макс. зарплата"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Адрес работы</label>
                <input v-model="form.workAddress" type="text" placeholder="Адрес работы"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
              </div>
            </div>
          </div>
        </div>

        <!-- Описание и требования -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Описание и требования
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <textarea v-model="form.description" rows="4" placeholder="Опишите вакансию..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none text-gray-900 bg-white placeholder-gray-500"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Требования</label>
                <textarea v-model="form.requirements" rows="4" placeholder="Укажите требования..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none text-gray-900 bg-white placeholder-gray-500"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Обязанности</label>
                <textarea v-model="form.responsibilities" rows="4" placeholder="Укажите обязанности..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none text-gray-900 bg-white placeholder-gray-500"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Параметры вакансии -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Параметры вакансии
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Тип занятости</label>
                <select v-model="form.employmentType" multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors min-h-[48px] text-gray-900 bg-white">
                  <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Удерживайте Ctrl для выбора нескольких</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Специализации</label>
                <select v-model="form.specializations" multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors min-h-[48px] text-gray-900 bg-white">
                  <option v-for="spec in specializations" :key="spec.id" :value="spec.id">{{ spec.name }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Удерживайте Ctrl для выбора нескольких</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">График работы</label>
                <select v-model="form.workSchedule" multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors min-h-[48px] text-gray-900 bg-white">
                  <option v-for="schedule in workSchedule" :key="schedule.id" :value="schedule.id">{{ schedule.name }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Удерживайте Ctrl для выбора нескольких</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Опыт работы</label>
                <select v-model="form.workExperience"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors text-gray-900 bg-white">
                  <option v-for="exp in workExperience" :key="exp.id" :value="exp.id">{{ exp.name }}</option>
                </select>
              </div>
              <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <input v-model="form.isActive" type="checkbox" id="active"
                       class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label for="active" class="ml-3 flex items-center cursor-pointer">
                  <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  <span class="text-sm font-medium text-gray-700">Активна</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Кнопки -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="flex justify-end space-x-3">
            <router-link to="/vacancy/employer/personal"
                         class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              Отмена
            </router-link>
            <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              {{ isEdit ? 'Сохранить изменения' : 'Создать вакансию' }}
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
  border-color: #8b5cf6;
  box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}
button:hover {
  background-color: #7c3aed;
}
</style>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api.js'

const route = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)

const form = reactive({
  name: '',
  company: null,
  incomeMin: null,
  incomeMax: null,
  workAddress: '',
  description: '',
  requirements: '',
  responsibilities: '',
  employmentType: [],
  specializations: [],
  workSchedule: [],
  workExperience: null,
  isActive: true
})

const companies = ref([])
const employmentTypes = ref([])
const specializations = ref([])
const workSchedule = ref([])
const workExperience = ref([])

const loadParameters = async (endpoint, target) => {
  try {
    const { data } = await api.get(`/many_vacancy_parameters/${endpoint}`)
    target.value = data
  } catch (e) {
    console.error(`Ошибка при загрузке ${endpoint}:`, e)
  }
}

const loadCompanies = async () => {
  try {
    const { data } = await api.get('/company/user/personal')
    companies.value = data
  } catch (e) {
    console.error('Ошибка при загрузке компаний:', e)
    alert('Не удалось загрузить список компаний')
  }
}

onMounted(async () => {
  await loadCompanies()
  loadParameters('employment_type', employmentTypes)
  loadParameters('specializations', specializations)
  loadParameters('work_schedule', workSchedule)
  loadParameters('work_experience', workExperience)

  if (isEdit.value) {
    try {
      const { data } = await api.get(`/vacancy/${route.params.id}`)
      Object.assign(form, {
        name: data.name,
        company: data.company?.id || null,
        incomeMin: data.incomeMin || null,
        incomeMax: data.incomeMax || null,
        workAddress: data.workAddress || '',
        description: data.description || '',
        requirements: data.requirements || '',
        responsibilities: data.responsibilities || '',
        employmentType: data.employmentType?.map(type => type.id) || [],
        specializations: data.specializations?.map(spec => spec.id) || [],
        workSchedule: data.workSchedule?.map(schedule => schedule.id) || [],
        workExperience: data.workExperience?.id || null,
        isActive: data.isActive || true
      })
    } catch (e) {
      console.error('Ошибка загрузки данных вакансии:', e)
      alert('Не удалось загрузить данные вакансии')
    }
  }
})

const submit = async () => {
  try {
    const payload = JSON.parse(JSON.stringify(form))
    if (isEdit.value) {
      await api.put(`/vacancy/${route.params.id}`, payload)
    } else {
      await api.post('/new_vacancy', payload)
    }
    router.push('/vacancy/employer/personal')
  } catch (e) {
    console.error('Ошибка сохранения вакансии:', e)
    alert('Не удалось сохранить вакансию')
  }
}
</script>