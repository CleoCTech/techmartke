<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { Search, SlidersHorizontal, X, Smartphone } from 'lucide-vue-next';

const props = defineProps({
    products: { type: Object, default: () => ({ data: [], links: [] }) },
    categories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    searchHints: { type: Array, default: () => [] },
});

const search = ref(props.filters.search || '');
const selectedCategories = ref(props.filters.categories || []);
const selectedBrands = ref(props.filters.brands || []);
const selectedCondition = ref(props.filters.condition || '');
const priceMin = ref(props.filters.price_min || '');
const priceMax = ref(props.filters.price_max || '');
const showMobileFilters = ref(false);
const showSuggestions = ref(false);
const selectedSuggIdx = ref(-1);

const conditions = [
    { value: 'new', label: 'New' },
    { value: 'ex-uk', label: 'Ex-UK' },
    { value: 'ex-us', label: 'Ex-US' },
    { value: 'refurbished', label: 'Refurbished' },
];

// Instant search suggestions — matches product names, numbers → iPhone hints
const suggestions = computed(() => {
    const q = search.value.trim().toLowerCase();
    if (q.length < 1) return [];

    const results = [];
    const seen = new Set();

    // If user typed a number (like "13", "14", "15"), suggest iPhones + Samsung
    const isNumeric = /^\d+$/.test(q);

    for (const p of props.searchHints) {
        const name = p.name.toLowerCase();
        const brand = (p.brand || '').toLowerCase();
        const matches = name.includes(q) || brand.includes(q) ||
            (isNumeric && (name.includes('iphone ' + q) || name.includes(' ' + q + ' ') || name.includes(' s' + q)));

        if (matches && !seen.has(p.name)) {
            seen.add(p.name);
            results.push(p);
            if (results.length >= 8) break;
        }
    }

    return results;
});

const onSearchInput = () => {
    showSuggestions.value = search.value.trim().length >= 1;
    selectedSuggIdx.value = -1;
};

const pickSuggestion = (hint) => {
    search.value = hint.name;
    showSuggestions.value = false;
    applyFilters();
};

const onSearchKeydown = (e) => {
    if (!showSuggestions.value || !suggestions.value.length) {
        if (e.key === 'Enter') { showSuggestions.value = false; applyFilters(); }
        return;
    }
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedSuggIdx.value = Math.min(selectedSuggIdx.value + 1, suggestions.value.length - 1);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedSuggIdx.value = Math.max(selectedSuggIdx.value - 1, -1);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedSuggIdx.value >= 0) {
            pickSuggestion(suggestions.value[selectedSuggIdx.value]);
        } else {
            showSuggestions.value = false;
            applyFilters();
        }
    } else if (e.key === 'Escape') {
        showSuggestions.value = false;
    }
};

const closeSuggestions = () => {
    setTimeout(() => { showSuggestions.value = false; }, 150);
};

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
    idx > -1 ? selectedCategories.value.splice(idx, 1) : selectedCategories.value.push(id);
    applyFilters();
};

const toggleBrand = (id) => {
    const idx = selectedBrands.value.indexOf(id);
    idx > -1 ? selectedBrands.value.splice(idx, 1) : selectedBrands.value.push(id);
    applyFilters();
};

const setCondition = (condition) => {
    selectedCondition.value = selectedCondition.value === condition ? '' : condition;
    applyFilters();
};

const formatPrice = (p) => 'KSh ' + Number(p).toLocaleString();
const productList = computed(() => props.products?.data || []);
const totalProducts = computed(() => props.products?.total || productList.value.length);
</script>

