<script setup>
import { usePage } from '@inertiajs/vue3'
import Navbar from '@/Guest/Partials/Navbar2.vue'
import Header from '@/Guest/Partials/Header.vue'
import Footer from '@/Guest/Partials/Footer3.vue'
import Loader from '@/Guest/Partials/Preloader.vue'
</script>

<template>
    <!-- <div id="app"> -->

    
    <div class="min-h-screen flex flex-col" id="app">
        <notifications />
        <Header />
        <main class="flex-grow">
            <slot />
        </main>
        <Footer />
        <!-- Suspended Book Now Button -->
        <!-- <Link href="#" class="fixed bottom-4 right-4 bg-companyGreen text-white px-14 mr-4 text-2xl  py-3 rounded-full shadow-lg hover:bg-purple-900 transition duration-300">
            Apply Now
        </Link> -->
        <Loader v-if="isLoading" class="fixed inset-0 z-50" />
    </div>
<!-- </div> -->
</template>

