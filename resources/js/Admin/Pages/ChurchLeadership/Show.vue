<template>
    <div>
      <Head :title="cardData.user?.name" />
      <x-show-template :setup="setup" :selected="selected">
        <x-grid>
          <!-- User -->
          <x-grid-col>
            <x-show-group>
              <template #label>User</template>
              <template #value>{{ cardData.user?.name }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Designation -->
          <x-grid-col>
            <x-show-group>
              <template #label>Designation</template>
              <template #value>{{ cardData.designation?.name }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Fiscal Period -->
          <x-grid-col>
            <x-show-group>
              <template #label>Fiscal Period</template>
              <template #value>{{ cardData.fiscal_period?.period_name }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Status -->
          <x-grid-col>
            <x-show-group>
              <template #label>Status</template>
              <template #value>
                <Badge :class="getStatusClass(cardData.status)">
                  {{ getStatusCaption(cardData.status) }}
                </Badge>
              </template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Date Created -->
          <x-grid-col>
            <x-show-group>
              <template #label>Date Created</template>
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
    
          <!-- Last Updated -->
          <x-grid-col>
            <x-show-group>
              <template #label>Last Updated</template>
              <template #value>{{ cardData.updated_at }}</template>
            </x-show-group>
          </x-grid-col>
    
          <!-- Last Updated By -->
          <x-grid-col>
            <x-show-group>
              <template #label>Last Updated By</template>
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
    import Badge from '@/Components/Mosaic/Badge.vue';
    import { ref } from 'vue';
    
    // Define props
    const props = defineProps(showProps);
    
    // Status options
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
    
    // Use the show composable
    const {    
      xGrid,
      xGridCol,
      xLoading,
      xPanel,
      xShowGroup,
      xBadge,
      xShowTemplate
    } = useShow(props);
    </script>