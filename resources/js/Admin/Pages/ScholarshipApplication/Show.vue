<template>
    <div>
        <Head title="Scholarship Application Details" />
        <x-show-template :setup="setup" :selected="selected">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.phone }}</p>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Application Details</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scholarship Type</label>
                            <div class="mt-1">
                                <Badge :class="getTypeClass(cardData.type)">
                                    {{ getTypeCaption(cardData.type) }}
                                </Badge>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1">
                                <Badge :class="getStatusClass(cardData.status)">
                                    {{ getStatusCaption(cardData.status) }}
                                </Badge>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.created_at }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.updated_at }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Additional Notes</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-900">{{ cardData.notes || 'No additional notes provided.' }}</p>
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Attachments</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Application form and supporting documents will be displayed here.</p>
                    </div>
                </div>
            </div>
        </x-show-template>
    </div>
</template>

<script setup>
import { showProps, useShow } from '@/Composables/useShow';
import { Head } from '@inertiajs/vue3';
import Badge from '@/Components/Mosaic/Badge.vue';
import { ref } from 'vue';

const props = defineProps({ ...showProps });

// Status mapping
const statuses = ref([
  { id: 1, caption: 'New', color: 'bg-gray-400 text-white' },
  { id: 2, caption: 'Pending Approval', color: 'bg-yellow-500 text-white' },
  { id: 3, caption: 'Approved', color: 'bg-green-500 text-white' },
  { id: 4, caption: 'Rejected', color: 'bg-red-500 text-white' },
]);

// Type mapping
const types = ref([
  { id: 'student', caption: 'Student Scholarship', color: 'bg-blue-500 text-white' },
  { id: 'professional', caption: 'Professional Scholarship', color: 'bg-green-500 text-white' },
]);

// Get the class for the status badge
function getStatusClass(status) {
  const statusObj = statuses.value.find((s) => s.id === status);
  return statusObj ? statusObj.color : 'bg-gray-300 text-black';
}

// Get the caption for the status badge
function getStatusCaption(status) {
  const statusObj = statuses.value.find((s) => s.id === status);
  return statusObj ? statusObj.caption : 'Unknown';
}

// Get the class for the type badge
function getTypeClass(type) {
  const typeObj = types.value.find((t) => t.id === type);
  return typeObj ? typeObj.color : 'bg-gray-300 text-black';
}

// Get the caption for the type badge
function getTypeCaption(type) {
  const typeObj = types.value.find((t) => t.id === type);
  return typeObj ? typeObj.caption : 'Unknown';
}

const { xShowTemplate } = useShow(props);
</script>
