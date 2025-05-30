import { createRouter, createWebHistory } from 'vue-router'
import VacanciesList from './components/VacanciesList.vue'
import VacancyForm from './components/VacancyForm.vue'
import SingleVacancy from './components/SingleVacancy.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import { isAuthenticated } from './auth'
import Profile from "./components/Profile.vue";
import ResumeForm from "./components/ResumeForm.vue";
import SingleResume from "./components/SingleResume.vue";
import VacancyResponseForm from "./components/VacancyResponseForm.vue";
import ResumeList from "./components/ResumeList.vue";
import VacancyResponeList from "./components/VacancyResponeList.vue";

const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    {
        path: '/',
        component: VacanciesList,
        meta: { requiresAuth: true }
    },
    {
        path: '/vacancy/new',
        component: VacancyForm,
        meta: { requiresAuth: true, requiresRole: 'employer' } // Только для работодателей
    },
    {
        path: '/vacancy/:id',
        component: SingleVacancy,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/vacancies',
        component: VacanciesList,
        meta: { requiresAuth: true }
    },
    {
        path: '/resume/new',
        component: ResumeForm,
        meta: { requiresAuth: true, requiresRole: 'USER_ROLE' } // Только для соискателей
    },
    {
        path: '/resume/:id',
        component: SingleResume,
        meta: { requiresAuth: true }
    },
    {
        path: '/vacancy_response/new',
        component: VacancyResponseForm,
    },
    {
        path: '/resume/user/personal',
        component: ResumeList
    },
    {
        path: '/vacancy_response/user/personal',
        component: VacancyResponeList
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

// Middleware для проверки роли
router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth && !await isAuthenticated()) {
        next('/login')
    } else if (to.meta.requiresRole) {
        const user = JSON.parse(localStorage.getItem('user'))
        if (!user || user.role.name !== to.meta.requiresRole) {
            next({ name: 'Unauthorized' }) // Перенаправить на страницу ошибки
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router