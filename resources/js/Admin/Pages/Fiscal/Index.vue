<template>
    <div>
        <Head title="Fiscal Years" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Year Name</Th>
                        <Th>Start Date</Th>
                        <Th>End Date</Th>
                        <Th>Is Current</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)" 
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <!-- Checkbox for selection -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        
                        <!-- Year Name -->
                        <Td>{{ record.year }}</Td>
                        
                        <!-- Start Date -->
                        <Td>{{ record.start_date }}</Td>
                        
                        <!-- End Date -->
                        <Td>{{ record.end_date }}</Td>
                        
                        <!-- Is Current -->
                        <Td>
                            <Badge v-if="record.is_current" class="bg-green-500">Current</Badge>
                            <Badge v-else class="bg-gray-500">Inactive</Badge>
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

<style scoped>
/* Add custom styles if needed */
</style>