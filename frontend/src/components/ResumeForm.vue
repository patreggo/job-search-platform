<template>
  <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-500">Создание резюме</h2>
    <form @submit.prevent="submit" class="space-y-8">

      <!-- Основные поля -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-black-700">Имя</label>
          <input v-model="form.firstName" type="text"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Фамилия</label>
          <input v-model="form.lastName" type="text"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Дата рождения</label>
          <input v-model="form.birthDate" type="date"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Желаемая зарплата</label>
          <input v-model.number="form.desiredSalary" type="number"
                 class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500" />
        </div>
      </section>

      <!-- Выпадающие списки -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-black-700">Пол</label>
          <select v-model="form.gender" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="gender in genders" :key="gender.id" :value="gender.id">{{ gender.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Город проживания</label>
          <select v-model="form.residenceCity" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Гражданство</label>
          <select v-model="form.citizenship" multiple class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Специализация</label>
          <select v-model="form.specialization"  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="spec in specializations" :key="spec.id" :value="spec.id">{{ spec.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">Тип занятости</label>
          <select v-model="form.employmentType" multiple
                  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-black-700">График</label>
          <select v-model="form.workSchedule"
                  class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            <option v-for="spec in workSchedule" :key="spec.id" :value="spec.id">{{ spec.name }}</option>
          </select>
        </div>
      </section>


      <!-- Место работы -->
      <section>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Место работы</h3>
        <div v-for="(item, index) in form.workPlace" :key="index" class="border rounded p-4 mb-4 relative">
          <button @click="removeWorkPlace(index)" type="button"
                  class="absolute top-2 right-2 text-red-500 hover:text-red-700">×</button>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black-700">Организация</label>
              <input v-model="item.organizationName" type="text" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Должность</label>
              <input v-model="item.professionName" type="text" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Начальная дата</label>
              <input v-model="item.startDate" type="date" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Конечная дата</label>
              <input v-model="item.endDate" type="date" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
          </div>
        </div>
        <button @click="addWorkPlace" type="button"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
          + Добавить место работы
        </button>
      </section>

      <!-- Образование -->
      <section>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Образование</h3>
        <div v-for="(item, index) in form.education" :key="index" class="border rounded p-4 mb-4 relative">
          <button @click="removeEducation(index)" type="button"
                  class="absolute top-2 right-2 text-red-500 hover:text-red-700">×</button>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black-700">Уровень образования</label>
              <select v-model="item.levelEducation" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
                <option v-for="edu in educationLevels" :key="edu.id" :value="edu.id">{{ edu.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Университет</label>
              <input v-model="item.university" type="text" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Специальность</label>
              <input v-model="item.speciality" type="text" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Факультет</label>
              <input v-model="item.faculty" type="text" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-black-700">Год окончания</label>
              <input v-model.number="item.graduationYear" type="number" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500">
            </div>
          </div>
        </div>
        <button @click="addEducation" type="button"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
          + Добавить образование
        </button>
      </section>

      <!-- Награды и достижения -->
      <section>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Награды и достижения</h3>
        <div v-for="(item, index) in form.awardAndAchievement" :key="index" class="border rounded p-4 mb-4 relative">
          <button @click="removeAward(index)" type="button"
                  class="absolute top-2 right-2 text-red-500 hover:text-red-700">×</button>
          <div>
            <label class="block text-sm font-medium text-black-700">Описание</label>
            <textarea v-model="item.description" class="mt-1 block w-full px-4 py-2 border border-black-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-500"></textarea>
          </div>
        </div>
        <button @click="addAward" type="button"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
          + Добавить награду
        </button>
      </section>

      <!-- Чекбоксы -->
      <section class="flex items-center space-x-6">
        <label class="inline-flex items-center">
          <input v-model="form.havePersonalCar" type="checkbox"
                 class="rounded border-black-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
          <span class="ml-2">Есть личный автомобиль</span>
        </label>
        <label class="inline-flex items-center">
          <input v-model="form.isActive" type="checkbox"
                 class="rounded border-black-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
          <span class="ml-2">Активное резюме</span>
        </label>
      </section>

      <!-- Кнопка отправки -->
      <div>
        <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Создать резюме
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
import { useRouter } from 'vue-router'
import api from '../api.js'

const router = useRouter()

const form = reactive({
  firstName: '',
  lastName: '',
  birthDate: '',
  desiredSalary: null,
  gender: null,
  residenceCity: null,
  citizenship:[],
  specialization: [],
  workPlace: [],
  workSchedule:[],
  education: [],
  employmentType: [],
  awardAndAchievement: [],
  havePersonalCar: false,
  isActive: true
})

// Справочники
const countries = ref([])
const employmentTypes = ref([])
const workSchedule = ref([])
const genders = ref([])
const cities = ref([])
const specializations = ref([])
const workExperiences = ref([])
const educationLevels = ref([])

// Загрузка параметров
const loadParameters = async (endpoint, target) => {
  try {
    const { data } = await api.get(`/many_vacancy_parameters/${endpoint}`)
    target.value = data
  } catch (e) {
    console.error(`Ошибка при загрузке ${endpoint}:`, e)
  }
}

const loadGenders = async (endpoint, target) => {
  try {
    const { data } = await api.get(`/many_resume_parameters/${endpoint}`)
    target.value = data
  } catch (e) {
    console.error(`Ошибка при загрузке ${endpoint}:`, e)
  }
}

const loadCities = async (target) => {
  try {
    const { data } = await api.get(`/city/list`)
    target.value = data
  } catch (e) {
    console.error(`Ошибка при загрузке`, e)
  }
}

const loadCountries = async (target) => {
  try {
    const { data } = await api.get(`/country/list`)
    target.value = data
  } catch (e) {
    console.error(`Ошибка при загрузке`, e)
  }
}

onMounted(() => {
  loadCities(cities)
})

onMounted(() => {
  loadCountries(countries)
})

onMounted(() => {
  loadParameters('employment_type', employmentTypes)
  loadParameters('specializations', specializations)
  loadParameters('work_experience', workExperiences)
  loadParameters('work_schedule', workSchedule)
  loadParameters('education', educationLevels)
})

onMounted(() => {
  loadGenders('gender', genders)
})

// Вспомогательные методы для массивов
const addWorkPlace = () => {
  form.workPlace.push({ organizationName: '', professionName: '', startDate: '', endDate: '', workExperience: null })
}
const removeWorkPlace = (index) => {
  form.workPlace.splice(index, 1)
}

const addEducation = () => {
  form.education.push({ levelEducation: null, university: '', speciality: '', faculty: '', graduationYear: null })
}
const removeEducation = (index) => {
  form.education.splice(index, 1)
}

const addAward = () => {
  form.awardAndAchievement.push({ description: '' })
}
const removeAward = (index) => {
  form.awardAndAchievement.splice(index, 1)
}

// Отправка формы
const submit = async () => {
  try {
    const payload = JSON.parse(JSON.stringify(form))
    const { data } = await api.post('/resume/new', payload, {
      headers: { 'Content-Type': 'application/json' }
    })
    await router.push(`/resume/${data.id}`)
  } catch (e) {
    console.error(e.response?.data || e)
    alert('Ошибка при создании резюме')
  }
}
</script>