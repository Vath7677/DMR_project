<template>
  <div class="min-h-screen bg-slate-50 flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-100 flex flex-col transition-all duration-300 z-10 shadow-sm">
      <div class="h-16 flex items-center px-8 font-bold text-xl tracking-wide text-slate-800">
        <Activity class="w-7 h-7 text-blue-600 mr-3" />
        DMR Portal
      </div>
      
      <nav class="flex-1 py-6 px-4 space-y-2 mt-2">
        <a href="#" class="flex items-center px-4 py-3 bg-blue-50 text-blue-600 rounded-xl font-semibold transition-colors">
          <LayoutDashboard class="w-5 h-5 mr-4" />
          Dashboard
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-medium transition-colors">
          <Users class="w-5 h-5 mr-4" />
          Patients
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-medium transition-colors">
          <Calendar class="w-5 h-5 mr-4" />
          Appointments
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-medium transition-colors">
          <Calendar class="w-5 h-5 mr-4" />
          Appointments
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-medium transition-colors">
          <Calendar class="w-5 h-5 mr-4" />
          Appointments
        </a>
        <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-medium transition-colors">
          <Calendar class="w-5 h-5 mr-4" />
          Appointments
        </a>
      </nav>

      <!-- logout button -->
      <div class="p-6 mt-auto">
        <button @click="handleLogout" class="flex items-center justify-center px-4 py-3 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl font-semibold transition-colors w-full border border-transparent hover:border-red-100">
          <LogOut class="w-5 h-5 mr-3" />
          Log out
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      <!-- Header រង្វង់មូលពណ៌ខៀវដិត -->
      <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-8 z-0">
        <div>
          <!-- Blank left side of header, as requested -->
        </div>
        <div class="flex items-center space-x-6">
          
          <div class="flex items-center space-x-3">
            <div class="text-sm text-slate-500 text-right">
              Welcome, <span class="font-bold text-slate-800">{{ username }}</span>
            </div>

            <!-- Dynamic Blue Circle for Initial -->
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold shadow-sm shadow-blue-200 text-lg">
              {{ userInitial }}
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="flex-1 p-8 overflow-y-auto bg-slate-50/60">
        
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-800">Overview</h1>
          <p class="text-slate-500 mt-1">Welcome back, Dr. {{ username }}. Here is today's summary.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Card 1 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
              <p class="text-sm font-medium text-slate-500">Total Patients</p>
              <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <Users class="w-5 h-5" />
              </div>
            </div>
            <div>
              <p class="text-4xl font-bold text-slate-800 tracking-tight">2,405</p>
              <p class="text-sm font-semibold text-green-500 mt-2 flex items-center">
                <TrendingUp class="w-4 h-4 mr-1" />
                +12.5% from last month
              </p>
            </div>
          </div>
          
          <!-- Card 2 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
              <p class="text-sm font-medium text-slate-500">Appointments Today</p>
              <div class="p-2.5 bg-green-50 text-green-600 rounded-xl">
                <Calendar class="w-5 h-5" />
              </div>
            </div>
            <div>
              <p class="text-4xl font-bold text-slate-800 tracking-tight">42</p>
              <p class="text-sm font-medium text-slate-400 mt-2">Next appt in 15 mins</p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="bg-slate-900 rounded-2xl shadow-sm border border-slate-800 p-6 flex flex-col justify-between hover:shadow-lg transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-xl group-hover:bg-white/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
              <p class="text-sm font-medium text-slate-400">Active Doctors</p>
              <div class="p-2.5 bg-white/10 text-white rounded-xl border border-white/10">
                <Stethoscope class="w-5 h-5" />
              </div>
            </div>
            <div class="relative z-10 flex items-end justify-between">
              <div>
                <p class="text-4xl font-bold text-white tracking-tight">18</p>
                <div class="flex items-center mt-3 -space-x-2">
                  <div class="w-8 h-8 rounded-full bg-slate-300 border-2 border-slate-900"></div>
                  <div class="w-8 h-8 rounded-full bg-slate-400 border-2 border-slate-900"></div>
                  <div class="w-8 h-8 rounded-full bg-slate-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold text-white">+15</div>
                </div>
              </div>
              <a href="#" class="text-sm font-medium text-white/80 hover:text-white pb-1">View all</a>
            </div>
          </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
          <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-slate-800">Recent Appointments</h3>
            <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700">See all records</a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/50">
                  <th class="px-8 py-4 font-semibold text-xs text-slate-400 uppercase tracking-wider">Patient Name</th>
                  <th class="px-8 py-4 font-semibold text-xs text-slate-400 uppercase tracking-wider">Diagnosis</th>
                  <th class="px-8 py-4 font-semibold text-xs text-slate-400 uppercase tracking-wider">Status</th>
                  <th class="px-8 py-4 font-semibold text-xs text-slate-400 uppercase tracking-wider text-right">Date</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-8 py-5">
                    <div class="flex items-center">
                      <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm mr-4">SC</div>
                      <div>
                        <p class="font-bold text-slate-800 text-sm">Sarah Connor</p>
                        <p class="text-xs text-slate-400">ID: #PT-8472</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-8 py-5 text-sm text-slate-500 font-medium">Routine Checkup</td>
                  <td class="px-8 py-5"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Completed</span></td>
                  <td class="px-8 py-5 text-sm text-slate-500 text-right">Today, 09:30 AM</td>
                </tr>
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-8 py-5">
                    <div class="flex items-center">
                      <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm mr-4">MW</div>
                      <div>
                        <p class="font-bold text-slate-800 text-sm">Michael Wright</p>
                        <p class="text-xs text-slate-400">ID: #PT-3921</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-8 py-5 text-sm text-slate-500 font-medium">Cardiology Consult</td>
                  <td class="px-8 py-5"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Waiting</span></td>
                  <td class="px-8 py-5 text-sm text-slate-500 text-right">Today, 11:15 AM</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'
import { Activity, LayoutDashboard, Users, Calendar, LogOut, TrendingUp, Stethoscope } from 'lucide-vue-next'

const router = useRouter()
const username = ref('User') 

const userInitial = computed(() => {
  return username.value ? username.value.charAt(0).toUpperCase() : 'U'
})

onMounted(() => {
  const storedName = localStorage.getItem('username')
  if (storedName) {
    username.value = storedName
  }
})

const handleLogout = async () => {
  try {
    await api.post('/api/auth/logout', {});
  } catch (error: any) {
    console.error('Logout error:', error);
  } finally {
    localStorage.removeItem('username');
    router.push('/');
  }
}
</script>