<template>

    <Head title="Dashboard" />
  
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
      <WelcomeBanner :name="$page.props.auth.user.name" :salutation="salutation" />
  
      <!-- Quick Actions -->
      <QuickActions />
  
      <!-- Card (Recent Activity) -->
      <!-- Cards -->
      <div class="grid grid-cols-12 gap-6">
        <AttendanceTrendsGraph class="col-span-full xl:col-span-6" />
        <RecentReports  class="col-span-full xl:col-span-6" />
        <!-- <PendingRequests class="col-span-full xl:col-span-6" /> -->
        <!-- <DepartmentOverview class="col-span-full xl:col-span-6" /> -->
        <FinancialOverview class="col-span-full xl:col-span-6" />
        <CalendarWidget class="col-span-full xl:col-span-6" />
        <!-- <RecentActivity class="col-span-full" /> -->
        <!-- <DashboardCard10 /> -->
        <!-- <DashboardCard05 /> -->
        <MediaGallery class="col-span-full xl:col-span-6" />
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
  import { ref, provide, getCurrentInstance, onMounted } from 'vue'
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
  
  import DashboardCard10 from '@/Components/Dashboard/DashboardCard10.vue'
  import DashboardCard05 from '@/Components/Dashboard/DashboardCard05.vue'
  
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
  
  onMounted(async () => {
    context.$showLoading()
    setSalutation();
    setTimeout(() => {
      context.$hideLoading()
    }, 3000);
  
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
    // chartData2.value = setChartData2();
    // chartOptions2.value = setChartOptions2();
  
  
    // isLoading.value = false;
  })
  
  </script>
  