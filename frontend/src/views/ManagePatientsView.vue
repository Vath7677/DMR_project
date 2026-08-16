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
          <div v-if="isProfileOpen || isSortOpen || isGenderOpen" @click="isProfileOpen = false; isSortOpen = false; isGenderOpen = false" class="fixed inset-0 z-40"></div>
          
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
        
        <div class="mb-6 flex justify-between items-end">
          <div>
            <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Patients</h1>
            <p class="text-[14px] text-slate-500 mt-1 font-medium">Manage patient profiles, demographics, and contact information.</p>
          </div>
          <button class="flex items-center gap-2 px-4 py-2.5 bg-teal-600 text-white rounded-lg font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm">
            <Plus class="w-4 h-4" />
            <span>Add Patient</span>
          </button>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
          <!-- Table Header Options -->
          <div class="p-6 border-b border-slate-100 bg-white">
            <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
              <!-- Search Bar -->
              <div class="relative w-full md:w-[400px] flex-none">
                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                <input type="text" placeholder="Search patients by name, ID, or phone..." class="w-full py-2.5 pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none focus:border-teal-500 focus:bg-white shadow-sm transition-colors" />
              </div>
              
              <div class="flex flex-wrap gap-4">
                <!-- Custom Gender Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Gender</label>
                  <button @click="isGenderOpen = !isGenderOpen; isSortOpen = false" class="relative w-[120px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
                    <span class="truncate block w-full">{{ filterGender }}</span>
                    <ChevronDown class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none transition-transform duration-200" :class="{'rotate-180': isGenderOpen}" />
                  </button>
                  <div v-if="isGenderOpen" class="absolute top-[68px] left-0 w-full bg-white border border-slate-100 rounded-lg shadow-xl z-50 overflow-hidden py-1 animate-fade-in">
                    <div @click="filterGender = '(All)'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === '(All)'}">(All)</div>
                    <div @click="filterGender = 'Male'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === 'Male'}">Male</div>
                    <div @click="filterGender = 'Female'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === 'Female'}">Female</div>
                  </div>
                </div>

                <!-- Custom Sort Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Sort By</label>
                  <button @click="isSortOpen = !isSortOpen; isGenderOpen = false" class="relative w-[140px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
                    <span class="truncate block w-full">{{ currentSort }}</span>
                    <ChevronDown class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none transition-transform duration-200" :class="{'rotate-180': isSortOpen}" />
                  </button>
                  <div v-if="isSortOpen" class="absolute top-[68px] right-0 w-[160px] bg-white border border-slate-100 rounded-lg shadow-xl z-50 overflow-hidden py-1 animate-fade-in">
                    <div @click="currentSort = 'Newest First'; isSortOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': currentSort === 'Newest First'}">Newest First</div>
                    <div @click="currentSort = 'Oldest First'; isSortOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': currentSort === 'Oldest First'}">Oldest First</div>
                    <div @click="currentSort = 'Name (A to Z)'; isSortOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': currentSort === 'Name (A to Z)'}">Name (A to Z)</div>
                    <div @click="currentSort = 'Name (Z to A)'; isSortOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': currentSort === 'Name (Z to A)'}">Name (Z to A)</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
              <thead class="bg-slate-800 text-white">
                <tr>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Patient ID</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Name</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Date of Birth</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Gender</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Phone</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <!-- Row 1 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm font-medium text-slate-500">P-1006</td>
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">CS</div>
                      <div class="font-bold text-slate-800 text-sm">chan sovannreach</div>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">1990-05-15</td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">Male</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">+855 12-345-678</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit Patient">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete Patient">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm font-medium text-slate-500">P-1005</td>
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">AO</div>
                      <div class="font-bold text-slate-800 text-sm">Amara Okonkwo</div>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">1985-08-22</td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-xs font-bold border border-pink-100">Female</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">+855 98-765-432</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit Patient">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete Patient">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
                
                <!-- Row 3 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm font-medium text-slate-500">P-1004</td>
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">JW</div>
                      <div class="font-bold text-slate-800 text-sm">James Wilson</div>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">1972-11-03</td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">Male</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">+855 88-999-000</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit Patient">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete Patient">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm font-medium text-slate-500">P-1003</td>
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">SC</div>
                      <div class="font-bold text-slate-800 text-sm">Sophia Chen</div>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">1995-02-14</td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-xs font-bold border border-pink-100">Female</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">+855 93-444-555</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit Patient">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete Patient">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm font-medium text-slate-500">P-1002</td>
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">MA</div>
                      <div class="font-bold text-slate-800 text-sm">Marcus Aurelius</div>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">1980-09-30</td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">Male</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600">+855 11-222-333</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit Patient">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete Patient">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
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
import { Activity, LayoutDashboard, Users, FileText, LogOut, Search, HeartPulse, LineChart, CalendarCheck, Apple, Plus, Filter, Edit, Trash2, Settings, ChevronDown, X, User, ArrowDownUp } from 'lucide-vue-next'

const router = useRouter()
const username = ref('User') 
const isProfileOpen = ref(false)
const isSortOpen = ref(false)
const currentSort = ref('Newest First')
const isGenderOpen = ref(false)
const filterGender = ref('(All)')

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
