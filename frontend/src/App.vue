<template>
  <!-- If on login page, render full screen without sidebar/header -->
  <div v-if="$route.path === '/'" class="w-full">
    <router-view />
  </div>

  <!-- Authenticated pages layout with shared sidebar & shared header -->
  <div v-else class="min-h-screen bg-slate-50 flex">
    <!-- Shared Sidebar -->
    <aside class="w-[260px] bg-slate-900 text-white flex flex-col z-20 shadow-[4px_0_15px_rgba(0,0,0,0.05)] shrink-0">
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
        <button @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-500/10 hover:text-rose-600 rounded-lg font-medium text-[14px] transition-all duration-200 cursor-pointer">
          <LogOut class="w-5 h-5" />
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <!-- Main View Content Area with Shared Top Header -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      <!-- Shared Top Bar Header -->
      <header class="h-[76px] bg-white border-b border-slate-100 flex items-center justify-end px-8 z-30 relative shrink-0">
        <div class="flex items-center">
          <button @click="isProfileOpen = !isProfileOpen" class="flex items-center gap-3 hover:bg-slate-50 p-2 rounded-lg transition-colors focus:outline-none cursor-pointer">
            <img 
              :src="userAvatar || defaultAvatar" 
              @error="handleImageFallback"
              alt="Doctor Avatar" 
              class="w-10 h-10 rounded-full object-cover border-2 border-teal-500 shadow-2xs bg-slate-100" 
            />
            <div class="flex flex-col text-left">
              <span class="text-[14px] font-bold text-slate-800 leading-tight">{{ username }}</span>
            </div>
            <ChevronDown class="w-4 h-4 text-slate-400 ml-1 transition-transform duration-200" :class="{ 'rotate-180': isProfileOpen }" />
          </button>
          
          <!-- Click outside overlay -->
          <div v-if="isProfileOpen" @click="isProfileOpen = false" class="fixed inset-0 z-40"></div>
          
          <!-- Profile Dropdown -->
          <div v-if="isProfileOpen" class="absolute top-[70px] right-8 w-[280px] bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden" style="animation: fadeIn 0.2s ease-in-out;">
            <div class="p-5 border-b border-slate-100 relative">
              <button @click="isProfileOpen = false" class="absolute top-3 right-3 text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1.5 rounded-full transition-colors group cursor-pointer" title="Close">
                <X class="w-4 h-4 group-hover:scale-110 transition-transform" />
              </button>
              <div class="flex flex-col items-center text-center mt-2">
                <img 
                  :src="userAvatar || defaultAvatar" 
                  @error="handleImageFallback"
                  alt="Doctor Avatar" 
                  class="w-16 h-16 rounded-full object-cover border-2 border-teal-500 mb-3 shadow-sm bg-slate-100" 
                />
                <span class="text-[16px] font-bold text-slate-800 leading-tight">{{ username }}</span>
                <span class="text-[12px] text-slate-500 mt-1">{{ userEmail }}</span>
              </div>
            </div>
            <div class="p-2">
              <router-link to="/settings" class="flex items-center justify-between px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:bg-slate-50 hover:text-teal-600 rounded-xl transition-colors" @click="isProfileOpen = false">
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

      <!-- Routed Page Main Body -->
      <div class="flex-1 overflow-y-auto">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { api } from './services/api'
import defaultAvatar from '@/assets/profiledefault.svg'
import { LineChart, Users, FileText, Settings, LogOut, ChevronDown, X, User, Edit } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

// Reactive user profile refs
const username = ref('admin')
const userEmail = ref('admin@gmail.com')
const userAvatar = ref<string>('')
const isProfileOpen = ref(false)

const resolveServerUrl = (path: string) => {
  if (!path) return ''
  if (path.startsWith('http') || path.startsWith('data:') || path.startsWith('blob:')) {
    return path
  }
  const cleanPath = path.startsWith('/') ? path.slice(1) : path
  return `http://localhost/DMR_project/backend/public/${cleanPath}`
}

const handleImageFallback = (e: Event) => {
  const img = e.target as HTMLImageElement
  if (img && img.src) {
    if (img.src.includes('/DMR_project/backend/public/uploads/')) {
      img.src = img.src.replace('/DMR_project/backend/public/uploads/', '/uploads/')
    } else if (img.src.includes(':5184/uploads/')) {
      img.src = `http://localhost/uploads/${img.src.split('/uploads/')[1]}`
    } else {
      img.src = defaultAvatar
    }
  }
}

const loadUserProfile = async () => {
  const storedName = localStorage.getItem('username')
  const storedEmail = localStorage.getItem('userEmail')
  const storedAvatar = localStorage.getItem('userAvatar')
  
  if (storedName) username.value = storedName
  if (storedEmail) userEmail.value = storedEmail
  if (storedAvatar) userAvatar.value = resolveServerUrl(storedAvatar)

  // Fetch latest profile from backend API
  try {
    const res = await api.get('/api/user/profile')
    if (res && res.status === 'success' && res.data) {
      if (res.data.username) {
        username.value = res.data.username
        localStorage.setItem('username', res.data.username)
      }
      if (res.data.email) {
        userEmail.value = res.data.email
        localStorage.setItem('userEmail', res.data.email)
      }
      if (res.data.avatar) {
        const url = resolveServerUrl(res.data.avatar)
        userAvatar.value = url
        localStorage.setItem('userAvatar', url)
      }
    }
  } catch (err) {
    // Continue with localStorage
  }
}

const onProfileUpdated = () => {
  const storedName = localStorage.getItem('username')
  const storedEmail = localStorage.getItem('userEmail')
  const storedAvatar = localStorage.getItem('userAvatar')
  if (storedName) username.value = storedName
  if (storedEmail) userEmail.value = storedEmail
  userAvatar.value = storedAvatar ? resolveServerUrl(storedAvatar) : ''
}

onMounted(() => {
  loadUserProfile()
  window.addEventListener('profile-updated', onProfileUpdated)
})

onUnmounted(() => {
  window.removeEventListener('profile-updated', onProfileUpdated)
})

// Keep profile in sync on route changes
watch(() => route.path, () => {
  loadUserProfile()
  isProfileOpen.value = false
})

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
    localStorage.removeItem('userEmail')
    localStorage.removeItem('userRole')
    localStorage.removeItem('userAvatar')
    router.push('/')
  }
}
</script>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>