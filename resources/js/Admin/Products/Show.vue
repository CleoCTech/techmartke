<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Pencil,
    Package,
    CheckCircle,
} from 'lucide-vue-next';
import ProductMenuBar from '@/Components/Admin/ProductMenuBar.vue';

const props = defineProps({
    product: Object,
});

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const conditionBadge = (condition) => {
    const badges = {
        new: { label: 'New', class: 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-orange-100 text-orange-800 dark:bg-orange-500/20 dark:text-orange-400' },
        refurbished: { label: 'Refurbished', class: 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400' },
    };
    return badges[condition?.toLowerCase()] || { label: condition, class: 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400' };
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
    <Head title="Product Details" />
    <ProductMenuBar active="show" :productUuid="product.uuid" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto space-y-6">
                <!-- Product Info -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Product Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Brand</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.brand?.name || '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Category</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.category?.name || '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Condition</dt>
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
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">SKU</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.base_sku || '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Base Price</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100 font-semibold">{{ formatCurrency(product.base_price) }}</dd>
                                    </div>
                                    <div v-if="product.original_price">
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Original Price</dt>
                                        <dd class="text-sm text-slate-500 dark:text-slate-400 line-through">{{ formatCurrency(product.original_price) }}</dd>
                                    </div>
                                    <div v-if="product.cost_price">
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Cost Price</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ formatCurrency(product.cost_price) }}</dd>
                                    </div>
                                    <div v-if="product.short_description">
                                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Short Description</dt>
                                        <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.short_description }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div v-if="product.description" class="md:col-span-2">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Description</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100 whitespace-pre-wrap">{{ product.description }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Gallery -->
                <div v-if="product.images && product.images.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Images</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                            <div
                                v-for="image in product.images"
                                :key="image.id"
                                class="aspect-square border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden"
                            >
                                <img :src="image.image_url || image.url || image.path" class="w-full h-full object-cover bg-slate-100 dark:bg-slate-900" :alt="product.name" @error="(e) => { e.target.style.opacity = '0.2'; }" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants -->
                <div v-if="product.variants && product.variants.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Variants</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Storage</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SIM Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Color</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cost Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200 dark:divide-slate-700">
                                    <tr v-for="variant in product.variants" :key="variant.id" class="hover:bg-slate-50 dark:bg-slate-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">{{ variant.storage || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">{{ variant.sim_type || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">{{ variant.color || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">{{ formatCurrency(variant.price) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">{{ variant.cost_price ? formatCurrency(variant.cost_price) : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="variant.stock_quantity > 5 ? 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400' : variant.stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400'"
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
                <div v-if="product.specifications && product.specifications.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Specifications</h3>
                        <div v-for="(specs, group) in groupedSpecifications" :key="group" class="mb-6 last:mb-0">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 border-b border-slate-200 dark:border-slate-700 pb-2">{{ group }}</h4>
                            <dl class="divide-y divide-slate-100 dark:divide-slate-700">
                                <div v-for="spec in specs" :key="spec.id" class="flex py-2">
                                    <dt class="w-1/3 text-sm font-medium text-slate-500 dark:text-slate-400">{{ spec.spec_name }}</dt>
                                    <dd class="w-2/3 text-sm text-slate-800 dark:text-slate-100">{{ spec.spec_value }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Advantages -->
                <div v-if="product.advantages && product.advantages.length > 0" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Advantages</h3>
                        <ul class="space-y-2">
                            <li v-for="(advantage, index) in product.advantages" :key="index" class="flex items-start">
                                <CheckCircle class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" />
                                <span class="text-sm text-slate-800 dark:text-slate-100">{{ advantage.text || advantage }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- SEO -->
                <div v-if="product.meta_title || product.meta_description" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">SEO</h3>
                        <dl class="space-y-3">
                            <div v-if="product.meta_title">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Meta Title</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.meta_title }}</dd>
                            </div>
                            <div v-if="product.meta_description">
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Meta Description</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ product.meta_description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
    </div>
</template>
