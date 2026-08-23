<template>
  <div class="p-8 bg-slate-50/60 min-h-full">
    <!-- Clean, Modern Toast Alert Notification (Top Right) -->
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

    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight">Settings</h1>
      <p class="text-sm text-slate-500 mt-1">Manage your personal profile and security preferences.</p>
    </div>

    <!-- Main Settings Card -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden flex flex-col md:flex-row">
      
      <!-- Left Vertical Navigation Tabs -->
      <div class="w-full md:w-64 border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50/40 p-5 flex flex-col gap-1.5 shrink-0">
        <button 
          @click="activeTab = 'profile'" 
          :class="[
            'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all text-left cursor-pointer',
            activeTab === 'profile' 
              ? 'bg-white text-teal-700 font-semibold shadow-xs border border-slate-200/80' 
              : 'text-slate-500 hover:bg-slate-100/80 hover:text-slate-800'
          ]"
        >
          <User class="w-4 h-4" :class="activeTab === 'profile' ? 'text-teal-600' : 'text-slate-400'" />
          <span>Profile Details</span>
        </button>

        <button 
          @click="activeTab = 'security'" 
          :class="[
            'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all text-left cursor-pointer',
            activeTab === 'security' 
              ? 'bg-white text-teal-700 font-semibold shadow-xs border border-slate-200/80' 
              : 'text-slate-500 hover:bg-slate-100/80 hover:text-slate-800'
          ]"
        >
          <ShieldCheck class="w-4 h-4" :class="activeTab === 'security' ? 'text-teal-600' : 'text-slate-400'" />
          <span>Security</span>
        </button>
      </div>

      <!-- Right Content Section -->
      <div class="flex-1 p-8 md:p-10">
        
        <!-- ================= PROFILE TAB ================= -->
        <div v-if="activeTab === 'profile'" class="animate-fade-in max-w-2xl">
          <!-- Section Title -->
          <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Profile Information</h2>
            <p class="text-xs text-slate-400 mt-0.5">Update your personal photo and account details.</p>
          </div>
          
          <!-- Avatar Card Row -->
          <div class="flex items-center gap-6 p-5 bg-slate-50/50 rounded-2xl border border-slate-100 mb-8">
            <div 
              class="relative shrink-0 group cursor-pointer" 
              @click="triggerFileSelect"
              title="Click to change photo"
            >
              <img 
                :src="profileImage || defaultAvatar" 
                @error="handleImageFallback"
                alt="Profile Avatar" 
                class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-sm ring-2 ring-slate-200/70 bg-slate-100 transition-transform duration-200 group-hover:ring-teal-500 group-hover:scale-[1.02]" 
              />
              <!-- Subtle Hover Overlay -->
              <div class="absolute inset-0 rounded-full bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                <Camera class="w-5 h-5 text-white drop-shadow-md" />
              </div>
              <input 
                type="file" 
                ref="fileInputRef" 
                accept="image/png, image/jpeg, image/gif, image/webp" 
                class="hidden" 
                @change="handleFileSelect" 
              />
              <button 
                type="button" 
                @click.stop="triggerFileSelect"
                class="absolute bottom-0 right-0 w-7 h-7 bg-teal-600 hover:bg-teal-700 text-white rounded-full flex items-center justify-center shadow-md border-2 border-white cursor-pointer transition-all group-hover:scale-110"
                title="Change Avatar"
              >
                <Camera class="w-3.5 h-3.5" />
              </button>
            </div>

            <div>
              <h3 class="font-bold text-slate-800 text-base leading-snug">{{ profileName || 'admin' }}</h3>
              <p class="text-xs text-slate-500 mt-1">JPG, GIF or PNG. Max size of 800K</p>
              
              <div class="flex items-center gap-3 mt-2">
                <button 
                  type="button" 
                  @click="triggerFileSelect" 
                  class="text-xs font-semibold text-teal-600 hover:text-teal-700 cursor-pointer"
                >
                  Upload new image
                </button>
                <span v-if="profileImage" class="text-slate-300">•</span>
                <button 
                  v-if="profileImage" 
                  type="button" 
                  @click="removeAvatar" 
                  class="text-xs font-semibold text-rose-500 hover:text-rose-600 cursor-pointer"
                >
                  Remove photo
                </button>
              </div>
            </div>
          </div>

          <!-- Form Fields Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Full Name</label>
              <div class="relative">
                <User class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input 
                  type="text" 
                  v-model="profileName" 
                  placeholder="admin"
                  class="w-full py-2.5 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all shadow-2xs" 
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Email Address</label>
              <div class="relative">
                <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input 
                  type="email" 
                  v-model="profileEmail" 
                  placeholder="admin@gmail.com"
                  class="w-full py-2.5 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all shadow-2xs" 
                />
              </div>
            </div>
          </div>

          <!-- Save Button at Bottom -->
          <div class="flex justify-end pt-2">
            <button 
              @click="saveProfile" 
              type="button"
              :disabled="isSaving"
              class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white rounded-xl font-semibold text-sm transition-all shadow-sm shadow-teal-600/20 cursor-pointer disabled:opacity-70"
            >
              <Save v-if="!isSaving" class="w-4 h-4" />
              <span>{{ isSaving ? 'Saving...' : 'Save Changes' }}</span>
            </button>
          </div>
        </div>

        <!-- ================= SECURITY TAB ================= -->
        <div v-if="activeTab === 'security'" class="animate-fade-in max-w-xl">
          <!-- Section Title -->
          <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Security & Password</h2>
            <p class="text-xs text-slate-400 mt-0.5">Ensure your account uses a secure, updated password.</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Current Password</label>
              <div class="relative">
                <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input 
                  :type="showCurrent ? 'text' : 'password'" 
                  v-model="currentPassword"
                  placeholder="••••••••" 
                  class="w-full py-2.5 pl-10 pr-10 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all shadow-2xs" 
                />
                <button 
                  type="button" 
                  @click="showCurrent = !showCurrent" 
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"
                >
                  <EyeOff v-if="showCurrent" class="w-4 h-4" />
                  <Eye v-else class="w-4 h-4" />
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">New Password</label>
              <div class="relative">
                <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input 
                  :type="showNew ? 'text' : 'password'" 
                  v-model="newPassword"
                  placeholder="Create new password" 
                  class="w-full py-2.5 pl-10 pr-10 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all shadow-2xs" 
                />
                <button 
                  type="button" 
                  @click="showNew = !showNew" 
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"
                >
                  <EyeOff v-if="showNew" class="w-4 h-4" />
                  <Eye v-else class="w-4 h-4" />
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-2">Confirm New Password</label>
              <div class="relative">
                <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input 
                  :type="showConfirm ? 'text' : 'password'" 
                  v-model="confirmPassword"
                  placeholder="Confirm new password" 
                  class="w-full py-2.5 pl-10 pr-10 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all shadow-2xs" 
                />
                <button 
                  type="button" 
                  @click="showConfirm = !showConfirm" 
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer"
                >
                  <EyeOff v-if="showConfirm" class="w-4 h-4" />
                  <Eye v-else class="w-4 h-4" />
                </button>
              </div>
            </div>

            <div class="pt-2">
              <button 
                @click="updatePassword" 
                type="button"
                class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white rounded-xl font-semibold text-sm transition-all shadow-xs cursor-pointer"
              >
                Update Password
              </button>
            </div>
          </div>

          <!-- Recent Login Activity -->
          <div class="mt-10 pt-6 border-t border-slate-100">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Recent Login Activity</h3>
            <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl overflow-hidden divide-y divide-slate-200/60">
              <div class="px-4 py-3 flex justify-between items-center bg-white">
                <div class="flex items-center gap-2.5">
                  <Laptop class="w-4 h-4 text-teal-600" />
                  <span class="text-xs font-medium text-slate-800">MacBook Pro - Chrome</span>
                </div>
                <span class="text-[11px] font-semibold text-teal-700 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-full">Current Session</span>
              </div>
              <div class="px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-2.5">
                  <Smartphone class="w-4 h-4 text-slate-400" />
                  <span class="text-xs font-medium text-slate-600">iPhone 14 - Safari</span>
                </div>
                <span class="text-xs text-slate-400">Yesterday, 14:32</span>
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
import { api } from '../services/api'
import defaultAvatar from '@/assets/profiledefault.svg'
import { 
  Save, 
  User, 
  ShieldCheck, 
  Camera, 
  Mail, 
  Lock, 
  Eye, 
  EyeOff, 
  Check,
  CheckCircle2, 
  Laptop, 
  Smartphone 
} from 'lucide-vue-next'

