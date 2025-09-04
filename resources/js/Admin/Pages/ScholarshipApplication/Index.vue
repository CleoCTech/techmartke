<template>
    <div>
        <Head title="Scholarship Applications" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Name</Th>
                        <Th>Email</Th>
                        <Th>Phone</Th>
                        <Th>Type</Th>
                        <Th>Status</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)" 
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <!-- Checkbox for selection -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        
                        <!-- Name -->
                        <Td>{{ record.name }}</Td>

                        <!-- Email -->
                        <Td>{{ record.email }}</Td>

                        <!-- Phone -->
                        <Td>{{ record.phone }}</Td>

                        <!-- Type -->
                        <Td>
                            <Badge :class="getTypeClass(record.type)">
                                {{ getTypeCaption(record.type) }}
                            </Badge>
                        </Td>

                        <!-- Status -->
                        <Td>
                            <Badge :class="getStatusClass(record.status)">
                                {{ getStatusCaption(record.status) }}
                            </Badge>
                        </Td>

                        <!-- Date Created -->
                        <Td>{{ record.created_at }}</Td>
                    </Tr>
                </template>
            </Table>
        </x-index-template>
    </div>
</template>

<script setup>
import { provide, getCurrentInstance } from 'vue';
import { indexProps, useIndex } from '@/Composables/useIndex';
import { Head } from '@inertiajs/vue3';
import Badge from '@/Components/Mosaic/Badge.vue';
import { ref } from 'vue';

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...indexProps });

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

// Use the index composable
const {
    xIndexTemplate,
    Table,
    Th,
    ThCheckbox,
    Td,
    TdCheckbox,
    Tr,
    xBadge,
    handleToggleSelectAll,
    isSelected,
    onCheck
} = useIndex(props);
</script>
