<template>

  <Head title="Dashboard" />

  <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <WelcomeBanner :name="$page.props.auth.user.name" :salutation="salutation" />

    <!-- Quick Actions -->
    <QuickActions />

    <!-- Cards -->
    <div class="grid grid-cols-12 gap-6">
      <WebTrafficStats class="col-span-full xl:col-span-6" />
      <CalendarWidget class="col-span-full xl:col-span-6" />
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

import WebTrafficStats from '@/Components/Dashboard/WebTrafficStats.vue'
import MediaGallery from '@/Components/Dashboard/MediaGallery.vue'
import QuickActions from '@/Components/Dashboard/QuickActions.vue'
import CalendarWidget from '@/Components/Dashboard/CalendarWidget.vue'

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




onMounted(async () => {
  try {
    context.$showLoading()
    setSalutation();
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

</script>
