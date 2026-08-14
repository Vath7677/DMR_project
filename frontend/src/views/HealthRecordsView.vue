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
        <router-link to="/patients" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg font-medium text-sm transition-all duration-200">
          <Users class="w-5 h-5" />
          <span>Manage Patients</span>
        </router-link>
        <router-link to="/health-records" class="flex items-center gap-3 px-4 py-3 bg-teal-600 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-[0_0_20px_rgba(13,148,136,0.2)]">
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
      <main class="flex-1 px-8 py-8 overflow-y-auto bg-slate-50/60">
        
        <div class="mb-6 flex justify-between items-end">
          <div>
            <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Health Records</h1>
            <p class="text-[14px] text-slate-500 mt-1 font-medium">Manage clinical notes, lab results, and patient documents.</p>
          </div>
          <button class="flex items-center gap-2 px-4 py-2.5 bg-teal-600 text-white rounded-lg font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm">
            <Plus class="w-4 h-4" />
            <span>Add Health Record</span>
          </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
          <!-- Table Header Options -->
          <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <div class="relative w-[320px]">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
              <input type="text" placeholder="Search records by ID, patient, or type..." class="w-full py-2 pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none focus:border-teal-500 focus:bg-white" />
            </div>
            
            <div class="flex gap-3">
              <button class="flex items-center gap-2 px-3 py-2 border border-slate-200 rounded-lg text-[13px] font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                <FileText class="w-4 h-4" />
                <span>Filter</span>
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
              <thead>
                <tr class="bg-white border-b border-slate-100">
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Date</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Patient</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Record Type</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Blood Pressure</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Pulse</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Weight / Height</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">BMI</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500">Attending Doctor</th>
                  <th class="px-6 py-4 font-semibold text-sm text-slate-500 text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <!-- Row 1 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">2026-08-12</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">Amara Okonkwo</div>
                    <div class="text-xs text-slate-500 mt-0.5">(P-1005)</div>
                  </td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-bold border border-teal-100">Lab Results</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">118/78</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">74 bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">58 kg / 1.62 m</td>
                  <td class="px-6 py-5"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold text-xs rounded-md">22.1</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">Dr. Sarah Jenkins</td>
                  <td class="px-6 py-5 text-center">
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-200 hover:border-red-100 shadow-sm">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </td>
                </tr>

                <!-- Row 2 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">2026-08-08</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">James Wilson</div>
                    <div class="text-xs text-slate-500 mt-0.5">(P-1004)</div>
                  </td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">Endocrinology Follow-up</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">128/82</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">76 bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">91.2 kg / 1.75 m</td>
                  <td class="px-6 py-5"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 font-bold text-xs rounded-md">29.8</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">Dr. Emily Carter</td>
                  <td class="px-6 py-5 text-center">
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-200 hover:border-red-100 shadow-sm">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </td>
                </tr>

                <!-- Row 3 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">2026-08-09</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">Sophia Chen</div>
                    <div class="text-xs text-slate-500 mt-0.5">(P-1003)</div>
                  </td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-bold border border-teal-100">Routine Exam</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">115/75</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">68 bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">55 kg / 1.65 m</td>
                  <td class="px-6 py-5"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold text-xs rounded-md">20.2</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">Dr. Sarah Jenkins</td>
                  <td class="px-6 py-5 text-center">
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-200 hover:border-red-100 shadow-sm">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </td>
                </tr>

                <!-- Row 4 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">2026-08-11</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">Marcus Aurelius</div>
                    <div class="text-xs text-slate-500 mt-0.5">(P-1002)</div>
                  </td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">Cardiology Evaluation</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">135/88</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">81 bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">84 kg / 1.78 m</td>
                  <td class="px-6 py-5"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 font-bold text-xs rounded-md">26.5</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">Dr. Robert Miller</td>
                  <td class="px-6 py-5 text-center">
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-200 hover:border-red-100 shadow-sm">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </td>
                </tr>

                <!-- Row 5 -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">2026-08-10</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">Eleanor Vance</div>
                    <div class="text-xs text-slate-500 mt-0.5">(P-1001)</div>
                  </td>
                  <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-bold border border-teal-100">General Checkup</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">120/80</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">72 bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">62.5 kg / 1.68 m</td>
                  <td class="px-6 py-5"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold text-xs rounded-md">22.1</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">Dr. Sarah Jenkins</td>
                  <td class="px-6 py-5 text-center">
                    <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-slate-200 hover:border-red-100 shadow-sm">
                      <Trash2 class="w-4 h-4" />
                    </button>
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
import { Activity, LayoutDashboard, Users, FileText, LogOut, Plus, Trash2, Search, HeartPulse, LineChart, CalendarCheck, Apple } from 'lucide-vue-next'

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
