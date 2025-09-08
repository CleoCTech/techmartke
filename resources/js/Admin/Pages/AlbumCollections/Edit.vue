<template>
  <AdminLayout>
    <div class="max-w-6xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex items-center gap-4">
        <Link
          :href="route('admin.album-collections.show', album.id)"
          class="text-gray-600 hover:text-gray-900"
        >
          <ArrowLeft class="w-5 h-5" />
        </Link>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Edit Album Collection</h1>
          <p class="text-gray-600">Update album details and manage images</p>
        </div>
      </div>

      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Main Form -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Album Title *
                  </label>
                  <input
                    v-model="form.title"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter album title"
                  />
                  <div v-if="errors.title" class="text-red-500 text-sm mt-1">
                    {{ errors.title }}
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                  </label>
                  <textarea
                    v-model="form.description"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Describe this album collection"
                  ></textarea>
                  <div v-if="errors.description" class="text-red-500 text-sm mt-1">
                    {{ errors.description }}
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Category *
                    </label>
                    <select
                      v-model="form.category"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                      <option value="">Select Category</option>
                      <option value="Events">Events</option>
                      <option value="Ministry">Ministry</option>
                      <option value="Community">Community</option>
                      <option value="General">General</option>
                    </select>
                    <div v-if="errors.category" class="text-red-500 text-sm mt-1">
                      {{ errors.category }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Cover Image
                    </label>
                    <input
                      ref="coverImageInput"
                      type="file"
                      accept="image/*"
                      @change="handleCoverImageChange"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="errors.cover_image" class="text-red-500 text-sm mt-1">
                      {{ errors.cover_image }}
                    </div>
                  </div>
                </div>

                <!-- Current Cover Image -->
                <div v-if="album.cover_image_url && !coverImagePreview" class="mt-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current Cover Image
                  </label>
                  <div class="relative w-48 h-32">
                    <img
                      :src="album.cover_image_url"
                      alt="Current cover"
                      class="w-full h-full object-cover rounded-lg border"
                    />
                    <button
                      type="button"
                      @click="removeCurrentCover"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"
                    >
                      <X class="w-4 h-4" />
                    </button>
                  </div>
                </div>

                <!-- New Cover Image Preview -->
                <div v-if="coverImagePreview" class="mt-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    New Cover Image Preview
                  </label>
                  <div class="relative w-48 h-32">
                    <img
                      :src="coverImagePreview"
                      alt="Cover preview"
                      class="w-full h-full object-cover rounded-lg border"
                    />
                    <button
                      type="button"
                      @click="removeCoverImage"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"
                    >
                      <X class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Current Images -->
            <div v-if="album.images && album.images.length > 0" class="bg-white p-6 rounded-lg shadow">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Images</h3>
              
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                  v-for="(image, index) in album.images"
                  :key="image.id"
                  class="relative group"
                >
                  <img
                    :src="image.image_url"
                    :alt="image.alt_text || `Image ${index + 1}`"
                    class="w-full h-24 object-cover rounded-lg border"
                  />
                  <button
                    type="button"
                    @click="deleteImage(image)"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                  <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg">
                    {{ image.original_filename }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Add More Images -->
            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Add More Images</h3>
              
              <div class="space-y-4">
                <div
                  class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                  @dragover.prevent
                  @drop.prevent="handleDrop"
                >
                  <input
                    ref="imageInput"
                    type="file"
                    multiple
                    accept="image/*"
                    @change="handleImageChange"
                    class="hidden"
                  />
                  
                  <div v-if="selectedImages.length === 0">
                    <Upload class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <p class="text-gray-600 mb-2">Drop images here or click to browse</p>
                    <button
                      type="button"
                      @click="$refs.imageInput.click()"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                    >
                      Select Images
                    </button>
                    <p class="text-sm text-gray-500 mt-2">
                      Supports JPG, PNG, GIF up to 5MB each
                    </p>
                  </div>
                  
                  <div v-else>
                    <p class="text-gray-600 mb-4">{{ selectedImages.length }} new images selected</p>
                    <button
                      type="button"
                      @click="$refs.imageInput.click()"
                      class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg"
                    >
                      Add More Images
                    </button>
                  </div>
                </div>

                <div v-if="errors.images" class="text-red-500 text-sm">
                  {{ errors.images }}
                </div>

                <!-- New Image Previews -->
                <div v-if="selectedImages.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div
                    v-for="(image, index) in selectedImages"
                    :key="index"
                    class="relative group"
                  >
                    <img
                      :src="image.preview"
                      :alt="image.name"
                      class="w-full h-24 object-cover rounded-lg border"
                    />
                    <button
                      type="button"
                      @click="removeImage(index)"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <X class="w-4 h-4" />
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg">
                      {{ image.name }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Settings -->
            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
              
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-gray-700">
                    Featured Album
                  </label>
                  <input
                    v-model="form.is_featured"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </div>

                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-gray-700">
                    Published
                  </label>
                  <input
                    v-model="form.is_published"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </div>
              </div>
            </div>

            <!-- Album Stats -->
            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Album Statistics</h3>
              
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total Images:</span>
                  <span class="font-medium">{{ album.image_count }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Created:</span>
                  <span class="font-medium">{{ formatDate(album.created_at) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Last Updated:</span>
                  <span class="font-medium">{{ formatDate(album.updated_at) }}</span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="bg-white p-6 rounded-lg shadow">
              <div class="space-y-3">
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2"
                >
                  <Loader2 v-if="isSubmitting" class="w-4 h-4 animate-spin" />
                  <Save v-else class="w-4 h-4" />
                  {{ isSubmitting ? 'Updating...' : 'Update Album' }}
                </button>
                
                <Link
                  :href="route('admin.album-collections.show', album.id)"
                  class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-center block"
                >
                  Cancel
                </Link>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Admin/Layouts/AppLayout.vue'
import {
  ArrowLeft,
  Upload,
  X,
  Save,
  Loader2,
  Trash2
} from 'lucide-vue-next'

const props = defineProps({
  album: Object,
  errors: Object
})

const imageInput = ref(null)
const coverImageInput = ref(null)
const selectedImages = ref([])
const coverImagePreview = ref(null)
const isSubmitting = ref(false)

const form = useForm({
  title: props.album.title,
  description: props.album.description,
  category: props.album.category,
  cover_image: null,
  images: [],
  is_featured: props.album.is_featured,
  is_published: props.album.is_published,
  metadata: props.album.metadata || {}
})

const handleCoverImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.cover_image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      coverImagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeCoverImage = () => {
  form.cover_image = null
  coverImagePreview.value = null
  coverImageInput.value.value = ''
}

const removeCurrentCover = () => {
  // This would need to be handled on the backend
  // For now, just clear the form field
  form.cover_image = null
}

const handleImageChange = (event) => {
  const files = Array.from(event.target.files)
  addImages(files)
}

const handleDrop = (event) => {
  const files = Array.from(event.dataTransfer.files)
  addImages(files)
}

const addImages = (files) => {
  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader()
      reader.onload = (e) => {
        selectedImages.value.push({
          file: file,
          name: file.name,
          preview: e.target.result
        })
      }
      reader.readAsDataURL(file)
    }
  })
}

const removeImage = (index) => {
  selectedImages.value.splice(index, 1)
}

const deleteImage = (image) => {
  if (confirm('Are you sure you want to delete this image?')) {
    router.delete(route('admin.album-collections.delete-image', [props.album.id, image.id]), {
      onSuccess: () => {
        // Image will be removed from the page after refresh
        window.location.reload()
      }
    })
  }
}

const submitForm = () => {
  isSubmitting.value = true
  
  // Prepare form data
  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('description', form.description)
  formData.append('category', form.category)
  formData.append('is_featured', form.is_featured ? '1' : '0')
  formData.append('is_published', form.is_published ? '1' : '0')
  
  if (form.cover_image) {
    formData.append('cover_image', form.cover_image)
  }

  selectedImages.value.forEach((image, index) => {
    formData.append(`images[${index}]`, image.file)
  })

  // Submit using Inertia
  form.put(route('admin.album-collections.update', props.album.id), {
    data: formData,
    forceFormData: true,
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}
</script>
