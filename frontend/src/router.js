import { createRouter, createWebHistory } from 'vue-router'
import VacanciesList from './components/VacanciesList.vue'
import VacancyForm from './components/VacancyForm.vue'
import SingleVacancy from './components/SingleVacancy.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import { isAuthenticated } from './auth'
import Profile from "./components/Profile.vue";
import Home from './components/Home.vue'

const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    {
        path: '/',
        component: VacanciesList,
        meta: { requiresAuth: true }
    },
    {
        path: '/new',
        component: VacancyForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/vacancy/:id',
        component: SingleVacancy,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile',
        component: Profile,
        meta: {requiresAuth: true}
    },
    {
        path: '/vacancies',
        component: VacanciesList,
        meta: {requiresAuth: true}
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth && !await isAuthenticated()) {
        next('/login')
    } else {
        next()
    }
})

export default router
