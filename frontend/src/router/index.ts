import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import Dashboard from '../views/DashboardView.vue'
import ManagePatients from '../views/ManagePatientsView.vue'
import HealthRecords from '../views/HealthRecordsView.vue'
import Reports from '../views/ReportsView.vue'
import ManageUsers from '../views/ManageUsersView.vue'
import Settings from '../views/SettingsView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'login', component: Login },
    { path: '/dashboard', name: 'dashboard', component: Dashboard },
    { path: '/patients', name: 'patients', component: ManagePatients },
    { path: '/health-records', name: 'health-records', component: HealthRecords },
    { path: '/reports', name: 'reports', component: Reports },
    { path: '/users', name: 'users', component: ManageUsers },
    { path: '/settings', name: 'settings', component: Settings },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' }
  ]
})

// 🛡️ Global Auth & RBAC Navigation Guard
router.beforeEach((to, from, next) => {
  const publicPages = ['/']
  const authRequired = !publicPages.includes(to.path)
  const loggedIn = localStorage.getItem('userEmail')

  // If page requires auth and user is not logged in, redirect to Login
  if (authRequired && !loggedIn) {
    return next('/')
  }

  // If user is already logged in and attempts to visit login page, redirect to Dashboard
  if (to.path === '/' && loggedIn) {
    return next('/dashboard')
  }

  // If user attempts to visit /users but is not a superadmin, redirect to Dashboard
  if (to.path === '/users') {
    const role = localStorage.getItem('userRole') || 'doctor'
    if (role !== 'superadmin') {
      return next('/dashboard')
    }
  }

  next()
})

export default router