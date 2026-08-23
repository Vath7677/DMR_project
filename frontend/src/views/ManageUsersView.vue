<template>
  <div class="p-8 bg-slate-50/60 min-h-full">
    <!-- Clean Top-Right Toast Notification -->
    <Transition name="toast">
      <div 
        v-if="showToast" 
        class="fixed top-24 right-8 z-50 flex items-center gap-3 px-4 py-2.5 bg-slate-900/95 backdrop-blur-md text-white rounded-2xl shadow-xl shadow-slate-900/10 border border-slate-800 text-xs font-medium"
      >
        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
          <Check class="w-3 h-3 stroke-[3]" />
        </div>
        <span class="text-slate-100 tracking-tight font-medium pr-1">{{ toastMessage }}</span>
      </div>
    </Transition>

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight flex items-center gap-2.5">
          <Users class="w-7 h-7 text-teal-600" />
          <span>User Management</span>
        </h1>
        <p class="text-sm text-slate-500 mt-1">Manage system staff, roles, and administrative access permissions.</p>
      </div>

      <button 
        @click="openAddModal" 
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white rounded-xl font-semibold text-sm transition-all shadow-sm shadow-teal-600/20 cursor-pointer shrink-0"
      >
        <UserPlus class="w-4 h-4" />
        <span>Add New User</span>
      </button>
    </div>

    <!-- Quick Stat Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
      <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Accounts</p>
        <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ users.length }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <p class="text-xs font-semibold text-teal-600 uppercase tracking-wider">Doctors</p>
        <p class="text-2xl font-extrabold text-teal-700 mt-1">{{ countRole('doctor') }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <p class="text-xs font-semibold text-sky-600 uppercase tracking-wider">Nurses</p>
        <p class="text-2xl font-extrabold text-sky-700 mt-1">{{ countRole('nurse') }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Superadmins</p>
        <p class="text-2xl font-extrabold text-purple-700 mt-1">{{ countRole('superadmin') }}</p>
      </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="relative w-full sm:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Search by name or email..." 
          class="w-full py-2 pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-teal-500 transition-all"
        />
      </div>

      <!-- Role Filter Pills -->
      <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto">
        <button 
          v-for="r in ['all', 'superadmin', 'doctor', 'nurse', 'staff']" 
          :key="r"
          @click="selectedRoleFilter = r"
          :class="[
            'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all capitalize cursor-pointer shrink-0',
            selectedRoleFilter === r 
              ? 'bg-teal-600 text-white shadow-xs' 
              : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80'
          ]"
        >
          {{ r === 'all' ? 'All Roles' : r }}
        </button>
      </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
              <th class="px-6 py-4">User</th>
              <th class="px-6 py-4">Email Address</th>
              <th class="px-6 py-4">System Role</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm">
            <tr v-if="filteredUsers.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                <Users class="w-10 h-10 mx-auto mb-2 opacity-30" />
                <span>No user accounts found matching your search.</span>
              </td>
            </tr>
            <tr 
              v-for="u in filteredUsers" 
              :key="u.id" 
              class="hover:bg-slate-50/60 transition-colors"
            >
              <!-- Avatar & Name -->
              <td class="px-6 py-4 flex items-center gap-3">
                <img 
                  :src="getAvatarUrl(u.avatar)" 
                  @error="onImgErr"
                  alt="Avatar" 
                  class="w-10 h-10 rounded-full object-cover border border-slate-200 bg-slate-100 shrink-0" 
                />
                <div>
                  <span class="font-bold text-slate-900 block leading-tight">{{ u.username }}</span>
                  <span class="text-xs text-slate-400">ID #{{ u.id }}</span>
                </div>
              </td>

              <!-- Email -->
              <td class="px-6 py-4 text-slate-600 font-medium font-mono text-xs">
                {{ u.email }}
              </td>

              <!-- Role Badge -->
              <td class="px-6 py-4">
                <span 
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5 border',
                    getRoleBadge(u.role)
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="getRoleDot(u.role)"></span>
                  {{ u.role }}
                </span>
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-1.5 w-fit">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Active
                </span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button 
                    @click="openEditModal(u)" 
                    class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors cursor-pointer"
                    title="Edit Role / User"
                  >
                    <Edit3 class="w-4 h-4" />
                  </button>
                  <button 
                    v-if="u.id !== 1 && u.role !== 'superadmin'" 
                    @click="deleteUserAccount(u)" 
                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                    title="Delete User"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ================= ADD USER MODAL ================= -->
    <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" @click="isAddModalOpen = false"></div>
      
      <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 z-10 animate-scale-up relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
              <UserPlus class="w-4 h-4" />
            </div>
            <h2 class="text-lg font-bold text-slate-800">Add New Staff Account</h2>
          </div>
          <button @click="isAddModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="submitAddUser" class="mt-5 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name / Username</label>
            <input 
              type="text" 
              v-model="newUser.username" 
              required
              placeholder="e.g. Dr. Sokha Chan" 
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address (Login Account)</label>
            <input 
              type="email" 
              v-model="newUser.email" 
              required
              placeholder="e.g. sokha@clinic.com" 
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
              <input 
                :type="showAddPassword ? 'text' : 'password'" 
                v-model="newUser.password" 
                required
                placeholder="Create password (min 6 characters)" 
                class="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500"
              />
              <button 
                type="button" 
                @click="showAddPassword = !showAddPassword" 
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
              >
                <EyeOff v-if="showAddPassword" class="w-4 h-4" />
                <Eye v-else class="w-4 h-4" />
              </button>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">System Role</label>
            <select 
              v-model="newUser.role" 
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500 capitalize"
            >
              <option value="doctor">Doctor (Medical & Health Records)</option>
              <option value="nurse">Nurse (Patient Care)</option>
              <option value="staff">Staff / Receptionist</option>
              <option value="admin">Administrator</option>
              <option value="superadmin">Superadmin (Full Access)</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
            <button 
              type="button" 
              @click="isAddModalOpen = false" 
              class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl shadow-xs disabled:opacity-50"
            >
              {{ isSubmitting ? 'Creating...' : 'Create Account' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================= EDIT USER MODAL ================= -->
    <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" @click="isEditModalOpen = false"></div>
      
      <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 z-10 animate-scale-up relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <h2 class="text-lg font-bold text-slate-800">Edit User Account</h2>
          <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="submitEditUser" class="mt-5 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
            <input 
              type="text" 
              v-model="editUserForm.username" 
              required
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
            <input 
              type="email" 
              v-model="editUserForm.email" 
              required
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role</label>
            <select 
              v-model="editUserForm.role" 
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-teal-500 capitalize"
            >
              <option value="doctor">Doctor</option>
              <option value="nurse">Nurse</option>
              <option value="staff">Staff</option>
              <option value="admin">Administrator</option>
              <option value="superadmin">Superadmin</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
            <button 
              type="button" 
              @click="isEditModalOpen = false" 
              class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl shadow-xs disabled:opacity-50"
            >
              {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { api } from '../services/api'
import defaultAvatar from '@/assets/profiledefault.svg'
import { 
  Users, 
  UserPlus, 
  Search, 
  Edit3, 
  Trash2, 
  Check, 
  X, 
  Eye, 
  EyeOff 
} from 'lucide-vue-next'

interface UserItem {
  id: number
  username: string
  email: string
  role: string
  avatar?: string | null
  created_at?: string
}

const users = ref<UserItem[]>([])
const searchQuery = ref('')
const selectedRoleFilter = ref('all')

const isAddModalOpen = ref(false)
const isEditModalOpen = ref(false)
const isSubmitting = ref(false)
const showAddPassword = ref(false)

const newUser = ref({
  username: '',
  email: '',
  password: '',
  role: 'doctor'
})

const editUserForm = ref({
  id: 0,
  username: '',
  email: '',
  role: 'doctor'
})

const showToast = ref(false)
const toastMessage = ref('')

const displayToast = (msg: string) => {
  toastMessage.value = msg
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const getAvatarUrl = (path?: string | null) => {
  if (!path) return defaultAvatar
  if (path.startsWith('http') || path.startsWith('data:')) return path
  const clean = path.startsWith('/') ? path.slice(1) : path
  return `http://localhost/DMR_project/backend/public/${clean}`
}

const onImgErr = (e: Event) => {
  const img = e.target as HTMLImageElement
  if (img && img.src.includes('/DMR_project/backend/public/uploads/')) {
    img.src = img.src.replace('/DMR_project/backend/public/uploads/', '/uploads/')
  } else {
    img.src = defaultAvatar
  }
}

const countRole = (role: string) => {
  return users.value.filter(u => u.role === role).length
}

const getRoleBadge = (role: string) => {
  switch (role) {
    case 'superadmin':
      return 'bg-purple-50 text-purple-700 border-purple-200'
    case 'doctor':
      return 'bg-teal-50 text-teal-700 border-teal-200'
    case 'nurse':
      return 'bg-sky-50 text-sky-700 border-sky-200'
    case 'staff':
      return 'bg-amber-50 text-amber-700 border-amber-200'
    default:
      return 'bg-slate-100 text-slate-700 border-slate-200'
  }
}

const getRoleDot = (role: string) => {
  switch (role) {
    case 'superadmin': return 'bg-purple-500'
    case 'doctor': return 'bg-teal-500'
    case 'nurse': return 'bg-sky-500'
    case 'staff': return 'bg-amber-500'
    default: return 'bg-slate-500'
  }
}

const filteredUsers = computed(() => {
  return users.value.filter(u => {
    const matchSearch = u.username.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchRole = selectedRoleFilter.value === 'all' || u.role === selectedRoleFilter.value
    return matchSearch && matchRole
  })
})

const fetchUsers = async () => {
  try {
    const res = await api.get('/api/admin/users')
    if (res && res.status === 'success' && Array.isArray(res.data)) {
      users.value = res.data
    }
  } catch (err) {
    console.error('Error fetching users:', err)
  }
}

onMounted(() => {
  fetchUsers()
})

const openAddModal = () => {
  newUser.value = {
    username: '',
    email: '',
    password: '',
    role: 'doctor'
  }
  showAddPassword.value = false
  isAddModalOpen.value = true
}

const submitAddUser = async () => {
  isSubmitting.value = true
  try {
    const res = await api.post('/api/admin/users', newUser.value)
    if (res && res.status === 'success') {
      displayToast('User account created successfully!')
      isAddModalOpen.value = false
      fetchUsers()
    } else {
      displayToast(res.message || 'Failed to create user')
    }
  } catch (err: any) {
    displayToast(err.message || 'Error creating user')
  } finally {
    isSubmitting.value = false
  }
}

const openEditModal = (u: UserItem) => {
  editUserForm.value = {
    id: u.id,
    username: u.username,
    email: u.email,
    role: u.role
  }
  isEditModalOpen.value = true
}

const submitEditUser = async () => {
  isSubmitting.value = true
  try {
    const res = await api.put(`/api/admin/users/${editUserForm.value.id}`, editUserForm.value)
    if (res && res.status === 'success') {
      displayToast('User updated successfully!')
      isEditModalOpen.value = false
      fetchUsers()
    } else {
      displayToast(res.message || 'Failed to update user')
    }
  } catch (err: any) {
    displayToast(err.message || 'Error updating user')
  } finally {
    isSubmitting.value = false
  }
}

const deleteUserAccount = async (u: UserItem) => {
  if (confirm(`Are you sure you want to delete user "${u.username}" (${u.email})?`)) {
    try {
      const res = await api.delete(`/api/admin/users/${u.id}`)
      if (res && res.status === 'success') {
        displayToast('User deleted successfully!')
        fetchUsers()
      } else {
        displayToast(res.message || 'Failed to delete user')
      }
    } catch (err: any) {
      displayToast(err.message || 'Error deleting user')
    }
  }
}
</script>

<style scoped>
.animate-scale-up {
  animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes scaleUp {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
