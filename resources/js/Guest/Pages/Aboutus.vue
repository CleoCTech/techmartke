<template>
    <div>
        <Head>
        <title>About Us</title>
    </Head>

    <div v-if="isLoading">
        <Loader />
    </div>

    <main v-else class="main">
        <!-- breadcrumb -->
          <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">About Us</h2>
                <ul class="breadcrumb-menu">
                    <li><Link href="/">Home</Link></li>
                    <li class="active">About Us</li>
                </ul>
            </div>
          </div>

            <!-- about area -->
            <div class="about-area py-120">
            <div class="container">
                <div class="row g-4 ">
                    <div class="col-lg-4">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <img class="img-1"
                                                    :src="'/storage/gallery/cover_images/' + footerData.galleries[randomIndex].cover_image"
                                                    :alt="footerData.galleries[randomIndex].name || 'Gallery Image'">
                                        <div class="about-experience mt-2">
                                            <div class="about-experience-icon">
                                                <img src="assets/img/icon/exchange-idea.svg" alt="">
                                            </div>
                                            <b class="text-start">24 Years Of <br> Quality Service</b>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <img class="img-2" :src="'/storage/gallery/cover_images/' + footerData.galleries[2].cover_image" alt="">
                                        <img class="img-3 mt-4" :src="'/storage/gallery/cover_images/' + footerData.galleries[0].cover_image"  alt="">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                            <div class="site-heading mb-3">
                                <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> About Us</span>
                                <h2 class="site-title">
                                    Our Education System <span>Inspires</span> You More.
                                </h2>
                            </div>
                            <p class="about-text" v-html="footerData.companyInfo.about_short">
                               
                            </p>
                            <div class="about-content">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="about-item">
                                            <div class="about-item-icon">
                                                <img src="assets/img/icon/open-book.svg" alt="">
                                            </div>
                                            <div class="about-item-content">
                                                <h5>Our Mission</h5>
                                                <p>{{footerData.companyInfo.mission}}.</p>
                                            </div>
                                        </div>
                                        <div class="about-item">
                                            <div class="about-item-icon">
                                                <img src="assets/img/icon/global-education.svg" alt="">
                                            </div>
                                            <div class="about-item-content">
                                                <h5>School Vision</h5>
                                                <p>{{footerData.companyInfo.vision}}</p>
                                            </div>
                                        </div>
                                        <div class="about-item">
                                            <div class="about-item-icon">
                                                <img src="assets/img/icon/global-education.svg" alt="">
                                            </div>
                                            <div class="about-item-content">
                                                <h5>School Motto</h5>
                                                <p>{{footerData.companyInfo.motto}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="about-quote">
                                            <p>{{footerData.companyInfo.highlight}}</p>
                                            <i class="far fa-quote-right"></i>
                                        </div>
                                        <div class="about-item mt-4">
                                            <div class="about-item-icon">
                                                <img src="assets/img/icon/global-education.svg" alt="">
                                            </div>
                                            <div class="about-item-content">
                                                <h5>Our Core Values</h5>
                                                <ul class="portfolio-single-list">
                                                    <li><i class="far fa-check"></i>Commitment</li>
                                                    <li><i class="far fa-check"></i>Integrity</li>
                                                    <li><i class="far fa-check"></i>Sacrifice
                                                    </li>
                                                    <li><i class="far fa-check"></i>Christ Centeredness</li>
                                                    <li><i class="far fa-check"></i>Hard work</li>
                                                    <li><i class="far fa-check"></i>Teamwork</li>
                                                    <li><i class="far fa-check"></i>Humility</li>
                                                    <li><i class="far fa-check"></i>Compassion</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="about-bottom">
                                <Link href="/about-us" class="theme-btn">Discover More<i
                                        class="fas fa-arrow-right-long"></i></Link>
                                <div class="about-phone">
                                    <div class="icon"><i class="fal fa-headset"></i></div>
                                    <div class="number">
                                        <span>Call Now</span>
                                        <h6><a href="tel:+21236547898">{{footerData.companyInfo.phone_numbers}}</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <!-- about area end -->

        <!-- counter area -->
        <div class="counter-area pt-60 pb-60 d-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/course.svg" alt="">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="500" data-speed="3000">500</span>
                                <h6 class="title">+ Total Cources </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/graduation.svg" alt="">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="1900" data-speed="3000">1900</span>
                                <h6 class="title">+ Our Students</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/teacher-2.svg" alt="">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="750" data-speed="3000">750</span>
                                <h6 class="title">+ Skilled Lecturers</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/award.svg" alt="">
                            </div>
                            <div>
                                <span class="counter" data-count="+" data-to="30" data-speed="3000">30</span>
                                <h6 class="title">+ Win Awards</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter area end -->

        <!-- team-area -->
        <div class="team-area py-120">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Our Team</span>
                                <h2 class="site-title">Meet With Our <span>Team</span></h2>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div v-for="(staff, index) in staffs" :key="index" class="col-md-6 col-lg-3">
                            <div class="team-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="team-img">
                                    <img :src="'/storage/staff/cover_images/' + staff.cover_image" alt="thumb">
                                </div>
                                <div class="team-social">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-youtube"></i></a>
                                </div>
                                <div class="team-content">
                                    <div class="team-bio">
                                        <h5><a href="#">{{staff.name}}</a></h5>
                                        <span>{{staff.title}}</span>
                                    </div>
                                </div>
                                <span class="team-social-btn"><i class="far fa-share-nodes"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- team-area end -->

        <!-- map -->
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7738794668726!2d36.197254713895724!3d-0.27042869980268497!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182994f3edebda27%3A0x8a48ece51be6af9!2sANESTAR%20VICTORY%20BOYS%20-%20LANET!5e0!3m2!1sen!2ske!4v1654831370303!5m2!1sen!2ske"
                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </main>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed  } from 'vue'
import { useNotify } from "@/Composables/useNotify";
import Loader from '@/Guest/Partials/Preloader.vue';
import { usePage, router } from '@inertiajs/vue3';


let { notification } = useNotify();
const { props } = usePage();
const isLoading = ref(true);
const footerData = ref({});

const staffs = props.staffs;

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

const randomIndex = computed(() => {
  if (footerData.value && footerData.value.galleries && footerData.value.galleries.length > 0) {
    return Math.floor(Math.random() * footerData.value.galleries.length);
  }
  return 0; // Default or safe fallback index
});
</script>

<style  scoped>

</style>