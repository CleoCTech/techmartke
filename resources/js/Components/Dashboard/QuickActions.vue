<template>
  <div class="mb-8">
    <div class="grid grid-cols-12 gap-6">
      <!-- Submit Report -->
      <div v-if="canSubmitRPTTab" class="col-span-full sm:col-span-6 xl:col-span-3">
        <div class="relative">
          <div 
            class="flex flex-col h-full bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 cursor-pointer hover:shadow-xl transition-shadow duration-200"
            @click="toggleReportDropdown"
          >
            <div class="px-5 py-5">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center bg-indigo-100 dark:bg-indigo-500/20">
                    <FileText class="w-5 h-5 text-indigo-500" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Submit Report</div>
                    <div class="text-xs text-slate-400 dark:text-slate-500">New service report</div>
                  </div>
                </div>
                <ChevronDown 
                  class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400 transition-transform duration-200"
                  :class="{ 'rotate-180': showReportDropdown }"
                />
              </div>
            </div>
          </div>
          
          <!-- Report Dropdown Menu -->
          <div 
            v-if="showReportDropdown" 
            class="absolute z-50 w-full mt-2 py-1 bg-white dark:bg-slate-800 rounded-md shadow-lg border border-slate-200 dark:border-slate-700"
          >
            <Link 
              v-for="(item, index) in reportLinks" 
              :key="index"
              :href="route(item.route)"
              class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700"
              @click="showReportDropdown = false"
            >
              {{ item.label }}
            </Link>
          </div>
        </div>
      </div>

      <!-- Add Request -->
      <div v-if="canAccessCreatRequestTab" class="col-span-full sm:col-span-6 xl:col-span-3">
        <div 
          class="flex flex-col h-full bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 cursor-pointer hover:shadow-xl transition-shadow duration-200"
          @click="openCreateRequestModal"
        >
          <div class="px-5 py-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-100 dark:bg-emerald-500/20">
                  <ClipboardList class="w-5 h-5 text-emerald-500" />
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Create Request</div>
                  <div class="text-xs text-slate-400 dark:text-slate-500">New department request</div>
                </div>
              </div>
              <ChevronRight class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400" />
            </div>
          </div>
        </div>
      </div>

      <!-- Schedule Event -->
      <Link v-if="canAccessEventsTab"
        :href="route('admin.event')"
        class="col-span-full sm:col-span-6 xl:col-span-3"
      >
        <div class="flex flex-col h-full bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-shadow duration-200">
          <div class="px-5 py-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-purple-100 dark:bg-purple-500/20">
                  <Calendar class="w-5 h-5 text-purple-500" />
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Schedule Event</div>
                  <div class="text-xs text-slate-400 dark:text-slate-500">Add new church event</div>
                </div>
              </div>
              <ChevronRight class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400" />
            </div>
          </div>
        </div>
      </Link>

      <!-- Upload Media -->
      <Link v-if="canAccessMediaTab"
        :href="route('admin.gallery')"
        class="col-span-full sm:col-span-6 xl:col-span-3"
      >
        <div class="flex flex-col h-full bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-shadow duration-200">
          <div class="px-5 py-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-rose-100 dark:bg-rose-500/20">
                  <Upload class="w-5 h-5 text-rose-500" />
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Upload Media</div>
                  <div class="text-xs text-slate-400 dark:text-slate-500">Add photos or videos</div>
                </div>
              </div>
              <ChevronRight class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400" />
            </div>
          </div>
        </div>
      </Link>
    </div>

    <!-- Create Request Modal -->
    <CreateRequestModal 
      :isOpen="isCreateRequestModalOpen"
      @close="closeCreateRequestModal"
      @submit="handleRequestSubmit"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { FileText, ClipboardList, Calendar, Upload, ChevronRight, ChevronDown } from 'lucide-vue-next'
import CreateRequestModal from '@/Admin/Pages/CreateRequestModal.vue'
import { useToast } from '@/Composables/useToast'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage();
const user = computed(() => page.props.auth.user);

const isCreateRequestModalOpen = ref(false)
const showReportDropdown = ref(false)
const { showToast } = useToast()

const reportLinks = [
  { label: 'Services', route: 'admin.service' },
  { label: 'Attendance', route: 'admin.attendance' },
  { label: 'Offerings', route: 'admin.offering' },
  { label: 'Testimonies', route: 'admin.testimony' }
]

const toggleReportDropdown = () => {
  showReportDropdown.value = !showReportDropdown.value
}

const closeDropdown = (e) => {
  if (!e.target.closest('.relative')) {
    showReportDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', closeDropdown)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown)
})

const openCreateRequestModal = () => {
  // console.log('Opening Create Request Modal')
  isCreateRequestModalOpen.value = true
}

const closeCreateRequestModal = () => {
  // console.log('Closing Create Request Modal')
  isCreateRequestModalOpen.value = false
}

const handleRequestSubmit = (formData) => {
  console.log('Request submitted:', formData)
  showToast('Request submitted successfully', 'success')
}

const canAccessCreatRequestTab = computed(() => {
  const permissions = [
    'managedepartment'
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
const canSubmitRPTTab = computed(() => {
  const permissions = [
    'managechurchservice',
    'managechurchattendance',
    'managechurchoffering',
    'managechurchtestimonies',
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
const canAccessEventsTab = computed(() => {
  const permissions = [
    'manageevents',
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
const canAccessMediaTab = computed(() => {
  const permissions = [
    'managegallery',
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
</script>

