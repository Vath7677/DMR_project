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
        <router-link to="/dashboard" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <LineChart class="w-5 h-5" />
          <span>Dashboard</span>
        </router-link>
        <router-link to="/patients" class="flex items-center gap-3 px-4 py-3 bg-teal-600 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-[0_0_20px_rgba(13,148,136,0.2)]">
          <Users class="w-5 h-5" />
          <span>Manage Patients</span>
        </router-link>
        <router-link to="/health-records" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <FileText class="w-5 h-5" />
          <span>Health Records</span>
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
      <!-- Header -->
      <header class="h-[76px] bg-white border-b border-slate-100 flex items-center justify-end px-8 z-0">
        <div class="flex items-center">
          <div class="flex items-center gap-3">
            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=150&auto=format&fit=crop&q=80" alt="Doctor Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-teal-500" />
            <div class="flex flex-col">
              <span class="text-[14px] font-bold text-slate-800 leading-tight">Dr. Sarah Jenkins</span>
              <span class="text-[12px] text-slate-500 leading-tight">Chief Medical Officer</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="flex-1 p-8 overflow-y-auto bg-slate-50/60">
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-800">Patients</h1>
          <p class="text-slate-500 mt-1">Manage your patients here.</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <!-- Placeholder for patients table -->
            <p class="text-slate-500">The patient list will be displayed here.</p>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'
import { Activity, LayoutDashboard, Users, FileText, LogOut, Search, HeartPulse, LineChart, CalendarCheck, Apple } from 'lucide-vue-next'

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
