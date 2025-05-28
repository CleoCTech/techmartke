<template>
  <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg overflow-hidden">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Financial Overview</h2>
        <select 
          v-model="timeRange" 
          class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm"
          @change="fetchFinancialData"
        >
          <option value="month">This Month</option>
          <option value="year">Year to Date</option>
        </select>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-red-500 text-center py-4">
        {{ error }}
      </div>

      <!-- Content -->
      <div v-else class="space-y-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white dark:bg-slate-700 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Offerings</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
              {{ formatCurrency(summaryData.total_offerings) }}
            </p>
            <span class="text-xs text-green-500">
              +{{ calculateGrowth(summaryData.total_offerings) }}% from last {{ timeRange }}
            </span>
          </div>

          <div class="bg-white dark:bg-slate-700 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tithes</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
              {{ formatCurrency(summaryData.total_tithes) }}
            </p>
            <span class="text-xs text-green-500">
              +{{ calculateGrowth(summaryData.total_tithes) }}% from last {{ timeRange }}
            </span>
          </div>

          <div class="bg-white dark:bg-slate-700 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cash Offerings</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
              {{ formatCurrency(summaryData.cash_offerings) }}
            </p>
            <span class="text-xs text-green-500">
              +{{ calculateGrowth(summaryData.cash_offerings) }}% from last {{ timeRange }}
            </span>
          </div>

          <div class="bg-white dark:bg-slate-700 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Online Offerings</h3>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
              {{ formatCurrency(summaryData.online_offerings) }}
            </p>
            <span class="text-xs text-green-500">
              +{{ calculateGrowth(summaryData.online_offerings) }}% from last {{ timeRange }}
            </span>
          </div>
        </div>

        <!-- Chart -->
        <div class="h-[300px]">
          <!-- <LineChart
            v-if="chartData && chartData.labels && chartData.labels.length > 0"
            :chart-data="chartData"
            :options="chartOptions"
          />
          <div v-else class="flex items-center justify-center h-full">
            <p class="text-gray-500 dark:text-gray-400">No data available for the selected time range.</p>
          </div> -->
        </div>

        <!-- Debug Information -->
        <!-- <div class="mt-4 p-4 bg-gray-100 dark:bg-slate-700 rounded-lg">
          <h3 class="text-lg font-semibold mb-2">Debug Information:</h3>
          <pre class="text-xs overflow-auto">{{ debugInfo }}</pre>
        </div> -->

        <!-- Recent Transactions -->
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Collections</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Service ID
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Amount
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Branch
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="transaction in recentTransactions" :key="transaction.service_id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {{ transaction.service.title }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {{ formatCurrency(transaction.total_offerings) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ formatDate(transaction.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ transaction.branch.name }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Line as LineChart } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const loading = ref(true);
const error = ref(null);
const timeRange = ref('month');
const debugInfo = ref({});

const summaryData = ref({
  total_offerings: 0,
  total_tithes: 0,
  cash_offerings: 0,
  online_offerings: 0
});
const monthlyTrends = ref([]);
const recentTransactions = ref([]);

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'KES'
  }).format(value || 0);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const calculateGrowth = (currentValue) => {
  // Placeholder for growth calculation
  return ((Math.random() * 10) + 5).toFixed(1);
};

const chartData = computed(() => {
  console.log('Computing chartData');
  console.log('monthlyTrends:', monthlyTrends.value);

  // Initialize with empty data structure
  const defaultData = {
    labels: [],
    datasets: []
  };

  if (!monthlyTrends.value || !Array.isArray(monthlyTrends.value) || monthlyTrends.value.length === 0) {
    console.log('No monthly trends data');
    return defaultData;
  }

  try {
    const labels = monthlyTrends.value.map(item => {
      const date = new Date(item.year || 0, (item.month || 1) - 1);
      return date.toLocaleString('default', { month: 'short', year: 'numeric' });
    });

    console.log('Labels:', labels);

    const datasets = [
      {
        label: 'Total Offerings',
        data: monthlyTrends.value.map(item => Number(item.total_offerings) || 0),
        borderColor: '#3b82f6',
        tension: 0.3
      },
      {
        label: 'Total Tithes',
        data: monthlyTrends.value.map(item => Number(item.total_tithes) || 0),
        borderColor: '#10b981',
        tension: 0.3
      }
    ];

    console.log('Datasets:', datasets);
    return { labels, datasets };
  } catch (error) {
    console.error('Error computing chart data:', error);
    return defaultData;
  }
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    intersect: false,
    mode: 'index'
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: (value) => formatCurrency(value)
      }
    }
  },
  plugins: {
    legend: {
      position: 'top'
    }
  }
};

const fetchFinancialData = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.get(route('admin.financial-overview'));
    const data = response.data;
    console.log(data);

    // Update summary data
    // summaryData.value = timeRange.value === 'month' ? data.currentMonth : data.yearToDate;
    summaryData.value =  data.yearToDate;

    // Update monthly trends
    monthlyTrends.value = data.monthlyTrends;

    // Update recent transactions
    recentTransactions.value = data.recentTransactions;

    // Update debug info
    updateDebugInfo();
  } catch (err) {
    console.error('Error fetching financial data:', err);
    error.value = 'Failed to load financial data. Please try again later.';
  } finally {
    loading.value = false;
  }
};

const updateDebugInfo = () => {
  debugInfo.value = {
    selectedTimeRange: timeRange.value,
    monthlyTrends: monthlyTrends.value,
    chartDataLabels: chartData.value?.labels,
    chartDataDatasets: chartData.value?.datasets.map(ds => ({
      label: ds.label,
      dataLength: ds.data.length,
      data: ds.data
    }))
  };
};

onMounted(() => {
  fetchFinancialData();
});
</script>