<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';
import { Search, SlidersHorizontal, X } from 'lucide-vue-next';

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    },
    categories: {
        type: Array,
        default: () => [],
    },
    brands: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || '');
const selectedCategories = ref(props.filters.categories || []);
const selectedBrands = ref(props.filters.brands || []);
const selectedCondition = ref(props.filters.condition || '');
const priceMin = ref(props.filters.price_min || '');
const priceMax = ref(props.filters.price_max || '');
const showMobileFilters = ref(false);

const conditions = ['New', 'Ex-UK', 'Refurbished'];

const applyFilters = () => {
    const params = {};
    if (search.value) params.search = search.value;
    if (selectedCategories.value.length) params.categories = selectedCategories.value;
    if (selectedBrands.value.length) params.brands = selectedBrands.value;
    if (selectedCondition.value) params.condition = selectedCondition.value;
    if (priceMin.value) params.price_min = priceMin.value;
    if (priceMax.value) params.price_max = priceMax.value;

    router.get('/products', params, { preserveState: true, preserveScroll: true });
};

const clearFilters = () => {
    search.value = '';
    selectedCategories.value = [];
    selectedBrands.value = [];
    selectedCondition.value = '';
    priceMin.value = '';
    priceMax.value = '';
    router.get('/products');
};

const toggleCategory = (id) => {
    const idx = selectedCategories.value.indexOf(id);
    if (idx > -1) {
        selectedCategories.value.splice(idx, 1);
    } else {
        selectedCategories.value.push(id);
    }
    applyFilters();
};

const toggleBrand = (id) => {
    const idx = selectedBrands.value.indexOf(id);
    if (idx > -1) {
        selectedBrands.value.splice(idx, 1);
    } else {
        selectedBrands.value.push(id);
    }
    applyFilters();
};

const setCondition = (condition) => {
    selectedCondition.value = selectedCondition.value === condition ? '' : condition;
    applyFilters();
};

const searchProducts = () => {
    applyFilters();
};

const productList = ref(props.products?.data || props.products || []);
</script>

<template>
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <div class="flex gap-3 max-w-2xl mx-auto">
                    <div class="relative flex-1">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search phones, computers..."
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:border-black focus:outline-none"
                            @keyup.enter="searchProducts"
                        />
                    </div>
                    <button
                        @click="searchProducts"
                        class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium"
                    >
                        Search
                    </button>
                    <button
                        @click="showMobileFilters = !showMobileFilters"
                        class="md:hidden px-4 py-3 border-2 border-gray-300 rounded-xl"
                    >
                        <SlidersHorizontal class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <div class="flex gap-8">
                <!-- Sidebar Filters -->
                <aside
                    :class="[
                        'w-64 shrink-0',
                        showMobileFilters ? 'block fixed inset-0 z-40 bg-white p-6 overflow-auto md:static md:p-0 md:z-auto' : 'hidden md:block'
                    ]"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold">Filters</h3>
                        <button
                            v-if="showMobileFilters"
                            @click="showMobileFilters = false"
                            class="md:hidden"
                        >
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Categories -->
                    <div v-if="categories.length" class="mb-6">
                        <h4 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Categories</h4>
                        <div class="space-y-2">
                            <label
                                v-for="category in categories"
                                :key="category.id"
                                class="flex items-center gap-2 cursor-pointer text-sm"
                            >
                                <input
                                    type="checkbox"
                                    :checked="selectedCategories.includes(category.id)"
                                    @change="toggleCategory(category.id)"
                                    class="rounded border-gray-300 text-black focus:ring-black"
                                />
                                {{ category.name }}
                            </label>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div v-if="brands.length" class="mb-6">
                        <h4 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Brands</h4>
                        <div class="space-y-2">
                            <label
                                v-for="brand in brands"
                                :key="brand.id"
                                class="flex items-center gap-2 cursor-pointer text-sm"
                            >
                                <input
                                    type="checkbox"
                                    :checked="selectedBrands.includes(brand.id)"
                                    @change="toggleBrand(brand.id)"
                                    class="rounded border-gray-300 text-black focus:ring-black"
                                />
                                {{ brand.name }}
                            </label>
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Condition</h4>
                        <div class="space-y-2">
                            <button
                                v-for="condition in conditions"
                                :key="condition"
                                @click="setCondition(condition.toLowerCase())"
                                :class="[
                                    'block w-full text-left px-3 py-2 rounded-lg text-sm transition',
                                    selectedCondition === condition.toLowerCase()
                                        ? 'bg-black text-white'
                                        : 'hover:bg-gray-100'
                                ]"
                            >
                                {{ condition }}
                            </button>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Price Range (KSh)</h4>
                        <div class="flex gap-2">
                            <input
                                v-model="priceMin"
                                type="number"
                                placeholder="Min"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-black focus:outline-none"
                                @change="applyFilters"
                            />
                            <input
                                v-model="priceMax"
                                type="number"
                                placeholder="Max"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-black focus:outline-none"
                                @change="applyFilters"
                            />
                        </div>
                    </div>

                    <button
                        @click="clearFilters"
                        class="w-full py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition"
                    >
                        Clear All Filters
                    </button>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    <div
                        v-if="(products?.data || products || []).length"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <ProductCard
                            v-for="product in (products?.data || products)"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                    <div v-else class="text-center py-16">
                        <p class="text-gray-500 text-lg mb-4">No products found matching your criteria.</p>
                        <button
                            @click="clearFilters"
                            class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition"
                        >
                            Clear Filters
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products?.links" class="mt-10 flex justify-center gap-2">
                        <template v-for="(link, index) in products.links" :key="index">
                            <button
                                v-if="link.url"
                                @click="router.get(link.url)"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm transition',
                                    link.active
                                        ? 'bg-black text-white'
                                        : 'bg-white border border-gray-300 hover:bg-gray-50'
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-4 py-2 text-sm text-gray-400"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
