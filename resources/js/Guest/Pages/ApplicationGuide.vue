<template>
    <div>

        <Head>
            <title>How To Apply</title>
        </Head>

        <div v-if="isLoading">
            <Loader />
        </div>

        <main v-else class="main">
            <!-- breadcrumb -->
            <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
                <div class="container">
                    <h2 class="breadcrumb-title">How To Apply</h2>
                    <ul class="breadcrumb-menu">
                        <li>
                            <Link href="/">Home</Link>
                        </li>
                        <li class="active">How To Apply</li>
                    </ul>
                </div>
            </div>

            <!-- how apply -->
            <div class="how-apply pt-120 pb-80">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                                <div class="site-heading mb-3">
                                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> How To
                                        Apply</span>
                                    <h2 class="site-title">
                                        Details About <span>How To Apply</span> Anestar-JSS.
                                    </h2>
                                </div>
                                <p class="content-text">
                                    At Anestar Junior Secondary School, we are committed to providing a seamless and welcoming admission process for all our prospective students and their families. Our application process is designed to help you easily take the steps needed to join our vibrant learning community. Below, you’ll find a step-by-step guide that outlines each part of the application process, from submitting your initial application to receiving your final admission decision. 
                                </p>
                                <p class="content-text mt-2">
                                    We are here to support you every step of the way and look forward to welcoming you to Anestar-JSS.
                                </p>
                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <ul class="content-list">
                                            <li><i class="fas fa-check-circle"></i>Start Online Submission</li>
                                            <li><i class="fas fa-check-circle"></i>Submit The Form</li>
                                            <li><i class="fas fa-check-circle"></i>Review The Submission</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="content-list">
                                            <li><i class="fas fa-check-circle"></i>Gather Necessary Documents</li>
                                            <li><i class="fas fa-check-circle"></i>Interviewing Process</li>
                                            <li><i class="fas fa-check-circle"></i>Last Decision</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="content-btn">
                                    <Link :href="route('application')" class="theme-btn">Apply Now<i
                                        class="fas fa-arrow-right-long"></i></Link>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="content-img wow fadeInRight" data-wow-delay=".25s">
                                <img src="assets/img/apply/03.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- how apply end-->


            <!-- apply details -->
            <!-- apply details end -->

            <!-- faq area -->
            <div class="faq-area py-120" id="faq">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="faq-right">
                                <div class="site-heading mb-3">
                                    <span class="site-title-tagline justify-content-start"><i
                                            class="far fa-book-open-reader"></i> Faq's</span>
                                    <h2 class="site-title my-3">General <span>frequently</span> asked questions</h2>
                                </div>
                                <p class="mb-3">Welcome to our Frequently Asked Questions section! Here, we've compiled answers to some of the most common inquiries we receive from parents and students. Whether you're curious about our admission process, school fees, academic programs, or extracurricular activities, this section aims to provide quick and helpful answers. </p>
                                <p class="mb-4">
                                    If you don't find what you're looking for, please don't hesitate to reach out to our live chat/contact form for further assistance.
                                </p>
                                <Link :href="route('contact-us')" class="theme-btn mt-2">Have Any Question ?</Link>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="accordion" id="accordionExample">
                                <div v-for="(faq, index) in faqs" :key="index" class="accordion-item">
                                    <h2 class="accordion-header" :id="`heading${index}`">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            :data-bs-target="`#collapse${index}`" :aria-controls="`collapse${index}`"
                                            :aria-expanded="index === 0 ? 'true' : 'false'">
                                            <span><i class="far fa-question"></i></span> {{ faq.question }}
                                        </button>
                                    </h2>
                                    <div :id="`collapse${index}`" class="accordion-collapse collapse"
                                        :class="{ show: index === 0 }" :aria-labelledby="`heading${index}`"
                                        data-bs-parent="#accordionExample">
                                        <div v-html="faq.answer" class="accordion-body"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- faq area end -->

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
const faqs = props.faqs;
const isLoading = ref(true);
const footerData = ref({});

const formData = reactive({
    first_name: '',
    last_name: '',
    email: '',
    message: ''
});

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

async function sendMessage() {
    console.log(formData);
    // return;
    isLoading.value = true
    if (formData.first_name == '' || formData.last_name == '' || formData.email == '' || formData.message == '') {
        isLoading.value = false
        return notification('Kindly fill all the required fields', 'error')
    }

    try {
        const response = await axios.post(`/customer-inquiry`, formData);
        if (response.data.error) {
            notification(response.data.error, 'error')
        }
        if (response.data.success) {
            notification(response.data.success, 'success')
        }
        formData.first_name = '';
        formData.email = '';
        formData.last_name = '';
        formData.message = '';

        isLoading.value = false
    } catch (error) {
        console.log(error.error);
        notification(error.error, 'error')
        isLoading.value = false
    }
}
</script>

<style scoped></style>