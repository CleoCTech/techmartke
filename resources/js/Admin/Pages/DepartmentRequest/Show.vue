<template>
    <div>
      <Head :title="cardData.title" />
      <x-show-template :setup="setup" :selected="selected">
        <x-grid>
          <!-- From Department -->
          <x-grid-col>
            <x-show-group>
              <template #label>From Department</template>
              <template #value>{{ cardData.from_department?.name }}</template>
            </x-show-group>
          </x-grid-col>
  
          <!-- To Department -->
          <x-grid-col>
            <x-show-group>
              <template #label>To Department</template>
              <template #value>{{ cardData.to_department?.name }}</template>
            </x-show-group>
          </x-grid-col>
  
          <!-- Request Type -->
          <x-grid-col>
            <x-show-group>
              <template #label>Request Type</template>
              <template #value>{{ capitalizeFirstLetter(cardData.type) }}</template>
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
              <template #value>{{ cardData.description }}</template>
            </x-show-group>
          </x-grid-col>
  
          <!-- Status -->
          <x-grid-col>
            <x-show-group>
              <template #label>Status</template>
              <template #value>
                <x-badge :class="getStatusClass(cardData.status)">
                  {{ capitalizeFirstLetter(cardData.status) }}
                </x-badge>
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
  
  // Define props
  const props = defineProps(showProps);
  
  // Use the show composable
  const {    
    xGrid,
    xGridCol,
    xShowGroup,
    xBadge,
    xShowTemplate
  } = useShow(props);
  
  // Helper functions
  const capitalizeFirstLetter = (string) => {
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
  </script>
  
  