<template>
    <div>
        <Head>
        <title>Event - {{ event.title }}</title>
    </Head>

    <div v-if="isLoading">
        <Loader />
    </div>

    <main v-else class="main">
        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(../assets/img/breadcrumb/01.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Events</h2>
            <ul class="breadcrumb-menu">
                <li><Link href="/">Home</Link></li>
                <li class="active">Events</li>
            </ul>
        </div>
        </div>

        <div class="event-single-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="event-details">
                            <img :src="'/storage/event/cover_images/' + event.cover_image" alt="">

                            <div class="my-4">
                                <h3 class="mb-2">{{event.title}}</h3>
                                <p v-html="event.content"></p>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="widget event-single-info">
                            <h4 class="widget-title">Event Information</h4>
                            <p>
                                {{event.decription}}
                            </p>
                            <div class="event-content">
                                <div class="event-content-single">
                                    <h5><a href="#">Event Date</a></h5>
                                    <p><i class="far fa-calendar-alt"></i> {{formattedStartDate }} - {{formattedEndDate }}</p>
                                </div>
                                <div class="event-content-single">
                                    <h5><a href="#">Event Time</a></h5>
                                    <p><i class="far fa-clock"></i> {{formattedStartTime }} - {{formattedEndTime }}</p>
                                </div>
                                <div class="event-content-single">
                                    <h5><a href="#">Event Location</a></h5>
                                    <p><i class="far fa-map-marker-alt"></i> {{event.location}}</p>
                                </div>

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
import { ref, onMounted, reactive, computed } from 'vue'
import { useNotify } from "@/Composables/useNotify";
import Loader from '@/Guest/Partials/Preloader.vue';
import { usePage, router } from '@inertiajs/vue3';


let { notification } = useNotify();
const { props } = usePage();
const isLoading = ref(true);
const footerData = ref({});

const event = props.event;

// Computed property for formatted date
const formattedStartDate = computed(() => {
  if (!event.start_time) return '';
  const date = new Date(event.start_time);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
});
const formattedEndDate = computed(() => {
  if (!event.end_time) return '';
  const date = new Date(event.end_time);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
});

// Computed property for formatted time
const formattedStartTime = computed(() => {
  if (!event.start_time) return '';
  const date = new Date(event.start_time);
  return new Intl.DateTimeFormat('en-US', {
    hour: 'numeric',
    minute: 'numeric',
    hour12: true
  }).format(date);
});
const formattedEndTime = computed(() => {
  if (!event.end_time) return '';
  const date = new Date(event.end_time);
  return new Intl.DateTimeFormat('en-US', {
    hour: 'numeric',
    minute: 'numeric',
    hour12: true
  }).format(date);
});
onMounted(async () => {
   // Delay added to ensure the DOM is rendered before initialization
   // initializeCarousel();
    isLoading.value = true; // Set loading to true at the start
    console.log(event);
    
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