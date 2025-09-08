<template>
    <div>
        <Head title="Album Collections" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Title</Th>
                        <Th>Category</Th>
                        <Th>Images</Th>
                        <Th>Status</Th>
                        <Th>Featured</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)" 
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <!-- Checkbox for selection -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        
                        <!-- Title -->
                        <Td>
                            <div class="flex items-center gap-3">
                                <div v-if="record.cover_image_url" class="w-10 h-10 rounded-lg overflow-hidden bg-gray-200">
                                    <img :src="record.cover_image_url" :alt="record.title" class="w-full h-full object-cover">
                                </div>
                                <div v-else class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <Camera class="w-5 h-5 text-gray-400" />
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ record.title }}</div>
                                    <div class="text-sm text-gray-500 line-clamp-1">{{ record.description }}</div>
                                </div>
                            </div>
                        </Td>

                        <!-- Category -->
                        <Td>
                            <Badge :class="getCategoryClass(record.category)">
                                {{ getCategoryCaption(record.category) }}
                            </Badge>
                        </Td>

                        <!-- Images Count -->
                        <Td>
                            <span class="text-sm text-gray-600">{{ record.image_count }} images</span>
                        </Td>

                        <!-- Status -->
                        <Td>
                            <Badge :class="getStatusClass(record.is_published)">
                                {{ getStatusCaption(record.is_published) }}
                            </Badge>
                        </Td>

                        <!-- Featured -->
                        <Td>
                            <Badge :class="getFeaturedClass(record.is_featured)">
                                {{ getFeaturedCaption(record.is_featured) }}
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
import { Head } from '@inertiajs/vue3';
import Badge from '@/Components/Mosaic/Badge.vue';
import { ref } from 'vue';
import { Camera } from 'lucide-vue-next';

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...indexProps });

// Status mapping
const statuses = ref([
  { id: 1, caption: 'Published', color: 'bg-green-500 text-white' },
  { id: 0, caption: 'Draft', color: 'bg-gray-500 text-white' },
]);

// Featured mapping
const featuredOptions = ref([
  { id: 1, caption: 'Featured', color: 'bg-yellow-500 text-white' },
  { id: 0, caption: 'Regular', color: 'bg-gray-400 text-white' },
]);

// Category mapping
const categories = ref([
  { id: 'Academic', caption: 'Academic', color: 'bg-blue-500 text-white' },
  { id: 'Computer Lab', caption: 'Computer Lab', color: 'bg-purple-500 text-white' },
  { id: 'Programming', caption: 'Programming', color: 'bg-green-500 text-white' },
  { id: 'General', caption: 'General', color: 'bg-gray-500 text-white' },
  { id: 'Student Life', caption: 'Student Life', color: 'bg-pink-500 text-white' },
  { id: 'Graduation', caption: 'Graduation', color: 'bg-yellow-500 text-white' },
  { id: 'Workshops', caption: 'Workshops', color: 'bg-orange-500 text-white' },
  { id: 'Technology', caption: 'Technology', color: 'bg-indigo-500 text-white' },
  { id: 'Events', caption: 'Events', color: 'bg-red-500 text-white' },
  { id: 'Achievements', caption: 'Achievements', color: 'bg-emerald-500 text-white' },
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

// Get the class for the featured badge
function getFeaturedClass(featured) {
  const featuredObj = featuredOptions.value.find((f) => f.id === featured);
  return featuredObj ? featuredObj.color : 'bg-gray-300 text-black';
}

// Get the caption for the featured badge
function getFeaturedCaption(featured) {
  const featuredObj = featuredOptions.value.find((f) => f.id === featured);
  return featuredObj ? featuredObj.caption : 'Unknown';
}

// Get the class for the category badge
function getCategoryClass(category) {
  const categoryObj = categories.value.find((c) => c.id === category);
  return categoryObj ? categoryObj.color : 'bg-gray-300 text-black';
}

// Get the caption for the category badge
function getCategoryCaption(category) {
  const categoryObj = categories.value.find((c) => c.id === category);
  return categoryObj ? categoryObj.caption : 'Unknown';
}

// Use the index composable
const {
    xIndexTemplate,
    Table,
    Th,
    ThCheckbox,
    Td,
    TdCheckbox,
    Tr,
    xBadge,
    handleToggleSelectAll,
    isSelected,
    onCheck
} = useIndex(props);
</script>