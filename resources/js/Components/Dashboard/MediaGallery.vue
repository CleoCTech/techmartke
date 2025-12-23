<template>
  <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100">Media Gallery</h2>
      <Link 
        :href="route('admin.gallery')"
        class="text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400"
      >
        View all
      </Link>
    </header>
    <div class="p-3">
      <div v-if="loading" class="flex justify-center items-center h-48">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
      </div>
      <div v-else-if="error" class="text-red-500 text-center py-4">
        {{ error }}
      </div>
      <div v-else-if="mediaItems.length === 0" class="text-center py-8 text-gray-500">
        No media items available
      </div>
      <div v-else class="grid grid-cols-3 gap-4">
        <div v-for="item in mediaItems" :key="item.id" class="relative group">
          <img 
            :src="item.thumbnail" 
            :alt="item.title"
            class="w-full aspect-video object-cover rounded-sm"
            @error="handleImageError"
          />
          <div class="absolute inset-0 bg-slate-900 bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <button 
              class="text-white bg-indigo-500 hover:bg-indigo-600 px-3 py-1 rounded-full text-sm font-medium"
              @click="openMedia(item)"
            >
              {{ item.type === 'video' ? 'Play' : 'View' }}
            </button>
          </div>
          <div class="mt-2">
            <h3 class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{{ item.title }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.date }}</p>
          </div>
        </div>
      </div>

      <!-- Modal for media viewing -->
      <div v-if="selectedMedia" class="fixed inset-0 bg-slate-900 bg-opacity-50 flex items-center justify-center z-50" @click="selectedMedia = null">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg max-w-3xl w-full mx-4" @click.stop>
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">{{ selectedMedia.title }}</h3>
            <button @click="selectedMedia = null" class="text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div v-if="selectedMedia.type === 'image'">
            <img :src="selectedMedia.url" :alt="selectedMedia.title" class="w-full rounded-lg" />
          </div>
          <div v-else-if="selectedMedia.type === 'video'" class="aspect-video">
            <iframe 
              v-if="isYouTubeUrl(selectedMedia.url)"
              :src="getYouTubeEmbedUrl(selectedMedia.url)" 
              class="w-full h-full rounded-lg"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
            <video v-else :src="selectedMedia.url" controls class="w-full h-full rounded-lg"></video>
          </div>
          <p v-if="selectedMedia.description" class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ selectedMedia.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()
const storagePaths = page.props.storagePaths || {}

const selectedMedia = ref(null)
const mediaItems = ref([])
const loading = ref(false) // Start as false, set to true when fetching
const error = ref(null)

const handleImageError = (event) => {
  // Prevent infinite loop by checking if already set to placeholder
  if (event.target.src && !event.target.src.includes('placeholder')) {
    event.target.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Crect fill="%23ddd" width="400" height="300"/%3E%3Ctext fill="%23999" font-family="sans-serif" font-size="18" x="50%25" y="50%25" text-anchor="middle" dy=".3em"%3ENo Image%3C/text%3E%3C/svg%3E'
  }
}

const isYouTubeUrl = (url) => {
  return url && (url.includes('youtube.com') || url.includes('youtu.be'))
}

const getYouTubeEmbedUrl = (url) => {
  if (!url) return ''
  const videoId = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/)?.[1]
  return videoId ? `https://www.youtube.com/embed/${videoId}` : url
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const getThumbnailUrl = (item) => {
  if (item.type === 'video') {
    if (item.thumbnail_url) {
      return item.thumbnail_url
    }
    if (item.cover_image && storagePaths.videos) {
      return (storagePaths.videos.cover_images?.readPath || '') + item.cover_image
    }
    if (item.video_url && isYouTubeUrl(item.video_url)) {
      const videoId = item.video_url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/)?.[1]
      return videoId ? `https://img.youtube.com/vi/${videoId}/0.jpg` : ''
    }
  } else {
    if (item.cover_image && storagePaths.gallery) {
      return (storagePaths.gallery.cover_images?.readPath || '') + item.cover_image
    }
  }
  // Return a data URI placeholder instead of a file path to avoid 404s
  return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Crect fill="%23ddd" width="400" height="300"/%3E%3Ctext fill="%23999" font-family="sans-serif" font-size="18" x="50%25" y="50%25" text-anchor="middle" dy=".3em"%3ENo Image%3C/text%3E%3C/svg%3E'
}

