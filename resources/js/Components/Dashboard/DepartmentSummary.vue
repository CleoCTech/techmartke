<template>
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-4">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Departments</h3>
        <Link 
          :href="route('admin.department.overview')"
          class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
        >
          View All
        </Link>
      </div>
    
      <div v-if="loading" class="flex justify-center items-center h-32">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
      </div>
    
      <div v-else-if="error" class="text-red-500 text-center py-4">
        {{ error }}
      </div>
    
      <div v-else class="grid grid-cols-2 gap-4">
        <div class="bg-indigo-100 dark:bg-indigo-900/30 rounded-lg p-3 flex items-center">
          <Users class="w-8 h-8 text-indigo-500 mr-3" />
          <div>
            <p class="text-2xl font-bold text-indigo-700 dark:text-indigo-300">{{ totalDepartments }}</p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400">Total Departments</p>
          </div>
        </div>
    
        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3 flex items-center">
          <UserCheck class="w-8 h-8 text-green-500 mr-3" />
          <div>
            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ activeDepartments }}</p>
            <p class="text-xs text-green-600 dark:text-green-400">Active Departments</p>
          </div>
        </div>
    
        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3 flex items-center">
          <Users class="w-8 h-8 text-blue-500 mr-3" />
          <div>
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ totalMembers }}</p>
            <p class="text-xs text-blue-600 dark:text-blue-400">Total Members</p>
          </div>
        </div>
    
        <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3 flex items-center">
          <Target class="w-8 h-8 text-yellow-500 mr-3" />
          <div>
            <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ activeGoals }}</p>
            <p class="text-xs text-yellow-600 dark:text-yellow-400">Active Goals</p>
          </div>
        </div>
      </div>
    
      <div v-if="!loading && !error" class="mt-4">
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600 dark:text-gray-400">Overall Progress</span>
          <span class="text-gray-900 dark:text-white">{{ overallProgress }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
          <div 
            class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
            :style="{ width: `${overallProgress}%` }"
          ></div>
        </div>
      </div>
    </div>
    </template>
    
    <script setup>
    import { ref, onMounted } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { Users, UserCheck, Target } from 'lucide-vue-next';
    
    const loading = ref(true);
    const error = ref(null);
    const totalDepartments = ref(0);
    const activeDepartments = ref(0);
    const totalMembers = ref(0);
    const activeGoals = ref(0);
    const overallProgress = ref(0);
    
    const fetchDepartmentSummary = async () => {
      try {
        const response = await axios.get(route('admin.departments.summary'));
        const data = response.data;
        totalDepartments.value = data.totalDepartments;
        activeDepartments.value = data.activeDepartments;
        totalMembers.value = data.totalMembers;
        activeGoals.value = data.activeGoals;
        overallProgress.value = data.overallProgress;
      } catch (err) {
        console.error('Error fetching department summary:', err);
        error.value = 'Failed to load department summary.';
      } finally {
        loading.value = false;
      }
    };
    
    onMounted(() => {
      fetchDepartmentSummary();
    });
    </script>
    
    