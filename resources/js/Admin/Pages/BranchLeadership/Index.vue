<template>
    <div>
        <Head title="Branch Leadership" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Branch</Th>
                        <Th>Leader</Th>
                        <Th>Designation</Th>
                        <Th>Fiscal Period</Th>
                        <Th>Created At</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" 
                        :key="index" 
                        :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)"
                        :url="setup.settings.indexRoute+'/show/'+record.uuid"
                    >
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <Td>{{ record.branch?.name }}</Td>
                        <Td>{{ record.user?.name }}</Td>
                        <Td>{{ record.designation?.name }}</Td>
                        <Td>{{ record.fiscal_period?.period_name }}</Td>
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
import Badge from '@/Components/Mosaic/Badge.vue';
import { ref } from 'vue';

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...indexProps });

// Status mapping
const statuses = ref([
    { id: 'active', color: 'bg-green-500 text-white', caption: 'Active' },
    { id: 'inactive', color: 'bg-red-500 text-white', caption: 'Inactive' }
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
    selected,
    onCheck,
    onRowClick,
    isSelected,
    xTable,
    xTabletd,
    xTabletdCheckbox,
    xTablethCheckbox,
    xTableth,
    xTabletr,
    xBadge,
    xIndexTemplate, 
    isMultipleSelect, 
    destroy,
    ThCheckbox, 
    TdCheckbox,
    Td, 
    Tr, 
    selectedItems, 
    selectedItemsMap,
    handleToggleSelectAll,
    Th,
    Table
} = useIndex(props);

// Provide necessary data to child components
provide('onRowClick', onRowClick);
provide('isMultipleSelect', isMultipleSelect);
</script>

