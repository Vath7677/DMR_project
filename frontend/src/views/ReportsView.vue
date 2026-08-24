<template>
  <div class="p-8 bg-slate-50/60 min-h-full">
    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 tracking-tight flex items-center gap-2.5">
          <BarChart3 class="w-7 h-7 text-teal-600" />
          <span>Financial & Salary Reports</span>
        </h1>
        <p class="text-sm text-slate-500 mt-1">Analytics on staff compensation, hospital revenue, and operational expenses.</p>
      </div>

      <div class="flex items-center gap-3">
        <!-- Date Selector -->
        <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-xs text-xs font-semibold text-slate-700">
          <Calendar class="w-4 h-4 text-teal-600" />
          <span>Last 6 Months (2026)</span>
        </div>
      </div>
    </div>

    <!-- Summary KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <!-- Total Salary Paid -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Monthly Salary</span>
          <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
            <DollarSign class="w-5 h-5" />
          </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 mt-2">${{ reportData?.summary.totalSalaryMonth.toLocaleString() || '37,200' }}</p>
        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-semibold mt-2">
          <TrendingUp class="w-3.5 h-3.5" />
          <span>+4.2% from last month</span>
        </div>
      </div>

      <!-- Gross Revenue -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gross Revenue</span>
          <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <CreditCard class="w-5 h-5" />
          </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 mt-2">${{ reportData?.summary.grossRevenueMonth.toLocaleString() || '67,400' }}</p>
        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-semibold mt-2">
          <TrendingUp class="w-3.5 h-3.5" />
          <span>+9.1% patient treatments</span>
        </div>
      </div>

      <!-- Net Margin -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Net Operating Profit</span>
          <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <TrendingUp class="w-5 h-5" />
          </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 mt-2">${{ reportData?.summary.netMargin.toLocaleString() || '30,200' }}</p>
        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium mt-2">
          <span>44.8% profit margin</span>
        </div>
      </div>

      <!-- Active Staff -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Staff & Medics</span>
          <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
            <Users class="w-5 h-5" />
          </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 mt-2">{{ reportData?.summary.activeStaffCount || '15' }} Personnel</p>
        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium mt-2">
          <span>Avg. salary ${{ reportData?.summary.averageSalary.toLocaleString() || '2,480' }}/mo</span>
        </div>
      </div>
    </div>

    <!-- Interactive Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      
      <!-- Chart 1: Salary Trends Breakdown -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-base font-bold text-slate-900">Staff Salary Expenditures</h2>
            <p class="text-xs text-slate-400 mt-0.5">Monthly compensation for Doctors, Nurses, and Administrative Staff</p>
          </div>
          <span class="px-2.5 py-1 bg-teal-50 text-teal-700 border border-teal-100 rounded-lg text-xs font-bold font-mono">USD ($)</span>
        </div>
        
        <div class="h-64">
          <Bar v-if="salaryChartData" :data="salaryChartData" :options="barChartOptions" />
        </div>
      </div>

      <!-- Chart 2: Revenue vs Expenses -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-base font-bold text-slate-900">Revenue vs Operating Expenses</h2>
            <p class="text-xs text-slate-400 mt-0.5">Comparison between gross income and total operational costs</p>
          </div>
          <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-bold font-mono">Net Profit</span>
        </div>

        <div class="h-64">
          <Line v-if="revenueChartData" :data="revenueChartData" :options="lineChartOptions" />
        </div>
      </div>

    </div>

    <!-- Bottom Section: Department Breakdown Table & Doughnut -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Doughnut Chart -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
        <div>
          <h2 class="text-base font-bold text-slate-900 mb-1">Salary Distribution by Department</h2>
          <p class="text-xs text-slate-400 mb-4">Percentage of payroll by medical division</p>
        </div>
        <div class="h-56 relative flex items-center justify-center">
          <Doughnut v-if="deptChartData" :data="deptChartData" :options="doughnutOptions" />
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
          <span>Highest: Surgery & Cardiology</span>
          <span class="font-bold text-slate-800">38%</span>
        </div>
      </div>

      <!-- Department Table -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <h2 class="text-base font-bold text-slate-900">Departmental Payroll Breakdown</h2>
          <span class="text-xs text-slate-400">Current Month</span>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                <th class="px-6 py-3.5">Department</th>
                <th class="px-6 py-3.5">Staff Count</th>
                <th class="px-6 py-3.5">Monthly Payroll</th>
                <th class="px-6 py-3.5">Share (%)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="d in reportData?.departments || defaultDepartments" :key="d.department" class="hover:bg-slate-50/50">
                <td class="px-6 py-3.5 font-bold text-slate-800">{{ d.department }}</td>
                <td class="px-6 py-3.5 text-slate-600 font-medium">{{ d.staffCount }} members</td>
                <td class="px-6 py-3.5 font-mono font-bold text-slate-900">${{ d.totalSalary.toLocaleString() }}</td>
                <td class="px-6 py-3.5">
                  <div class="flex items-center gap-3">
                    <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                      <div class="h-full bg-teal-600 rounded-full" :style="{ width: d.percentage + '%' }"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ d.percentage }}%</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { api } from '../services/api'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement
} from 'chart.js'
import { Bar, Line, Doughnut } from 'vue-chartjs'
import { 
  BarChart3, 
  DollarSign, 
  CreditCard, 
  TrendingUp, 
  Users, 
  Calendar 
} from 'lucide-vue-next'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
)

