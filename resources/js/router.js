import { createRouter, createWebHistory } from 'vue-router'

import LoginPage from '../js/pages/LoginPage.vue'
import RegisterPage from '../js/pages/RegisterPage.vue'
import ProfilePage from '../js/pages/ProfilePage.vue'
import Main from '../js/pages/Main.vue'
import UniversityPage from '../js/pages/UniversityPage.vue'
import AdminDashboard from '../js/pages/AdminDashboard.vue'
import FacultyPage from './pages/FacultyPage.vue'
import CalendarPage from './Pages/CalendarPage.vue'


const routes = [
  { path: '/', component: Main },
  { path: '/login', component: LoginPage },
  { path: '/register', component: RegisterPage },
  { path: '/profile', component: ProfilePage },
  { path: '/university/:id', component: UniversityPage },
  { path: '/admin', component: AdminDashboard },
  { path: '/faculty/:id', component: FacultyPage },
  { path: '/calendar', component: CalendarPage}
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router