<template>
  <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg overflow-hidden">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Pending Requests</h2>
        <div class="flex items-center space-x-4">
          <select 
            v-model="filterType" 
            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm"
          >
            <option value="">All Types</option>
            <option value="resource">Resource</option>
            <option value="collaboration">Collaboration</option>
            <option value="budget">Budget</option>
            <option value="approval">Approval</option>
          </select>
          <button 
            @click="fetchRequests"
            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <RefreshCw class="w-5 h-5" />
          </button>
        </div>
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
        <!-- Requests Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">From</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">To</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr 
                v-for="request in filteredRequests" 
                :key="request.id"
                class="hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ request.title }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                      {{ truncateText(request.description, 50) }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                  {{ request.from_department_name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                  {{ request.to_department_name }}
                </td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 text-xs font-medium rounded-full capitalize"
                    :class="getTypeClass(request.type)"
                  >
                    {{ request.type }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(request.created_at) }}
                </td>
                <td class="px-6 py-4 text-sm font-medium">
                  <button 
                    @click="openRequestModal(request)"
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                  >
                    View Details
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Request Modal -->
    <PendingRequestModal
      :is-open="isModalOpen"
      :request="selectedRequest"
      @close="closeModal"
      @action="handleAction"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RefreshCw } from 'lucide-vue-next'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'
import PendingRequestModal from '@/Components/PendingRequestModal.vue'
import {useNotify} from "@/Composables/useNotify";

let {notification} = useNotify();

const loading = ref(true)
const error = ref(null)
const requests = ref([])
const filterType = ref('')
const isModalOpen = ref(false)
const selectedRequest = ref(null)

const { showToast } = useToast()

const filteredRequests = computed(() => {
  if (!filterType.value) return requests.value
  return requests.value.filter(request => request.type === filterType.value)
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const truncateText = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

const getTypeClass = (type) => {
  const classes = {
    resource: 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
    collaboration: 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
    budget: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
    approval: 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100'
  }
  return classes[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
}

const fetchRequests = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await axios.get(route('admin.pending-requests'))
    requests.value = response.data.requests
  } catch (err) {
    console.error('Error fetching pending requests:', err)
    error.value = 'Failed to load pending requests. Please try again later.'
  } finally {
    loading.value = false
  }
}

const openRequestModal = (request) => {
  selectedRequest.value = request
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  selectedRequest.value = null
}

const handleAction = async ({ type, comment }) => {
  try {
    await axios.post(route('admin.request.update-status', { id: selectedRequest.value.id }), {
      status: type === 'approve' ? 'approved' : 'rejected',
      remarks: comment
    })
    notification(`Request ${type}d successfully`, 'success');

    // showToast({
    //   message: `Request ${type}d successfully`,
    //   type: 'success'
    // })

    fetchRequests()
    closeModal()
  } catch (err) {
    console.error('Error updating request status:', err)
    notification('Failed to update request status', 'error');

    // showToast({
    //   message: 'Failed to update request status',
    //   type: 'error'
    // })
  }
}

onMounted(() => {
  fetchRequests()
})
</script>