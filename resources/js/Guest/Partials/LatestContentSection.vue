<template>
  <section class="bg-white py-12 sm:py-16 md:py-20 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Mobile Layout (Stack) -->
      <div class="lg:hidden space-y-8">
        <!-- Header -->
        <div class="space-y-4">
          <h2 class="text-2xl sm:text-3xl font-medium text-gray-900">Latest Content</h2>
          <p class="text-gray-600 text-base sm:text-lg leading-relaxed">
            Created to help you meditate on the truths of Scripture, these resources address different aspects of the Christian life, unpack theology, and provide encouragement as you work to live for the glory of God.
          </p>
        </div>

        <!-- Tabs Scrollable Container -->
        <div class="border-b border-gray-200 -mx-4 px-4">
          <div class="flex overflow-x-auto hide-scrollbar">
            <nav class="flex space-x-6 sm:space-x-8 pb-4">
              <button 
                v-for="tab in tabs" 
                :key="tab"
                @click="activeTab = tab"
                class="whitespace-nowrap px-1 text-sm font-medium transition-colors relative"
                :class="[
                  activeTab === tab 
                    ? 'text-blue-500 border-b-2 border-blue-500' 
                    : 'text-gray-500 hover:text-gray-700'
                ]"
              >
                {{ tab }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Featured Content -->
        <div class="group relative aspect-[4/3] sm:aspect-[2/1] overflow-hidden rounded-lg">
          <!-- Single Image for Mobile -->
          <div class="absolute inset-0">
            <img 
              src="https://images.unsplash.com/photo-1511649475669-e288648b2339?auto=format&fit=crop&w=800&q=80"
              alt="Worship service" 
              class="h-full w-full object-cover grayscale"
            />
            <div class="absolute inset-0 bg-black/10"></div>
          </div>

          <!-- Content Overlay -->
          <div class="absolute inset-x-0 bottom-0 p-4 sm:p-6 bg-gradient-to-t from-black/60 to-transparent">
            <div class="flex items-end justify-between text-white">
              <div>
                <div class="text-xs sm:text-sm font-medium mb-1 sm:mb-2">1.5.25 | ABLE</div>
                <h3 class="text-xl sm:text-2xl font-medium group-hover:underline">God is Able</h3>
              </div>
              <div class="transform translate-x-4 group-hover:translate-x-0 transition-transform">
                <ArrowRight class="w-5 h-5 sm:w-6 sm:h-6" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Desktop Layout (Side by Side) -->
      <div class="hidden lg:flex gap-8 xl:gap-16">
        <!-- Sticky Header -->
        <div class="w-80 xl:w-96 sticky top-8 self-start">
          <div class="space-y-6">
            <h2 class="text-4xl font-medium text-gray-900">Latest Content</h2>
            <p class="text-gray-600 text-lg leading-relaxed">
              Created to help you meditate on the truths of Scripture, these resources address different aspects of the Christian life, unpack theology, and provide encouragement as you work to live for the glory of God.
            </p>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Tabs -->
          <div class="border-b border-gray-200 mb-12">
            <nav class="flex space-x-8">
              <button 
                v-for="tab in tabs" 
                :key="tab"
                @click="activeTab = tab"
                class="pb-4 px-1 text-sm font-medium transition-colors relative"
                :class="[
                  activeTab === tab 
                    ? 'text-blue-500 border-b-2 border-blue-500' 
                    : 'text-gray-500 hover:text-gray-700'
                ]"
              >
                {{ tab }}
              </button>
            </nav>
          </div>

          <!-- Featured Content -->
          <div class="group relative aspect-[2/1] overflow-hidden rounded-lg">
            <div class="absolute inset-0 grid grid-cols-3">
              <!-- Left Section -->
              <div class="relative col-span-1">
                <img 
                  src="https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=800&q=80"
                  alt="Church community" 
                  class="h-full w-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/10"></div>
              </div>
              
              <!-- Middle Section -->
              <div class="relative col-span-1">
                <img 
                  src="https://images.unsplash.com/photo-1511649475669-e288648b2339?auto=format&fit=crop&w=800&q=80"
                  alt="Worship service" 
                  class="h-full w-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/10"></div>
              </div>
              
              <!-- Right Section -->
              <div class="relative col-span-1">
                <img 
                  src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80"
                  alt="City view" 
                  class="h-full w-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/10"></div>
              </div>
            </div>

            <!-- Content Overlay -->
            <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black/60 to-transparent">
              <div class="flex items-end justify-between text-white">
                <div>
                  <div class="text-sm font-medium mb-2">1.5.25 | ABLE</div>
                  <h3 class="text-2xl font-medium group-hover:underline">God is Able</h3>
                </div>
                <div class="transform translate-x-4 group-hover:translate-x-0 transition-transform">
                  <ArrowRight class="w-6 h-6" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { ArrowRight } from 'lucide-vue-next'

const tabs = ['VIDEOS', 'ARTICLES', 'RESOURCES']
const activeTab = ref('VIDEOS')
</script>

<style scoped>
/* Prevent image drag */
img {
  user-drag: none;
  -webkit-user-drag: none;
  user-select: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}

/* Hide scrollbar but keep functionality */
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;  /* Chrome, Safari and Opera */
}

/* Smooth transitions */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>

