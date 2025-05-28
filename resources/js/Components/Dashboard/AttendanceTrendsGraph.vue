<template>
  <div class="flex flex-col col-span-full sm:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100">Attendance Trends</h2>
      <div class="flex items-center">
        <select 
          v-model="timeRange" 
          @change="fetchAttendanceData"
          class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white mr-2"
        >
          <option value="week">Last Week</option>
          <option value="month">Last Month</option>
          <option value="year">Last Year</option>
        </select>
        <Tooltip class="ml-2">
          <div class="text-xs text-center whitespace-nowrap">Daily church attendance</div>
        </Tooltip>
      </div>
    </header>
    <div class="px-5 py-3">
      <div v-if="loading" class="flex justify-center items-center h-[248px]">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
      </div>
      <div v-else-if="error" class="flex justify-center items-center h-[248px]">
        <p class="text-red-500">{{ error }}</p>
      </div>
      <FlexibleChart 
        v-else
        :data="chartData" 
        :width="595" 
        :height="248"
        :value-formatter="formatAttendance"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Tooltip from '@/Components/Mosaic/Tooltip.vue'
import FlexibleChart from '@/Components/Charts/FlexibleChart.vue'

// Import utilities
import { tailwindConfig, hexToRGB } from '@/Utils/Utils'

// Refs
const timeRange = ref('month')
const loading = ref(true)
const error = ref(null)
const attendanceData = ref(null)

// Format attendance numbers without currency symbol
const formatAttendance = (value) => Math.round(value).toString()

// Fetch attendance data
const fetchAttendanceData = async () => {
  loading.value = true
  error.value = null
  attendanceData.value = null

  try {
    const response = await axios.get(route('admin.attendance.trends'), {
      params: { timeRange: timeRange.value }
    })
    attendanceData.value = response.data
  } catch (err) {
    console.error('Error fetching attendance data:', err)
    error.value = 'Failed to load attendance data'
  } finally {
    loading.value = false
  }
}

// Computed chart data
const chartData = computed(() => {
  if (!attendanceData.value) return null

  return {
    labels: attendanceData.value.dates,
    datasets: [
      {
        label: 'Total Attendance',
        data: attendanceData.value.totals,
        fill: true,
        backgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.blue[500])}, 0.08)`,
        borderColor: tailwindConfig().theme.colors.indigo[500],
        borderWidth: 2,
        tension: 0,
        pointRadius: 0,
        pointHoverRadius: 3,
        pointBackgroundColor: tailwindConfig().theme.colors.indigo[500],
        clip: 20,
      },
    ],
  }
})

// Initial fetch on mount
onMounted(() => {
  fetchAttendanceData()
})
</script>

