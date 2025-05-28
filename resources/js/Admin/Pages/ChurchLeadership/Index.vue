<template>
    <div>
      <Head title="Church Leadership" />
      <x-index-template :setup="setup" :listData="listData" :selected="selected">
        <Table :setup="setup" :listData="listData">
          <template #thead>
            <tr class="">
              <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
              <Th>Name</Th>
              <Th>Designation</Th>
              <Th>Fiscal Period</Th>
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
              
              <!-- Name -->
              <Td>{{ record.user?.name }}</Td>
              
              <!-- Designation -->
              <Td>{{ record.designation?.name }}</Td>
              
              <!-- Fiscal Period -->
              <Td>{{ record.fiscal_period?.period_name }}</Td>
              
              <!-- Date Created -->
              <Td>{{ record.created_at }}</Td>
              
              <!-- Status -->
              <Td>
                <Badge :class="getStatusClass(record.status)">
                  {{ getStatusCaption(record.status) }}
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
    import { ref } from 'vue';
    
    const context = getCurrentInstance()?.appContext.config.globalProperties;
    
    // Define props
    const props = defineProps({ ...indexProps });
    
    // Status mapping
    const statuses = ref([
      { id: 1, caption: 'Inactive', color: 'bg-gray-400 text-white' },
      { id: 2, caption: 'Active', color: 'bg-green-500 text-white' },
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