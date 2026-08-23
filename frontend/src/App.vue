<template>
  <!-- If on login page, render full screen without sidebar -->
  <div v-if="$route.path === '/'" class="w-full">
    <router-view />
  </div>

  <!-- Authenticated pages layout with shared sidebar -->
  <div v-else class="min-h-screen bg-slate-50 flex">
    <!-- Shared Sidebar -->
    <aside class="w-[260px] bg-slate-900 text-white flex flex-col z-10 shadow-[4px_0_15px_rgba(0,0,0,0.05)] shrink-0">
      <div class="p-6 flex items-center gap-3 border-b border-white/10">
        <div class="w-10 h-10 flex items-center justify-center shrink-0">
          <img src="@/assets/hospital-logo.png" alt="Hospital Logo" class="w-10 h-10 object-contain drop-shadow-md" />
        </div>
        <div class="flex flex-col">
          <span class="font-heading font-bold text-xl leading-tight text-white tracking-wide">Patient</span>
          <span class="text-[11px] text-slate-400 tracking-[0.05em] uppercase font-semibold">Management</span>
        </div>
      </div>
      
      <nav class="p-5 flex flex-col gap-1.5 flex-1">
        <router-link 
          v-for="item in menuItems" 
          :key="item.path" 
          :to="item.path" 
          class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all duration-200"
          :class="[
            $route.path === item.path 
              ? 'bg-teal-600 text-white font-semibold shadow-[0_0_20px_rgba(13,148,136,0.2)]' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium'
          ]"
        >
          <component :is="item.icon" class="w-5 h-5" />
          <span>{{ item.name }}</span>
        </router-link>
      </nav>

      <!-- Logout button -->
      <div class="p-5 border-t border-white/10 mt-auto">
        <button @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-500/10 hover:text-rose-600 rounded-lg font-medium text-[14px] transition-all duration-200">
          <LogOut class="w-5 h-5" />
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <!-- Main View Content Area -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from './services/api'
import { HeartPulse, LineChart, Users, FileText, Settings, LogOut } from 'lucide-vue-next'

const router = useRouter()

const menuItems = ref([
  { name: 'Dashboard', path: '/dashboard', icon: LineChart },
  { name: 'Manage Patients', path: '/patients', icon: Users },
  { name: 'Health Records', path: '/health-records', icon: FileText },
  { name: 'Settings', path: '/settings', icon: Settings }
])

const handleLogout = async () => {
  try {
    await api.post('/api/auth/logout', {})
  } catch (error: any) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('username')
    router.push('/')
  }
}
</script>