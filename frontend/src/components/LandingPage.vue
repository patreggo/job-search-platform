<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-blue-100/10"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
        <div class="text-center">
          <h1 class="text-5xl md:text-7xl font-bold bg-gradient-to-r from-blue-600 to-blue-600 bg-clip-text text-transparent mb-6">
            Найдите свою <br />
            <span class="relative">
              идеальную работу
              <div class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-500 rounded-full"></div>
            </span>
          </h1>
          <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
            Современная платформа, которая соединяет талантливых специалистов с лучшими работодателями.
            Более 10,00 вакансий и растущее сообщество профессионалов.
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
            <button
                class="group bg-gradient-to-r from-blue-600 to-blue-600 text-white px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center"
            >
              Найти работу
              <ArrowRightIcon class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </button>
            <button
                class="group bg-white text-gray-700 px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-gray-200 flex items-center"
            >
              Разместить вакансию
              <BriefcaseIcon class="ml-2 w-5 h-5 group-hover:scale-110 transition-transform" />
            </button>
          </div>

          <!-- Search Bar -->
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div v-for="(stat, index) in stats" :key="index" class="text-center group">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-50 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300"
            >
              <component :is="stat.icon" class="w-8 h-8 text-blue-600" />
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2">{{ stat.number }}</div>
            <div class="text-gray-600">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">
            Почему выбирают нашу платформу?
          </h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Мы предоставляем современные инструменты для эффективного поиска работы и найма
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- For Job Seekers -->
          <div class="space-y-8">
            <div class="text-center lg:text-left">
              <h3 class="text-2xl font-bold text-gray-900 mb-6">Для соискателей</h3>
            </div>
            <div v-for="(feature, index) in jobSeekerFeatures" :key="index" class="flex items-start space-x-4 group">
              <div class="bg-green-100 p-2 rounded-lg group-hover:bg-green-200 transition-colors">
                <CheckCircleIcon class="w-6 h-6 text-green-600" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 mb-1">{{ feature.title }}</h4>
                <p class="text-gray-600">{{ feature.desc }}</p>
              </div>
            </div>
          </div>

          <!-- For Employers -->
          <div class="space-y-8">
            <div class="text-center lg:text-left">
              <h3 class="text-2xl font-bold text-gray-900 mb-6">Для работодателей</h3>
            </div>
            <div v-for="(feature, index) in employerFeatures" :key="index" class="flex items-start space-x-4 group">
              <div class="bg-blue-100 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                <CheckCircleIcon class="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 mb-1">{{ feature.title }}</h4>
                <p class="text-gray-600">{{ feature.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular Categories -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">
            Популярные категории
          </h2>
          <p class="text-xl text-gray-600">
            Найдите работу в самых востребованных сферах
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="(category, index) in categories" :key="index" class="group cursor-pointer">
            <div
                class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
            >
              <div
                  :class="`w-12 h-12 ${category.color} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`"
              >
                <BriefcaseIcon class="w-6 h-6 text-white" />
              </div>
              <h3 class="font-semibold text-gray-900 mb-2">{{ category.name }}</h3>
              <p class="text-gray-600 text-sm">{{ category.count }} вакансий</p>
              <div class="flex items-center mt-3 text-blue-600 group-hover:text-blue-700">
                <span class="text-sm font-medium">Смотреть все</span>
                <ChevronRightIcon class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-blue-600">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
          Готовы начать свой карьерный путь?
        </h2>
        <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
          Присоединяйтесь к тысячам специалистов, которые уже нашли работу мечты через нашу платформу
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button
              class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
          >
            Зарегистрироваться как соискатель
          </button>
          <button
              class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300"
          >
            Зарегистрироваться как работодатель
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-2xl font-bold mb-4">JobPlatform</h3>
            <p class="text-gray-400">
              Современная платформа для поиска работы и найма талантов
            </p>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Для соискателей</h4>
            <ul class="space-y-2 text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Поиск работы</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Создать резюме</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Карьерные советы</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Для работодателей</h4>
            <ul class="space-y-2 text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Разместить вакансию</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Поиск кандидатов</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Тарифы</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Поддержка</h4>
            <ul class="space-y-2 text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Помощь</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Контакты</a></li>
              <li><a href="#" class="hover:text-white transition-colors">О нас</a></li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
          <p>© 2024 JobPlatform. Все права защищены.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import {
  MagnifyingGlassIcon,
  BriefcaseIcon,
  UserGroupIcon,
  ArrowTrendingUpIcon,
  StarIcon,
  CheckCircleIcon,
  ChevronRightIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline';

// Define stats data
const stats = [
  { number: '10,000+', label: 'Активных вакансий', icon: BriefcaseIcon },
  { number: '50,000+', label: 'Соискателей', icon: UserGroupIcon },
  { number: '2,500+', label: 'Компаний', icon: ArrowTrendingUpIcon },
  { number: '95%', label: 'Успешных найма', icon: StarIcon }
];

// Define job seeker features
const jobSeekerFeatures = [
  { title: 'Умный поиск', desc: 'Находите вакансии по навыкам, зарплате и местоположению' },
  { title: 'Конструктор резюме', desc: 'Создавайте профессиональные резюме за несколько минут' },
  { title: 'Быстрые отклики', desc: 'Откликайтесь на вакансии в один клик' },
  { title: 'Уведомления', desc: 'Получайте уведомления о новых подходящих вакансиях' }
];

// Define employer features
const employerFeatures = [
  { title: 'База кандидатов', desc: 'Доступ к базе из 50,000+ активных кандидатов' },
  { title: 'Фильтры поиска', desc: 'Находите идеальных кандидатов по 20+ параметрам' },
  { title: 'Управление вакансиями', desc: 'Удобная панель для управления всеми вакансиями' },
  { title: 'Аналитика', desc: 'Подробная статистика по откликам и просмотрам' }
];

// Define categories data
const categories = [
  { name: 'IT и разработка', count: '2,500+', color: 'bg-blue-500' },
  { name: 'Маркетинг', count: '1,200+', color: 'bg-green-500' },
  { name: 'Дизайн', count: '800+', color: 'bg-purple-500' },
  { name: 'Продажи', count: '1,500+', color: 'bg-orange-500' },
  { name: 'Финансы', count: '600+', color: 'bg-indigo-500' },
  { name: 'HR', count: '400+', color: 'bg-pink-500' },
  { name: 'Логистика', count: '700+', color: 'bg-teal-500' },
  { name: 'Медицина', count: '900+', color: 'bg-red-500' }
];
</script>