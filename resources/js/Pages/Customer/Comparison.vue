<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Check, SearchX } from 'lucide-vue-next';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
    budget: {
        type: [Number, String],
        default: 0,
    },
    recommendation: {
        type: String,
        default: '',
    },
});

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};
</script>

<template>
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold mb-2">Budget Comparison</h2>
                <p class="text-gray-600 mb-8">
                    Showing the best options within your budget of
                    <span class="font-bold text-black">{{ formatPrice(budget) }}</span>
                </p>

                <!-- Products Comparison -->
                <div v-if="products.length" class="grid md:grid-cols-2 gap-8">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="border-2 border-black rounded-2xl p-6"
                    >
                        <Link :href="`/products/${product.slug || product.id}`">
                            <img
                                :src="product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg'"
                                :alt="product.name"
                                class="w-full h-64 object-cover rounded-xl mb-4"
                            />
                        </Link>
                        <h4 class="text-2xl font-bold mb-2">{{ product.name }}</h4>
                        <p class="text-3xl font-bold mb-4">{{ formatPrice(product.base_price || product.price) }}</p>

                        <!-- Advantages -->
                        <div v-if="product.advantages?.length" class="space-y-3 mb-4">
                            <div
                                v-for="(advantage, idx) in product.advantages"
                                :key="idx"
                                class="bg-green-50 border border-green-200 rounded-lg p-3"
                            >
                                <p class="text-sm font-medium text-green-800 flex items-center gap-2">
                                    <Check class="w-4 h-4 shrink-0" />
                                    {{ advantage.text || advantage }}
                                </p>
                            </div>
                        </div>

                        <!-- Specs Summary -->
                        <div v-if="product.specifications?.length" class="space-y-2 text-sm text-gray-600">
                            <p v-for="spec in product.specifications.slice(0, 5)" :key="spec.id || spec.name">
                                <strong>{{ spec.name || spec.key }}:</strong> {{ spec.value }}
                            </p>
                        </div>

                        <Link
                            :href="`/products/${product.slug || product.id}`"
                            class="mt-4 block text-center px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium"
                        >
                            View Details
                        </Link>
                    </div>
                </div>

                <!-- No Products -->
                <div v-else class="text-center py-20">
                    <SearchX class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-2xl font-bold mb-2">No products found</h3>
                    <p class="text-gray-500 mb-6">
                        We couldn't find any products within your budget of {{ formatPrice(budget) }}.
                        Try increasing your budget or browse all products.
                    </p>
                    <Link
                        href="/products"
                        class="px-8 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium inline-block"
                    >
                        Browse All Products
                    </Link>
                </div>

                <!-- AI Recommendation -->
                <div v-if="recommendation" class="mt-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="font-bold mb-3 text-lg">AI Recommendation</h4>
                    <p class="text-gray-700 leading-relaxed">{{ recommendation }}</p>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
