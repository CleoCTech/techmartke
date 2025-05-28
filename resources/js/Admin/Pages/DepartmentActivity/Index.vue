<template>
    <div>
      <Head title="Department Activities" />
      <x-index-template :setup="setup" :listData="listData" :selected="selected">
        <Table :setup="setup" :listData="listData">
          <template #thead>
            <tr class="">
              <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
              <Th>Department</Th>
              <Th>Title</Th>
              <Th>Activity Date</Th>
              <Th>Status</Th>
              <Th>Created At</Th>
            </tr>
          </template>
          <template #tbody>
            <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                :isSelected="isSelected(record.uuid+'#'+index)" 
                :url="setup.settings.indexRoute+'/show/'+record.uuid">
              
              <!-- Checkbox for selection -->
              <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
              
              <!-- Department -->
              <Td>{{ record.department?.name }}</Td>
              
              <!-- Title -->
              <Td>{{ record.title }}</Td>
              
              <!-- Activity Date -->
              <Td>{{ record.activity_date }}</Td>
              
              <!-- Status -->
              <Td>
                <Badge :class="getStatusClass(record.status)">
                  {{ getStatusCaption(record.status) }}
                </Badge>
              </Td>
              
              <!-- Created At -->
              <Td>{{ record.created_at }}</Td>
            </Tr>
          </template>
        </Table>
      </x-index-template>
    </div>
    </template>
    
    <script setup>
    import { provide, getCurrentInstance, ref } from 'vue';
    import { indexProps, useIndex } from '@/Composables/useIndex';
    import Badge from '@/Components/Mosaic/Badge.vue';
    import { Head } from '@inertiajs/vue3';
    
    const context = getCurrentInstance()?.appContext.config.globalProperties;
    
    // Define props
    const props = defineProps({ ...indexProps });
    
    // Status mapping
    const statuses = ref([
      { id: 'planned', color: 'bg-blue-500 text-white', caption: 'Planned' },
      { id: 'in_progress', color: 'bg-yellow-500 text-black', caption: 'In Progress' },
      { id: 'completed', color: 'bg-green-500 text-white', caption: 'Completed' },
      { id: 'cancelled', color: 'bg-red-500 text-white', caption: 'Cancelled' }
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
    