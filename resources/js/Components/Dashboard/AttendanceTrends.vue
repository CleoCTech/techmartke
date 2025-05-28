<template>
  <div class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100">Attendance Trends</h2>
    </header>
    <div class="p-3">
      <div class="flex flex-col">
        <div v-for="(day, index) in attendanceData" :key="index" class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-700">
          <span class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ day.date }}</span>
          <div class="flex items-center">
            <div class="relative w-32 h-2 bg-slate-200 dark:bg-slate-700 rounded-full mr-3">
              <div 
                class="absolute top-0 left-0 h-2 bg-indigo-500 rounded-full" 
                :style="{ width: `${(day.attendance / maxAttendance) * 100}%` }"
              ></div>
            </div>
            <span class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ day.attendance }}</span>
          </div>
        </div>
      </div>
      <div class="mt-4 flex justify-between items-center">
        <div>
          <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase mb-1">Average</div>
          <div class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ averageAttendance }}</div>
        </div>
        <div>
          <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase mb-1">Total</div>
          <div class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ totalAttendance }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const attendanceData = [
  { date: 'Sun', attendance: 320 },
  { date: 'Mon', attendance: 180 },
  { date: 'Tue', attendance: 200 },
  { date: 'Wed', attendance: 250 },
  { date: 'Thu', attendance: 180 },
  { date: 'Fri', attendance: 200 },
  { date: 'Sat', attendance: 280 },
]

const maxAttendance = computed(() => Math.max(...attendanceData.map(day => day.attendance)))

const totalAttendance = computed(() => attendanceData.reduce((sum, day) => sum + day.attendance, 0))

const averageAttendance = computed(() => Math.round(totalAttendance.value / attendanceData.length))
</script>
