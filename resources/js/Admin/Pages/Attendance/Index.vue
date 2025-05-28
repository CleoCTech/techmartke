<template>
    <div>
        <Head title="Attendances" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Service</Th>
                        <Th>Total Attendance</Th>
                        <Th>Adults</Th>
                        <Th>Children</Th>
                        <Th>Visitors</Th>
                        <Th>Remarks</Th>
                        <Th>Date Created</Th>
                        <Th>Status</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)" 
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <!-- Checkbox for selection -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        
                        <!-- Service -->
                        <Td>{{ record.service?.title }} ({{ record.service?.service_date }})</Td>
                        
                        <!-- Total Attendance -->
                        <Td>{{ record.total_attendance }}</Td>
                        
                        <!-- Adults -->
                        <Td>{{ record.adults }}</Td>
                        
                        <!-- Children -->
                        <Td>{{ record.children }}</Td>
                        
                        <!-- Visitors -->
                        <Td>{{ record.visitors }}</Td>
                        
                        <!-- Remarks -->
                        <Td>{{ record.remarks }}</Td>
                        
                        <!-- Date Created -->
                        <Td>{{ record.created_at }}</Td>
                        
                        <!-- Status -->
                        <Td>
                            <Badge :class="setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].color">
                                {{ setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].caption }}
                            </Badge>
                        </Td>
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

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...indexProps });

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