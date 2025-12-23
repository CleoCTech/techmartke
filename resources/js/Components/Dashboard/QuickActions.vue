<template>
  <div class="mb-8">
    <div class="grid grid-cols-12 gap-6">
      <!-- Submit Report -->
      
      <!-- Add Request -->
      
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { FileText, ClipboardList, Calendar, Upload, ChevronRight, ChevronDown } from 'lucide-vue-next'
import { useToast } from '@/Composables/useToast'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

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

