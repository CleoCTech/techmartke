<template>
  <section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-8">
      <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
        <!-- Welcome Text -->
        <div>
          <h3 class="text-sm font-medium tracking-wider text-gray-600 mb-6">
            WELCOME TO THE DIVINELY CALLED MINISTRIES
          </h3>
          <h2 class="text-4xl md:text-5xl lg:text-6xl font-medium text-gray-900 leading-tight mb-6">
            To take the divine presence of God to the nations and peoples of the world; and to demonstrate the character of the Spirit.
          </h2>
          <p class="text-lg text-gray-700 mb-8">
            At Divinely Called Ministries we pride ourselves on providing practical Biblical teaching that can encourage, strengthen, and challenge people to grow and apply Biblical philosophies to their everyday life. We’re a busy community church with a wide variety of services, events, and activities. Our doors are open to people from all backgrounds, regardless of your spiritual journey. Please get in touch with us or come and visit us soon!
          </p>
          <!-- <a href="#" class="inline-block bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-300">
            Join Us This Sunday
          </a> -->
        </div>

        <!-- Pastor's Image and Quote -->
        <div class="relative">
          <div class="aspect-[3/4] rounded-lg overflow-hidden shadow-xl">
            <img
              src="/assets/images/church/pastor-portrait.jpg"
              alt="Pastor's Portrait"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="absolute bottom-0 left-0 right-0 bg-white bg-opacity-90 p-6 rounded-b-lg">
            <p class="text-gray-800 italic mb-2">
              "Our church is a beacon of hope, guiding souls towards God's infinite love and grace."
            </p>
            <p class="text-gray-600 font-semibold">
              - Pastor Maxwell Kusi
            </p>
          </div>
        </div>
      </div>

      <!-- Scrolling Images Container -->
      <div class="relative">
        <!-- Images Track -->
        <div 
          ref="scrollTrack" 
          class="flex gap-8 transition-transform duration-[30000ms] ease-linear"
          :style="{ transform: `translateX(${translateX}px)` }"
          @mouseenter="pauseScroll"
          @mouseleave="resumeScroll"
        >
          <!-- First Set of Images -->
          <div v-for="(image, index) in images" :key="`first-${index}`" 
               class="relative min-w-[600px] h-[400px] rounded-lg overflow-hidden">
            <img
              :src="image.src"
              :alt="image.alt"
              class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            />
          </div>
          
          <!-- Duplicated Set for Seamless Loop -->
          <div v-for="(image, index) in images" :key="`second-${index}`"
               class="relative min-w-[600px] h-[400px] rounded-lg overflow-hidden">
            <img
              :src="image.src"
              :alt="image.alt"
              class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// Image data
const images = [
  {
    src: '/assets/images/church/slider.jpeg',
    alt: 'Church community members smiling'
  },
  {
    src: '/assets/images/church/slide2.jpeg',
    alt: 'Church event with people gathering'
  },
  {
    src: 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80?height=400&width=600',
    alt: 'Baptism ceremony with people gathered around'
  },
  {
    src: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80?height=400&width=600',
    alt: 'Worship service with musician playing guitar'
  },
]

// Scroll animation logic
const scrollTrack = ref(null)
const translateX = ref(0)
let animationFrameId = null
let isPaused = false

const animate = () => {
  if (!isPaused) {
    translateX.value -= 0.5
    // Reset position when all images have scrolled
    if (Math.abs(translateX.value) >= (images.length * 608)) { // 600px width + 8px gap
      translateX.value = 0
    }
  }
  animationFrameId = requestAnimationFrame(animate)
}

const pauseScroll = () => {
  isPaused = true
}

const resumeScroll = () => {
  isPaused = false
}

onMounted(() => {
  animationFrameId = requestAnimationFrame(animate)
})

onUnmounted(() => {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
  }
})
</script>

<style scoped>
.overflow-hidden {
  overflow: hidden;
}

/* Smooth scroll effect */
.transition-transform {
  will-change: transform;
}

/* Prevent image drag */
img {
  user-drag: none;
  -webkit-user-drag: none;
}
</style>