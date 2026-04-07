<script setup>
import { ref, watch, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Search,
    Plus,
    Eye,
    Pencil,
    Trash2,
    Upload,
    Package,
    Filter,
    Clock,
} from 'lucide-vue-next';
import ProductMenuBar from '@/Components/Admin/ProductMenuBar.vue';

const props = defineProps({
    products: Object,
    categories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters?.search || '');
const categoryFilter = ref(props.filters?.category || '');
const brandFilter = ref(props.filters?.brand || '');
const viewMode = ref('cards'); // 'cards' or 'table'

const applyFilters = () => {
    router.get('/admin/products', {
        search: search.value || undefined,
        category: categoryFilter.value || undefined,
        brand: brandFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});
watch([categoryFilter, brandFilter], applyFilters);

const formatCurrency = (amount) => 'KSh ' + Number(amount).toLocaleString('en-KE');

// Track broken image URLs across all product cards (reactive)
const brokenImageUrls = ref(new Set());

// Get all usable (non-empty, non-broken) image URLs for a product
const usableImages = (product) => {
    const images = Array.isArray(product?.images) ? product.images : [];
    const out = [];
    for (const img of images) {
        const url = img?.image_url || img?.url || img?.path;
        if (url && String(url).trim() !== '' && !brokenImageUrls.value.has(url)) {
            out.push(url);
        }
    }
    return out;
};

// First valid image URL for a product (or null if none)
const productImageUrl = (product) => usableImages(product)[0] || null;

const onProductImageError = (product, e) => {
    const failedSrc = e.target?.currentSrc || e.target?.src || '';
    const images = Array.isArray(product?.images) ? product.images : [];
    for (const img of images) {
        const url = img?.image_url || img?.url || img?.path;
        if (url && (failedSrc === url || failedSrc.endsWith(url))) {
            brokenImageUrls.value.add(url);
            // Trigger reactivity by reassigning the Set
            brokenImageUrls.value = new Set(brokenImageUrls.value);
            break;
        }
    }
};

const totalStock = (product) => {
    if (!product.variants) return 0;
    return product.variants.reduce((sum, v) => sum + (v.stock_quantity || 0), 0);
};

const isInStock = (product) => product.stock_status === 'in_stock' && totalStock(product) > 0;

const stockLabel = (product) => {
    if (product.stock_status === 'in_stock' && totalStock(product) > 0) return 'In Stock';
    if (product.stock_status === 'pre_order') return 'Pre Order';
    return 'Out of Stock';
};

const stockClass = (product) => {
    if (isInStock(product)) return 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400';
    if (product.stock_status === 'pre_order') return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400';
    return 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400';
};

const conditionBadge = (condition) => {
    const badges = {
        new: { label: 'New', class: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400' },
        refurbished: { label: 'Refurb', class: 'bg-slate-100 text-slate-700 dark:bg-slate-600 dark:text-slate-300' },
    };
    return badges[condition?.toLowerCase()] || { label: condition, class: 'bg-slate-100 text-slate-700' };
};

const deleteProduct = (product) => {
    if (confirm(`Delete "${product.name}"?`)) {
        router.delete(`/admin/products/delete/${product.uuid}`);
    }
};

const stats = computed(() => {
    const data = props.products?.data || [];
    return {
        total: props.products?.total || data.length,
        inStock: data.filter(p => p.stock_status === 'in_stock').length,
        outOfStock: data.filter(p => p.stock_status !== 'in_stock').length,
    };
});
</script>

<template>
    <Head title="Products" />
    <ProductMenuBar active="list" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Products</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ stats.total }} total &middot;
                    <span class="text-green-600 dark:text-green-400">{{ stats.inStock }} in stock</span> &middot;
                    <span class="text-red-500 dark:text-red-400">{{ stats.outOfStock }} out of stock</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link :href="route('admin.bulk-upload')"
                    class="inline-flex items-center px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                    <Upload class="h-4 w-4 mr-1.5" /> Bulk Upload
                </Link>
                <Link :href="route('admin.products.create')"
                    class="inline-flex items-center px-3 py-2 bg-ablue rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
                    <Plus class="h-4 w-4 mr-1.5" /> Add Product
                </Link>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-400" />
                    <input v-model="search" type="text" placeholder="Search products..."
                        class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm" />
                </div>
                <select v-model="brandFilter"
                    class="w-full py-2 px-3 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm">
                    <option value="">All Brands</option>
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                </select>
                <select v-model="categoryFilter"
                    class="w-full py-2 px-3 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm">
                    <option value="">All Categories</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
            <div v-for="product in products.data" :key="product.id"
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-200"
                :class="isInStock(product) ? 'hover:shadow-md' : 'opacity-50 grayscale-[30%]'">

                <!-- Image -->
                <div class="relative aspect-square bg-slate-100 dark:bg-slate-700">
                    <img v-if="productImageUrl(product)" :src="productImageUrl(product)" :alt="product.name"
                        class="w-full h-full object-cover"
                        @error="(e) => onProductImageError(product, e)" />
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <Package class="w-12 h-12 text-slate-300 dark:text-slate-500" />
                    </div>

                    <!-- Stock Badge -->
                    <div class="absolute top-2 left-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold" :class="stockClass(product)">
                            <span class="w-1.5 h-1.5 rounded-full" :class="isInStock(product) ? 'bg-green-500' : 'bg-red-500'"></span>
                            {{ stockLabel(product) }}
                        </span>
                    </div>

                    <!-- Condition Badge -->
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold" :class="conditionBadge(product.condition).class">
                            {{ conditionBadge(product.condition).label }}
                        </span>
                    </div>

                    <!-- Out of stock overlay -->
                    <div v-if="!isInStock(product)"
                        class="absolute inset-0 bg-slate-900/10 dark:bg-slate-900/30 flex items-center justify-center">
                        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-lg px-3 py-1.5 text-center">
                            <Clock class="w-4 h-4 text-slate-500 dark:text-slate-400 mx-auto mb-0.5" />
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-300">Available Soon</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ product.name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ product.brand?.name }} &middot; {{ product.category?.name }}
                            </p>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <span class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ formatCurrency(product.base_price) }}</span>
                        <span v-if="product.original_price && product.original_price > product.base_price"
                            class="ml-2 text-sm text-slate-400 line-through">{{ formatCurrency(product.original_price) }}</span>
                    </div>

                    <!-- Variants Summary -->
                    <div v-if="product.variants && product.variants.length > 0" class="flex flex-wrap gap-1 mb-3">
                        <span v-for="v in product.variants" :key="v.id"
                            class="px-1.5 py-0.5 rounded text-xs font-medium"
                            :class="v.stock_quantity > 0
                                ? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'
                                : 'bg-slate-50 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 line-through'">
                            {{ v.storage }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1 pt-3 border-t border-slate-100 dark:border-slate-700">
                        <Link :href="`/admin/products/show/${product.uuid}`"
                            class="flex-1 flex items-center justify-center gap-1 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <Eye class="w-3.5 h-3.5" /> View
                        </Link>
                        <Link :href="`/admin/products/edit/${product.uuid}`"
                            class="flex-1 flex items-center justify-center gap-1 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <Pencil class="w-3.5 h-3.5" /> Edit
                        </Link>
                        <button @click="deleteProduct(product)"
                            class="flex-1 flex items-center justify-center gap-1 py-1.5 rounded-lg text-xs font-medium text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                            <Trash2 class="w-3.5 h-3.5" /> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="products.data.length === 0"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center">
            <Package class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" />
            <p class="text-slate-500 dark:text-slate-400">No products found</p>
            <Link :href="route('admin.products.create')"
                class="inline-flex items-center mt-4 px-4 py-2 bg-ablue rounded-lg text-sm text-white hover:bg-blue-700 transition">
                <Plus class="h-4 w-4 mr-1.5" /> Add Product
            </Link>
        </div>

        <!-- Pagination -->
        <div v-if="products.last_page > 1"
            class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Showing {{ products.from }} to {{ products.to }} of {{ products.total }}
            </p>
            <div class="flex items-center gap-1">
                <template v-for="link in products.links" :key="link.label">
                    <Link v-if="link.url" :href="link.url"
                        class="px-3 py-1.5 text-sm rounded-lg transition"
                        :class="link.active ? 'bg-ablue text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700'"
                        v-html="link.label" preserve-state />
                    <span v-else class="px-3 py-1.5 text-sm text-slate-400 dark:text-slate-500" v-html="link.label" />
                </template>
            </div>
        </div>
    </div>
</template>
