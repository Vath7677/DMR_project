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
          <div v-if="isProfileOpen || isGenderOpen || isStatusOpen || isRangeOpen || isSortOpen" @click="isProfileOpen = false; isGenderOpen = false; isStatusOpen = false; isRangeOpen = false; isSortOpen = false" class="fixed inset-0 z-40"></div>
          
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
            <h1 class="text-[24px] font-heading font-bold text-slate-900 tracking-tight">Health Records</h1>
            <p class="text-[14px] text-slate-500 mt-1 font-medium">Manage clinical notes, lab results, and patient documents.</p>
          </div>
          <button @click="openAddModal" class="flex items-center gap-2 px-4 py-2.5 bg-teal-600 text-white rounded-lg font-semibold text-sm hover:bg-teal-700 transition-colors shadow-sm">
            <Plus class="w-4 h-4" />
            <span>Add Health Record</span>
          </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
          <!-- Table Header Options -->
          <div class="p-6 border-b border-slate-100 bg-white">
            <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
              <!-- Search Bar -->
              <div class="relative w-full md:w-[400px] flex-none">
                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                <input type="text" v-model="searchQuery" placeholder="Search patients by name, ID, or phone..." class="w-full py-2.5 pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none focus:border-teal-500 focus:bg-white shadow-sm transition-colors" />
              </div>
              
              <div class="flex flex-wrap gap-4">
                
                <!-- Custom Gender Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Gender</label>
                  <button @click="isGenderOpen = !isGenderOpen; isStatusOpen = false; isRangeOpen = false; isSortOpen = false" class="relative w-[120px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
                    <span class="truncate block w-full">{{ filterGender }}</span>
                    <ChevronDown class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none transition-transform duration-200" :class="{'rotate-180': isGenderOpen}" />
                  </button>
                  <div v-if="isGenderOpen" class="absolute top-[68px] left-0 w-full bg-white border border-slate-100 rounded-lg shadow-xl z-50 overflow-hidden py-1 animate-fade-in">
                    <div @click="filterGender = '(All)'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === '(All)'}">(All)</div>
                    <div @click="filterGender = 'Male'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === 'Male'}">Male</div>
                    <div @click="filterGender = 'Female'; isGenderOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterGender === 'Female'}">Female</div>
                  </div>
                </div>

                <!-- Custom Status Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Status</label>
                  <button @click="isStatusOpen = !isStatusOpen; isGenderOpen = false; isRangeOpen = false; isSortOpen = false" class="relative w-[120px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
                    <span class="truncate block w-full">{{ filterStatus }}</span>
                    <ChevronDown class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none transition-transform duration-200" :class="{'rotate-180': isStatusOpen}" />
                  </button>
                  <div v-if="isStatusOpen" class="absolute top-[68px] left-0 w-full bg-white border border-slate-100 rounded-lg shadow-xl z-50 overflow-hidden py-1 animate-fade-in">
                    <div @click="filterStatus = 'Active'; isStatusOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterStatus === 'Active'}">Active</div>
                    <div @click="filterStatus = 'Inactive'; isStatusOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterStatus === 'Inactive'}">Inactive</div>
                  </div>
                </div>

                <!-- Custom Range Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Last Visited</label>
                  <button @click="isRangeOpen = !isRangeOpen; isGenderOpen = false; isStatusOpen = false; isSortOpen = false" class="relative w-[130px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
                    <span class="truncate block w-full">{{ filterRange }}</span>
                    <ChevronDown class="absolute right-3 w-4 h-4 text-slate-400 pointer-events-none transition-transform duration-200" :class="{'rotate-180': isRangeOpen}" />
                  </button>
                  <div v-if="isRangeOpen" class="absolute top-[68px] right-0 w-full bg-white border border-slate-100 rounded-lg shadow-xl z-50 overflow-hidden py-1 animate-fade-in">
                    <div @click="filterRange = '(Last Month)'; isRangeOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterRange === '(Last Month)'}">(Last Month)</div>
                    <div @click="filterRange = '(Last 3 Months)'; isRangeOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterRange === '(Last 3 Months)'}">(Last 3 Months)</div>
                    <div @click="filterRange = '(Last Year)'; isRangeOpen = false" class="px-3 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-teal-600 cursor-pointer transition-colors" :class="{'bg-teal-50 text-teal-700 font-medium': filterRange === '(Last Year)'}">(Last Year)</div>
                  </div>
                </div>

                <!-- Custom Sort Dropdown -->
                <div class="flex flex-col gap-1 relative">
                  <label class="text-[12px] font-semibold text-slate-700">Sort By</label>
                  <button @click="isSortOpen = !isSortOpen; isGenderOpen = false; isStatusOpen = false; isRangeOpen = false" class="relative w-[140px] py-2.5 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-600 focus:outline-none cursor-pointer flex items-center hover:bg-white transition-colors text-left shadow-sm">
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
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Date</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Patient</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Record Type</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Blood Pressure</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Pulse</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Weight / Height</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">BMI</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide">Attending Doctor</th>
                  <th class="px-6 py-4 font-semibold text-[13px] tracking-wide text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="record in filteredRecords" :key="record.id" class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-5 text-sm text-slate-600">{{ record.date }}</td>
                  <td class="px-6 py-5">
                    <div class="font-bold text-slate-800 text-sm">{{ record.patientName }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">({{ record.patientId }})</div>
                  </td>
                  <td class="px-6 py-5">
                    <span :class="['px-3 py-1 rounded-full text-xs font-bold border', getBadgeClass(record.recordType)]">{{ record.recordType }}</span>
                  </td>
                  <td class="px-6 py-5 text-sm text-slate-600"><span class="font-bold text-slate-800">{{ record.bloodPressure.split('/')[0] }}/{{ record.bloodPressure.split('/')[1] || '' }}</span> mmHg</td>
                  <td class="px-6 py-5 text-sm text-slate-600">{{ record.pulse }} bpm</td>
                  <td class="px-6 py-5 text-sm text-slate-600">{{ record.weightHeight }}</td>
                  <td class="px-6 py-5"><span :class="['px-2 py-1 font-bold text-xs rounded-md', getBmiClass(record.bmi)]">{{ record.bmi }}</span></td>
                  <td class="px-6 py-5 text-sm text-slate-600">{{ record.attendingDoctor }}</td>
                  <td class="px-6 py-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="openViewModal(record)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100" title="View Details">
                        <Eye class="w-4 h-4" />
                      </button>
                      <button @click="openEditModal(record)" class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors border border-transparent hover:border-teal-100" title="Edit">
                        <Edit class="w-4 h-4" />
                      </button>
                      <button @click="deleteRecord(record.id)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Delete">
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
                
                <!-- Empty State -->
                <tr v-if="filteredRecords.length === 0">
                  <td colspan="9" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                      <FileText class="w-12 h-12 mb-3 text-slate-300" />
                      <p class="text-[15px] font-medium text-slate-600">No health records found</p>
                      <p class="text-[13px] mt-1">Try adjusting your filters or search query.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- View Health Record Modal -->
        <div v-if="isViewModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center">
          <div class="absolute inset-0 bg-slate-900/40" @click="isViewModalOpen = false"></div>
          
          <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh] animate-scale-up mx-4">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
              <h3 class="text-lg font-bold text-slate-800">Health Record Details</h3>
              <button @click="isViewModalOpen = false" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">
                <X class="w-5 h-5" />
              </button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scrollbar">
              <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm shrink-0">
                  <Activity class="w-6 h-6" />
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-800">{{ viewingRecord?.patientName }}</h3>
                  <p class="text-[13px] text-slate-500 font-medium mt-0.5">ID: {{ viewingRecord?.patientId }} &bull; {{ viewingRecord?.gender }}</p>
                </div>
              </div>

              <div class="space-y-4">
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 grid grid-cols-2 gap-y-5 gap-x-4">
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Date</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ viewingRecord?.date }}</p>
                  </div>
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Record Type</p>
                    <span :class="['px-2 py-0.5 rounded-full text-[11px] font-bold border inline-block mt-0.5', getBadgeClass(viewingRecord?.recordType)]">{{ viewingRecord?.recordType }}</span>
                  </div>
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Blood Pressure</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ viewingRecord?.bloodPressure }} mmHg</p>
                  </div>
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pulse</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ viewingRecord?.pulse }} bpm</p>
                  </div>
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Weight / Height</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ viewingRecord?.weightHeight }}</p>
                  </div>
                  <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">BMI</p>
                    <span :class="['px-2 py-0.5 font-bold text-[11px] rounded inline-block mt-0.5', getBmiClass(viewingRecord?.bmi)]">{{ viewingRecord?.bmi }}</span>
                  </div>
                  <div class="col-span-2 pt-2 border-t border-slate-200">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Attending Doctor</p>
                    <div class="flex items-center gap-2">
                      <User class="w-4 h-4 text-slate-400" />
                      <p class="text-[13px] font-medium text-slate-800">{{ viewingRecord?.attendingDoctor }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end">
              <button @click="isViewModalOpen = false" class="px-5 py-2 text-[13px] font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors shadow-sm focus:outline-none">
                Done
              </button>
            </div>
          </div>
        </div>

        <!-- Add/Edit Health Record Modal -->
        <div v-if="isAddModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center">
          <div class="absolute inset-0 bg-slate-900/40" @click="isAddModalOpen = false"></div>
          
          <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh] animate-scale-up">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
              <h3 class="text-lg font-bold text-slate-800">{{ editingRecordId ? 'Edit Health Record' : 'Add New Health Record' }}</h3>
              <button @click="isAddModalOpen = false" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">
                <X class="w-5 h-5" />
              </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto">
              <form @submit.prevent="saveRecord" class="space-y-6">
                <!-- Patient Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Patient Name <span class="text-rose-500">*</span></label>
                    <input list="recent-patients" type="text" v-model="newRecord.patientName" @keydown.enter.prevent="bpInput?.focus()" required placeholder="e.g. John Doe" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="recent-patients">
                      <option v-for="p in recentPatients" :key="p.id" :value="p.name">{{ p.id }}</option>
                    </datalist>
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Patient ID</label>
                    <input type="text" v-model="newRecord.patientId" placeholder="Auto-generated if blank" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                  </div>
                </div>

                <!-- Record Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div class="space-y-1.5 relative">
                    <label class="text-[13px] font-medium text-slate-700">Record Type <span class="text-rose-500">*</span></label>
                    <div v-if="isTypeOpen" @click="isTypeOpen = false" class="fixed inset-0 z-40"></div>
                    <button type="button" @click="isTypeOpen = !isTypeOpen" class="relative w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 focus:outline-none focus:border-teal-500 transition-colors text-left flex items-center justify-between">
                      <span :class="newRecord.recordType ? 'text-slate-800' : 'text-slate-400'">{{ newRecord.recordType || 'Select type' }}</span>
                      <ChevronDown class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': isTypeOpen}" />
                    </button>
                    <!-- Custom Dropdown Menu -->
                    <div v-if="isTypeOpen" class="absolute top-[62px] left-0 w-full bg-white border border-slate-100 rounded-lg shadow-xl z-50 py-1 animate-fade-in">
                      <button type="button" @click="newRecord.recordType = 'General Checkup'; isTypeOpen = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-slate-50 hover:text-teal-600 transition-colors">General Checkup</button>
                      <button type="button" @click="newRecord.recordType = 'Lab Results'; isTypeOpen = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-slate-50 hover:text-teal-600 transition-colors">Lab Results</button>
                      <button type="button" @click="newRecord.recordType = 'Routine Exam'; isTypeOpen = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-slate-50 hover:text-teal-600 transition-colors">Routine Exam</button>
                      <button type="button" @click="newRecord.recordType = 'Cardiology Evaluation'; isTypeOpen = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-slate-50 hover:text-teal-600 transition-colors">Cardiology Evaluation</button>
                      <button type="button" @click="newRecord.recordType = 'Endocrinology Follow-up'; isTypeOpen = false" class="w-full text-left px-3 py-2 text-[13px] hover:bg-slate-50 hover:text-teal-600 transition-colors">Endocrinology Follow-up</button>
                    </div>
                  </div>
                  <div class="space-y-1.5 relative">
                    <label class="text-[13px] font-medium text-slate-700">Date <span class="text-rose-500">*</span></label>
                    <div v-if="isDateOpen" @click="isDateOpen = false" class="fixed inset-0 z-40"></div>
                    <button type="button" @click="isDateOpen = !isDateOpen" class="relative w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 focus:outline-none focus:border-teal-500 transition-colors text-left flex items-center justify-between">
                      <span :class="formattedDate ? 'text-slate-800' : 'text-slate-400'" class="flex items-center">
                        <CalendarCheck class="w-4 h-4 mr-2 text-slate-400"/>
                        {{ formattedDate || 'Select date' }}
                      </span>
                      <ChevronDown class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': isDateOpen}" />
                    </button>
                    <!-- Custom Date Popover -->
                    <div v-if="isDateOpen" class="absolute top-[62px] right-0 w-full sm:w-[320px] bg-white border border-slate-100 rounded-xl shadow-xl z-50 p-4 animate-fade-in origin-top-right">
                      <div class="flex gap-3 h-[200px]">
                        <!-- Month -->
                        <div class="flex-1 flex flex-col">
                          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Month</span>
                          <div class="flex-1 overflow-y-auto space-y-1 pr-1 pb-4" style="scrollbar-width: none;">
                            <button type="button" v-for="m in months" :key="m.value" @click="recMonth = m.value" :class="['w-full py-2 text-[13px] rounded-lg transition-colors', recMonth === m.value ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50']">
                              {{ m.label }}
                            </button>
                          </div>
                        </div>
                        <!-- Day -->
                        <div class="flex-1 flex flex-col border-l border-slate-100 pl-3">
                          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Day</span>
                          <div class="flex-1 overflow-y-auto space-y-1 pr-1 pb-4" style="scrollbar-width: none;">
                            <button type="button" v-for="d in 31" :key="d" @click="recDay = d" :class="['w-full py-2 text-[13px] rounded-lg transition-colors', recDay == d ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50']">
                              {{ d }}
                            </button>
                          </div>
                        </div>
                        <!-- Year -->
                        <div class="flex-1 flex flex-col border-l border-slate-100 pl-3">
                          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Year</span>
                          <div class="flex-1 overflow-y-auto space-y-1 pr-1 pb-4" style="scrollbar-width: none;">
                            <button type="button" v-for="y in 20" :key="y" @click="recYear = currentYear - y + 6" :class="['w-full py-2 text-[13px] rounded-lg transition-colors', recYear == (currentYear - y + 6) ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50']">
                              {{ currentYear - y + 6 }}
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="mt-5 pt-4 border-t border-slate-100">
                        <button type="button" @click="isDateOpen = false" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-bold rounded-lg transition-colors">
                          Confirm Date
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Vitals -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Blood Pressure</label>
                    <input list="bp-options" ref="bpInput" v-model="newRecord.bloodPressure" @keydown.enter.prevent="pulseInput?.focus()" placeholder="120/80" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="bp-options">
                      <option value="90/60"></option>
                      <option value="100/60"></option>
                      <option value="100/70"></option>
                      <option value="110/70"></option>
                      <option value="115/75"></option>
                      <option value="120/80"></option>
                      <option value="125/80"></option>
                      <option value="130/80"></option>
                      <option value="130/85"></option>
                      <option value="135/85"></option>
                      <option value="140/90"></option>
                      <option value="145/90"></option>
                      <option value="150/95"></option>
                      <option value="160/100"></option>
                    </datalist>
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Pulse (bpm)</label>
                    <input list="pulse-options" ref="pulseInput" type="number" v-model="newRecord.pulse" @keydown.enter.prevent="weightInput?.focus()" placeholder="72" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="pulse-options">
                      <option value="60"></option>
                      <option value="65"></option>
                      <option value="70"></option>
                      <option value="72"></option>
                      <option value="75"></option>
                      <option value="80"></option>
                      <option value="85"></option>
                      <option value="90"></option>
                      <option value="95"></option>
                      <option value="100"></option>
                      <option value="105"></option>
                      <option value="110"></option>
                      <option value="120"></option>
                    </datalist>
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">BMI</label>
                    <input type="number" step="0.1" v-model="newRecord.bmi" placeholder="Auto-calculated" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" readonly />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Weight (kg)</label>
                    <input list="weight-options" type="number" ref="weightInput" step="0.1" v-model="newRecord.weight" @keydown.enter.prevent="heightInput?.focus()" placeholder="60" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="weight-options">
                      <option value="40"></option><option value="45"></option><option value="50"></option>
                      <option value="55"></option><option value="60"></option><option value="65"></option>
                      <option value="70"></option><option value="75"></option><option value="80"></option>
                      <option value="85"></option><option value="90"></option><option value="95"></option>
                      <option value="100"></option><option value="110"></option><option value="120"></option>
                    </datalist>
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Height (m)</label>
                    <input list="height-options" type="number" ref="heightInput" step="0.01" v-model="newRecord.height" @keydown.enter.prevent="doctorInput?.focus()" placeholder="1.65" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="height-options">
                      <option value="1.40"></option><option value="1.45"></option><option value="1.50"></option>
                      <option value="1.55"></option><option value="1.60"></option><option value="1.65"></option>
                      <option value="1.70"></option><option value="1.75"></option><option value="1.80"></option>
                      <option value="1.85"></option><option value="1.90"></option><option value="1.95"></option>
                    </datalist>
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-slate-700">Attending Doctor</label>
                    <input list="doctor-options" ref="doctorInput" type="text" v-model="newRecord.attendingDoctor" placeholder="Dr. Name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:bg-white transition-colors" />
                    <datalist id="doctor-options">
                      <option value="Dr. Sarah Jenkins"></option>
                      <option value="Dr. James Wilson"></option>
                      <option value="Dr. Amara Okonkwo"></option>
                      <option value="Dr. Michael Chen"></option>
                      <option value="Dr. Elena Rodriguez"></option>
                      <option value="Dr. David Smith"></option>
                      <option value="Dr. Sophia Patel"></option>
                    </datalist>
                  </div>
                </div>

                <!-- Footer -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3 mt-8">
                  <button type="button" @click="isAddModalOpen = false" class="px-5 py-2.5 text-[14px] font-semibold text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                    Cancel
                  </button>
                  <button type="submit" class="px-5 py-2.5 text-[14px] font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors shadow-sm">
                    {{ editingRecordId ? 'Update Record' : 'Save Record' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'
import { Activity, LayoutDashboard, Users, FileText, LogOut, Plus, Trash2, Search, HeartPulse, LineChart, CalendarCheck, Apple, Settings, ChevronDown, X, User, Edit, Eye } from 'lucide-vue-next'

// TypeScript Interfaces for strict typing
interface HealthRecord {
  id: string;
  date: string;
  patientName: string;
  patientId: string;
  gender: string;
  status: string;
  recordType: string;
  bloodPressure: string;
  pulse: string;
  weightHeight: string;
  bmi: string;
  attendingDoctor: string;
}

interface HealthRecordForm extends Omit<HealthRecord, 'id'> {
  weight?: string | number;
  height?: string | number;
}

const router = useRouter()
const username = ref('User') 
const isProfileOpen = ref(false)

// Dropdown state
const isGenderOpen = ref(false)
const filterGender = ref('(All)')
const isStatusOpen = ref(false)
const filterStatus = ref('Active')
const isRangeOpen = ref(false)
const filterRange = ref('(Last Month)')
const isSortOpen = ref(false)
const currentSort = ref('Newest First')
const searchQuery = ref('')

// Modal states
const isAddModalOpen = ref(false)
const isViewModalOpen = ref(false)
const viewingRecord = ref<HealthRecord | null>(null)
const editingRecordId = ref<string | null>(null)

const openViewModal = (record: HealthRecord) => {
  viewingRecord.value = record
  isViewModalOpen.value = true
}

const isTypeOpen = ref(false)
const isDateOpen = ref(false)

const bpInput = ref<HTMLInputElement | null>(null)
const pulseInput = ref<HTMLInputElement | null>(null)
const weightInput = ref<HTMLInputElement | null>(null)
const heightInput = ref<HTMLInputElement | null>(null)
const doctorInput = ref<HTMLInputElement | null>(null)

const recDay = ref<string | number>('')
const recMonth = ref('')
const recYear = ref<string | number>('')
const currentYear = new Date().getFullYear()

const months = [
  { value: '01', label: 'Jan' }, { value: '02', label: 'Feb' }, { value: '03', label: 'Mar' },
  { value: '04', label: 'Apr' }, { value: '05', label: 'May' }, { value: '06', label: 'Jun' },
  { value: '07', label: 'Jul' }, { value: '08', label: 'Aug' }, { value: '09', label: 'Sep' },
  { value: '10', label: 'Oct' }, { value: '11', label: 'Nov' }, { value: '12', label: 'Dec' }
]

const formattedDate = computed(() => {
  if (recDay.value && recMonth.value && recYear.value) {
    const m = months.find(m => m.value === recMonth.value)
    return `${m?.label} ${recDay.value}, ${recYear.value}`
  }
  return ''
})


interface RecentPatient {
  id: string;
  name: string;
  timestamp: number;
}

const recentPatients = ref<RecentPatient[]>([])

const loadRecentPatients = () => {
  try {
    const stored = localStorage.getItem('recentPatients')
    if (stored) {
      const parsed = JSON.parse(stored) as RecentPatient[]
      const oneHour = 60 * 60 * 1000
      const now = Date.now()
      // Only keep suggestions added within the last hour
      recentPatients.value = parsed.filter(p => now - p.timestamp < oneHour)
    }
  } catch (e) {
    console.error('Failed to load recent patients', e)
  }
}

onMounted(() => {
  loadRecentPatients()
})



// Form state
const newRecord = ref<HealthRecordForm>({
  date: '',
  patientName: '',
  patientId: '',
  gender: 'Male',
  status: 'Active',
  recordType: 'General Checkup',
  bloodPressure: '',
  pulse: '',
  weightHeight: '',
  bmi: '',
  attendingDoctor: '',
  weight: '',
  height: ''
})

watch(() => newRecord.value.patientName, (newName) => {
  const p = recentPatients.value.find(rp => rp.name === newName)
  if (p) {
    newRecord.value.patientId = p.id
  }
})

watch([recDay, recMonth, recYear], ([d, m, y]) => {
  if (d && m && y) {
    const dStr = d.toString().padStart(2, '0')
    newRecord.value.date = `${y}-${m}-${dStr}`
  } else {
    newRecord.value.date = ''
  }
})

watch([() => newRecord.value.weight, () => newRecord.value.height], ([w, h]) => {
  if (w && h) {
    const weight = Number(w)
    const height = Number(h)
    if (weight > 0 && height > 0) {
      newRecord.value.bmi = (weight / (height * height)).toFixed(1)
    } else {
      newRecord.value.bmi = ''
    }
  } else {
    newRecord.value.bmi = ''
  }
})

// Mock Data
const records = ref<HealthRecord[]>([
  // { id: 'HR-1005', date: '2026-08-12', patientName: 'Amara Okonkwo', patientId: 'P-1005', gender: 'Female', status: 'Active', recordType: 'Lab Results', bloodPressure: '118/78', pulse: '74', weightHeight: '58 kg / 1.62 m', bmi: '22.1', attendingDoctor: 'Dr. Sarah Jenkins' },
  // { id: 'HR-1004', date: '2026-08-08', patientName: 'James Wilson', patientId: 'P-1004', gender: 'Male', status: 'Active', recordType: 'Endocrinology Follow-up', bloodPressure: '128/82', pulse: '76', weightHeight: '91.2 kg / 1.75 m', bmi: '29.8', attendingDoctor: 'Dr. Emily Carter' },
  // { id: 'HR-1003', date: '2026-08-09', patientName: 'Sophia Chen', patientId: 'P-1003', gender: 'Female', status: 'Active', recordType: 'Routine Exam', bloodPressure: '115/75', pulse: '68', weightHeight: '55 kg / 1.65 m', bmi: '20.2', attendingDoctor: 'Dr. Sarah Jenkins' },
  // { id: 'HR-1002', date: '2026-08-11', patientName: 'Marcus Aurelius', patientId: 'P-1002', gender: 'Male', status: 'Inactive', recordType: 'Cardiology Evaluation', bloodPressure: '135/88', pulse: '81', weightHeight: '84 kg / 1.78 m', bmi: '26.5', attendingDoctor: 'Dr. Robert Miller' },
  // { id: 'HR-1001', date: '2026-08-10', patientName: 'Eleanor Vance', patientId: 'P-1001', gender: 'Female', status: 'Active', recordType: 'General Checkup', bloodPressure: '120/80', pulse: '72', weightHeight: '62.5 kg / 1.68 m', bmi: '22.1', attendingDoctor: 'Dr. Sarah Jenkins' }
])

const filteredRecords = computed(() => {
  let result = [...records.value]

  // 1. Search Filter
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(r => 
      r.patientName.toLowerCase().includes(q) || 
      r.patientId.toLowerCase().includes(q) ||
      r.attendingDoctor.toLowerCase().includes(q)
    )
  }

  // 2. Gender Filter
  if (filterGender.value !== '(All)') {
    result = result.filter(r => r.gender === filterGender.value)
  }

  // 3. Status Filter (UI toggles Active/Inactive)
  // If the user selects a specific status, filter. If it has an (All) option in the future, we skip.
  if (filterStatus.value) {
    result = result.filter(r => r.status === filterStatus.value)
  }

  // 4. Sort By
  result.sort((a, b) => {
    if (currentSort.value === 'Name (A to Z)') {
      return a.patientName.localeCompare(b.patientName)
    } else if (currentSort.value === 'Name (Z to A)') {
      return b.patientName.localeCompare(a.patientName)
    } else if (currentSort.value === 'Oldest First') {
      return new Date(a.date).getTime() - new Date(b.date).getTime()
    } else {
      // Newest First (default)
      return new Date(b.date).getTime() - new Date(a.date).getTime()
    }
  })

  return result
})

const getBadgeClass = (type: string) => {
  const map: Record<string, string> = {
    'Lab Results': 'bg-teal-50 text-teal-700 border-teal-100',
    'Endocrinology Follow-up': 'bg-emerald-50 text-emerald-700 border-emerald-100',
    'Routine Exam': 'bg-teal-50 text-teal-700 border-teal-100',
    'Cardiology Evaluation': 'bg-emerald-50 text-emerald-700 border-emerald-100',
    'General Checkup': 'bg-teal-50 text-teal-700 border-teal-100'
  }
  return map[type] || 'bg-slate-50 text-slate-700 border-slate-200'
}

const getBmiClass = (bmiStr: string) => {
  const bmi = parseFloat(bmiStr)
  if (isNaN(bmi)) return 'bg-slate-100 text-slate-700'
  if (bmi < 18.5) return 'bg-blue-100 text-blue-700'
  if (bmi >= 18.5 && bmi < 25) return 'bg-green-100 text-green-700'
  if (bmi >= 25 && bmi < 30) return 'bg-yellow-100 text-yellow-700'
  return 'bg-red-100 text-red-700'
}

// Modal Actions
const openAddModal = () => {
  editingRecordId.value = null
  loadRecentPatients() // Refresh list on open
  
  isTypeOpen.value = false
  isDateOpen.value = false
  
  const today = new Date()
  recYear.value = today.getFullYear()
  recMonth.value = (today.getMonth() + 1).toString().padStart(2, '0')
  recDay.value = today.getDate()

  newRecord.value = {
    date: new Date().toISOString().split('T')[0] || '',
    patientName: '',
    patientId: '',
    gender: 'Male',
    status: 'Active',
    recordType: 'General Checkup',
    bloodPressure: '',
    pulse: '',
    weightHeight: '',
    bmi: '',
    attendingDoctor: 'Dr. Sarah Jenkins',
    weight: '',
    height: ''
  }
  isAddModalOpen.value = true
}

const openEditModal = (record: HealthRecord) => {
  editingRecordId.value = record.id
  
  if (record.date) {
    const dParts = record.date.split('-')
    if (dParts.length === 3) {
      recYear.value = dParts[0]!
      recMonth.value = dParts[1]!
      recDay.value = parseInt(dParts[2]!)
    }
  } else {
    recDay.value = ''
    recMonth.value = ''
    recYear.value = ''
  }

  let w = '', h = ''
  if (record.weightHeight) {
    const parts = record.weightHeight.split(' / ')
    if (parts.length === 2) {
      w = parts[0]!.replace(' kg', '')
      h = parts[1]!.replace(' m', '')
    }
  }
  
  newRecord.value = { ...record, weight: w, height: h }
  isAddModalOpen.value = true
}

const deleteRecord = (id: string) => {
  if (confirm('Are you sure you want to delete this health record?')) {
    records.value = records.value.filter(r => r.id !== id)
  }
}

const saveRecord = () => {
  if (!newRecord.value.patientName) return

  const patientId = newRecord.value.patientId || `P-${Math.floor(1000 + Math.random() * 9000)}`
  
  let newIdNum = 1001
  if (!editingRecordId.value && records.value.length > 0) {
    const maxId = Math.max(...records.value.map(r => parseInt(r.id.split('-')[1] || '0')))
    newIdNum = maxId + 1
  }

  const recordToSave: HealthRecord = {
    id: editingRecordId.value || `HR-${newIdNum}`,
    date: newRecord.value.date,
    patientName: newRecord.value.patientName,
    patientId: patientId,
    gender: newRecord.value.gender,
    status: newRecord.value.status,
    recordType: newRecord.value.recordType,
    bloodPressure: newRecord.value.bloodPressure,
    pulse: newRecord.value.pulse.toString(),
    weightHeight: newRecord.value.weight && newRecord.value.height ? `${newRecord.value.weight} kg / ${newRecord.value.height} m` : newRecord.value.weightHeight,
    bmi: newRecord.value.bmi.toString(),
    attendingDoctor: newRecord.value.attendingDoctor
  }

  if (editingRecordId.value) {
    const index = records.value.findIndex(r => r.id === editingRecordId.value)
    if (index !== -1) {
      records.value[index] = recordToSave
    }
  } else {
    records.value.unshift(recordToSave)
    
    // Remove from recent patients suggestions once a record is added
    try {
      const stored = localStorage.getItem('recentPatients')
      if (stored) {
        const parsed = JSON.parse(stored) as RecentPatient[]
        const updated = parsed.filter(p => p.id !== patientId)
        localStorage.setItem('recentPatients', JSON.stringify(updated))
        loadRecentPatients()
      }
    } catch (e) {}
  }

  isAddModalOpen.value = false
}

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
