<template>
    <div>

        <Head title="Video Gallery" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <x-input type="text" v-model="form.title" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Sequence</template>
                            <template #value>
                                <x-input 
                                    type="number" 
                                    v-model="form.sequence"
                                    :placeholder="'Next available sequence: ' + (maxSequence + 1)"
                                />
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">
                                    Next available sequence: {{ maxSequence + 1 }}
                                </span>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <x-input type="text" v-model="form.description" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Duration</template>
                            <template #value>
                                <x-input type="text" v-model="form.duration" placeholder="Duration will be set automatically" readonly />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Category</template>
                            <template #value>
                                <x-input type="text" v-model="form.category" placeholder="e.g., Testimonials" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Views</template>
                            <template #value>
                                <x-input type="number" v-model="form.views" placeholder="e.g., 0" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status, index) in setup.statuses" :key="index" :value="status.id">
                                        {{ status.caption }}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col v-if="form.status == 3">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value><x-input type="datetime-local" v-model="form.publish_time" /></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Video Upload</template>
                            <template #value>
                                <div class="space-y-4">
                                    <!-- Video upload input -->
                                    <input type="file" ref="videoInput" @change="handleVideoSelect" accept="video/*"
                                        class="hidden" />

                                    <!-- Custom upload button -->
                                    <button type="button" @click="$refs.videoInput.click()"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                        :disabled="isUploading || isFormEmpty">
                                        {{ isUploading ? 'Uploading...' : 'Choose Video' }}
                                    </button>

                                    <!-- Selected file info -->
                                    <div v-if="selectedFile" class="text-sm text-gray-600 dark:text-gray-400">
                                        Selected: {{ selectedFile.name }} ({{ formatFileSize(selectedFile.size) }})
                                    </div>

                                    <!-- Upload progress -->
                                    <div v-if="isUploading" class="space-y-2">
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Uploading chunk {{ currentChunk }}/{{ totalChunks }}</span>
                                            <span>{{ Math.round(uploadProgress) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                                :style="{ width: `${uploadProgress}%` }"></div>
                                        </div>
                                    </div>

                                    <!-- Error message -->
                                    <div v-if="uploadError" class="text-sm text-red-500">
                                        {{ uploadError }}
                                    </div>
                                    <!-- Thumbnail preview -->
                                    <div v-if="form.thumbnail_url" class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thumbnail
                                            Preview:</h4>
                                        <img :src="form.thumbnail_url" alt="Video Thumbnail"
                                            class="max-w-full h-auto rounded-lg shadow-md" />
                                    </div>
                                </div>
                            </template>
                        </x-form-group>
                    </x-grid-col>


                    <!-- Thumbnail Image -->
                    <x-grid-col class="sm:col-span-4">
            <x-form-group>
              <template #label>Thumbnail Image</template>
              <template #value>
                <div class="space-y-4">
                  <!-- Display existing thumbnail -->
                  <img 
                    v-if="!show_image && form.thumbnail_url" 
                    :src="form.thumbnail_url" 
                    alt="Current Thumbnail" 
                    class="h-20 w-20 object-cover rounded-lg"
                  >
                  
                  <!-- File input for thumbnail -->
                  <x-input 
                    type="file" 
                    @input="onFileChange($event, 'thumbnail_url', show_image)" 
                    ref="thumbnailInput"
                    accept="image/*"
                    class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"
                  />
                  
                  <!-- Preview of selected thumbnail -->
                  <img 
                    v-if="show_image" 
                    :src="show_image" 
                    alt="Thumbnail Preview" 
                    class="h-20 w-20 object-cover rounded-lg"
                  >
                  
                  <!-- Clear thumbnail button -->
                  <button 
                    v-if="show_image || form.thumbnail_url"
                    @click.prevent="clearThumbnail"
                    type="button"
                    class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm"
                  >
                    Clear Thumbnail
                  </button>
                </div>
              </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Video URL</template>
                            <template #value>
                                <x-input type="text" v-model="form.video_url" readonly />
                            </template>
                        </x-form-group>
                    </x-grid-col>



                </x-grid>


                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>

            </form>

        </x-create-edit-template>


    </div>
</template>
<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'

import { ref, reactive, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    ...createEditProps,
    maxSequence: {
        type: Number,
        default: 0
    }
})

onMounted(() => {
    // Set default sequence to next available number for new records
    if (!props.cardData?.uuid && !form.sequence) {
        form.sequence = props.maxSequence + 1
    }
})

// Video upload related refs
const videoInput = ref(null)
const selectedFile = ref(null)
const thumbnailInput = ref(null)
const isUploading = ref(false)
const uploadProgress = ref(0)
const uploadError = ref(null)
const currentChunk = ref(0)
const totalChunks = ref(0)
const chunkSize = 1024 * 1024 * 2 // 2MB chunks

const form = reactive({
    uuid: null,
    title: null,
    description: null,
    sequence: null,
    is_global: null,
    thumbnail_url: null,
    cover_image: null,
    video_url: null,
    status: null,
    publish_time: null,
    duration: null,
    category: null,
    views: 0,
})


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.is_global = props.cardData.is_global === 1; // Convert 1 to true, 0 to false;
        form.sequence = props.cardData.sequence;
        form.description = props.cardData.description;
        form.thumbnail_url = props.cardData.cover_image;
        // form.cover_image = props.cardData.cover_image;
        form.video_url = props.cardData.video_path;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
        form.duration = props.cardData.duration ?? null;
        form.category = props.cardData.category ?? null;
        form.views = props.cardData.views ?? 0;
    }

}

