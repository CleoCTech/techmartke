<template>
    <section class="py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-8">
        <!-- Header -->
        <div class="max-w-2xl mb-16">
          <h2 class="text-4xl font-medium text-gray-900 mb-4">What's Happening</h2>
          <p class="text-lg text-gray-600 mb-6">
            Here's what's happening this week at The Divinely Called Ministeries - Kenya. Want to stay up to date on upcoming events, classes, and more? Follow us on social media!
          </p>
          <button class="bg-gray-900 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
            FOLLOW US
          </button>
        </div>
  
        <!-- Tabs -->
        <div class="mb-12">
          <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
              <button 
                v-for="tab in tabs" 
                :key="tab"
                @click="activeTab = tab"
                class="pb-4 px-1 text-sm font-medium transition-colors relative"
                :class="[
                  activeTab === tab 
                    ? 'text-blue-600 border-b-2 border-blue-600' 
                    : 'text-gray-500 hover:text-gray-700'
                ]"
              >
                {{ tab }}
              </button>
            </nav>
          </div>
        </div>
  
        <!-- Events List -->
        <div v-if="activeTab === 'EVENTS'" class="space-y-6">
          <div 
            v-for="event in events" 
            :key="event.id"
            class="group flex items-center justify-between py-6 border-b border-gray-200 hover:bg-white transition-colors rounded-lg px-4 -mx-4"
          >
            <!-- Date -->
            <div class="flex items-center space-x-12">
              <div class="w-24">
                <div class="text-sm text-gray-600">{{ event.month }}</div>
                <div class="text-3xl font-medium">{{ event.day }}</div>
              </div>
  
              <!-- Event Details -->
              <div>
                <div class="text-sm text-gray-600 mb-1">{{ event.type }}</div>
                <div class="text-xl font-medium group-hover:text-blue-600 transition-colors">
                  {{ event.title }}
                </div>
              </div>
            </div>
  
            <!-- Arrow -->
            <div class="transform group-hover:translate-x-2 transition-transform">
              <ArrowRight class="w-6 h-6" />
            </div>
          </div>
        </div>
  
        <!-- Placeholder content for other tabs -->
        <div v-if="activeTab === 'CLASSES'" class="text-gray-600">
          Classes content coming soon...
        </div>
  
        <div v-if="activeTab === 'GROUPS'" class="text-gray-600">
          Groups content coming soon...
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { ArrowRight } from 'lucide-vue-next'
  
  const tabs = ['CLASSES', 'EVENTS', 'GROUPS']
  const activeTab = ref('EVENTS')
  
  const events = [
    {
      id: 1,
      month: 'Jan',
      day: '9',
      type: 'EVANGELISM',
      title: 'Evangelism Monday'
    },
    {
      id: 2,
      month: 'Jan',
      day: '12',
      type: 'SERVICE',
      title: 'Wednesday and Friday Mid-Week services'
    },
    {
      id: 3,
      month: 'Jan',
      day: '19',
      type: 'EVENT',
      title: 'Students Worship'
    },
    {
      id: 4,
      month: 'Jan',
      day: '27',
      type: 'PRAYER GATHERING',
      title: 'Morning General Prayers'
    },
    {
      id: 5,
      month: 'Jan',
      day: '31',
      type: 'SEMINAR',
      title: 'Youth Seminar'
    },

  ]
  </script>
  
  <style scoped>
  /* Smooth transitions */
  .transition-colors {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  
  .transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  </style>