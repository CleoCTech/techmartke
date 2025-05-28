<script setup>
import { usePage } from '@inertiajs/vue3'
import GuestNavbar from '@/Guest/Partials/Navbar.vue'
import GuestFooter from '@/Guest/Partials/Footer.vue'

</script>

<template>
    <div id="app">
        <div>
            <notifications />
            <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="fullPage"/>
            <!--navbar-->
            <GuestNavbar />
                <slot />
            <!--footer-->
            <GuestFooter />
        </div>
    </div>
</template>