// Format file size to human readable format
const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

// Handle video file selection
const handleVideoSelect = (event) => {
    const file = event.target.files[0]
    if (!file) return

    selectedFile.value = file
    uploadError.value = null
    totalChunks.value = Math.ceil(file.size / chunkSize)
    currentChunk.value = 0
    uploadProgress.value = 0

    // Start upload automatically when file is selected
    uploadChunks()
}

const handleThumbnailSelect = (event) => {
    const file = event.target.files[0]
    if (!file) return

    // Clear auto-generated thumbnail if it exists
    form.thumbnail_url = null

    // Create URL for preview
    form.cover_image = URL.createObjectURL(file)
}

const clearThumbnail = () => {
    form.thumbnail_url = null
    form.cover_image = null
    if (thumbnailInput.value) {
        thumbnailInput.value.value = ''
    }
}
// Upload file in chunks
const uploadChunks = async () => {
    if (!selectedFile.value) return

    isUploading.value = true
    uploadError.value = null
    const file = selectedFile.value
    const fileId = Date.now() // Unique identifier for this upload

    try {
        while (currentChunk.value < totalChunks.value) {
            const start = currentChunk.value * chunkSize
            const end = Math.min(start + chunkSize, file.size)
            const chunk = file.slice(start, end)

            const formData = new FormData()
            formData.append('file', chunk)
            formData.append('fileName', file.name)
            formData.append('fileId', fileId)
            formData.append('chunkIndex', currentChunk.value)
            formData.append('totalChunks', totalChunks.value)

            await axios.post(route('admin.video-gallery.upload-chunk'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    const chunkProgress = (progressEvent.loaded / progressEvent.total) * 100
                    const totalProgress = ((currentChunk.value + (chunkProgress / 100)) / totalChunks.value) * 100
                    uploadProgress.value = totalProgress
                }
            })

            currentChunk.value++
        }

        // Final step - merge chunks
        const response = await axios.post(route('admin.video-gallery.merge-chunks'), {
            fileName: file.name,
            fileId: fileId
        })

        form.video_url = response.data.path
        // Only set thumbnail_url if no custom thumbnail was uploaded
        if (!form.thumbnail_url) {
            form.thumbnail_url = response.data.thumbnail
        }
        // Set duration from backend
        if (response.data.duration) {
            form.duration = response.data.duration
        }
        isUploading.value = false
    } catch (error) {
        console.error('Upload error:', error)
        uploadError.value = 'An error occurred during upload. Please try again.'
        isUploading.value = false
    }
}


const { editor, editorConfig, submit, onFileChange, ckeditor, xGrid,
    xFormGroup,
    xGridCol,
    xLoading,
    xPanel,
    xInput,
    xSelect,
    xTextarea,
    xCheckbox,
    Checkbox,
    xButton,
    AppLayout,
    xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form)


const isFormEmpty = computed(() => {
    return !form.title &&
        !form.description &&
        !form.sequence &&
        !form.status 
});

</script>
<style scoped>
input[type="file"] {
    /* display: none; */
}

.custom-file-input {
    background-color: blue;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

/* 
label {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

label::before {
  content: "Choose file";
} */

/* label::after {
  content: "📷";
} */
</style>