<template>
    <Head title="Browse Phones, Laptops & Accessories" />
    <CustomerLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            <!-- Sticky Search Bar -->
            <div class="sticky top-[57px] md:top-[65px] z-20 bg-white py-3 -mx-4 px-4 sm:-mx-6 sm:px-6 border-b border-[#E5E5EA]">
                <div class="flex gap-2 items-center">
                    <div class="relative flex-1">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868B]" />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search phones, laptops, accessories..."
                            class="w-full pl-9 pr-4 py-2.5 text-sm bg-[#F5F5F7] border border-[#E5E5EA] rounded-full focus:bg-white focus:border-black focus:outline-none focus:ring-1 focus:ring-black/10 transition-all text-[#1D1D1F] placeholder-[#86868B]"
                            @input="onSearchInput"
                            @keydown="onSearchKeydown"
                            @blur="closeSuggestions"
                            @focus="onSearchInput"
                            autocomplete="off"
                        />

                        <!-- Clear -->
                        <button
                            v-if="search"
                            @click="search = ''; showSuggestions = false; applyFilters()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#86868B] hover:text-black cursor-pointer"
                        >
                            <X class="w-4 h-4" />
                        </button>

                        <!-- Instant Suggestions Dropdown -->
                        <div
                            v-if="showSuggestions && suggestions.length"
                            class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-2xl border border-[#E5E5EA] z-50 max-h-[320px] overflow-y-auto"
                        >
                            <button
                                v-for="(hint, i) in suggestions"
                                :key="hint.slug"
                                @mousedown.prevent="pickSuggestion(hint)"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-left transition-colors hover:bg-[#F5F5F7] border-b border-[#F5F5F7] last:border-0 cursor-pointer"
                                :class="selectedSuggIdx === i ? 'bg-[#F5F5F7]' : ''"
                            >
                                <Smartphone class="w-4 h-4 text-[#86868B] flex-shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] truncate">{{ hint.name }}</p>
                                    <p class="text-[11px] text-[#86868B]">{{ hint.brand }} · {{ formatPrice(hint.price) }}</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile filter toggle -->
                    <button
                        @click="showMobileFilters = !showMobileFilters"
                        class="lg:hidden w-10 h-10 flex items-center justify-center border border-[#E5E5EA] rounded-full hover:bg-[#F5F5F7] transition cursor-pointer"
                    >
                        <SlidersHorizontal class="w-4 h-4 text-[#1D1D1F]" />
                    </button>
                </div>

                <!-- Results count -->
                <p class="text-[11px] text-[#86868B] mt-1.5">{{ totalProducts }} product{{ totalProducts !== 1 ? 's' : '' }}</p>
            </div>

            <div class="flex gap-6 mt-4">
                <!-- Sidebar Filters -->
                <aside
                    :class="[
                        'w-56 shrink-0',
                        showMobileFilters ? 'block fixed inset-0 z-40 bg-white p-5 overflow-auto lg:static lg:p-0 lg:z-auto' : 'hidden lg:block'
                    ]"
                >
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-bold text-black">Filters</h3>
                        <button v-if="showMobileFilters" @click="showMobileFilters = false" class="lg:hidden cursor-pointer">
                            <X class="w-5 h-5 text-[#1D1D1F]" />
                        </button>
                    </div>

                    <!-- Categories -->
                    <div v-if="categories.length" class="mb-5">
                        <h4 class="text-[11px] font-bold text-[#86868B] uppercase tracking-wider mb-2">Categories</h4>
                        <div class="space-y-1.5">
                            <label
                                v-for="cat in categories"
                                :key="cat.id"
                                class="flex items-center gap-2 cursor-pointer text-sm text-[#1D1D1F] hover:text-black"
                            >
                                <input
                                    type="checkbox"
                                    :checked="selectedCategories.includes(cat.id)"
                                    @change="toggleCategory(cat.id)"
                                    class="rounded border-[#E5E5EA] text-black focus:ring-black w-3.5 h-3.5"
                                />
                                {{ cat.name }}
                            </label>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div v-if="brands.length" class="mb-5">
                        <h4 class="text-[11px] font-bold text-[#86868B] uppercase tracking-wider mb-2">Brands</h4>
                        <div class="space-y-1.5">
                            <label
                                v-for="brand in brands"
                                :key="brand.id"
                                class="flex items-center gap-2 cursor-pointer text-sm text-[#1D1D1F] hover:text-black"
                            >
                                <input
                                    type="checkbox"
                                    :checked="selectedBrands.includes(brand.id)"
                                    @change="toggleBrand(brand.id)"
                                    class="rounded border-[#E5E5EA] text-black focus:ring-black w-3.5 h-3.5"
                                />
                                {{ brand.name }}
                            </label>
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="mb-5">
                        <h4 class="text-[11px] font-bold text-[#86868B] uppercase tracking-wider mb-2">Condition</h4>
                        <div class="flex flex-wrap gap-1.5">
                            <button
                                v-for="cond in conditions"
                                :key="cond.value"
                                @click="setCondition(cond.value)"
                                class="px-3 py-1 rounded-full text-xs font-medium transition cursor-pointer border"
                                :class="selectedCondition === cond.value
                                    ? 'bg-black text-white border-black'
                                    : 'bg-white text-[#1D1D1F] border-[#E5E5EA] hover:border-black'"
                            >
                                {{ cond.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-5">
                        <h4 class="text-[11px] font-bold text-[#86868B] uppercase tracking-wider mb-2">Price (KSh)</h4>
                        <div class="flex gap-2">
                            <input
                                v-model="priceMin"
                                type="number"
                                placeholder="Min"
                                class="w-full px-2.5 py-1.5 border border-[#E5E5EA] rounded-lg text-xs focus:border-black focus:outline-none text-[#1D1D1F]"
                                @change="applyFilters"
                            />
                            <input
                                v-model="priceMax"
                                type="number"
                                placeholder="Max"
                                class="w-full px-2.5 py-1.5 border border-[#E5E5EA] rounded-lg text-xs focus:border-black focus:outline-none text-[#1D1D1F]"
                                @change="applyFilters"
                            />
                        </div>
                    </div>

                    <button
                        @click="clearFilters"
                        class="w-full py-2 border border-[#E5E5EA] rounded-full text-xs font-medium hover:bg-[#F5F5F7] transition cursor-pointer text-[#1D1D1F]"
                    >
                        Clear All
                    </button>
                </aside>

                <!-- Product Grid — 2 cols mobile, 3 desktop -->
                <div class="flex-1 min-w-0">
                    <div
                        v-if="productList.length"
                        class="grid grid-cols-2 lg:grid-cols-3 gap-px bg-[#E5E5EA]"
                    >
                        <div v-for="product in productList" :key="product.id" class="bg-white">
                            <ProductCard :product="product" />
                        </div>
                    </div>

                    <div v-else class="text-center py-16">
                        <Smartphone class="w-12 h-12 text-[#E5E5EA] mx-auto mb-3" />
                        <p class="text-sm font-medium text-[#86868B] mb-4">No products found</p>
                        <button
                            @click="clearFilters"
                            class="px-5 py-2 bg-black text-white rounded-full text-sm font-semibold hover:bg-[#1D1D1F] transition cursor-pointer"
                        >
                            Clear Filters
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products?.links?.length > 3" class="mt-8 flex justify-center gap-1">
                        <template v-for="(link, index) in products.links" :key="index">
                            <button
                                v-if="link.url"
                                @click="router.get(link.url)"
                                class="px-3 py-1.5 rounded-full text-xs transition cursor-pointer"
                                :class="link.active
                                    ? 'bg-black text-white'
                                    : 'text-[#86868B] hover:text-black hover:bg-[#F5F5F7]'"
                                v-html="link.label"
                            />
                            <span v-else class="px-2 py-1.5 text-xs text-[#E5E5EA]" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
