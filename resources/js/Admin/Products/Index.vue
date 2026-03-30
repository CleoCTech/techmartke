<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import {
    Search,
    Plus,
    Eye,
    Pencil,
    Trash2,
    ChevronLeft,
    ChevronRight,
} from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    categories: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters?.search || '');
const categoryFilter = ref(props.filters?.category || '');
const brandFilter = ref(props.filters?.brand || '');

const applyFilters = () => {
    router.get('/admin/products', {
        search: search.value || undefined,
        category: categoryFilter.value || undefined,
        brand: brandFilter.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});

watch([categoryFilter, brandFilter], applyFilters);

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const stockStatusBadge = (status) => {
    const badges = {
        in_stock: { label: 'In Stock', class: 'bg-green-100 text-green-800' },
        out_of_stock: { label: 'Out of Stock', class: 'bg-red-100 text-red-800' },
        pre_order: { label: 'Pre Order', class: 'bg-yellow-100 text-yellow-800' },
    };
    return badges[status] || { label: status, class: 'bg-gray-100 text-gray-800' };
};

const conditionBadge = (condition) => {
    const badges = {
        new: { label: 'New', class: 'bg-blue-100 text-blue-800' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-orange-100 text-orange-800' },
        refurbished: { label: 'Refurbished', class: 'bg-gray-100 text-gray-800' },
    };
    return badges[condition?.toLowerCase()] || { label: condition, class: 'bg-gray-100 text-gray-800' };
};

const deleteProduct = (product) => {
    if (confirm(`Are you sure you want to delete "${product.name}"?`)) {
        router.delete(`/admin/products/${product.id}`);
    }
};
</script>

<template>
    <AppLayout title="Products">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Products
                </h2>
                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <Plus class="h-4 w-4 mr-1" />
                    Create New Product
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <!-- Filters -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search products..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                            </div>
                            <select
                                v-model="categoryFilter"
                                class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            >
                                <option value="">All Categories</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                            <select
                                v-model="brandFilter"
                                class="w-full py-2 px-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            >
                                <option value="">All Brands</option>
                                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img
                                            v-if="product.primary_image"
                                            :src="product.primary_image"
                                            :alt="product.name"
                                            class="h-10 w-10 rounded-md object-cover"
                                        />
                                        <div v-else class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                            <Package class="h-5 w-5 text-gray-400" />
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ product.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ product.brand?.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ product.category?.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ formatCurrency(product.base_price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="stockStatusBadge(product.stock_status).class"
                                        >
                                            {{ stockStatusBadge(product.stock_status).label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="conditionBadge(product.condition).class"
                                        >
                                            {{ conditionBadge(product.condition).label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            <Link
                                                :href="`/admin/products/${product.id}`"
                                                class="text-gray-500 hover:text-indigo-600"
                                                title="View"
                                            >
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                            <Link
                                                :href="`/admin/products/${product.id}/edit`"
                                                class="text-gray-500 hover:text-blue-600"
                                                title="Edit"
                                            >
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                            <button
                                                @click="deleteProduct(product)"
                                                class="text-gray-500 hover:text-red-600"
                                                title="Delete"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">
                                        No products found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.last_page > 1" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ products.from }}</span> to
                            <span class="font-medium">{{ products.to }}</span> of
                            <span class="font-medium">{{ products.total }}</span> results
                        </p>
                        <div class="flex items-center space-x-1">
                            <template v-for="link in products.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-3 py-1 text-sm rounded-md"
                                    :class="link.active ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100'"
                                    v-html="link.label"
                                    preserve-state
                                />
                                <span
                                    v-else
                                    class="px-3 py-1 text-sm text-gray-400"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
