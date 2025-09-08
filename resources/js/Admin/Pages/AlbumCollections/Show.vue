<template>
    <div>
        <Head title="Album Collection Details" />
        <x-show-template :setup="setup" :selected="selected">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Album Information</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <div class="mt-1">
                                <Badge class="bg-blue-100 text-blue-800">
                                    {{ cardData.category }}
                                </Badge>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.description || 'No description provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.sort_order }}</p>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Status & Settings</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1">
                                <Badge :class="getStatusClass(cardData.is_published)">
                                    {{ getStatusCaption(cardData.is_published) }}
                                </Badge>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Featured</label>
                            <div class="mt-1">
                                <Badge :class="getFeaturedClass(cardData.is_featured)">
                                    {{ getFeaturedCaption(cardData.is_featured) }}
                                </Badge>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Images Count</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.image_count }} images</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.created_at }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ cardData.updated_at }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cover Image -->
                <div v-if="cardData.cover_image_url" class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Cover Image</h3>
                    <div class="flex justify-center">
                        <img :src="cardData.cover_image_url" :alt="cardData.title" class="max-w-md h-64 object-cover rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Album Images -->
                <div v-if="cardData.images && cardData.images.length > 0" class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">Album Images</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <div v-for="(image, index) in cardData.images" :key="index" class="relative group">
                            <img :src="image.image_url" :alt="image.alt_text || `Image ${index + 1}`" 
                                 class="w-full h-32 object-cover rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow"
                                 @click="openImageModal(image)">
                            <div v-if="image.caption" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 rounded-b-lg">
                                {{ image.caption }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="mt-6 text-center py-8">
                    <Camera class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Images</h3>
                    <p class="text-gray-500">This album doesn't have any images yet.</p>
                </div>
            </div>
        </x-show-template>

        <!-- Image Modal -->
        <Modal :show="showImageModal" @close="showImageModal = false">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Image Preview</h3>
                    <button @click="showImageModal = false" class="text-gray-400 hover:text-gray-600">
                        <X class="w-6 h-6" />
                    </button>
                </div>
                <div v-if="selectedImage" class="text-center">
                    <img :src="selectedImage.image_url" :alt="selectedImage.alt_text" class="max-w-full max-h-96 object-contain mx-auto rounded-lg">
                    <div v-if="selectedImage.caption" class="mt-4 text-sm text-gray-600">
                        {{ selectedImage.caption }}
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { provide, getCurrentInstance, ref } from 'vue';
import { showProps, useShow } from '@/Composables/useShow';
import { Head } from '@inertiajs/vue3';
import Badge from '@/Components/Mosaic/Badge.vue';
import Modal from '@/Components/Modal.vue';
import { Camera, X } from 'lucide-vue-next';

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...showProps });

// Modal state
const showImageModal = ref(false);
const selectedImage = ref(null);

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

// Open image modal
const openImageModal = (image) => {
  selectedImage.value = image;
  showImageModal.value = true;
};

// Use the show composable
const {
    xShowTemplate,
    cardData
} = useShow(props);
</script>