import { createRouter, createWebHistory } from 'vue-router'

import LoginPage from '../js/Pages/LoginPage.vue'
import RegisterPage from '../js/Pages/RegisterPage.vue'
import ProfilePage from '../js/Pages/ProfilePage.vue'
import Main from '../js/Pages/Main.vue'
import UniversityPage from '../js/Pages/UniversityPage.vue'
import AdminDashboard from '../js/Pages/AdminDashboard.vue'
import FacultyPage from './Pages/FacultyPage.vue'
import CalendarPage from './Pages/CalendarPage.vue'
import FacultyList from './Pages/FacultyList.vue'
import QuizPage from './Pages/QuizPage.vue'
import NotFound from './Pages/NotFound.vue'


const routes = [
  { path: '/', component: Main },
  { path: '/login', component: LoginPage },
  { path: '/register', component: RegisterPage },
  { path: '/profile', component: ProfilePage },
  { path: '/university/:id', component: UniversityPage },
  { path: '/admin', component: AdminDashboard },
  { path: '/faculty/:id', component: FacultyPage },
  { path: '/calendar', component: CalendarPage},
  { path: '/field-faculties', component: FacultyList},
  { path: '/quiz', component: QuizPage},
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }


]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router