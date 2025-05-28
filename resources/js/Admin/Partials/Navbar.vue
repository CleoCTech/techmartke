<script setup>
import { ref } from 'vue'

import SearchModal from '@/Components/Mosaic/ModalSearch.vue'
import Notifications from '@/Components/Mosaic/DropdownNotifications.vue'
import Help from '@/Components/Mosaic/DropdownHelp.vue'
import ThemeToggle from '@/Components/Mosaic/ThemeToggle.vue'
import UserMenu from '@/Components/Mosaic/DropdownProfile.vue'


const searchModalOpen = ref(false)

</script>
<template>
  <header class="sticky top-0 bg-white dark:bg-[#182235] border-b border-slate-200 dark:border-slate-700 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 -mb-px">

        <!-- Header: Left side -->
        <div class="flex">

          <!-- Hamburger button -->
          <button class="text-slate-500 hover:text-slate-600 lg:hidden" @click.stop="$emit('toggle-sidebar')" aria-controls="sidebar" :aria-expanded="sidebarOpen">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <rect x="4" y="5" width="16" height="2" />
              <rect x="4" y="11" width="16" height="2" />
              <rect x="4" y="17" width="16" height="2" />
            </svg>
          </button>

        </div>

        <!-- Header: Right side -->
        <div class="flex items-center space-x-3">
          <div>
            <SearchModal id="search-modal" searchId="search" :modalOpen="searchModalOpen" @open-modal="searchModalOpen = true" @close-modal="searchModalOpen = false" /> 
          </div>

          <!-- <Notifications align="right" /> -->
          <ThemeToggle />

          <!-- Divider -->
          <hr class="w-px h-6 bg-slate-200 dark:bg-slate-700 border-none" />

          <UserMenu align="right" class="" />

        </div>

      </div>
    </div>
  </header>
</template>


