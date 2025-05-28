<template>
    <div>
        <div>
            <Component
            :is="link.url ? 'Link' : 'span'"
                v-for="(link, index) in links" :key="index"
                :href ="link.url"
                v-html="link.label"
                class="px-2"
                :class ="{'text-gray': ! link.url, 'text-gray-bold' : link.active  }"
            />
        </div>
    </div>
</template>

<script setup>
 import { Link } from '@inertiajs/vue3'
const props = defineProps({
    links: Array,
});
</script>

<style scoped>
    .text-gray {
        color:rgb(185, 183, 183);
    }
    .text-gray-bold {
        color:rgb(124, 44, 44);
        font-weight: bold;
    }
</style>