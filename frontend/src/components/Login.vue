<template>
  <div class="relative min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4 lg:p-8">
    
    <!-- Grid Background Layer -->
    <div class="absolute inset-0 z-2 bg-[size:40px_40px] bg-[linear-gradient(to_right,rgba(0,0,0,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.05)_1px,transparent_1px)]"></div>
    
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center z-10">
      
      <!-- Left Side Content -->
      <div class="space-y-8 pr-0 lg:pr-12">
        
        <!-- Badge -->
        <div class="inline-flex items-center space-x-2 bg-teal-50 text-teal-700 px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide">
          <Activity class="w-4 h-4" />
          <span>DMR HOSPITAL PORTAL</span>
        </div>

        <!-- Headline -->
        <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight">
          Welcome to the <br/>
          <span class="text-teal-600">Healthcare</span> <br/>
          <span class="text-teal-600">Dashboard</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-gray-600 text-lg leading-relaxed max-w-md">
          Sign in to access patient records, manage daily appointments, and securely oversee hospital resources.
        </p>

        <!-- Features Cards -->
        <div class="flex flex-col sm:flex-row gap-6 pt-6">
          <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1">
            <Sparkles class="w-6 h-6 text-teal-600 mb-3" />
            <h3 class="font-bold text-gray-900 mb-1">Simple & Intuitive</h3>
            <p class="text-sm text-gray-500">Designed to be incredibly easy to use, so you can focus on patient care.</p>
          </div>
          <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1">
            <Lock class="w-6 h-6 text-teal-600 mb-3" />
            <h3 class="font-bold text-gray-900 mb-1">Secure Records</h3>
            <p class="text-sm text-gray-500">Patient data is protected by industry-leading encryption.</p>
          </div>
        </div>
      </div>

      <!-- Right Side Form Card -->
      <div class="bg-white p-8 lg:p-12 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50">
        <div class="text-center mb-10">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Sign in to your account</h2>
          <p class="text-gray-500">Welcome back! Please enter your details.</p>
        </div>

        <form class="space-y-6" @submit.prevent="handleLogin">
          <!-- Email Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Mail class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="email" 
                type="email" 
                placeholder="doctor@gamil.com" 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-600
                focus:border-teal-600 sm:text-sm bg-gray-50/50 outline-none transition-colors"
                required/>
            </div>
          </div>

          <!-- Password Field with Show/Hide -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Lock class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                placeholder="••••••••" 
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-600 
                focus:border-teal-600 sm:text-sm bg-gray-50/50 outline-none transition-colors"
                required/>
              <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <EyeOff v-if="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <Eye v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between mt-6">
            <label class="flex items-center cursor-pointer group">
              <input type="checkbox" v-model="rememberMe" class="rounded border-gray-300 text-teal-600 focus:ring-teal-600 h-4 w-4 accent-teal-600" />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" :disabled="isLocked"
            class="w-full font-semibold py-3 px-4 rounded-lg transition-colors duration-200 shadow-sm mt-6 text-white"
            :class="isLocked ? 'bg-gray-400 cursor-not-allowed' : 'bg-teal-600 hover:bg-teal-700'">
            {{ isLocked ? `Try again in ${lockoutTimeRemaining}s` : 'Sign In' }}
          </button>
          
          <!-- Error / Lockout Message -->
          <div v-if="errorMessage" class="mt-4 p-3 rounded-lg text-sm text-center font-medium border" :class="isLocked ? 'bg-red-50 text-red-700 border-red-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200'">
            {{ errorMessage }}
          </div>
        </form>

        <!-- Copyright Footer -->
        <div class="text-center mt-4">
          <p class="text-gray-500 font-bold text-sm">
            Copyright &copy; TechSimplified- 2026
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { api } from '../services/api';
import { Activity, Sparkles, Lock, Mail, Eye, EyeOff } from 'lucide-vue-next';

const router = useRouter();

// State simple that was show the emial and password
const email = ref<string>('');
const password = ref<string>('');
const showPassword = ref<boolean>(false);

// State for Error and Lock acc
const errorMessage = ref<string>('');
const isLocked = ref<boolean>(false);
const lockoutTimeRemaining = ref<number>(0);
let timer: any = null;

// make an remember checkbox
const rememberMe = ref<boolean> (false);

// close Password
const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

// the feature login use to connect by API to backend
const handleLogin = async () => {
  try {
    const data = await api.post('/api/auth/login', {
      email: email.value,
      password: password.value
    });

    if (data.status === 'error') {
        errorMessage.value = data.message;
    } else if (data.status === 'locked') {
        errorMessage.value = data.message;
        isLocked.value = true;
        lockoutTimeRemaining.value = data.remaining_time;
            
        if (timer) clearInterval(timer);
        timer = setInterval(() => {
            lockoutTimeRemaining.value--;
            if (lockoutTimeRemaining.value <= 0) {
                clearInterval(timer);
                isLocked.value = false;
                errorMessage.value = '';
            }
        }, 1000);
    } else if (data.status === 'success') {
        isLocked.value = false;
        if (timer) clearInterval(timer);
        console.log("Login Success! Role:", data.role);
        localStorage.setItem('username', data.username);
        
        // Save OR Remove email and password based on Remember Me checkbox
        if (rememberMe.value === true) {
            localStorage.setItem('savedEmail', email.value);
            localStorage.setItem('savedPassword', password.value);
        } else {
            localStorage.removeItem('savedEmail');
            localStorage.removeItem('savedPassword');
        }

        router.push('/dashboard');
    } 
  } catch (error) {
    console.error('Error connecting to backend:', error);
    errorMessage.value = 'Cannot connect to server!';
  }
};

// rememberMe help fill the data in the input box when page loads
onMounted(() => {
  const storedEmail = localStorage.getItem('savedEmail');
  const storedPassword = localStorage.getItem('savedPassword');
  if (storedEmail) {
    email.value = storedEmail; 
    
    if (storedPassword) {
      password.value = storedPassword;
    }
    
    rememberMe.value = true;   
  }
});
</script>