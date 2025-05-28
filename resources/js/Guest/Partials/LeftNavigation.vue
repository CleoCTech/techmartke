<template>
    <div class="fixed left-8 top-0 h-screen flex items-center z-40">
      <div class="flex flex-col items-center gap-6">
        <!-- GIVE Button -->
        <button 
        class="relative group px-4 py-6 rounded-full transition-all duration-300 border"
        :class="isScrolled ? 'bg-white/90 border-transparent' : 'bg-transparent border-white'"
      >
        <span 
          class="[writing-mode:vertical-rl] transform rotate-180 text-[14px] tracking-[0.25em] uppercase font-medium transition-colors duration-300"
          :class="isScrolled ? 'text-gray-900' : 'text-white'"
        >
          Give
        </span>
      </button>
  
        <!-- GET INVOLVED Button -->
        <button 
          class="relative group px-4 py-8 rounded-full transition-all duration-300"
          :class="isScrolled ? 'bg-gray-900' : 'bg-white/20'"
        >
          <span 
            class="[writing-mode:vertical-rl] transform rotate-180 text-[14px] tracking-[0.25em] uppercase font-medium transition-colors duration-300"
            :class="isScrolled ? 'text-gray-200' : 'text-white'"
          >
            Get Involved
          </span>
        </button>
  
        
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  
  const isScrolled = ref(false)
  
  const handleScroll = () => {
    isScrolled.value = window.scrollY > 100
  }
  
  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
    handleScroll() // Initial check
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  </script>
  
  <style scoped>
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }
  
  button:hover {
    transform: translateX(4px);
  }
  </style>