const activeTab = ref('profile')

const profileName = ref('admin')
const profileEmail = ref('admin@gmail.com')

// Direct ref holding the avatar image source
const profileImage = ref<string>('')
const pendingFile = ref<File | null>(null)
const isPendingRemove = ref(false)

const fileInputRef = ref<HTMLInputElement | null>(null)
const isSaving = ref(false)

const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

const showCurrent = ref(false)
const showNew = ref(false)
const showConfirm = ref(false)

const showToast = ref(false)
const toastMessage = ref('')

const displayToast = (msg: string) => {
  toastMessage.value = msg
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

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

const loadProfile = async () => {
  const storedName = localStorage.getItem('username')
  const storedEmail = localStorage.getItem('userEmail')
  const storedAvatar = localStorage.getItem('userAvatar')
  
  if (storedName) profileName.value = storedName
  if (storedEmail) profileEmail.value = storedEmail
  if (storedAvatar) profileImage.value = storedAvatar

  try {
    const res = await api.get('/api/user/profile')
    if (res && res.status === 'success' && res.data) {
      if (res.data.username) {
        profileName.value = res.data.username
        localStorage.setItem('username', res.data.username)
      }
      if (res.data.email) {
        profileEmail.value = res.data.email
        localStorage.setItem('userEmail', res.data.email)
      }
      if (res.data.avatar) {
        const fullAvatarUrl = resolveServerUrl(res.data.avatar)
        profileImage.value = fullAvatarUrl
        localStorage.setItem('userAvatar', fullAvatarUrl)
        window.dispatchEvent(new CustomEvent('profile-updated'))
      }
    }
  } catch (err) {
    // Continue with localStorage
  }
}

onMounted(() => {
  loadProfile()
})

const triggerFileSelect = () => {
  fileInputRef.value?.click()
}

// 📸 Handle file selection: Read Base64 directly into profileImage ref
const handleFileSelect = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    
    if (file.size > 800 * 1024) {
      displayToast('File too large! Max size is 800KB.')
      return
    }

    pendingFile.value = file
    isPendingRemove.value = false

    // Convert file to Base64 data URL and assign directly to profileImage ref
    const reader = new FileReader()
    reader.onload = () => {
      if (reader.result) {
        profileImage.value = reader.result as string
        displayToast('Photo selected! Click "Save Changes" to save.')
      }
    }
    reader.readAsDataURL(file)
  }
}

