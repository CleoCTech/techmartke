<template>
    <div>
        <Head title="Departments" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Name</Th>
                        <Th>Description</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" 
                        :row="record.uuid+'#'+index"
                        :isSelected="isSelected(record.uuid+'#'+index)"
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <TdCheckbox :item="record.uuid+'#'+index" @onCheck="onCheck"/>
                        <Td>{{ record.name }}</Td>
                        <Td class="truncate max-w-[200px]" > {{ truncateDescription(record.description, 50) }}</Td>
                        <Td>{{ record.created_at }}</Td>
                    </Tr>
                </template>
            </Table>
        </x-index-template>
    </div>
</template>

<script setup>
import { provide } from 'vue';
import { indexProps, useIndex } from '@/Composables/useIndex';

const props = defineProps({ ...indexProps });


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
    truncateDescription,
    Th,
    Table
} = useIndex(props);

// Provide necessary data to child components
provide('onRowClick', onRowClick);
provide('isMultipleSelect', isMultipleSelect);
</script>