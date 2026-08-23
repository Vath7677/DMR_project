<template>
  <div class="flex-1 flex flex-col h-full overflow-hidden">
    <!-- Header -->
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
              <router-link to="/settings" class="flex items-center justify-between px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:bg-slate-50 hover:text-teal-600 rounded-xl transition-colors" @click="isProfileOpen = false; activeTab = 'profile'">
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
        
        <div class="mb-6 flex justify-between items-end">
          <div>
            <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Settings</h1>
            <p class="text-[14px] text-slate-500 mt-1 font-medium">Manage your personal profile, notifications, and security preferences.</p>
          </div>
          <button class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-lg font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm">
            <Save class="w-4 h-4" />
            <span>Save Changes</span>
          </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
          
          <!-- Settings Navigation -->
          <div class="w-full md:w-[240px] border-r border-slate-100 bg-slate-50/50 p-6 flex flex-col gap-2">
            <button @click="activeTab = 'profile'" :class="['flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-medium transition-all duration-200 w-full text-left', activeTab === 'profile' ? 'bg-white text-teal-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:bg-slate-100/80']">
              <User class="w-4 h-4" />
              <span>Profile Details</span>
            </button>
            <button @click="activeTab = 'notifications'" :class="['flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-medium transition-all duration-200 w-full text-left', activeTab === 'notifications' ? 'bg-white text-teal-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:bg-slate-100/80']">
              <Bell class="w-4 h-4" />
              <span>Notifications</span>
            </button>
            <button @click="activeTab = 'security'" :class="['flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-medium transition-all duration-200 w-full text-left', activeTab === 'security' ? 'bg-white text-teal-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:bg-slate-100/80']">
              <ShieldCheck class="w-4 h-4" />
              <span>Security</span>
            </button>
          </div>

          <!-- Settings Content Area -->
          <div class="flex-1 p-8">
            
            <!-- Profile Tab -->
            <div v-if="activeTab === 'profile'" class="animate-fade-in">
              <h2 class="text-[18px] font-bold text-slate-800 mb-6">Profile Information</h2>
              
              <div class="flex items-center gap-6 mb-8 pb-8 border-b border-slate-100">
                <div class="relative">
                  <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=150&auto=format&fit=crop&q=80" alt="Doctor Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-sm" />
                  <button class="absolute bottom-0 right-0 w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center hover:bg-teal-700 transition-colors shadow-md border-2 border-white">
                    <Camera class="w-4 h-4" />
                  </button>
                </div>
                <div>
                  <h3 class="font-bold text-slate-800 text-[16px]">Profile Picture</h3>
                  <p class="text-[13px] text-slate-500 mt-1">JPG, GIF or PNG. Max size of 800K</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Full Name</label>
                  <input type="text" value="Dr. Sarah Jenkins" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Email Address</label>
                  <input type="email" value="sarah.jenkins@dmr.hospital" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Medical Specialty</label>
                  <input type="text" value="Chief Medical Officer" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Medical License Number</label>
                  <input type="text" value="MD-4958-X" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Professional Bio</label>
                  <textarea rows="3" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors resize-none">Board-certified physician with over 15 years of experience in internal medicine and hospital administration.</textarea>
                </div>
              </div>
            </div>

            <!-- Notifications Tab -->
            <div v-if="activeTab === 'notifications'" class="animate-fade-in">
              <h2 class="text-[18px] font-bold text-slate-800 mb-2">Notification Preferences</h2>
              <p class="text-[14px] text-slate-500 mb-8">Choose how you want to be notified about patient activity.</p>
              
              <div class="space-y-6">
                <!-- Toggle 1 -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100">
                  <div class="flex-1 pr-6">
                    <h3 class="font-bold text-slate-800 text-[14px]">Critical Lab Results</h3>
                    <p class="text-[13px] text-slate-500 mt-1">Receive immediate push notifications when abnormal lab results are uploaded for your patients.</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-teal-100 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                  </label>
                </div>

                <!-- Toggle 2 -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100">
                  <div class="flex-1 pr-6">
                    <h3 class="font-bold text-slate-800 text-[14px]">New Appointment Alerts</h3>
                    <p class="text-[13px] text-slate-500 mt-1">Get an email digest when new appointments are booked in your schedule.</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-teal-100 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                  </label>
                </div>

                <!-- Toggle 3 -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100">
                  <div class="flex-1 pr-6">
                    <h3 class="font-bold text-slate-800 text-[14px]">Direct Patient Messages</h3>
                    <p class="text-[13px] text-slate-500 mt-1">Notify me when a patient sends a direct message to the portal.</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-teal-100 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Security Tab -->
            <div v-if="activeTab === 'security'" class="animate-fade-in">
              <h2 class="text-[18px] font-bold text-slate-800 mb-6">Security & Password</h2>
              
              <div class="max-w-md space-y-5">
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Current Password</label>
                  <input type="password" placeholder="••••••••" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">New Password</label>
                  <input type="password" placeholder="Create new password" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <div>
                  <label class="block text-[13px] font-semibold text-slate-700 mb-2">Confirm New Password</label>
                  <input type="password" placeholder="Confirm new password" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                </div>
                <button class="mt-4 px-5 py-2.5 bg-slate-800 text-white rounded-xl font-semibold text-sm hover:bg-slate-900 transition-colors shadow-sm">
                  Update Password
                </button>
              </div>

              <div class="mt-12 pt-8 border-t border-slate-100">
                <h3 class="font-bold text-slate-800 text-[14px] mb-4">Recent Login Activity</h3>
                <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                  <div class="px-5 py-3 border-b border-slate-200 flex justify-between items-center bg-white">
                    <span class="text-[13px] font-medium text-slate-800">MacBook Pro - Chrome</span>
                    <span class="text-[11px] font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full">Current Session</span>
                  </div>
                  <div class="px-5 py-3 border-b border-slate-200 flex justify-between items-center">
                    <span class="text-[13px] font-medium text-slate-600">iPhone 14 - Safari</span>
                    <span class="text-[12px] text-slate-500">Yesterday, 14:32</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

      </main>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'
import { Activity, LayoutDashboard, Users, FileText, LogOut, Search, HeartPulse, LineChart, CalendarCheck, Settings, Save, User, Bell, ShieldCheck, Camera, ChevronDown, X, Edit } from 'lucide-vue-next'

const router = useRouter()
const username = ref('User') 
const activeTab = ref('profile')
const isProfileOpen = ref(false)

const menuItems = ref([
  { name: 'Dashboard', path: '/dashboard', icon: LineChart },
  { name: 'Manage Patients', path: '/patients', icon: Users },
  { name: 'Health Records', path: '/health-records', icon: FileText },
  { name: 'Settings', path: '/settings', icon: Settings }
])

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

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
