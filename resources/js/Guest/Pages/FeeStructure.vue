<template>
    <div>

        <Head>
            <title>Fee Structure</title>
        </Head>

        <div v-if="isLoading">
            <Loader />
        </div>

        <main v-else class="main">
            <!-- breadcrumb -->
            <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
                <div class="container">
                    <h2 class="breadcrumb-title">Fee Structure</h2>
                    <ul class="breadcrumb-menu">
                        <li>
                            <Link href="/">Home</Link>
                        </li>
                        <li class="active">Fee Structure</li>
                    </ul>
                </div>
            </div>
            <!-- tuition fee -->

            <div class=" tuition-fee py-12">
                <div class="container">
                    <div class="tuition-wrap">
                        <div class="site-heading mb-3">
                            <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> JSS School Fees
                                Structure -2025</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="tuition-fee-table">
                                    <h4 class="site-title my-4">
                                        <span class="site-title-tagline">Full Boarding</span>
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-light">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Grade</th>
                                                    <th>Term 1</th>
                                                    <th>Term 2</th>
                                                    <th>Term 3</th>
                                                    <th scope="col">Totals</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="fee in fullBoarding" :key="fee.id">
                                                    <td>{{ fee.grade }}</td>
                                                    <td>{{ fee.term1 }}</td>
                                                    <td>{{ fee.term2 }}</td>
                                                    <td>{{ fee.term3 }}</td>
                                                    <td>{{ formatCurrency(fee.total) }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="site-title my-4">
                                        <span class="site-title-tagline">Flex Boarding</span>
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-light">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Grade</th>
                                                    <th>Term 1</th>
                                                    <th>Term 2</th>
                                                    <th>Term 3</th>
                                                    <th scope="col">Totals</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="fee in flexBoarding" :key="fee.id">
                                                    <td>{{ fee.grade }}</td>
                                                    <td>{{ fee.term1 }}</td>
                                                    <td>{{ fee.term2 }}</td>
                                                    <td>{{ fee.term3 }}</td>
                                                    <td>{{ formatCurrency(fee.total) }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="site-title my-4">
                                        <span class="site-title-tagline">Day School</span>
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-light">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Grade</th>
                                                    <th>Term 1</th>
                                                    <th>Term 2</th>
                                                    <th>Term 3</th>
                                                    <th>Totals</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="fee in daySchool" :key="fee.id">
                                                    <td>{{ fee.grade }}</td>
                                                    <td>{{ fee.term1 }}</td>
                                                    <td>{{ fee.term2 }}</td>
                                                    <td>{{ fee.term3 }}</td>
                                                    <td>{{ formatCurrency(fee.total) }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget department-download">


                                    <p v-html="footerData.companyInfo.fee_naration"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <div class="widget department-download">
                                <h4 class="widget-title">MPESA PAYBILL No: 522533 Account No: 7704582#student first name and admission number [no spacing]</h4>
                                <hr>

                                <h4 class="widget-title">ACCOUNT NAME: A.G.S STAND ALONE J.S.S CAMPUS-LANET</h4>

                                <div class="table-responsive">
                                        <table class="table table-light">
                                            <thead>
                                                <tr>
                                                    <th scope="col">BANK</th>
                                                    <th scope="col">ACOUNT NUMBER</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Kenya Commercial Bank (KCB)</td>
                                                    <td>1304794075</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                <p>
                                    J.S.S Lanet Campus provides a world class learning experience and the best quality
                                    education for your child at an affordable price. Thank you for the prompt payment of
                                    fees. We’re always available if you have any questions or wish to discuss any aspect
                                    of the finances.
                                </p>
                            </div>

                            <a :href="'/fee-structure/'+feeStructure.uuid" class="theme-btn mt-4"><span class="far fa-file-pdf"></span> Download Fee
                                Structure</a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- tuition fee end -->


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

const fees = props.fees;
const feeStructure = props.feeStructure;
// Refactor fees based on categories
const fullBoarding = ref(props.fees.filter(fee => fee.category === 'full_boarding'));
const flexBoarding = ref(props.fees.filter(fee => fee.category === 'flex_boarding'));
const daySchool = ref(props.fees.filter(fee => fee.category === 'day_school'));

// Format currency
const formatCurrency = (amount) => {
    return `KSH.${amount.toLocaleString()}`;
};

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

<style scoped></style>