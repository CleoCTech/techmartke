<script setup>
import {ref, onMounted, watch} from 'vue'
import { SwiperSlide } from 'swiper/vue';
import xSwiper from '@/Components/Swiper.vue'

const isLoading =  ref(true);
const props = defineProps({
    testmonials:Object,
});

onMounted(() => {
    isLoading.value = false;
})
// watch(() => isLoading.value, () => {
//     // Set the loading state to false once the main content has finished loading
//     isLoading.value = false;
// });
</script>
<template>
    <div>
        <div v-if="isLoading" class="preloader">
            <div class="loader">
                <div class="sbl-half-circle-spin"></div>
            </div>
        </div>
        <div v-if="!isLoading" class="hospital-testimonials-area ptb-100">
            <div class="container">
                <div class="section-title with-hospital-color text-start">
                <span>Testimonials</span>
                <h2>Our Clients Feedback</h2>
                </div>
                <div class="hospital-testimonials-slides owl-theme">
                <x-swiper :slidesPerView="2">
                    <swiper-slide v-for="(testimonial, index) in testmonials" :key="index">
                        <div class="hospital-testimonials-card">
                        <p>
                        {{ testimonial.review }}
                        </p>
                        <div
                        class="info-content d-flex justify-content-between align-items-center"
                        >
                        <div class="info d-flex align-items-center">
                            <div class="title">
                            <h3>  {{ testimonial.name }}</h3>
                            <span>  {{ testimonial.title }}</span>
                            </div>
                        </div>
                        <ul class="rating">
                            <li>
                            <i class="bx bxs-star"></i>
                            </li>
                            <li>
                            <i class="bx bxs-star"></i>
                            </li>
                            <li>
                            <i class="bx bxs-star"></i>
                            </li>
                            <li>
                            <i class="bx bxs-star"></i>
                            </li>
                            <li>
                            <i class="bx bxs-star"></i>
                            </li>
                        </ul>
                        </div>
                    </div>
                    </swiper-slide>
                </x-swiper>
                </div>
            </div>
        </div>
    </div>
</template>