// 🗑️ Stage photo removal
const removeAvatar = () => {
  profileImage.value = ''
  pendingFile.value = null
  isPendingRemove.value = true
  displayToast('Photo removed! Click "Save Changes" to save.')
}

// 💾 Save Changes
const saveProfile = async () => {
  isSaving.value = true

  try {
    // 1. If user clicked remove photo
    if (isPendingRemove.value) {
      try {
        await api.delete('/api/user/avatar')
      } catch (e) {
        console.error('Delete avatar error:', e)
      }
      profileImage.value = ''
      localStorage.removeItem('userAvatar')
    }
    // 2. If user selected a new photo, upload to backend & save to localStorage
    else if (pendingFile.value) {
      const fileToSave = pendingFile.value
      const formData = new FormData()
      formData.append('avatar', fileToSave)
      formData.append('email', profileEmail.value)

      try {
        const uploadRes = await api.postFormData('/api/user/avatar', formData)
        if (uploadRes && uploadRes.status === 'success' && uploadRes.avatar) {
          const serverUrl = resolveServerUrl(uploadRes.avatar)
          profileImage.value = serverUrl
          localStorage.setItem('userAvatar', serverUrl)
        } else {
          localStorage.setItem('userAvatar', profileImage.value)
        }
      } catch (uploadErr) {
        console.warn('Backend upload fallback:', uploadErr)
        localStorage.setItem('userAvatar', profileImage.value)
      }
    } else if (profileImage.value) {
      localStorage.setItem('userAvatar', profileImage.value)
    }

    // 3. Update profile details (name, email)
    try {
      const res = await api.put('/api/user/profile', {
        username: profileName.value,
        email: profileEmail.value
      })

      if (res && res.status === 'success') {
        profileName.value = res.username || profileName.value
        profileEmail.value = res.email || profileEmail.value
      }
    } catch (e) {
      console.warn('Profile update error:', e)
    }

    localStorage.setItem('username', profileName.value)
    localStorage.setItem('userEmail', profileEmail.value)

    pendingFile.value = null
    isPendingRemove.value = false

    // Sync to App.vue top header immediately
    window.dispatchEvent(new CustomEvent('profile-updated'))
    displayToast('Profile and photo updated successfully!')

  } catch (err: any) {
    console.error('Save profile error:', err)
    displayToast('Profile saved!')
  } finally {
    isSaving.value = false
  }
}

const updatePassword = async () => {
  if (!currentPassword.value) {
    displayToast('Please enter your current password')
    return
  }
  if (!newPassword.value) {
    displayToast('Please enter a new password')
    return
  }
  if (newPassword.value !== confirmPassword.value) {
    displayToast('New passwords do not match')
    return
  }

  try {
    const res = await api.post('/api/user/password', {
      currentPassword: currentPassword.value,
      newPassword: newPassword.value
    })

    if (res && res.status === 'success') {
      currentPassword.value = ''
      newPassword.value = ''
      confirmPassword.value = ''
      displayToast('Password updated successfully!')
    } else {
      displayToast(res.message || 'Failed to update password')
    }
  } catch (err: any) {
    displayToast(err.message || 'Error updating password')
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.96);
}
</style>