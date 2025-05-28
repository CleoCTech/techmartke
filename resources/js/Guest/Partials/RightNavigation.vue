<template>
    <div class="fixed right-8 top-0 h-screen flex items-center z-40">
      <!-- Progress Line Container -->
      <div class="absolute right-0 h-full w-[5px] bg-white/5 overflow-hidden">
        <!-- Animated Progress Line -->
        <div 
          class="absolute top-0 left-0 w-full h-full transition-all duration-150 ease-out origin-top"
          :class="isDarkBackground ? 'bg-white/20' : 'bg-gray-700/20'"
          :style="{ transform: `scaleY(${scrollProgress})` }"
        ></div>
      </div>
      
      <!-- Vertical Text with Separator -->
      <div class="flex flex-col items-center ml-10 mx-4">
        <span 
          class="[writing-mode:vertical-rl] transform rotate-180 text-[14px] tracking-[0.25em] uppercase font-medium transition-colors duration-300"
          :class="isDarkBackground ? 'text-white' : 'text-gray-800'"
        >
          Home
        </span>
        
        <!-- Horizontal Line Separator -->
        <div 
          class="w-[20px] h-[1px] my-4 transition-colors duration-300"
          :class="isDarkBackground ? 'bg-white' : 'bg-gray-800'"
        ></div>
        
        <span 
          class="[writing-mode:vertical-rl] transform rotate-180 text-[14px] tracking-[0.25em] uppercase font-medium transition-colors duration-300"
          :class="isDarkBackground ? 'text-white' : 'text-gray-800'"
        >
          Divinely Called Ministries
        </span>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  
  const scrollProgress = ref(0)
  const isDarkBackground = ref(false)
  
  const handleScroll = () => {
    const windowHeight = document.documentElement.scrollHeight - window.innerHeight
    const currentScroll = window.scrollY
    scrollProgress.value = currentScroll / windowHeight
  }
  
  const checkBackgroundColor = (entries) => {
    const entry = entries[0]
    if (entry.isIntersecting) {
      const backgroundColor = window.getComputedStyle(entry.target).backgroundColor
      const rgb = backgroundColor.match(/\d+/g)
      if (rgb) {
        // Calculate perceived brightness
        const brightness = (parseInt(rgb[0]) * 299 + parseInt(rgb[1]) * 587 + parseInt(rgb[2]) * 114) / 1000
        isDarkBackground.value = brightness < 128
      }
    }
  }
  
  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
    handleScroll() // Initial calculation
  
    // Set up Intersection Observer for each section
    const sections = document.querySelectorAll('section')
    const observer = new IntersectionObserver(checkBackgroundColor, {
      threshold: 0.5 // Trigger when 50% of the section is visible
    })
  
    sections.forEach(section => observer.observe(section))
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  </script>
  
  <style scoped>
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }
  </style>