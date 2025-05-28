<script setup>
import {onMounted, ref, watch} from 'vue'
import { SwiperSlide } from 'swiper/vue';
import xSwiper from '@/Components/Swiper.vue'

const isLoading = ref(true)
const props =  defineProps({
    services:Object,
})

// watch(() => isLoading.value, () => {
//     // Set the loading state to false once the main content has finished loading
//     isLoading.value = false;
// });
onMounted(() => {
    isLoading.value = false;
})
</script>
<template>
    <div>
        <div v-if="isLoading" class="preloader">
            <div class="loader">
                <div class="sbl-half-circle-spin"></div>
            </div>
        </div>
        <div v-else >
            <div class="hospital-services-area pt-100 pb-75">
                <div class="container">
                <div
                    class="section-title with-hospital-color d-flex justify-content-between align-items-center"
                >
                    <div class="div text-start">
                    <span>We Offer Services</span>
                    <h2>Our Special Services</h2>
                    </div>
                    <Link :href="route('services')" class="default-btn">View All Services</Link>
                </div>
                
                <div class="hospital-services-slides owl-theme">
                    <x-swiper :slidesPerView="4">
                        <swiper-slide v-for="(service, index) in services" :key="index">
                            <div  class="hospital-services-card">
                                <div class="title">
                                <img :src="'/storage/service/cover_images/'+service.cover_image" alt="image" />
                                <h3>
                                    <Link :href="'/services/'+service.title.replace(/ /g,'-').toLowerCase()">{{service.title}}</Link>
                                </h3>
                                </div>
                                <p v-html="service.summary"></p>
                                <Link :href="'/services/'+service.title.replace(/ /g,'-').toLowerCase()" class="services-btn">Read More</Link>
                            </div>
                        </swiper-slide>
                    </x-swiper>
                </div>

                </div>
            </div>
        </div>
    </div>
</template>


