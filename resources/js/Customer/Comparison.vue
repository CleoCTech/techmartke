<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Search, SearchX, Sparkles, ArrowLeft, Check, Star, Clock } from 'lucide-vue-next';

const props = defineProps({
    products: { type: Array, default: () => [] },
    query: { type: String, default: '' },
    budget: { type: [Number, String], default: 0 },
    interpretation: { type: String, default: '' },
    recommendation: { type: String, default: '' },
    searchType: { type: String, default: 'budget' },
});

const newQuery = ref(props.query.replace('Budget: ', '').replace(/^KSh /, ''));
const sortBy = ref('relevance');

const formatPrice = (price) => 'KSh ' + Number(price).toLocaleString();

const isOutOfStock = (product) => product.stock_status !== 'in_stock';

const sortedProducts = computed(() => {
    const list = [...props.products];
    switch (sortBy.value) {
        case 'price_low': return list.sort((a, b) => a.base_price - b.base_price);
        case 'price_high': return list.sort((a, b) => b.base_price - a.base_price);
        default: return list;
    }
});

const search = () => {
    const q = newQuery.value.trim();
    if (!q) return;
    const numOnly = q.replace(/[,\s]/g, '');
    if (/^\d+$/.test(numOnly)) {
        router.get('/compare', { budget: numOnly });
    } else {
        router.get('/compare', { q });
    }
};

const bestValue = computed(() => {
    if (props.products.length < 2) return null;
    return props.products.reduce((best, p) => {
        const score = (p.specifications?.length || 0) + (p.advantages?.length || 0);
        const bestScore = (best.specifications?.length || 0) + (best.advantages?.length || 0);
        return score > bestScore ? p : best;
    }, props.products[0]);
});
</script>

