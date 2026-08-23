<template>
  <div class="px-8 py-8 bg-slate-50/60 min-h-full">
    <div class="mb-6 flex justify-between items-end">
      <div>
        <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Settings</h1>
        <p class="text-[14px] text-slate-500 mt-1 font-medium">Manage your personal profile and security preferences.</p>
      </div>
      <button @click="saveProfile" class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-lg font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm cursor-pointer">
        <Save class="w-4 h-4" />
        <span>Save Changes</span>
      </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col md:flex-row min-h-[550px]">
      
      <!-- Settings Navigation -->
      <div class="w-full md:w-[240px] border-r border-slate-100 bg-slate-50/50 p-6 flex flex-col gap-2">
        <button @click="activeTab = 'profile'" :class="['flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-medium transition-all duration-200 w-full text-left cursor-pointer', activeTab === 'profile' ? 'bg-white text-teal-600 shadow-sm border border-slate-100 font-semibold' : 'text-slate-500 hover:bg-slate-100/80']">
          <User class="w-4 h-4" />
          <span>Profile Details</span>
        </button>
        <button @click="activeTab = 'security'" :class="['flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-medium transition-all duration-200 w-full text-left cursor-pointer', activeTab === 'security' ? 'bg-white text-teal-600 shadow-sm border border-slate-100 font-semibold' : 'text-slate-500 hover:bg-slate-100/80']">
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
              <button type="button" class="absolute bottom-0 right-0 w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center hover:bg-teal-700 transition-colors shadow-md border-2 border-white cursor-pointer">
                <Camera class="w-4 h-4" />
              </button>
            </div>
            <div>
              <h3 class="font-bold text-slate-800 text-[16px]">{{ profileName }}</h3>
              <p class="text-[13px] text-slate-500 mt-1">JPG, GIF or PNG. Max size of 800K</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-[13px] font-semibold text-slate-700 mb-2">Full Name</label>
              <input type="text" v-model="profileName" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
            </div>
            <div>
              <label class="block text-[13px] font-semibold text-slate-700 mb-2">Email Address</label>
              <input type="email" v-model="profileEmail" class="w-full py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
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
            <button class="mt-4 px-5 py-2.5 bg-teal-600 text-white rounded-xl font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm cursor-pointer">
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
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Save, User, ShieldCheck, Camera } from 'lucide-vue-next'

const activeTab = ref('profile')

const profileName = ref('Admin')
const profileEmail = ref('admin@gmail.com')

onMounted(() => {
  const storedName = localStorage.getItem('username')
  const storedEmail = localStorage.getItem('userEmail')
  
  if (storedName) profileName.value = storedName
  if (storedEmail) profileEmail.value = storedEmail
})

const saveProfile = () => {
  localStorage.setItem('username', profileName.value)
  localStorage.setItem('userEmail', profileEmail.value)
  alert('Profile updated successfully!')
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