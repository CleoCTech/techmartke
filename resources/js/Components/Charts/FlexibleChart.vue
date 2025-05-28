<template>
  <div class="flex flex-col h-full">
    <div v-if="showHeader" class="px-5 py-3">
      <div class="flex items-start">
        <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2 tabular-nums">
          <span ref="chartValue">{{ currentValue }}</span>
        </div>
        <div v-if="showDeviation" ref="chartDeviation" class="text-sm font-semibold text-white px-1.5 rounded-full"></div>
      </div>
    </div>
    <div class="grow">
      <canvas ref="canvas" :width="width" :height="height"></canvas>
    </div>
  </div>
</template>

<script>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import { useDark } from '@vueuse/core'
import { chartColors } from './ChartjsConfig'

import {
  Chart, LineController, LineElement, PointElement, LinearScale, TimeScale, Tooltip,
} from 'chart.js'
import 'chartjs-adapter-moment'

// Import utilities
import { tailwindConfig } from '@/Utils/Utils'

Chart.register(LineController, LineElement, PointElement, LinearScale, TimeScale, Tooltip)

export default {
  name: 'FlexibleChart',
  props: {
    data: {
      type: Object,
      required: true
    },
    width: {
      type: [Number, String],
      default: 595
    },
    height: {
      type: [Number, String],
      default: 248
    },
    showHeader: {
      type: Boolean,
      default: true
    },
    showDeviation: {
      type: Boolean,
      default: true
    },
    valueFormatter: {
      type: Function,
      default: value => value
    }
  },
  setup(props) {
    const canvas = ref(null)
    const chartValue = ref(null)
    const chartDeviation = ref(null)
    let chart = null
    const darkMode = useDark()
    const { textColor, gridColor, tooltipTitleColor, tooltipBodyColor, tooltipBgColor, tooltipBorderColor } = chartColors

    const currentValue = computed(() => {
      const dataset = props.data.datasets[0]
      return props.valueFormatter(dataset.data[dataset.data.length - 1])
    })

    const handleHeaderValues = (data) => {
      if (!props.showHeader) return

      const currentValue = data.datasets[0].data[data.datasets[0].data.length - 1]
      const previousValue = data.datasets[0].data[data.datasets[0].data.length - 2]
      
      if (chartValue.value) {
        chartValue.value.innerHTML = props.valueFormatter(currentValue)
      }

      if (props.showDeviation && chartDeviation.value && previousValue) {
        const diff = ((currentValue - previousValue) / previousValue) * 100
        chartDeviation.value.style.backgroundColor = diff < 0 
          ? tailwindConfig().theme.colors.amber[500]
          : tailwindConfig().theme.colors.emerald[500]
        chartDeviation.value.innerHTML = `${diff > 0 ? '+' : ''}${diff.toFixed(2)}%`
      }
    }    
    
    onMounted(() => {
      const ctx = canvas.value
      chart = new Chart(ctx, {
        type: 'line',
        data: props.data,
        options: {
          layout: {
            padding: 20,
          },
          scales: {
            y: {
              border: {
                display: false,
              },
              suggestedMin: 0,
              suggestedMax: 300,
              ticks: {
                maxTicksLimit: 6,
                callback: (value) => props.valueFormatter(value),
                color: darkMode.value ? textColor.dark : textColor.light,
              },
              grid: {
                color: darkMode.value ? gridColor.dark : gridColor.light,
              },              
            },
            x: {
              type: 'time',
              time: {
                parser: 'YYYY-MM-DD HH:mm:ss',
                unit: 'day',
                displayFormats: {
                  day: 'MMM DD'
                }
              },
              border: {
                display: false,
              },
              grid: {
                display: false,
              },
              ticks: {
                autoSkipPadding: 48,
                maxRotation: 0,
                color: darkMode.value ? textColor.dark : textColor.light,
              },
            },
          },
          plugins: {
            legend: {
              display: false,
            },
            tooltip: {
              titleFont: {
                weight: '600',
              },
              callbacks: {
                label: (context) => props.valueFormatter(context.parsed.y),
              },
              titleColor: darkMode.value ? tooltipTitleColor.dark : tooltipTitleColor.light,
              bodyColor: darkMode.value ? tooltipBodyColor.dark : tooltipBodyColor.light,
              backgroundColor: darkMode.value ? tooltipBgColor.dark : tooltipBgColor.light,
              borderColor: darkMode.value ? tooltipBorderColor.dark : tooltipBorderColor.light,               
            },
          },
          interaction: {
            intersect: false,
            mode: 'nearest',
          },
          animation: false,
          maintainAspectRatio: false,
        },
      })
      handleHeaderValues(props.data)
    })

    onUnmounted(() => chart?.destroy())

    watch(() => props.data, (data) => {
      if (chart) {
        chart.data = data
        chart.update('none')
        handleHeaderValues(data)
      }
    })

    watch(() => darkMode.value, () => {
      if (!chart) return
      
      if (darkMode.value) {
        chart.options.scales.x.ticks.color = textColor.dark
        chart.options.scales.y.ticks.color = textColor.dark
        chart.options.scales.y.grid.color = gridColor.dark
        chart.options.plugins.tooltip.titleColor = tooltipTitleColor.dark
        chart.options.plugins.tooltip.bodyColor = tooltipBodyColor.dark
        chart.options.plugins.tooltip.backgroundColor = tooltipBgColor.dark
        chart.options.plugins.tooltip.borderColor = tooltipBorderColor.dark
      } else {
        chart.options.scales.x.ticks.color = textColor.light
        chart.options.scales.y.ticks.color = textColor.light
        chart.options.scales.y.grid.color = gridColor.light
        chart.options.plugins.tooltip.titleColor = tooltipTitleColor.light
        chart.options.plugins.tooltip.bodyColor = tooltipBodyColor.light
        chart.options.plugins.tooltip.backgroundColor = tooltipBgColor.light
        chart.options.plugins.tooltip.borderColor = tooltipBorderColor.light
      }
      chart.update('none')
    })

    return {
      canvas,
      chartValue,
      chartDeviation,
      currentValue,
    }
  }
}
</script>