<template>
    <Head :title="`${query} — AI Smart Search Results`" />
    <CustomerLayout>
        <div class="bg-gray-50 min-h-screen">
            <!-- Search Header (sticky below nav) -->
            <div class="bg-white border-b border-gray-200 sticky top-[57px] md:top-[65px] z-20">
                <div class="container mx-auto px-4 py-3">
                    <div class="flex items-center gap-3 max-w-3xl mx-auto">
                        <Link href="/" class="p-2 hover:bg-gray-100 rounded-lg transition flex-shrink-0">
                            <ArrowLeft class="w-5 h-5 text-gray-500" />
                        </Link>
                        <div class="relative flex-1">
                            <Sparkles class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input
                                v-model="newQuery"
                                type="text"
                                class="w-full pl-10 pr-20 py-2.5 border border-gray-200 rounded-xl focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 text-sm"
                                placeholder="Search phones, laptops, or enter budget..."
                                @keyup.enter="search"
                            />
                            <button
                                @click="search"
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition"
                            >
                                <Search class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 py-6 md:py-8">
                <div class="max-w-6xl mx-auto">

                    <!-- AI Recommendation Card -->
                    <div v-if="interpretation || recommendation" class="mb-6">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <Sparkles class="w-5 h-5 text-white" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-sm text-gray-900">AI Recommendation</h3>
                                        <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full font-medium">Smart Search</span>
                                    </div>
                                    <p v-if="interpretation" class="text-xs text-gray-400 mb-2">Understanding: {{ interpretation }}</p>
                                    <p v-if="recommendation" class="text-sm text-gray-700 leading-relaxed">{{ recommendation }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Header + Sort -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-900">
                                {{ products.length }} {{ products.length === 1 ? 'result' : 'results' }} found
                            </h2>
                            <p class="text-sm text-gray-500 mt-0.5">
                                <template v-if="searchType === 'smart'">AI-powered results for "{{ query }}"</template>
                                <template v-else-if="searchType === 'budget'">Devices within your budget of {{ formatPrice(budget) }}</template>
                                <template v-else>Results for "{{ query }}"</template>
                            </p>
                        </div>
                        <select
                            v-model="sortBy"
                            class="text-sm border border-gray-200 rounded-lg py-2 px-3 bg-white focus:border-black focus:outline-none cursor-pointer self-start"
                        >
                            <option value="relevance">Best Match</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>
                    </div>

                    <!-- Product Results (list style) -->
                    <div v-if="sortedProducts.length" class="space-y-4">
                        <Link
                            v-for="(product, idx) in sortedProducts"
                            :key="product.id"
                            :href="`/products/${product.slug || product.id}`"
                            class="block bg-white rounded-2xl border shadow-sm hover:shadow-md transition-all overflow-hidden group"
                            :class="isOutOfStock(product) ? 'border-gray-200 opacity-70' : 'border-gray-100 hover:border-gray-200'"
                        >
                            <div class="flex flex-col sm:flex-row">
                                <!-- Image -->
                                <div class="sm:w-48 md:w-56 flex-shrink-0 bg-gray-50 p-4 relative"
                                    :class="isOutOfStock(product) ? 'grayscale-[30%]' : ''"
                                >
                                    <img
                                        :src="product.images?.[0]?.image_url || product.images?.[0]?.url || '/assets/img/placeholder.jpg'"
                                        :alt="product.name"
                                        class="w-full h-40 sm:h-full object-contain transition-transform duration-300"
                                        :class="isOutOfStock(product) ? '' : 'group-hover:scale-105'"
                                        loading="lazy"
                                    />
                                    <div v-if="bestValue?.id === product.id && !isOutOfStock(product)" class="absolute top-2 left-2 bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <Star class="w-3 h-3" /> Best Pick
                                    </div>
                                    <div v-if="searchType === 'smart'" class="absolute top-2 right-2 w-7 h-7 bg-black/80 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        #{{ idx + 1 }}
                                    </div>
                                    <!-- Out of stock overlay -->
                                    <div v-if="isOutOfStock(product)" class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 text-center shadow-sm">
                                            <Clock class="w-4 h-4 text-amber-500 mx-auto mb-0.5" />
                                            <p class="text-[10px] font-bold text-gray-700">Available in ~1 week</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 p-4 sm:p-5">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span v-if="product.brand" class="text-xs font-semibold text-gray-400 uppercase">{{ product.brand?.name || product.brand }}</span>
                                                <span
                                                    v-if="product.condition"
                                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                                    :class="product.condition === 'new' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600'"
                                                >
                                                    {{ product.condition === 'ex-uk' ? 'Ex-UK' : product.condition }}
                                                </span>
                                                <span v-if="isOutOfStock(product)" class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 flex items-center gap-1">
                                                    <Clock class="w-3 h-3" /> Restocking
                                                </span>
                                            </div>
                                            <h3 class="text-base md:text-lg font-bold group-hover:text-black" :class="isOutOfStock(product) ? 'text-gray-500' : 'text-gray-900'">{{ product.name }}</h3>
                                        </div>
                                        <p class="text-lg md:text-xl font-extrabold whitespace-nowrap flex-shrink-0" :class="isOutOfStock(product) ? 'text-gray-400' : 'text-gray-900'">{{ formatPrice(product.base_price) }}</p>
                                    </div>

                                    <p v-if="product.short_description" class="text-sm text-gray-500 mb-3 line-clamp-2">{{ product.short_description }}</p>

                                    <!-- Out of stock notice -->
                                    <div v-if="isOutOfStock(product)" class="flex items-center gap-2 mb-3 p-2.5 bg-amber-50 border border-amber-100 rounded-lg">
                                        <Clock class="w-4 h-4 text-amber-500 flex-shrink-0" />
                                        <p class="text-xs text-amber-700">
                                            <strong>Currently out of stock</strong> — We can get this for you within about a week. Contact us to pre-order!
                                        </p>
                                    </div>

                                    <!-- Advantages -->
                                    <div v-if="product.advantages?.length" class="flex flex-wrap gap-2 mb-3">
                                        <span
                                            v-for="(adv, i) in product.advantages.slice(0, 3)"
                                            :key="i"
                                            class="inline-flex items-center gap-1 text-[11px] font-medium text-green-700 bg-green-50 px-2 py-1 rounded-md"
                                        >
                                            <Check class="w-3 h-3" />
                                            {{ adv.advantage || adv.text || adv }}
                                        </span>
                                    </div>

                                    <!-- Key Specs -->
                                    <div v-if="product.specifications?.length" class="flex flex-wrap gap-x-4 gap-y-1 mb-3">
                                        <span
                                            v-for="spec in product.specifications.slice(0, 4)"
                                            :key="spec.id"
                                            class="text-xs text-gray-400"
                                        >
                                            <strong class="text-gray-500">{{ spec.spec_name }}:</strong> {{ spec.spec_value }}
                                        </span>
                                    </div>

                                    <!-- Variants -->
                                    <div v-if="product.variants?.length" class="flex gap-1.5 pt-3 border-t border-gray-100">
                                        <span
                                            v-for="v in product.variants"
                                            :key="v.id"
                                            class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 font-medium"
                                        >
                                            {{ v.storage }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                        <SearchX class="w-14 h-14 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-xl font-bold mb-2 text-gray-900">No devices found</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm">
                            We couldn't find products matching "{{ query }}". Try a different search or browse all products.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <Link href="/products" class="px-6 py-2.5 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium text-sm">
                                Browse All Products
                            </Link>
                            <Link href="/" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                                New Search
                            </Link>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
