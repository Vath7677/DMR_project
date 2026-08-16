<template>
  <div class="min-h-screen bg-slate-50 flex">
    
    <!-- Sidebar -->
    <aside class="w-[260px] bg-slate-900 text-white flex flex-col z-10 shadow-[4px_0_15px_rgba(0,0,0,0.05)]">
      <div class="p-6 flex items-center gap-3 border-b border-white/10">
        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center shadow-lg shadow-teal-900/50">
           <HeartPulse class="w-6 h-6 text-white" />
        </div>
        <div class="flex flex-col">
          <span class="font-heading font-bold text-xl leading-tight text-white tracking-wide">Patient</span>
          <span class="text-[11px] text-slate-400 tracking-[0.05em] uppercase font-semibold">Management</span>
        </div>
      </div>
      
      <nav class="p-5 flex flex-col gap-1.5 flex-1">
        <router-link to="/dashboard" class="flex items-center gap-3 px-4 py-3 bg-teal-600 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-[0_0_20px_rgba(13,148,136,0.2)]">
          <LineChart class="w-5 h-5" />
          <span>Dashboard</span>
        </router-link>
        <router-link to="/patients" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <Users class="w-5 h-5" />
          <span>Manage Patients</span>
        </router-link>
        <router-link to="/health-records" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <FileText class="w-5 h-5" />
          <span>Health Records</span>
        </router-link>
        <router-link to="/settings" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <Settings class="w-5 h-5" />
          <span>Settings</span>
        </router-link>
      </nav>

      <!-- logout button -->
      <div class="p-5 border-t border-white/10 mt-auto">
        <button @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-500/10 hover:text-rose-600 rounded-lg font-medium text-[14px] transition-all duration-200">
          <LogOut class="w-5 h-5" />
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      <header class="h-[76px] bg-white border-b border-slate-100 flex items-center justify-end px-8 z-50 relative">
        <div class="flex items-center">
          <button @click="isProfileOpen = !isProfileOpen" class="flex items-center gap-3 hover:bg-slate-50 p-2 rounded-lg transition-colors focus:outline-none">
            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=150&auto=format&fit=crop&q=80" alt="Doctor Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-teal-500" />
            <div class="flex flex-col text-left">
              <span class="text-[14px] font-bold text-slate-800 leading-tight">Dr. Sarah Jenkins</span>
              <span class="text-[12px] text-slate-500 leading-tight">Chief Medical Officer</span>
            </div>
            <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
          </button>
          
          <!-- Click outside overlay -->
          <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-40"></div>
          
          <!-- Profile Dropdown -->
          <div v-if="isProfileOpen" class="absolute top-[70px] right-8 w-[280px] bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden" style="animation: fadeIn 0.2s ease-in-out;">
            <div class="p-5 border-b border-slate-100 relative">
              <button @click="isProfileOpen = false" class="absolute top-3 right-3 text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1.5 rounded-full transition-colors group" title="Close">
                <X class="w-4 h-4 group-hover:scale-110 transition-transform" />
              </button>
              <div class="flex flex-col items-center text-center mt-2">
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=150&auto=format&fit=crop&q=80" alt="Doctor Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-teal-500 mb-3 shadow-sm" />
                <span class="text-[16px] font-bold text-slate-800 leading-tight">Dr. Sarah Jenkins</span>
                <span class="text-[13px] text-teal-600 font-medium leading-tight mt-1">Chief Medical Officer</span>
                <span class="text-[12px] text-slate-500 mt-1">sarah.jenkins@dmr.hospital</span>
              </div>
            </div>
            <div class="p-2">
              <router-link to="/settings" class="flex items-center justify-between px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:bg-slate-50 hover:text-teal-600 rounded-xl transition-colors">
                <div class="flex items-center gap-3">
                  <User class="w-4 h-4" />
                  <span>Edit Profile</span>
                </div>
                <Edit class="w-4 h-4" />
              </router-link>
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="flex-1 px-8 py-8 overflow-y-auto bg-slate-50/60">
        
        <div class="mb-6">
          <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Admin Dashboard / Patient Management System</h1>
          <p class="text-[14px] text-slate-500 mt-1 font-medium">Overview of healthcare operations, patient volume, and recent activities.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
          <!-- Card 1 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-teal-50 text-teal-600 rounded-xl mr-4 shrink-0">
              <Users class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Total Patients</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">6</p>
              <p class="text-[12px] font-semibold text-teal-600 mt-1.5 flex items-center">
                <TrendingUp class="w-[14px] h-[14px] mr-1" />
                +12% this month
              </p>
            </div>
          </div>
          
          <!-- Card 2 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-green-50 text-green-600 rounded-xl mr-4 shrink-0">
              <HeartPulse class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Active Care Plans</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">2</p>
              <p class="text-[12px] font-medium text-slate-400 mt-1.5">Ongoing Regimens</p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-yellow-50 text-yellow-600 rounded-xl mr-4 shrink-0">
              <FilePlus class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Health Records Logged</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">5</p>
              <p class="text-[12px] font-semibold text-green-600 mt-1.5 flex items-center">
                <Check class="w-[14px] h-[14px] mr-1" />
                All synced
              </p>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl mr-4 shrink-0">
              <Calendar class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Upcoming Appointments</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">3</p>
              <p class="text-[12px] font-medium text-blue-600 mt-1.5">Next 7 Days</p>
            </div>
          </div>
        </div>

        <!-- Charts and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
          
          <!-- Patient Visits Chart -->
          <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="flex justify-between items-center pb-6 border-b border-slate-100 mb-6">
              <h3 class="text-[16px] font-bold text-slate-800">Patient Visits - Last 7 Days</h3>
              <span class="px-3 py-1 bg-teal-50 text-teal-600 border border-teal-100 rounded-full text-[12px] font-medium tracking-wide">Live Data</span>
            </div>
            <!-- Chart.js implementation -->
            <div class="h-[280px] w-full mt-2 relative">
              <Bar :data="chartData" :options="chartOptions" />
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-[16px] font-bold text-slate-800">Recent Activity</h3>
              <a href="#" class="text-[13px] font-medium text-teal-600 hover:text-teal-700">View All</a>
            </div>
            
            <div class="space-y-5">
              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <UserPlus class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">New Patient Registered</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">Just now</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">chan sovannreach (P-1006) was added to the system</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <UserPlus class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">New Patient Registered</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">Just now</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">David Miller (P-1006) was added to the system</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <Calendar class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Appointment Scheduled</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">4 hours ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Cardiology appointment set for Aug 16 with Dr. Miller</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <FileText class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Lab Result Attached</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">2 hours ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Thyroid panel results uploaded for Amara Okonkwo</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <HeartPulse class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Vitals Updated</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">45 mins ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Marcus Aurelius BP recorded: 135/88 mmHg</p>
                </div>
              </div>
            </div>
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
import { Activity, LayoutDashboard, Users, FileText, LogOut, TrendingUp, Stethoscope, Search, HeartPulse, LineChart, CalendarCheck, Apple, FilePlus, Check, Calendar, UserPlus, Settings, ChevronDown, X, User, Edit } from 'lucide-vue-next'

import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const chartData = {
  labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  datasets: [
    {
      label: 'Patient Visits',
      backgroundColor: '#0d9488', 
      borderRadius: 6,
      borderSkipped: 'bottom' as const,
      barPercentage: 0.6,
      data: [12, 19, 15, 22, 28, 14, 8]
    }
  ]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: '#1e293b', // slate-800
      titleFont: { size: 13, family: 'Inter' },
      bodyFont: { size: 13, family: 'Inter' },
      padding: 10,
      cornerRadius: 6,
      displayColors: true,
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 30,
      ticks: {
        stepSize: 5,
        color: '#64748b', // slate-500
        font: {
          size: 13,
          family: 'Inter',
        },
        padding: 12
      },
      grid: {
        color: '#f1f5f9', // slate-100
        drawTicks: false,
      },
      border: {
        display: false
      }
    },
    x: {
      ticks: {
        color: '#64748b', // slate-500
        font: {
          size: 13,
          family: 'Inter',
        },
        padding: 12
      },
      grid: {
        display: false,
        drawTicks: false,
      },
      border: {
        display: true,
        color: '#e2e8f0' // slate-200
      }
    }
  }
}

const router = useRouter()
const username = ref('User') 
const isProfileOpen = ref(false)

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