<script setup>
import Header from '@/Admin/Partials/Navbar.vue'
import Sidebar from '@/Admin/Partials/Sidebar.vue'
import Sidenav from '@/Admin/Partials/Sidenav.vue'
import GuestSidebar from '@/Admin/Partials/GuestSidebar.vue'
import DefaultFooter from '@/Admin/Partials/Footer.vue'
import { ref, getCurrentInstance, onMounted } from 'vue'
import { useNotify } from "@/Composables/useNotify";

import { provideToast } from '@/Composables/useToast';

provideToast();

let { notification } = useNotify();
let isLoading = ref(false);
const context = getCurrentInstance().appContext.config.globalProperties;

// Add this line to create a reactive sidebarOpen state
const sidebarOpen = ref(false)

// Add this function to toggle the sidebar
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}

context.$showLoading = () => {
    isLoading.value = true
}
context.$hideLoading = () => {
    isLoading.value = false
}

onMounted(() => {
    // notification('Test error message', 'error');

})
</script>

<template>
    <div id="app">
        <notifications />
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="fullPage" />
        <div class="flex h-[100dvh] overflow-hidden">
            <Sidebar :sidebarOpen="sidebarOpen" @close-sidebar="sidebarOpen = false" />
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <Header :sidebarOpen="sidebarOpen" @toggle-sidebar="toggleSidebar" />

                <main class="grow">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
