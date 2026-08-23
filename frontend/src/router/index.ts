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
    { path: '/settings', name: 'settings', component: Settings }
  ]
})

export default router