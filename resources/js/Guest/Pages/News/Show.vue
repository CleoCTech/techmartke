<template>
    <div>
        <Head>
        <title>News </title>
    </Head>

    <div v-if="isLoading">
        <Loader />
    </div>

    <main v-else class="main">
        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(../assets/img/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">News</h2>
            <ul class="breadcrumb-menu">
                <li><Link href="/">Home</Link></li>
                <li class="active">News</li>
            </ul>
        </div>
        </div>

        <div class="event-single-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="event-details">
                            <!-- <img src="assets/img/event/single.jpg" alt=""> -->
                            <img :src="'/storage/news/cover_images/' + news.cover_image" alt="">

                            <div class="my-4">
                                <h3 class="mb-2">{{news.title}}</h3>
                                
                                <p v-html="news.content"></p>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


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

const news = props.news;

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