<template>
    <div>
        <Head title="Training Modules" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Title</Th>
                        <Th>Description</Th>
                        <Th>Sort Order</Th>
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
                        
                        <!-- Title -->
                        <Td>{{ record.title }}</Td>

                        <!-- Description -->
                        <Td>{{ record.description ? (record.description.length > 50 ? record.description.substring(0, 50) + '...' : record.description) : '-' }}</Td>

                        <!-- Sort Order -->
                        <Td>{{ record.sort_order }}</Td>

                        <!-- Status -->
                        <Td>
                            <Badge :class="getStatusClass(record.is_active)">
                                {{ getStatusCaption(record.is_active) }}
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
  { id: true, caption: 'Active', color: 'bg-green-500 text-white' },
  { id: false, caption: 'Inactive', color: 'bg-gray-400 text-white' },
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
