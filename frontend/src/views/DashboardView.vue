<template>
  <div class="px-8 py-8 bg-slate-50/60 min-h-full">
    <div class="mb-6">
      <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Patient Management System</h1>
      <p class="text-[14px] text-slate-500 mt-1 font-medium">Overview of healthcare operations, patient volume, and recent activities.</p>
    </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
          <!-- Card 1 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-teal-50 text-teal-600 rounded-xl mr-4 shrink-0">
              <Users class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Total Patients</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">6</p>
              <p class="text-[12px] font-semibold text-teal-600 mt-1.5 flex items-center">
                <TrendingUp class="w-[14px] h-[14px] mr-1" />
                +12% this month
              </p>
            </div>
          </div>
          
          <!-- Card 2 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-green-50 text-green-600 rounded-xl mr-4 shrink-0">
              <HeartPulse class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Active Care Plans</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">2</p>
              <p class="text-[12px] font-medium text-slate-400 mt-1.5">Ongoing Regimens</p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-yellow-50 text-yellow-600 rounded-xl mr-4 shrink-0">
              <FileText class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Health Records Logged</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">5</p>
              <p class="text-[12px] font-semibold text-green-600 mt-1.5 flex items-center">
                <Check class="w-[14px] h-[14px] mr-1" />
                All synced
              </p>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="w-[52px] h-[52px] flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl mr-4 shrink-0">
              <Calendar class="w-6 h-6" />
            </div>
            <div>
              <p class="text-[13px] font-medium text-slate-500">Upcoming Appointments</p>
              <p class="text-[32px] leading-none font-heading font-bold text-slate-800 mt-1.5 tracking-tight">3</p>
              <p class="text-[12px] font-medium text-blue-600 mt-1.5">Next 7 Days</p>
            </div>
          </div>
        </div>

        <!-- Charts and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
          
          <!-- Patient Visits Chart -->
          <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="flex justify-between items-center pb-6 border-b border-slate-100 mb-6">
              <h3 class="text-[16px] font-bold text-slate-800">Patient Visits - Last 7 Days</h3>
              <span class="px-3 py-1 bg-teal-50 text-teal-600 border border-teal-100 rounded-full text-[12px] font-medium tracking-wide">Live Data</span>
            </div>
            <!-- Chart.js implementation -->
            <div class="h-[280px] w-full mt-2 relative">
              <Bar :data="chartData" :options="chartOptions" />
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-[16px] font-bold text-slate-800">Recent Activity</h3>
              <a href="#" class="text-[13px] font-medium text-teal-600 hover:text-teal-700">View All</a>
            </div>
            
            <div class="space-y-5">
              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <UserPlus class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">New Patient Registered</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">Just now</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">chan sovannreach (P-1006) was added to the system</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <UserPlus class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">New Patient Registered</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">Just now</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">David Miller (P-1006) was added to the system</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <Calendar class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Appointment Scheduled</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">4 hours ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Cardiology appointment set for Aug 16 with Dr. Miller</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <FileText class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Lab Result Attached</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">2 hours ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Thyroid panel results uploaded for Amara Okonkwo</p>
                </div>
              </div>

              <div class="flex gap-3">
                <div class="mt-0.5">
                  <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center shrink-0">
                    <HeartPulse class="w-4 h-4 text-teal-600" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start">
                    <p class="text-[13px] font-semibold text-slate-800">Vitals Updated</p>
                    <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">45 mins ago</span>
                  </div>
                  <p class="text-[13px] text-slate-500 mt-0.5 leading-snug">Marcus Aurelius BP recorded: 135/88 mmHg</p>
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Users, TrendingUp, HeartPulse, CalendarCheck, Apple, Calendar, UserPlus } from 'lucide-vue-next'

import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const chartData = {
  labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  datasets: [
    {
      label: 'Patient Visits',
      backgroundColor: '#0d9488', 
      borderRadius: 6,
      borderSkipped: 'bottom' as const,
      barPercentage: 0.6,
      data: [12, 19, 15, 22, 28, 14, 8]
    }
  ]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: '#1e293b', // slate-800
      titleFont: { size: 13, family: 'Inter' },
      bodyFont: { size: 13, family: 'Inter' },
      padding: 10,
      cornerRadius: 6,
      displayColors: true,
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 30,
      ticks: {
        stepSize: 5,
        color: '#64748b', // slate-500
        font: {
          size: 13,
          family: 'Inter',
        },
        padding: 12
      },
      grid: {
        color: '#f1f5f9', // slate-100
        drawTicks: false,
      },
      border: {
        display: false
      }
    },
    x: {
      ticks: {
        color: '#64748b', // slate-500
        font: {
          size: 13,
          family: 'Inter',
        },
        padding: 12
      },
      grid: {
        display: false,
        drawTicks: false,
      },
      border: {
        display: true,
        color: '#e2e8f0' // slate-200
      }
    }
  }
}
</script>