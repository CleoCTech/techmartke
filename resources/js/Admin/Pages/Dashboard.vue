<template>

  <Head title="Dashboard" />

  <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <WelcomeBanner :name="$page.props.auth.user.name" :salutation="salutation" />

    <!-- Quick Actions -->
    <QuickActions />

    <!-- Card (Recent Activity) -->
    <!-- Cards -->
    <div class="grid grid-cols-12 gap-6">
      <ApplicationStats class="col-span-full xl:col-span-6" />
      <AttendanceTrendsGraph class="col-span-full xl:col-span-6" />
      <RecentReports class="col-span-full xl:col-span-6" />
      <PendingRequests v-if="canViewPendingRequests" class="col-span-full xl:col-span-6" />
      <DepartmentSummary class="col-span-full xl:col-span-6" />
      <!-- <DepartmentOverview class="col-span-full xl:col-span-6"  
      v-for="department in departments" 
      :key="department.id" 
      :department-id="department.id"/> -->
      <FinancialOverview v-if="canAccessfinancesummary" class="col-span-full xl:col-span-6" />
      <CalendarWidget class="col-span-full xl:col-span-6" />
      <!-- <RecentActivity class="col-span-full" />
      <DashboardCard10 /> -->
      <!-- <DashboardCard05 /> -->
      <MediaGallery class="col-span-full xl:col-span-12" />
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-6">
         
        </div>
      </div>
    </div>

  </div>

</template>
<script setup>
import { ref, provide, getCurrentInstance, onMounted, computed } from 'vue'
import WelcomeBanner from '@/Admin/Partials/Dashboard/WelcomeBanner.vue'
import DashboardCard1 from '@/Components/Dashboard/DashboardCard.vue'
import StatsCard from '@/Components/Dashboard/StatsCard.vue'

import FilterButton from '@/Components/Mosaic/DropdownFilter.vue'
import Datepicker from '@/Components/Mosaic/Datepicker.vue'

import 'primevue/resources/themes/aura-dark-blue/theme.css'

import Chart from 'primevue/chart';

import RecentReports from '@/Components/Dashboard/RecentReports.vue'
import PendingRequests from '@/Components/Dashboard/PendingRequests.vue'
import AttendanceTrends from '@/Components/Dashboard/AttendanceTrends.vue'
import AttendanceTrendsGraph from '@/Components/Dashboard/AttendanceTrendsGraph.vue'
import FinancialOverview from '@/Components/Dashboard/FinancialOverview.vue'
import DepartmentOverview from '@/Components/Dashboard/DepartmentOverview.vue'
import MediaGallery from '@/Components/Dashboard/MediaGallery.vue'
import QuickActions from '@/Components/Dashboard/QuickActions.vue'
import RecentActivities from '@/Components/Dashboard/RecentActivities.vue'
import CalendarWidget from '@/Components/Dashboard/CalendarWidget.vue'
import DepartmentSummary from '@/Components/Dashboard/DepartmentSummary.vue'
import ApplicationStats from '@/Components/Dashboard/ApplicationStats.vue'

import DashboardCard10 from '@/Components/Dashboard/DashboardCard10.vue'
import DashboardCard05 from '@/Components/Dashboard/DashboardCard05.vue'
import axios from 'axios';
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const salutation = ref('');
const context = getCurrentInstance()?.appContext.config.globalProperties;

const setSalutation = () => {
  const currentTime = new Date();
  const currentHour = currentTime.getHours();

  if (currentHour >= 5 && currentHour < 12) {
    salutation.value = 'Good morning';
  } else if (currentHour >= 12 && currentHour < 18) {
    salutation.value = 'Good afternoon';
  } else {
    salutation.value = 'Good evening';
  }
};

const recentReports = ref([
  { id: 1, title: 'Sunday Service Report', minister: 'John Doe', date: '2023-06-18' },
  { id: 2, title: 'Youth Ministry Report', minister: 'Jane Smith', date: '2023-06-17' },
])
const pendingRequests = ref([
  { id: 1, title: 'New Equipment Request', department: 'Media', status: 'Pending' },
  { id: 2, title: 'Welfare Assistance', department: 'Welfare', status: 'Pending' },
])
const attendanceData = ref({
  labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
  datasets: [{ data: [150, 200, 180, 220] }]
})
const financialData = ref({
  labels: ['Tithes', 'Offerings', 'Special Gifts'],
  datasets: [{ data: [5000, 3000, 2000] }]
})



const departments = ref([]);

const fetchDepartments = async () => {
  try {
    const response = await axios.get('/admin/departments/list');
    departments.value = response.data;
  } catch (error) {
    console.error('Error fetching departments:', error);
  }
};

onMounted(async () => {
  try {
    context.$showLoading()
    setSalutation();
    await fetchDepartments();
  } catch (error) {
    console.error('Error in Dashboard onMounted:', error);
  } finally {
    setTimeout(() => {
      context.$hideLoading()
    }, 500);
  }

  // chartData.value = setChartData();
  // chartOptions.value = setChartOptions();
  // chartData2.value = setChartData2();
  // chartOptions2.value = setChartOptions2();


  // isLoading.value = false;
})

const canAccessfinancesummary = computed(() => {
  const permissions = [
    'canviewfinancesummary',
  ];

  return (
    user.value?.roles?.includes('administrator') ||
    user.value?.roles?.includes('superadmin') ||
    user.value?.permissions?.some(permission => permissions.includes(permission))
  );
});
const canViewPendingRequests = computed(() => {
  const permissions = [
    'canviewpendingrequests',
  ];

  return (
    user.value?.roles?.includes('administrator') ||
    user.value?.roles?.includes('superadmin') ||
    user.value?.permissions?.some(permission => permissions.includes(permission))
  );
});
</script>
