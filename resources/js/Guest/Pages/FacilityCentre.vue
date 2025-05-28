<template>
    <div>

        <Head>
            <title>Our ICT Centre</title>
        </Head>

        <div v-if="isLoading">
            <Loader />
        </div>

        <main v-else class="main">
            <!-- breadcrumb -->
            <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
                <div class="container">
                    <h2 class="breadcrumb-title">Resources and Facilities</h2>
                    <ul class="breadcrumb-menu">
                        <li>
                            <Link href="/">Home</Link>
                        </li>
                        <li class="active">Resources and Facilities</li>
                    </ul>
                </div>
            </div>


            <!-- facility fee -->
            <div class="facility-single-area py-120">
            <div class="container">
                <div class="facility-single-wrapper">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4">
                            <div class="facility-sidebar">
                                <div class="widget category">
                                    <h4 class="widget-title">All Facilities</h4>
                                    <div class="category-list">
                                        <a href="#"><i class="far fa-long-arrow-right"></i>Library and ICT</a>
                                        <a href="#"><i class="far fa-long-arrow-right"></i>Medical Facility</a>
                                        <a href="#"><i class="far fa-long-arrow-right"></i>Pastoral Care & Couseling</a>
                                    </div>
                                </div>
                                <!-- <div class="widget facility-download">
                                    <h4 class="widget-title">Download</h4>
                                    <a href="#"><i class="far fa-file-pdf"></i> Download Library eBook</a>
                                    <a href="#"><i class="far fa-file-alt"></i> Download Application</a>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8">
                            <div class="facility-details">
                                <div class="facility-details-img mb-30">
                                    <img src="assets/img/facility/ICT.jpg" alt="thumb">
                                </div>
                                <div class="facility-details">
                                    <h3 class="mb-20">Library & ICT Centre</h3>
                                    <p class="mb-20">
                                        At Anestar Schools, we have a wide range of quality resources and facilities which support our students in their learning. These include; Course books, Mock books, Supplementary books, Charts, Computers and screens with internet access allowing students to enhance their research skills. Our schools also have Science laboratories with all the necessary equipment supporting scientific investigations. We also have an Art room, Music rooms, home science and cookery rooms that are fully equipped.
                                    </p>
                                    <p class="mb-20">
                                        We have tutors who provide learning support to the students, just in case they have any difficulty with a subject.
                                    </p>
                                    <p class="mb-20">
                                        We are aware of the difficulties some students face in their learning and our tutors work with the teachers to help them progress as learners. Through a lot of hard work, these students are able to make significant progress and adopt a more positive attitude towards both their learning and schools life
                                    </p>
                                    <div class="row">
                                        <div class="col-md-8 mb-20">
                                            <img src="assets/img/facility/med.jpg" alt="">
                                        </div>
                                        <!-- <div class="col-md-6 mb-20">
                                            <img src="assets/img/facility/02.jpg" alt="">
                                        </div> -->
                                    </div>
                                    
                                    <div class="my-4">
                                        <div class="mb-3">
                                            <h3 class="mb-3">Medical</h3>
                                            <p>Our Schools have partnered with various hospitals and doctors to give medical care to our students at the cost of
                                            the Parent. In the case of an accident or more serious illness, the student is taken by one of our dorm Patrons or
                                            Matrons to any of the hospitals in Nakuru prior agreed with the parent for more specialized treatment.
                                            </p>
                                            <p>Parents or Guardians are informed of any accidents or illness, other than minor day to day complaints and when
                                            appropriate asked to make arrangements for hospital treatment.
                                            The school on various occasions will normally pay students’ medical costs and invoice parents on the next fees
                                            accounts.
                                            </p>
                                            <p class="mb-20">
                                                For larger bills, the hospital will invoice the parents directly
                                            </p>
                                        </div>
                                    </div>

                                    <div class="my-4">
                                        <h3 class="mb-3">Pastoral Care & Couseling</h3>
                                        <p>
                                            At Anestar, we uphold a praying culture and devotional life. We have well planned devotions every day from 6:30 pm – 7:00 pm. We also have weekly prayer services every Friday and Sunday services.
                                        </p>
                                        <p>
                                            We have a school Chaplain and dedicated team of Patron teachers that are committed to this. We also recognize
                                            other religions without exception.
                                        </p>
                                        <p>
                                            These Includes: 
                                        </p>
                                        <ul class="facility-single-list">
                                            <li><i class="far fa-check"></i>C.U</li>
                                            <li><i class="far fa-check"></i>Y.C.S</li>
                                            <li><i class="far fa-check"></i>S.D.A
                                            </li>
                                            <li><i class="far fa-check"></i>Muslims</li>
                                        </ul>
                                        <p>
                                            Guidance and counseling program assists to identify any challenges that the students may be having. Each of
our campuses have got guidance and counseling office that is headed by a resident school Chaplain.
                                        </p>
                                        <p>We also invite Counselors to counsel our students on various life issues and equip them on how to deal with life
                                            challenges</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- facility fee end -->


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


onMounted(async () => {
    // Delay added to ensure the DOM is rendered before initialization
    // initializeCarousel();
    isLoading.value = true; // Set loading to true at the start
    try {
        //await getFooterData(); // Await getFooterData function
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

<style scoped></style>