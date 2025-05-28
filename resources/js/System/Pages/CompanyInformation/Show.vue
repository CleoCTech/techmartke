<template>
    <div>
      <Head :title="cardData.title" />
      <x-show-template :setup="setup" :selected="selected">
        <x-grid>
          <x-grid-col>
            <x-show-group>
              <template #label>Church Name</template>
              <template #value>{{ cardData.company_name }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Short Name</template>
              <template #value>{{ cardData.short_name }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Date of Establishment</template>
              <template #value>{{ cardData.establishment_date }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Vision</template>
              <template #value>{{ cardData.vision }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Mission</template>
              <template #value>{{ cardData.mission }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Location</template>
              <template #value>{{ cardData.location }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Phone Numbers</template>
              <template #value>{{ cardData.phone_numbers }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Emails</template>
              <template #value>{{ cardData.emails }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Mission</template>
              <template #value>{{ cardData.mission }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Address</template>
              <template #value>{{ cardData.address }}</template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col class="sm:col-span-3">
            <x-show-group class="sm:grid-cols-1">
              <template #label>History</template>
              <template #value><span v-html="cardData.history"></span></template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col class="sm:col-span-3">
            <x-show-group class="sm:grid-cols-1">
              <template #label>What we do</template>
              <template #value><span v-html="cardData.about"></span></template>
            </x-show-group>
          </x-grid-col>
            <x-grid-col>
              <x-show-group>
                <template #label>Logo</template>
                <template #value>
                  <img :src="$page.props.storagePaths[setup.settings.storageName].images.readPath + cardData.logo" class="h-28 w-28" />
                </template>
              </x-show-group>
            </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Status</template>
              <template #value>
                <x-badge :class="setup.statuses[setup.statuses.findIndex(x => x.id === cardData.status)].color">{{ setup.statuses[setup.statuses.findIndex(x => x.id === cardData.status)].caption }}</x-badge>
              </template>
            </x-show-group>
          </x-grid-col>
          <x-grid-col>
            <x-show-group>
              <template #label>Last Updated</template>
              <template #value>{{ cardData.updated_at }}</template>
            </x-show-group>
          </x-grid-col>
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
  const props = defineProps(showProps);
  const { xGrid, xGridCol, xLoading, xPanel, xShowGroup, xBadge, xShowTemplate } = useShow(props);
  </script>
  
  <style>
  /* Add your styles here */
  </style>
  