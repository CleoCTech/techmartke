<template>
    <div>
        <Head title="Department Heads" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Name</Th>
                        <Th>Department</Th>
                        <Th>Designation</Th>
                        <Th>Fiscal Period</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid + '#' + index" 
                        :isSelected="isSelected(record.uuid + '#' + index)" 
                        :url="setup.settings.indexRoute + '/show/' + record.uuid">
                        <TdCheckbox :item="record.uuid + '#' + listData.data.indexOf(record)" @onCheck="onCheck" />
                        <Td>{{ record.user.name }}</Td>
                        <Td>{{ record.department.name }}</Td>
                        <Td>{{ record.designation.name }}</Td>
                        <Td>{{ record.fiscal_period.period_name }}</Td>
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

const context = getCurrentInstance()?.appContext.config.globalProperties;
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
    Th,
    Table
} = useIndex(props);

// Provide necessary data to child components
provide('onRowClick', onRowClick);
provide('isMultipleSelect', isMultipleSelect);
</script>