import { createRouter, createWebHistory } from 'vue-router'
import VacanciesList from './VacanciesList.vue'
import VacancyForm from './VacancyForm.vue'
import SingleVacancy from './SingleVacancy.vue'

const routes = [
    { path: '/', component: VacanciesList },
    { path: '/new', component: VacancyForm },
    { path: '/vacancy/:id', component: SingleVacancy },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
