<template>
    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
      <div class="px-5 pt-5">
        <header>
          <h3 class="text-sm font-semibold text-slate-500 uppercase mb-1">
            <span class="text-slate-800 dark:text-slate-100">{{title}}</span> - {{action}}
          </h3>
          <div class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-1">{{total}}</div>
          <div class="text-sm">
            <!-- <span class="font-medium text-red-500">-$49 (4,7%)</span> - Today -->
          </div>
        </header>
      </div>
      <!-- Chart built with Chart.js 3 -->
      <div class="grow">
        <!-- Change the height attribute to adjust the chart height -->
        <LineChart :data="chartData" width="286" height="98" />
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import LineChart from '@/Components/Charts/LineChart.vue'
import EditMenu from '@/Components/Mosaic/DropdownEditMenu.vue'

import SVG1 from '../../../../public/assets/images/icon-01.svg'
// Import utilities
import { tailwindConfig, hexToRGB } from '@/Utils/Utils.js'

const props = defineProps({
  chartData: [],
  title: String,
  total: String,
  action: String,
})

onMounted(() => {
  
})

const chartData = ref({
    labels: [
    '12-01-2020', '01-01-2021', '02-01-2021',
    '03-01-2021', '04-01-2021', '05-01-2021',
    '06-01-2021', '07-01-2021', '08-01-2021',
    '09-01-2021', '10-01-2021', '11-01-2021',
    '12-01-2021', '01-01-2022', '02-01-2022',
    '03-01-2022', '04-01-2022', '05-01-2022',
    '06-01-2022', '07-01-2022', '08-01-2022',
    '09-01-2022', '10-01-2022', '11-01-2022',
    '12-01-2022', '01-01-2023',
    ],
    datasets: [
    // Indigo line
    {
        data: [
        732, 610, 610, 504, 504, 504, 349,
        349, 504, 342, 504, 610, 391, 192,
        154, 273, 191, 191, 126, 263, 349,
        252, 423, 622, 470, 532,
        ],
        fill: true,
        backgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.blue[500])}, 0.08)`,
        borderColor: tailwindConfig().theme.colors.indigo[500],
        borderWidth: 2,
        tension: 0,
        pointRadius: 0,
        pointHoverRadius: 3,
        pointBackgroundColor: tailwindConfig().theme.colors.indigo[500],
        pointHoverBackgroundColor: tailwindConfig().theme.colors.indigo[500],
        pointBorderWidth: 0,
        pointHoverBorderWidth: 0,          
        clip: 20,
    },
    // Gray line
    {
        data: [
        532, 532, 532, 404, 404, 314, 314,
        314, 314, 314, 234, 314, 234, 234,
        314, 314, 314, 388, 314, 202, 202,
        202, 202, 314, 720, 642,
        ],
        borderColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
        borderWidth: 2,
        tension: 0,
        pointRadius: 0,
        pointHoverRadius: 3,
        pointBackgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
        pointHoverBackgroundColor: `rgba(${hexToRGB(tailwindConfig().theme.colors.slate[500])}, 0.25)`,
        pointBorderWidth: 0,
        pointHoverBorderWidth: 0,               
        clip: 20,
    },
    ],
})

</script>

<style  scoped>

</style>