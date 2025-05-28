<template>
    <div>
        <Head title="Designations" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Name</Th>
                        <Th>Type</Th>
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
                        <Td>
                            <Badge :class="getTypeClass(record.type)">
                                {{ getTypeCaption(record.type) }}
                            </Badge>
                        </Td>
                        <Td class="truncate max-w-[200px]">{{ record.description }}</Td>
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
import Badge from '@/Components/Mosaic/Badge.vue';

const props = defineProps({ ...indexProps });

// Type styling configuration
const types = [
  { id: 'spiritual', color: 'bg-purple-500 text-white', caption: 'Spiritual' },
  { id: 'administrative', color: 'bg-blue-500 text-white', caption: 'Administrative' },
  { id: 'operational', color: 'bg-amber-500 text-white', caption: 'Operational' },
  { id: 'general', color: 'bg-gray-500 text-white', caption: 'General' },
];

function getTypeClass(type) {
  return types.find(t => t.id === type)?.color || 'bg-gray-300';
}

function getTypeCaption(type) {
  return types.find(t => t.id === type)?.caption || 'Unknown';
}

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