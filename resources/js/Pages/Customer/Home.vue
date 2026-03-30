<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { Search } from 'lucide-vue-next';

defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
});

const budget = ref('');

const compareNow = () => {
    if (budget.value) {
        router.get('/compare', { budget: budget.value });
    }
};
</script>

<template>
    <CustomerLayout>
        <!-- Hero Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-black to-gray-500 bg-clip-text text-transparent">
                        Find Your Perfect Device
                    </h2>
                    <p class="text-xl mb-10 text-gray-600">
                        Smart AI-powered comparison within your budget
                    </p>

                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-200">
                        <label class="block text-left font-semibold mb-3 text-gray-700">
                            Your Budget (KES)
                        </label>
                        <div class="flex gap-3">
                            <input
                                v-model="budget"
                                type="number"
                                class="flex-1 px-6 py-4 text-2xl border-2 border-gray-300 rounded-xl focus:border-black focus:outline-none"
                                placeholder="Enter your budget"
                                @keyup.enter="compareNow"
                            />
                            <button
                                @click="compareNow"
                                class="px-8 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium flex items-center gap-2"
                            >
                                <Search class="w-5 h-5" />
                                Compare Now
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-3 text-left">
                            We'll show you the best phones and computers within your budget with AI-powered comparisons
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <h3 class="text-3xl font-bold mb-8">Featured Products</h3>
                <div
                    v-if="featuredProducts.length"
                    class="grid md:grid-cols-3 gap-8"
                >
                    <ProductCard
                        v-for="product in featuredProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
                <div v-else class="text-center py-16 text-gray-500">
                    <p class="text-lg">No featured products available at the moment.</p>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>
