<template>
    <div>
        <Head>
        <title>ICT Centre</title>
    </Head>

    <div v-if="isLoading">
        <Loader />
    </div>

    <main v-else class="main">
        <!-- breadcrumb -->
          <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Gallery</h2>
                <ul class="breadcrumb-menu">
                    <li><Link href="/">Home</Link></li>
                    <li class="active">Our ICT Centre</li>
                </ul>
            </div>
          </div>

          
        <!-- gallery-area -->
        <div class="gallery-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-book-open-reader"></i>ICT Centre Gallery</span>
                            <h2 class="site-title">Our <span>ICT</span> Centre</h2>
                            <p>At Anestar Schools, we have a wide range of quality resources and facilities which support our students in their learning. </p>
                        </div>
                    </div>
                </div>
                <div class="row popup-gallery">
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".25s">
                        <div v-for="(image, index) in contents.slice(0, 2)" :key="index" class="gallery-item">
                            <div class="gallery-img">
                                <img :src="'/storage/ictFiles/cover_images/' + image.cover_image" alt="">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" :href="'/storage/ictFile/cover_images/' + image.cover_image"><i
                                        class="fal fa-plus"></i></a>
                                        <!-- <a class="popup-img gallery-link" href="assets/img/gallery/01.jpg"><i
                                            class="fal fa-plus"></i></a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".25s">
                        <div v-for="(image, index) in contents.slice(2, 4)" :key="index" class="gallery-item">
                            <div class="gallery-img">
                                <img :src="'/storage/ictFile/cover_images/' + image.cover_image" alt="">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" :href="'/storage/ictFile/cover_images/' + image.cover_image"><i
                                        class="fal fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".25s">
                        <div v-for="(image, index) in contents.slice(4, 6)" :key="index" class="gallery-item">
                            <div class="gallery-img">
                                <img :src="'/storage/ictFile/cover_images/' + image.cover_image" alt="">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" :href="'/storage/ictFile/cover_images/' + image.cover_image"><i
                                        class="fal fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay=".25s">
                        <div v-for="(image, index) in contents.slice(6, 8)" :key="index" class="gallery-item">
                            <div class="gallery-img">
                                <img :src="'/storage/ictFile/cover_images/' + image.cover_image" alt="">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" :href="'/storage/ictFile/cover_images/' + image.cover_image"><i
                                        class="fal fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- gallery-area end -->

    </main>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useNotify } from "@/Composables/useNotify";
import Loader from '@/Guest/Partials/Preloader.vue';
import { usePage, router } from '@inertiajs/vue3';


let { notification } = useNotify();
const { props } = usePage();
const isLoading = ref(true);
const footerData = ref({});

const contents = props.contents;

onMounted(async () => {
   // Delay added to ensure the DOM is rendered before initialization
   // initializeCarousel();
    isLoading.value = true; // Set loading to true at the start
    try {
        await getFooterData(); // Await getFooterData function
    } finally {
        setTimeout(() => {
            isLoading.value = false; // Ensure loading is set to false no matter what
        }, 3000);
    }
   
});


const getFooterData = async () => {
  isLoading.value = true; // Set loading to true before starting the request
  try {
    const response = await axios.get('/footer-data');
    footerData.value = response.data;
    
  } catch (error) {
    console.log(error);
    notification(error, 'error');
  } finally {
    setTimeout(() => {
        isLoading.value = false; // Ensure loading is set to false no matter what
    }, 3000);
    
  }
};
</script>

<style  scoped>

</style>