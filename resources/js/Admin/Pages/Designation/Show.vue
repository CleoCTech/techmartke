<template>
    <div>
        <Head :title="cardData.name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Name</template>
                        <template #value>{{ cardData.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Type</template>
                        <template #value>
                            <Badge :class="getTypeClass(cardData.type)">
                                {{ getTypeCaption(cardData.type) }}
                            </Badge>
                        </template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Description</template>
                        <template #value>{{ cardData.description }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{ cardData.created_at }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Created By</template>
                        <template #value>{{ cardData.created_by }}</template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>

<script setup>
import { showProps, useShow } from '@/Composables/useShow';
import { Head } from '@inertiajs/vue3';
import Badge from '@/Components/Mosaic/Badge.vue';

const props = defineProps(showProps);

// Reuse type styling logic from Index
const types = [
  { id: 'spiritual', color: 'bg-purple-500 text-white', caption: 'Spiritual' },
  { id: 'administrative', color: 'bg-blue-500 text-white', caption: 'Administrative' },
  { id: 'operational', color: 'bg-orange-500 text-white', caption: 'Operational' },
  { id: 'general', color: 'bg-gray-500 text-white', caption: 'General' },
];

function getTypeClass(type) {
  return types.find(t => t.id === type)?.color || 'bg-gray-300';
}

function getTypeCaption(type) {
  return types.find(t => t.id === type)?.caption || 'Unknown';
}

const {    
    xGrid,
    xGridCol,
    xShowGroup,
    xShowTemplate
} = useShow(props);
</script>