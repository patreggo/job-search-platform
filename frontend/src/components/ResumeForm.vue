<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-5xl mx-auto px-4">
      <!-- Заголовок -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Создание резюме</h1>
        <p class="text-gray-600">Заполните информацию о себе, чтобы создать привлекательное резюме</p>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Основная информация -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Основная информация
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                <input v-model="form.firstName" type="text" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Фамилия</label>
                <input v-model="form.lastName" type="text" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Дата рождения</label>
                <input v-model="form.birthDate" type="date"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-900 bg-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Желаемая зарплата (₽)</label>
                <input v-model.number="form.desiredSalary" type="number" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-gray-900 bg-white">
              </div>
            </div>
          </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Дополнительная информация
            </h2>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Пол</label>
                <select v-model="form.gender"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 bg-white">
                  <option value="">Выберите пол</option>
                  <option v-for="gender in genders" :key="gender.id" :value="gender.id">{{ gender.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Город проживания</label>
                <select v-model="form.residenceCity"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 bg-white">
                  <option value="">Выберите город</option>
                  <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Специализация</label>
                <select v-model="form.specialization"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 bg-white">
                  <option value="">Выберите специализацию</option>
                  <option v-for="spec in specializations" :key="spec.id" :value="spec.id">{{ spec.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Гражданство</label>
                <select v-model="form.citizenship" multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-h-[48px] text-gray-900 bg-white">
                  <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Удерживайте Ctrl для выбора нескольких</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Тип занятости</label>
                <select v-model="form.employmentType" multiple
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-h-[48px] text-gray-900 bg-white">
                  <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Удерживайте Ctrl для выбора нескольких</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">График работы</label>
                <select v-model="form.workSchedule"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 bg-white">
                  <option value="">Выберите график</option>
                  <option v-for="schedule in workSchedule" :key="schedule.id" :value="schedule.id">{{ schedule.name }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Опыт работы -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
              </svg>
              Опыт работы
            </h2>
          </div>

          <div class="p-6">
            <div v-if="form.workPlace.length === 0" class="text-center py-8 text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <p>Пока нет добавленных мест работы</p>
            </div>

            <div class="space-y-6">
              <div v-for="(item, index) in form.workPlace" :key="index"
                   class="border border-gray-200 rounded-lg p-6 relative bg-gray-50">
                <button @click="removeWorkPlace(index)" type="button"
                        class="absolute top-4 right-4 w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Организация</label>
                    <input v-model="item.organizationName" type="text"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Должность</label>
                    <input v-model="item.professionName" type="text"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Дата начала</label>
                    <input v-model="item.startDate" type="date"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Дата окончания</label>
                    <input v-model="item.endDate" type="date"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-gray-900 bg-white">
                  </div>
                </div>
              </div>
            </div>

            <button @click="addWorkPlace" type="button"
                    class="mt-6 w-full flex items-center justify-center px-6 py-3 border-2 border-dashed border-purple-300 text-purple-600 rounded-lg hover:border-purple-400 hover:bg-purple-50 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Добавить место работы
            </button>
          </div>
        </div>

        <!-- Образование -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
              </svg>
              Образование
            </h2>
          </div>

          <div class="p-6">
            <div v-if="form.education.length === 0" class="text-center py-8 text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
              <p>Пока нет добавленного образования</p>
            </div>

            <div class="space-y-6">
              <div v-for="(item, index) in form.education" :key="index"
                   class="border border-gray-200 rounded-lg p-6 relative bg-gray-50">
                <button @click="removeEducation(index)" type="button"
                        class="absolute top-4 right-4 w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Уровень образования</label>
                    <select v-model="item.levelEducation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-gray-900 bg-white">
                      <option value="">Выберите уровень</option>
                      <option v-for="edu in educationLevels" :key="edu.id" :value="edu.id">{{ edu.name }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Университет</label>
                    <input v-model="item.university" type="text"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Специальность</label>
                    <input v-model="item.speciality" type="text"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Факультет</label>
                    <input v-model="item.faculty" type="text"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-gray-900 bg-white">
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Год окончания</label>
                    <input v-model.number="item.graduationYear" type="number" min="1950" :max="new Date().getFullYear() + 10"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-gray-900 bg-white">
                  </div>
                </div>
              </div>
            </div>

            <button @click="addEducation" type="button"
                    class="mt-6 w-full flex items-center justify-center px-6 py-3 border-2 border-dashed border-orange-300 text-orange-600 rounded-lg hover:border-orange-400 hover:bg-orange-50 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Добавить образование
            </button>
          </div>
        </div>

        <!-- Награды и достижения -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
              </svg>
              Награды и достижения
            </h2>
          </div>

          <div class="p-6">
            <div v-if="form.awardAndAchievement.length === 0" class="text-center py-8 text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
              </svg>
              <p>Пока нет добавленных наград и достижений</p>
            </div>

            <div class="space-y-4">
              <div v-for="(item, index) in form.awardAndAchievement" :key="index"
                   class="border border-gray-200 rounded-lg p-6 relative bg-gray-50">
                <button @click="removeAward(index)" type="button"
                        class="absolute top-4 right-4 w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors flex items-center justify-center">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Описание награды или достижения</label>
                  <textarea v-model="item.description" rows="3"
                            placeholder="Опишите ваше достижение..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors resize-none text-gray-900 bg-white placeholder-gray-500"></textarea>
                </div>
              </div>
            </div>

            <button @click="addAward" type="button"
                    class="mt-6 w-full flex items-center justify-center px-6 py-3 border-2 border-dashed border-yellow-300 text-yellow-600 rounded-lg hover:border-yellow-400 hover:bg-yellow-50 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Добавить награду
            </button>
          </div>
        </div>

        <!-- Дополнительные параметры -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Дополнительные параметры
            </h2>
          </div>

          <div class="p-6">
            <div class="space-y-4">
              <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <input v-model="form.havePersonalCar" type="checkbox" id="car"
                       class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label for="car" class="ml-3 flex items-center cursor-pointer">
                  <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  <span class="text-sm font-medium text-gray-700">Есть личный автомобиль</span>
                </label>
              </div>

              <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <input v-model="form.isActive" type="checkbox" id="active"
                       class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label for="active" class="ml-3 flex items-center cursor-pointer">
                  <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                    <span class="text-sm font-medium text-gray-700">Есть личный автомобиль</span>
                </label>
              </div>
              <div>
                <button type="submit"
                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Создать резюме
                </button>
              </div>
            </div>
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