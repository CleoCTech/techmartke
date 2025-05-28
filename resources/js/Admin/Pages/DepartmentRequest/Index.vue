<template>
    <div>
      <Head title="Department Requests" />
      <x-index-template :setup="setup" :listData="listData" :selected="selected">
        <Table :setup="setup" :listData="listData">
          <template #thead>
            <tr class="">
              <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
              <Th>From Department</Th>
              <Th>To Department</Th>
              <Th>Type</Th>
              <Th>Title</Th>
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
              
              <!-- From Department -->
              <Td>{{ record.from_department?.name }}</Td>
              
              <!-- To Department -->
              <Td>{{ record.to_department?.name }}</Td>
              
              <!-- Type -->
              <Td>{{ capitalizeFirstLetter(record.type) }}</Td>
              
              <!-- Title -->
              <Td>{{ record.title }}</Td>
              
              <!-- Status -->
              <Td>
                <Badge :class="getStatusClass(record.status)">
                  {{ capitalizeFirstLetter(record.status) }}
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
  import Badge from '@/Components/Mosaic/Badge.vue';
  import { Head } from '@inertiajs/vue3';
  
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
  
  // Helper functions
  const capitalizeFirstLetter = (string) => {
    string = String(string); // Ensure the input is a string
    return string.charAt(0).toUpperCase() + string.slice(1);
    }
  
  const getStatusClass = (status) => {
    switch (status) {
      case 'pending':
        return 'bg-yellow-100 text-yellow-800';
      case 'approved':
        return 'bg-green-100 text-green-800';
      case 'rejected':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  }
  
  // Provide necessary data to child components
  provide('onRowClick', onRowClick);
  provide('isMultipleSelect', isMultipleSelect);
  </script>
  
  