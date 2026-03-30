<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Upload,
    CheckCircle,
    AlertCircle,
    Loader2,
} from 'lucide-vue-next';

const priceText = ref('');
const parsedProducts = ref([]);
const parseErrors = ref([]);
const isParsing = ref(false);
const showPreview = ref(false);
const isUploading = ref(false);

const parseText = async () => {
    if (!priceText.value.trim()) return;

    isParsing.value = true;
    showPreview.value = false;
    parsedProducts.value = [];
    parseErrors.value = [];

    try {
        const response = await fetch('/admin/products/bulk-upload/parse', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ text: priceText.value }),
        });

        const data = await response.json();
        parsedProducts.value = data.products || [];
        parseErrors.value = data.errors || [];
        showPreview.value = true;
    } catch (error) {
        parseErrors.value = [{ line: 0, message: 'Failed to parse text. Please try again.' }];
        showPreview.value = true;
    } finally {
        isParsing.value = false;
    }
};

const confirmUpload = () => {
    if (parsedProducts.value.length === 0) return;

    isUploading.value = true;
    router.post('/admin/products/bulk-upload', {
        text: priceText.value,
        products: parsedProducts.value,
    }, {
        onFinish: () => {
            isUploading.value = false;
        },
    });
};
</script>

<template>
    <AppLayout title="Bulk Price Upload">
        <template #header>
            <div class="flex items-center">
                <Link href="/admin/products" class="mr-4 text-gray-500 hover:text-gray-700">
                    <ArrowLeft class="h-5 w-5" />
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Bulk Price Upload
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Input Section -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Paste Price Text</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Paste the price list text below. Each line should contain a product name followed by its price information.
                    </p>
                    <textarea
                        v-model="priceText"
                        rows="12"
                        placeholder="Paste your price list here...&#10;&#10;Example:&#10;iPhone 15 128GB Single Sim 85,000&#10;iPhone 15 256GB Single Sim 95,000&#10;Samsung Galaxy S24 128GB Dual Sim 72,000"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono"
                    ></textarea>
                    <div class="mt-4 flex justify-end">
                        <button
                            @click="parseText"
                            :disabled="isParsing || !priceText.trim()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                        >
                            <Loader2 v-if="isParsing" class="h-4 w-4 mr-2 animate-spin" />
                            <Upload v-else class="h-4 w-4 mr-2" />
                            {{ isParsing ? 'Parsing...' : 'Parse & Preview' }}
                        </button>
                    </div>
                </div>

                <!-- Parse Errors -->
                <div v-if="showPreview && parseErrors.length > 0" class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center mb-3">
                        <AlertCircle class="h-5 w-5 text-red-500 mr-2" />
                        <h3 class="text-sm font-semibold text-red-800">Unparsed Lines ({{ parseErrors.length }})</h3>
                    </div>
                    <ul class="space-y-1">
                        <li v-for="(error, index) in parseErrors" :key="index" class="text-sm text-red-700">
                            <span v-if="error.line" class="font-medium">Line {{ error.line }}:</span>
                            {{ error.message || error.text || error }}
                        </li>
                    </ul>
                </div>

                <!-- Preview Table -->
                <div v-if="showPreview && parsedProducts.length > 0" class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <CheckCircle class="h-5 w-5 text-green-500 mr-2" />
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Parsed Products ({{ parsedProducts.length }})
                                </h3>
                            </div>
                            <button
                                @click="confirmUpload"
                                :disabled="isUploading"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                            >
                                <Loader2 v-if="isUploading" class="h-4 w-4 mr-2 animate-spin" />
                                {{ isUploading ? 'Uploading...' : 'Confirm Upload' }}
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Storage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SIM Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (KSh)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Valid</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(product, index) in parsedProducts" :key="index" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.storage || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.sim_type || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ product.price ? 'KSh ' + Number(product.price).toLocaleString('en-KE') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <CheckCircle class="h-5 w-5 text-green-500 inline-block" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- No Results -->
                <div v-if="showPreview && parsedProducts.length === 0 && parseErrors.length === 0" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 text-center">
                    <p class="text-sm text-gray-500">No products could be parsed from the provided text.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
