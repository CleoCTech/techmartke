<template>
  <div class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100">Media Gallery</h2>
      <button class="text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400">
        View all
      </button>
    </header>
    <div class="p-3">
      <!-- Gallery grid -->
      <div class="grid grid-cols-3 gap-4">
        <div v-for="item in mediaItems" :key="item.id" class="relative group">
          <img 
            :src="item.thumbnail" 
            :alt="item.title"
            class="w-full aspect-video object-cover rounded-sm"
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
      <div v-if="selectedMedia" class="fixed inset-0 bg-slate-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg max-w-3xl w-full">
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
            <video :src="selectedMedia.url" controls class="w-full h-full rounded-lg"></video>
          </div>
          <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ selectedMedia.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'MediaGallery',
  setup() {
    const selectedMedia = ref(null)

    const mediaItems = [
      {
        id: 1,
        type: 'image',
        title: 'Sunday Service',
        thumbnail: 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80',
        url: 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
        date: 'Jan 15, 2024',
        description: 'Highlights from our recent Sunday service.'
      },
      {
        id: 2,
        type: 'video',
        title: 'Youth Conference',
        thumbnail: 'https://img.youtube.com/vi/dQw4w9WgXcQ/0.jpg',
        url: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        date: 'Jan 10, 2024',
        description: 'Recap of our annual youth conference.'
      },
      {
        id: 3,
        type: 'image',
        title: 'Community Outreach',
        thumbnail: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80',
        url: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
        date: 'Jan 5, 2024',
        description: 'Our church members participating in community service.'
      },
      {
        id: 4,
        type: 'image',
        title: 'Worship Team',
        thumbnail: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80',
        url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
        date: 'Dec 31, 2023',
        description: 'Our worship team leading praise and worship.'
      },
      {
        id: 5,
        type: 'video',
        title: 'Christmas Play',
        thumbnail: 'https://img.youtube.com/vi/1A3Puw-Zy7I/0.jpg',
        url: 'https://www.youtube.com/watch?v=1A3Puw-Zy7I',
        date: 'Dec 25, 2023',
        description: 'Highlights from our annual Christmas play.'
      },
      {
        id: 6,
        type: 'image',
        title: 'Bible Study Group',
        thumbnail: 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80',
        url: 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
        date: 'Dec 20, 2023',
        description: 'Our weekly Bible study group in action.'
      }
    ]

    const openMedia = (media) => {
      selectedMedia.value = media
    }

    return {
      mediaItems,
      selectedMedia,
      openMedia
    }
  }
}
</script>

