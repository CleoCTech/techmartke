<script setup>
import { usePage } from '@inertiajs/vue3'
import Header from '@/Guest/Partials/Header.vue'
import Footer from '@/Guest/Partials/Footer2.vue'

</script>

<template>
    <div id="app" >
        <div>
            <h1>HEEEY</h1>
            <notifications />
            <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="fullPage"/>
            <!--navbar-->
            <Header />
                <slot />
            <!--footer-->
            <Footer />
        </div>
    </div>
</template>

