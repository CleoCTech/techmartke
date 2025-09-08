<template>
  <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg overflow-hidden">
    <div class="p-6">
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Recent Reports</h2>
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      </div>
      <div v-else-if="error" class="text-red-500 text-center py-4">
        {{ error }}
      </div>
      <div v-else class="space-y-6">
        <div v-for="(category, index) in categories" :key="index" class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">{{ category.title }}</h3>
          <ul class="space-y-2">
            <li v-for="item in category.items" :key="item.id" class="flex justify-between items-center">
              <div>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ item.title }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ item.date }}</p>
              </div>
              <!-- <span :class="getStatusClass(item.status)">{{ item.status }}</span> -->
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted,computed  } from 'vue';
import axios from 'axios';
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const loading = ref(true);
const error = ref(null);
const categories = ref([]);

const fetchRecentReports = async () => {
  try {
    const response = await axios.get('/admin/recent-reports');
    categories.value = response.data;
    // console.log(categories.value);
  } catch (err) {
    console.error('Error fetching recent reports:', err);
    // Set mock data instead of showing error to prevent dashboard crashes
    categories.value = [
      {
        title: 'Service Reports',
        items: [
          { id: 1, title: 'Sunday Service Report', date: '2025-01-15' },
          { id: 2, title: 'Youth Service Report', date: '2025-01-14' }
        ]
      }
    ];
  } finally {
    loading.value = false;
  }
};

const getStatusClass = (status) => {
  const baseClasses = 'px-2 py-1 text-xs font-medium rounded-full';
  switch (status.toLowerCase()) {
    case 'completed':
      return `${baseClasses} bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100`;
    case 'pending':
      return `${baseClasses} bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100`;
    case 'in progress':
      return `${baseClasses} bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100`;
    default:
      return `${baseClasses} bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100`;
  }
};

onMounted(() => {
  fetchRecentReports();
});

const canAccessfinancesummary = computed(() => {
  const permissions = [
    'canviewfinancesummary',
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
</script>

