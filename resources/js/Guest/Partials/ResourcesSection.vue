<template>
    <section class="bg-gray-900 py-24">
      <div class="max-w-7xl mx-auto px-8">
        <!-- Header -->
        <div class="mb-16 space-y-4">
          <h3 class="text-sm font-medium tracking-wider text-gray-400">
            MORE FROM WATCH | LISTEN | READ
          </h3>
          <h2 class="text-4xl md:text-5xl font-medium text-white max-w-3xl">
            Beautiful, artistic, and insightful resources to help you connect with Jesus.
          </h2>
        </div>
  
        <!-- Resources Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-16 gap-y-12">
          <!-- Watch Column -->
          <div class="space-y-8">
            <h3 class="text-sm font-medium tracking-wider text-gray-400">WATCH</h3>
            
            <!-- Sermons -->
            <a href="#" class="group flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full overflow-hidden">
                  <img 
                    src="https://images.unsplash.com/photo-1544531585-9847b68c8c86?auto=format&fit=crop&w=100&h=100&q=80" 
                    alt="Sermons"
                    class="w-full h-full object-cover"
                  />
                </div>
                <span class="text-white text-lg group-hover:text-gray-300 transition-colors">
                  Sermons
                </span>
              </div>
              <ArrowRight class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
            </a>
  
            <!-- Videos -->
            <a href="#" class="group flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full overflow-hidden">
                  <img 
                    src="https://images.unsplash.com/photo-1601506521793-dc748fc80b67?auto=format&fit=crop&w=100&h=100&q=80" 
                    alt="Videos"
                    class="w-full h-full object-cover"
                  />
                </div>
                <span class="text-white text-lg group-hover:text-gray-300 transition-colors">
                  Videos
                </span>
              </div>
              <ArrowRight class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
            </a>
          </div>
  
          <!-- Listen Column -->


  
          <!-- Read Column -->
          <div class="space-y-8">
            <h3 class="text-sm font-medium tracking-wider text-gray-400">READ</h3>
            
            <!-- Articles -->
            <a href="#" class="group flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full overflow-hidden">
                  <img 
                    src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=100&h=100&q=80" 
                    alt="Articles"
                    class="w-full h-full object-cover"
                  />
                </div>
                <span class="text-white text-lg group-hover:text-gray-300 transition-colors">
                  Articles
                </span>
              </div>
              <ArrowRight class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
            </a>
  
            <!-- Resources -->
            <a href="#" class="group flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full overflow-hidden">
                  <img 
                    src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=100&h=100&q=80" 
                    alt="Resources"
                    class="w-full h-full object-cover"
                  />
                </div>
                <span class="text-white text-lg group-hover:text-gray-300 transition-colors">
                  Resources
                </span>
              </div>
              <ArrowRight class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform" />
            </a>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ArrowRight } from 'lucide-vue-next'
  </script>
  
  <style scoped>
  /* Prevent image drag */
  img {
    user-drag: none;
    -webkit-user-drag: none;
  }
  </style>