<template>
    <div>
        <Head>
        <title>Events </title>
    </Head>

    <div v-if="isLoading">
        <Loader />
    </div>

    <main v-else class="main">
        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Events</h2>
            <ul class="breadcrumb-menu">
                <li><Link href="/">Home</Link></li>
                <li class="active">Events</li>
            </ul>
        </div>
        </div>


        <div class="event-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Events</span>
                            <h2 class="site-title">Our Upcoming <span>Events</span></h2>
                            <p>It is a long established fact that a reader will be distracted by the readable content of
                                a page when looking at its layout.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="event-item">
                            <div class="event-location">
                                <span><i class="far fa-map-marker-alt"></i> 25/B Milford Road, New York</span>
                            </div>
                            <div class="event-img">
                                <img src="assets/img/event/01.jpg" alt="">
                            </div>
                            <div class="event-info">
                                <div class="event-meta">
                                    <span class="event-date"><i class="far fa-calendar-alt"></i>16 June, 2024</span>
                                    <span class="event-time"><i class="far fa-clock"></i>10.00AM - 04.00PM</span>
                                </div>
                                <h4 class="event-title"><a href="#">High School Program 2024</a></h4>
                                <p>There are many variations of passages the majority have some injected humour.</p>
                                <div class="event-btn">
                                    <a href="#" class="theme-btn">Join Event<i class="fas fa-arrow-right-long"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="event-item">
                            <div class="event-location">
                                <span><i class="far fa-map-marker-alt"></i> 25/B Milford Road, New York</span>
                            </div>
                            <div class="event-img">
                                <img src="assets/img/event/02.jpg" alt="">
                            </div>
                            <div class="event-info">
                                <div class="event-meta">
                                    <span class="event-date"><i class="far fa-calendar-alt"></i>16 June, 2024</span>
                                    <span class="event-time"><i class="far fa-clock"></i>10.00AM - 04.00PM</span>
                                </div>
                                <h4 class="event-title"><a href="#">High School Program 2024</a></h4>
                                <p>There are many variations of passages the majority have some injected humour.</p>
                                <div class="event-btn">
                                    <a href="#" class="theme-btn">Join Event<i class="fas fa-arrow-right-long"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="event-item">
                            <div class="event-location">
                                <span><i class="far fa-map-marker-alt"></i> 25/B Milford Road, New York</span>
                            </div>
                            <div class="event-img">
                                <img src="assets/img/event/03.jpg" alt="">
                            </div>
                            <div class="event-info">
                                <div class="event-meta">
                                    <span class="event-date"><i class="far fa-calendar-alt"></i>16 June, 2024</span>
                                    <span class="event-time"><i class="far fa-clock"></i>10.00AM - 04.00PM</span>
                                </div>
                                <h4 class="event-title"><a href="#">High School Program 2024</a></h4>
                                <p>There are many variations of passages the majority have some injected humour.</p>
                                <div class="event-btn">
                                    <a href="#" class="theme-btn">Join Event<i class="fas fa-arrow-right-long"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- pagination -->
                <div class="pagination-area">
                    <div aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- pagination end -->
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