<template>
    <div>
      <Head :title="cardData.title" />
      <x-show-template :setup="setup" :selected="selected">
        <x-grid>
          <!-- Department -->
          <x-grid-col>
            <x-show-group>
              <template #label>Department</template>
              <template #value>{{ cardData.department?.name }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Fiscal Period -->
          <x-grid-col>
            <x-show-group>
              <template #label>Fiscal Period</template>
              <template #value>{{ cardData.fiscal_period?.period_name }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Title -->
          <x-grid-col>
            <x-show-group>
              <template #label>Title</template>
              <template #value>{{ cardData.title }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Description -->
          <x-grid-col class="sm:col-span-2">
            <x-show-group>
              <template #label>Description</template>
              <template #value>
                <div v-html="cardData.description"></div>
              </template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Activity Date -->
          <x-grid-col>
            <x-show-group>
              <template #label>Activity Date</template>
              <template #value>{{ cardData.activity_date }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Status -->
          <x-grid-col>
            <x-show-group>
              <template #label>Status</template>
              <template #value>
                <x-badge :class="getStatusClass(cardData.status)">
                  {{ getStatusCaption(cardData.status) }}
                </x-badge>
              </template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Remarks -->
          <x-grid-col class="sm:col-span-2">
            <x-show-group>
              <template #label>Remarks</template>
              <template #value>{{ cardData.remarks }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Created At -->
          <x-grid-col>
            <x-show-group>
              <template #label>Created At</template>
              <template #value>{{ cardData.created_at }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Created By -->
          <x-grid-col>
            <x-show-group>
              <template #label>Created By</template>
              <template #value>{{ cardData.created_by }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Updated At -->
          <x-grid-col>
            <x-show-group>
              <template #label>Updated At</template>
              <template #value>{{ cardData.updated_at }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Updated By -->
          <x-grid-col>
            <x-show-group>
              <template #label>Updated By</template>
              <template #value>{{ cardData.updated_by }}</template>
            </x-show-group>
          </x-grid-col>
        </x-grid>
      </x-show-template>
    </div>
    </template>
    
    <script setup>
    import { showProps, useShow } from '@/Composables/useShow.js';
    import { Head } from '@inertiajs/vue3';
    import { ref } from 'vue';
    
    // Define props
    const props = defineProps(showProps);
    
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
    
    // Use the show composable
    const {    
      xGrid,
      xGridCol,
      xShowGroup,
      xBadge,
      xShowTemplate
    } = useShow(props);
    </script>
    
    