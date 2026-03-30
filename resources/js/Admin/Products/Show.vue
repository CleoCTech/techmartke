<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Pencil,
    Package,
    CheckCircle,
} from 'lucide-vue-next';

const props = defineProps({
    product: Object,
});

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const conditionBadge = (condition) => {
    const badges = {
        new: { label: 'New', class: 'bg-blue-100 text-blue-800' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-orange-100 text-orange-800' },
        refurbished: { label: 'Refurbished', class: 'bg-gray-100 text-gray-800' },
    };
    return badges[condition?.toLowerCase()] || { label: condition, class: 'bg-gray-100 text-gray-800' };
};

const groupedSpecifications = computed(() => {
    if (!props.product.specifications) return {};
    return props.product.specifications.reduce((groups, spec) => {
        const group = spec.spec_group || 'General';
        if (!groups[group]) groups[group] = [];
        groups[group].push(spec);
        return groups;
    }, {});
});
</script>

<template>
    <AppLayout title="Product Details">
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <Link href="/admin/products" class="mr-4 text-gray-500 hover:text-gray-700">
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ product.name }}
                    </h2>
                </div>
                <Link
                    :href="`/admin/products/${product.id}/edit`"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
                >
                    <Pencil class="h-4 w-4 mr-1" />
                    Edit
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Product Info -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                                        <dd class="text-sm text-gray-900">{{ product.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                        <dd class="text-sm text-gray-900">{{ product.brand?.name || '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                                        <dd class="text-sm text-gray-900">{{ product.category?.name || '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Condition</dt>
                                        <dd>
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="conditionBadge(product.condition).class"
                                            >
                                                {{ conditionBadge(product.condition).label }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                        <dd class="text-sm text-gray-900">{{ product.base_sku || '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Base Price</dt>
                                        <dd class="text-sm text-gray-900 font-semibold">{{ formatCurrency(product.base_price) }}</dd>
                                    </div>
                                    <div v-if="product.original_price">
                                        <dt class="text-sm font-medium text-gray-500">Original Price</dt>
                                        <dd class="text-sm text-gray-500 line-through">{{ formatCurrency(product.original_price) }}</dd>
                                    </div>
                                    <div v-if="product.cost_price">
                                        <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                        <dd class="text-sm text-gray-900">{{ formatCurrency(product.cost_price) }}</dd>
                                    </div>
                                    <div v-if="product.short_description">
                                        <dt class="text-sm font-medium text-gray-500">Short Description</dt>
                                        <dd class="text-sm text-gray-900">{{ product.short_description }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div v-if="product.description" class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ product.description }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Gallery -->
                <div v-if="product.images && product.images.length > 0" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Images</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                            <div
                                v-for="image in product.images"
                                :key="image.id"
                                class="aspect-square border border-gray-200 rounded-lg overflow-hidden"
                            >
                                <img :src="image.url || image.path" class="w-full h-full object-cover" :alt="product.name" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants -->
                <div v-if="product.variants && product.variants.length > 0" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Variants</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Storage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SIM Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="variant in product.variants" :key="variant.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ variant.storage || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ variant.sim_type || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ variant.color || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(variant.price) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ variant.cost_price ? formatCurrency(variant.cost_price) : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="variant.stock_quantity > 5 ? 'bg-green-100 text-green-800' : variant.stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'"
                                            >
                                                {{ variant.stock_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                <div v-if="product.specifications && product.specifications.length > 0" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                        <div v-for="(specs, group) in groupedSpecifications" :key="group" class="mb-6 last:mb-0">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2 border-b border-gray-200 pb-2">{{ group }}</h4>
                            <dl class="divide-y divide-gray-100">
                                <div v-for="spec in specs" :key="spec.id" class="flex py-2">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">{{ spec.spec_name }}</dt>
                                    <dd class="w-2/3 text-sm text-gray-900">{{ spec.spec_value }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Advantages -->
                <div v-if="product.advantages && product.advantages.length > 0" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Advantages</h3>
                        <ul class="space-y-2">
                            <li v-for="(advantage, index) in product.advantages" :key="index" class="flex items-start">
                                <CheckCircle class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" />
                                <span class="text-sm text-gray-900">{{ advantage.text || advantage }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- SEO -->
                <div v-if="product.meta_title || product.meta_description" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO</h3>
                        <dl class="space-y-3">
                            <div v-if="product.meta_title">
                                <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                <dd class="text-sm text-gray-900">{{ product.meta_title }}</dd>
                            </div>
                            <div v-if="product.meta_description">
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd class="text-sm text-gray-900">{{ product.meta_description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
