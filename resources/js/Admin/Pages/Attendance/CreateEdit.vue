<template>
    <div>
        <Head title="Attendance" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Service Selection -->
                    <x-grid-col class="col-span-12 sm:col-span-4">
                        <x-form-group>
                            <template #label>
                                <div class="flex items-center">
                                    <span>Service</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </div>
                            </template>
                            <template #value>
                                <div class="space-y-2">
                                    <!-- Empty services notification -->
                                    <div v-if="!services || services.length === 0" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-2">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    No services available. Please create a service first.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <x-select 
                                            v-model="form.service_id" 
                                            class="w-full" 
                                            :class="{ 'border-red-500': validationErrors.service_id }"
                                        >
                                            <option value="">-- Select Service --</option>
                                            <option v-for="service in services" :key="service.id" :value="service.id">
                                                {{ service.title }} ({{ service.service_date }})
                                            </option>
                                        </x-select>
                                        
                                        <Link 
                                            :href="route('admin.service')" 
                                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                        </Link>
                                    </div>
                                    
                                    <div v-if="validationErrors.service_id" class="text-red-500 text-sm mt-1">
                                        {{ validationErrors.service_id }}
                                    </div>
                                </div>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Total Attendance -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Total Attendance</template>
                            <template #value>
                                <TextInput type="number" v-model="form.total_attendance" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Adults -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Adults</template>
                            <template #value>
                                <TextInput type="number" v-model="form.adults" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Children -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Children</template>
                            <template #value>
                                <TextInput type="number" v-model="form.children" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Visitors -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Visitors</template>
                            <template #value>
                                <TextInput type="number" v-model="form.visitors" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Remarks -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Remarks</template>
                            <template #value>
                                <TextInput type="text" v-model="form.remarks" />
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit';
import { ref, reactive, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Link from '@/Components/Link.vue';

const props = defineProps({
    ...createEditProps,
    services: {
        type: Array,
        default: () => []
    } // Services passed from the backend
});

// Validation errors
const validationErrors = reactive({
    service_id: null
});
// Validate form before submission
const validateAndSubmit = () => {
    // Reset validation errors
    validationErrors.service_id = null;
    
    // Validate service_id
    if (!form.service_id) {
        validationErrors.service_id = 'Please select a service';
        return;
    }
    
    // If validation passes, submit the form
    submit();
};
// Initialize form
const form = reactive({
    uuid: null,
    service_id: null,
    total_attendance: null,
    adults: null,
    children: null,
    visitors: null,
    remarks: null,
});

// Set data if editing
const setData = () => {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.service_id = props.cardData.service_id;
        form.total_attendance = props.cardData.total_attendance;
        form.adults = props.cardData.adults;
        form.children = props.cardData.children;
        form.visitors = props.cardData.visitors;
        form.remarks = props.cardData.remarks;
    }
};

// Use the createEdit composable
const {
    submit,
    xGrid,
    xFormGroup,
    xGridCol,
    xSelect,
    xButton,
    TextInput,
    xCreateEditTemplate,
} = useCreateEdit(props, setData, form);
</script>

<style scoped>
/* Add custom styles if needed */
</style>