const reportData = ref<any>(null)

const defaultDepartments = [
  { department: 'Cardiology & General Surgery', totalSalary: 14500, staffCount: 4, percentage: 38 },
  { department: 'Pediatrics & Internal Medicine', totalSalary: 10200, staffCount: 3, percentage: 27 },
  { department: 'Nursing & Clinical Care', totalSalary: 8400, staffCount: 5, percentage: 22 },
  { department: 'Administration & Laboratory', totalSalary: 5100, staffCount: 3, percentage: 13 }
]

const fetchReports = async () => {
  try {
    const res = await api.get('/api/reports/financial')
    if (res && res.status === 'success' && res.data) {
      reportData.value = res.data
    }
  } catch (err) {
    console.error('Error fetching reports:', err)
  }
}

onMounted(() => {
  fetchReports()
})

// 📊 Salary Bar Chart Data
const salaryChartData = computed(() => {
  const trends = reportData.value?.salaryTrends || {
    months: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
    doctorSalary: [18500, 19200, 20400, 19800, 21500, 22400],
    nurseSalary: [8200, 8400, 8900, 8900, 9300, 9600],
    staffSalary: [4500, 4600, 4800, 4800, 5100, 5200]
  }

  return {
    labels: trends.months,
    datasets: [
      {
        label: 'Doctors',
        backgroundColor: '#0d9488', // teal-600
        data: trends.doctorSalary,
        borderRadius: 6
      },
      {
        label: 'Nurses',
        backgroundColor: '#0284c7', // sky-600
        data: trends.nurseSalary,
        borderRadius: 6
      },
      {
        label: 'Staff & Admin',
        backgroundColor: '#f59e0b', // amber-500
        data: trends.staffSalary,
        borderRadius: 6
      }
    ]
  }
})

// 📈 Revenue vs Expenses Line Chart Data
const revenueChartData = computed(() => {
  const rev = reportData.value?.revenueTrends || {
    months: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
    revenue: [48500, 52300, 58900, 54200, 61800, 67400],
    expenses: [31200, 32200, 34100, 33500, 35900, 37200]
  }

  return {
    labels: rev.months,
    datasets: [
      {
        label: 'Gross Revenue ($)',
        borderColor: '#10b981', // emerald-500
        backgroundColor: 'rgba(16, 185, 129, 0.1)',
        data: rev.revenue,
        tension: 0.35,
        fill: true
      },
      {
        label: 'Total Expenses ($)',
        borderColor: '#ef4444', // red-500
        backgroundColor: 'rgba(239, 68, 68, 0.1)',
        data: rev.expenses,
        tension: 0.35,
        fill: true
      }
    ]
  }
})

// 🍩 Department Doughnut Chart Data
const deptChartData = computed(() => {
  const depts = reportData.value?.departments || defaultDepartments
  return {
    labels: depts.map((d: any) => d.department.split(' & ')[0]),
    datasets: [
      {
        backgroundColor: ['#0d9488', '#0284c7', '#8b5cf6', '#f59e0b'],
        data: depts.map((d: any) => d.totalSalary)
      }
    ]
  }
})

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom' as const,
      labels: { boxWidth: 12, font: { family: 'inherit', size: 11 } }
    }
  },
  scales: {
    x: { grid: { display: false } },
    y: { 
      grid: { color: '#f1f5f9' },
      ticks: {
        callback: (val: any) => `$${val / 1000}k`
      }
    }
  }
}

const lineChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom' as const,
      labels: { boxWidth: 12, font: { family: 'inherit', size: 11 } }
    }
  },
  scales: {
    x: { grid: { display: false } },
    y: { 
      grid: { color: '#f1f5f9' },
      ticks: {
        callback: (val: any) => `$${val / 1000}k`
      }
    }
  }
}

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom' as const,
      labels: { boxWidth: 10, font: { family: 'inherit', size: 10 } }
    }
  },
  cutout: '68%'
}
</script>
