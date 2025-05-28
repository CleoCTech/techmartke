<template>
  <div class="bg-slate-900 rounded-lg p-6">
    <!-- Department Header -->
    <div class="mb-4">
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold text-white">{{ department.name }}</h3>
        <span class="bg-slate-800 text-slate-300 px-2 py-1 rounded text-sm">{{ department.id }}</span>
      </div>
      <p class="text-slate-400 mt-2 text-sm">{{ department.description }}</p>
    </div>
  
    <!-- Metrics Grid -->
    <div class="grid grid-cols-4 gap-4 mb-8">
      <!-- Active Members -->
      <div class="flex flex-col items-center">
        <div class="bg-blue-900/50 p-3 rounded-full mb-2">
          <Users class="w-6 h-6 text-blue-400" />
        </div>
        <span class="text-2xl font-bold text-white">{{ statistics.activeMembersCount }}</span>
        <span class="text-xs text-slate-400">Active Members</span>
      </div>
  
      <!-- Active Goals -->
      <div class="flex flex-col items-center">
        <div class="bg-green-900/50 p-3 rounded-full mb-2">
          <Target class="w-6 h-6 text-green-400" />
        </div>
        <span class="text-2xl font-bold text-white">{{ statistics.activeGoalsCount }}</span>
        <span class="text-xs text-slate-400">Active Goals</span>
      </div>
  
      <!-- Pending Requests -->
      <div class="flex flex-col items-center">
        <div class="bg-amber-900/50 p-3 rounded-full mb-2">
          <ClipboardList class="w-6 h-6 text-amber-400" />
        </div>
        <span class="text-2xl font-bold text-white">{{ statistics.pendingRequestsCount }}</span>
        <span class="text-xs text-slate-400">Pending Requests</span>
      </div>
  
      <!-- Goals Progress -->
      <div class="flex flex-col items-center">
        <div class="bg-purple-900/50 p-3 rounded-full mb-2">
          <Activity class="w-6 h-6 text-purple-400" />
        </div>
        <div class="flex items-baseline">
          <span class="text-2xl font-bold text-white">{{ statistics.goalsProgress }}</span>
          <span class="text-white ml-1">%</span>
        </div>
        <span class="text-xs text-slate-400">Goals Progress</span>
      </div>
    </div>
  
    <!-- Department Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Department Leadership -->
      <div class="bg-slate-800/50 rounded-lg p-4">
        <h4 class="text-white font-semibold mb-4">Department Leadership</h4>
        <div v-if="loading" class="flex justify-center py-4">
          <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
        </div>
        <div v-else-if="error" class="text-red-400 text-center py-4">
          {{ error }}
        </div>
        <div v-else class="space-y-3">
          <div v-for="leader in departmentLeaders" :key="leader.id" 
            class="flex items-center space-x-3 p-2 rounded bg-slate-800/50">
            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center">
              <User2 class="w-5 h-5 text-slate-400" />
            </div>
            <div>
              <div class="text-white text-sm font-medium">{{ leader.name }}</div>
              <div class="text-slate-400 text-xs">{{ leader.role }}</div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Recent Activities -->
      <div class="bg-slate-800/50 rounded-lg p-4">
        <h4 class="text-white font-semibold mb-4">Recent Activities</h4>
        <div v-if="loading" class="flex justify-center py-4">
          <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
        </div>
        <div v-else-if="error" class="text-red-400 text-center py-4">
          {{ error }}
        </div>
        <div v-else class="space-y-3">
          <div v-for="activity in recentActivities" :key="activity.id"
            class="p-2 rounded bg-slate-800/50">
            <div class="flex justify-between items-start">
              <div class="text-white text-sm font-medium">{{ activity.title }}</div>
              <span class="text-xs text-slate-400">{{ formatDate(activity.date) }}</span>
            </div>
            <p class="text-slate-400 text-xs mt-1">{{ activity.description }}</p>
          </div>
        </div>
      </div>
  
      <!-- Goals Progress Details -->
      <div class="lg:col-span-2 bg-slate-800/50 rounded-lg p-4">
        <h4 class="text-white font-semibold mb-4">Goals Progress</h4>
        <div v-if="loading" class="flex justify-center py-4">
          <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
        </div>
        <div v-else-if="error" class="text-red-400 text-center py-4">
          {{ error }}
        </div>
        <div v-else class="space-y-4">
          <div v-for="goal in departmentGoals" :key="goal.id" class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="text-white text-sm">{{ goal.title }}</span>
              <span class="text-slate-400 text-sm">{{ goal.progress }}%</span>
            </div>
            <div class="w-full bg-slate-700 rounded-full h-2">
              <div 
                class="bg-purple-500 h-2 rounded-full transition-all duration-300"
                :style="{ width: `${goal.progress}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { Users, Target, ClipboardList, Activity, User2 } from 'lucide-vue-next';
  import axios from 'axios';
  
  const props = defineProps({
    departmentId: {
      type: [Number, String],
      required: true
    }
  });
  
  const loading = ref(true);
  const error = ref(null);
  const department = ref({});
  const statistics = ref({
    activeMembersCount: 0,
    activeGoalsCount: 0,
    pendingRequestsCount: 0,
    goalsProgress: 0
  });
  const departmentLeaders = ref([]);
  const recentActivities = ref([]);
  const departmentGoals = ref([]);
  
  const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric'
    });
  };
  
  const fetchDepartmentData = async () => {
    loading.value = true;
    error.value = null;
  
    try {
      const response = await axios.get(route('admin.department.overview', props.departmentId));
      const data = response.data;
  
      department.value = data.department;
      statistics.value = data.statistics;
      departmentLeaders.value = data.leaders;
      recentActivities.value = data.activities;
      departmentGoals.value = data.goals;
    } catch (err) {
      console.error('Error fetching department data:', err);
      error.value = 'Failed to load department information.';
    } finally {
      loading.value = false;
    }
  };
  
  onMounted(() => {
    fetchDepartmentData();
  });
  </script>
  
  