const fetchMediaItems = async () => {
  console.log('MediaGallery: fetchMediaItems called, loading.value:', loading.value)
  
  // Prevent multiple simultaneous calls
  if (loading.value) {
    console.log('MediaGallery: Already loading, skipping')
    return
  }
  
  console.log('MediaGallery: Setting loading to true')
  loading.value = true
  error.value = null
  
  try {
    console.log('MediaGallery: Entering try block')
    console.log('MediaGallery: Fetching data...')
    const url = '/admin/dashboard/media-gallery'
    console.log('MediaGallery: Request URL:', url)
    console.log('MediaGallery: About to call axios.get')
    
    const response = await axios.get(url, {
      timeout: 5000,
      withCredentials: true, // Ensure cookies are sent
      validateStatus: function (status) {
        console.log('MediaGallery: Response status:', status)
        // Accept 200-299 and 302 (redirect) to catch auth issues
        return (status >= 200 && status < 300) || status === 302
      }
    }).catch(err => {
      console.error('MediaGallery: Axios catch block:', err)
      if (err.response && err.response.status === 302) {
        console.error('MediaGallery: Got redirect (302) - authentication issue?')
        error.value = 'Authentication required'
      }
      throw err
    })
    
    console.log('MediaGallery: Response received', response.data)
    
    // Check if response is valid
    if (!response || !response.data) {
      throw new Error('Invalid response from server')
    }
    
    const data = response.data || {}
    
    // Combine galleries and videos
    const items = []
    
    // Add galleries
    if (data.galleries && Array.isArray(data.galleries)) {
      data.galleries.forEach(gallery => {
        items.push({
          id: `gallery-${gallery.id}`,
          type: 'image',
          title: gallery.title || 'Untitled Gallery',
          thumbnail: getThumbnailUrl({ ...gallery, type: 'image' }),
          url: getThumbnailUrl({ ...gallery, type: 'image' }),
          date: formatDate(gallery.created_at),
          originalDate: gallery.created_at, // Keep original for sorting
          description: gallery.description || ''
        })
      })
    }
    
    // Add videos
    if (data.videos && Array.isArray(data.videos)) {
      data.videos.forEach(video => {
        items.push({
          id: `video-${video.id}`,
          type: 'video',
          title: video.title || 'Untitled Video',
          thumbnail: getThumbnailUrl({ ...video, type: 'video' }),
          url: video.video_url || '',
          date: formatDate(video.created_at),
          originalDate: video.created_at, // Keep original for sorting
          description: video.description || ''
        })
      })
    }
    
    // Sort by date (newest first) and limit to 6
    // Use created_at from original data for proper sorting
    mediaItems.value = items
      .sort((a, b) => {
        // Sort by original created_at if available, otherwise by formatted date
        const dateA = a.originalDate ? new Date(a.originalDate) : new Date(a.date)
        const dateB = b.originalDate ? new Date(b.originalDate) : new Date(b.date)
        return dateB - dateA
      })
      .slice(0, 6)
    
    console.log('MediaGallery: Items processed', mediaItems.value.length, 'items')
  } catch (err) {
    console.error('MediaGallery: Error fetching media items:', err)
    console.error('MediaGallery: Error details:', {
      message: err.message,
      response: err.response?.data,
      status: err.response?.status,
      code: err.code
    })
    error.value = 'Failed to load media gallery'
    mediaItems.value = []
    // Ensure loading is set to false even on error
    loading.value = false
  } finally {
    // Double-check loading is false
    if (loading.value) {
      loading.value = false
    }
    console.log('MediaGallery: Loading complete')
  }
}

const openMedia = (media) => {
  selectedMedia.value = media
}

onMounted(() => {
  console.log('MediaGallery: Component mounted')
  // Add a timeout to prevent infinite loading
  const timeout = setTimeout(() => {
    if (loading.value) {
      console.warn('MediaGallery: Request timed out, setting loading to false')
      loading.value = false
      error.value = 'Request timed out'
    }
  }, 10000) // 10 second timeout
  
  console.log('MediaGallery: Calling fetchMediaItems')
  fetchMediaItems().finally(() => {
    clearTimeout(timeout)
  })
})
</script>

