<script setup>
import {ref, onMounted, watch} from 'vue'
import { SwiperSlide } from 'swiper/vue';
import xSwiper from '@/Components/Swiper.vue'
import Loader from '@/Guest/Partials/Preloader.vue';

const isLoading =  ref(true);
const props = defineProps({
    partners:Object,
});

onMounted(() => {
    isLoading.value = false;
})

</script>
<template>
    <div>
        <div v-if="isLoading" class="preloader">
            <Loader />
        </div>

        <section v-if="!isLoading" class="bg-white">
            <div class="max-w-screen-xl mx-auto">
                <h2 class="font-black text-purple-800 text-center text-3xl leading-none uppercase max-w-2xl mx-auto mb-12">
                    Our Partners</h2>
                    <div class="container">
                <div class="row">
                    <div class="section-title with-hospital-color">
                        <h2 style="color:white">Our Partners</h2>
                    </div>
                    <x-swiper :slidesPerView="3">
                        <swiper-slide v-for="(partner, index) in partners" :key="index">
                           
                            <!-- <div class="col-md-3 col-6 mb-4">
                                <img :src="'/storage/partner/cover_images/'+partner.cover_image" class="img-fluid partner-logo" alt="Partner 1">
                                <p class="text-center mt-3" style="color:white">{{partner.name}}</p>
                            </div> -->
                            <!-- <div class="d-flex flex-column align-items-center">
                                <img :src="'/storage/partner/cover_images/'+partner.cover_image" class="img-fluid partner-logo" alt="Partner 1">
                                <p class="text-center mt-3" style="color:white">{{partner.name}}</p>
                            </div> -->

                            <div class="max-w-7xl mx-auto py-12 px-4 ml-8 sm:px-6 lg:py-16 lg:px-8">
                                <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                                    <div class="space-y-8 xl:col-span-1">
                                        <img class="h-50" :src="'/storage/partner/cover_images/'+partner.cover_image"  alt="Company name">
                                        <p class="text-gray-500 text-center ">
                                            {{partner.name}}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </swiper-slide>
                    </x-swiper>
                </div>
            </div>
            </div>
            
            
        </section>
    </div>
</template>
<style scoped>
  .partner-logo {
    width: 150px;
    height: 150px;
    object-fit: contain;
  }
</style>

