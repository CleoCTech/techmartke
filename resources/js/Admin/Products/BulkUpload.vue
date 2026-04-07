<script setup>
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Upload,
    CheckCircle,
    AlertCircle,
    Loader2,
    RefreshCw,
    ArrowUpDown,
    Plus,
    Pencil,
    Sparkles,
    X,
    Check,
} from 'lucide-vue-next';
import ProductMenuBar from '@/Components/Admin/ProductMenuBar.vue';

const page = usePage();
const priceText = ref('');
const items = ref([]);
const skipped = ref([]);
const summary = ref(null);
const error = ref('');
const isParsing = ref(false);
const isUploading = ref(false);
const showResults = ref(false);
const markOthersOutOfStock = ref(true); // Default: mark unlisted products as out of stock

const selectedCount = computed(() => items.value.filter(i => i.selected).length);
const updateCount = computed(() => items.value.filter(i => i.selected && i.action === 'update').length);
const newVariantCount = computed(() => items.value.filter(i => i.selected && i.action === 'new_variant').length);
const createCount = computed(() => items.value.filter(i => i.selected && i.action === 'create').length);

const parseText = async () => {
    if (!priceText.value.trim()) return;

    isParsing.value = true;
    showResults.value = false;
    items.value = [];
    skipped.value = [];
    summary.value = null;
    error.value = '';

    try {
        const response = await fetch('/admin/products/bulk-upload/parse', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ raw_text: priceText.value }),
        });

        const data = await response.json();

        if (data.error) {
            error.value = data.error;
            showResults.value = true;
            return;
        }

        items.value = (data.items || []).map(item => ({
            ...item,
            selected: true,
        }));
        skipped.value = data.skipped || [];
        summary.value = data.summary || null;
        showResults.value = true;
    } catch (e) {
        error.value = 'Failed to connect to AI service. Please check your API key and try again.';
        showResults.value = true;
    } finally {
        isParsing.value = false;
    }
};

const toggleAll = (checked) => {
    items.value.forEach(i => i.selected = checked);
};

const confirmUpload = () => {
    const selectedItems = items.value.filter(i => i.selected);
    if (selectedItems.length === 0) return;

    isUploading.value = true;
    router.post('/admin/products/bulk-upload/store', {
        raw_text: priceText.value,
        items: selectedItems,
        mark_others_out_of_stock: markOthersOutOfStock.value,
    }, {
        onFinish: () => {
            isUploading.value = false;
        },
        onSuccess: () => {
            items.value = [];
            skipped.value = [];
            summary.value = null;
            showResults.value = false;
            priceText.value = '';
        },
    });
};

const actionLabel = (action) => {
    return { update: 'Update Price', new_variant: 'New Variant', create: 'New Product' }[action] || action;
};

const actionClass = (action) => {
    return {
        update: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
        new_variant: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
        create: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    }[action] || 'bg-slate-100 text-slate-700 dark:bg-slate-600 dark:text-slate-300';
};

const confidenceClass = (confidence) => {
    return {
        high: 'text-green-600 dark:text-green-400',
        medium: 'text-amber-600 dark:text-amber-400',
        low: 'text-red-600 dark:text-red-400',
    }[confidence] || 'text-slate-500';
};

const formatPrice = (price) => {
    if (!price) return '-';
    return 'KSh ' + Number(price).toLocaleString('en-KE');
};

const reset = () => {
    items.value = [];
    skipped.value = [];
    summary.value = null;
    showResults.value = false;
    error.value = '';
};
</script>

<template>
    <Head title="AI Bulk Upload" />
    <ProductMenuBar active="bulk" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-6xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <Sparkles class="w-5 h-5 text-ablue" />
                    AI Bulk Price Upload
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Paste a price list and AI will match products, detect updates vs new items
                </p>
            </div>
        </div>

        <!-- Flash Messages -->
        <div v-if="page.props.flash?.success" class="flex items-center gap-2 p-4 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
            <CheckCircle class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" />
            <p class="text-sm text-green-700 dark:text-green-400">{{ page.props.flash.success }}</p>
        </div>
        <div v-if="page.props.flash?.error" class="flex items-center gap-2 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
            <AlertCircle class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" />
            <p class="text-sm text-red-700 dark:text-red-400">{{ page.props.flash.error }}</p>
        </div>

        <!-- Input Section -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Paste Price List</h3>
                <button v-if="showResults" @click="reset"
                    class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center gap-1">
                    <RefreshCw class="w-3.5 h-3.5" /> Start Over
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                Paste any format - price lists, WhatsApp messages, spreadsheet data. AI will parse it intelligently.
            </p>
            <textarea
                v-model="priceText"
                rows="10"
                :disabled="isParsing"
                placeholder="Paste your price list here...&#10;&#10;Examples of supported formats:&#10;Ex US SAMSUNG&#10;S10e 128GB - 15,000/-&#10;S10 128GB - 19,000/-&#10;&#10;IPHONES&#10;iPhone 15 128GB Single Sim 85,000&#10;iPhone 15 Pro 256GB eSIM 125,000"
                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue font-mono disabled:opacity-50"
            ></textarea>
            <div class="mt-4 flex justify-end">
                <button @click="parseText" :disabled="isParsing || !priceText.trim()"
                    class="inline-flex items-center px-5 py-2.5 bg-ablue rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:ring-2 focus:ring-ablue focus:ring-offset-2 dark:focus:ring-offset-slate-800 disabled:opacity-50 transition">
                    <Loader2 v-if="isParsing" class="h-4 w-4 mr-2 animate-spin" />
                    <Sparkles v-else class="h-4 w-4 mr-2" />
                    {{ isParsing ? 'AI is analyzing...' : 'Analyze with AI' }}
                </button>
            </div>
        </div>

        <!-- Error -->
        <div v-if="error" class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
            <AlertCircle class="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
            <div>
                <p class="text-sm font-medium text-red-700 dark:text-red-400">Analysis Failed</p>
                <p class="text-sm text-red-600 dark:text-red-400/80 mt-1">{{ error }}</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div v-if="showResults && items.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ items.length }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total Parsed</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-blue-200 dark:border-blue-500/30 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ summary?.updates || 0 }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Price Updates</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-amber-200 dark:border-amber-500/30 p-4 text-center">
                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ summary?.new_variants || 0 }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">New Variants</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-green-200 dark:border-green-500/30 p-4 text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ summary?.creates || 0 }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">New Products</div>
            </div>
        </div>

        <!-- Results Table -->
        <div v-if="showResults && items.length > 0"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">

            <!-- Table Header -->
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <CheckCircle class="h-5 w-5 text-green-500" />
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100">
                        AI Analysis Results
                    </h3>
                    <span class="text-sm text-slate-500 dark:text-slate-400">({{ selectedCount }} selected)</span>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" :checked="selectedCount === items.length"
                            @change="toggleAll($event.target.checked)"
                            class="rounded border-slate-300 dark:border-slate-600 text-ablue focus:ring-ablue" />
                        Select All
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" v-model="markOthersOutOfStock"
                            class="rounded border-slate-300 dark:border-slate-600 text-amber-500 focus:ring-amber-500" />
                        Mark unlisted as out of stock
                    </label>
                    <button @click="confirmUpload" :disabled="isUploading || selectedCount === 0"
                        class="inline-flex items-center px-4 py-2 bg-green-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 disabled:opacity-50 transition">
                        <Loader2 v-if="isUploading" class="h-4 w-4 mr-2 animate-spin" />
                        <Check v-else class="h-4 w-4 mr-2" />
                        {{ isUploading ? 'Processing...' : `Apply ${selectedCount} Changes` }}
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                            <th class="px-4 py-3 text-left w-10"></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Action</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden sm:table-cell">Storage</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden md:table-cell">Cost Price</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Sell Price</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden md:table-cell">Current</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden lg:table-cell">Confidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="index"
                            class="border-b border-slate-100 dark:border-slate-700/50 last:border-0 transition"
                            :class="item.selected ? 'hover:bg-slate-50 dark:hover:bg-slate-700/30' : 'opacity-40'">
                            <td class="px-4 py-3">
                                <input type="checkbox" v-model="item.selected"
                                    class="rounded border-slate-300 dark:border-slate-600 text-ablue focus:ring-ablue" />
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium" :class="actionClass(item.action)">
                                    <Pencil v-if="item.action === 'update'" class="w-3 h-3" />
                                    <ArrowUpDown v-else-if="item.action === 'new_variant'" class="w-3 h-3" />
                                    <Plus v-else class="w-3 h-3" />
                                    {{ actionLabel(item.action) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800 dark:text-slate-100">{{ item.product_name }}</div>
                                <div v-if="item.notes" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ item.notes }}</div>
                                <div v-if="item.condition && item.condition !== 'new'" class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">{{ item.condition }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300 hidden sm:table-cell">{{ item.storage || '-' }}</td>
                            <td class="px-4 py-3 text-right hidden md:table-cell">
                                <span class="text-slate-500 dark:text-slate-400">{{ formatPrice(item.raw_price) }}</span>
                                <span v-if="item.markup_applied" class="block text-[10px] text-green-600 dark:text-green-400">+{{ formatPrice(item.markup_applied) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800 dark:text-slate-100">{{ formatPrice(item.price) }}</td>
                            <td class="px-4 py-3 text-right hidden md:table-cell">
                                <template v-if="item.existing_price">
                                    <span :class="item.price < item.existing_price ? 'text-green-600 dark:text-green-400' : item.price > item.existing_price ? 'text-red-600 dark:text-red-400' : 'text-slate-500 dark:text-slate-400'">
                                        {{ formatPrice(item.existing_price) }}
                                    </span>
                                </template>
                                <span v-else class="text-slate-400 dark:text-slate-500">-</span>
                            </td>
                            <td class="px-4 py-3 text-center hidden lg:table-cell">
                                <span class="text-xs font-medium capitalize" :class="confidenceClass(item.confidence)">
                                    {{ item.confidence || '-' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Skipped Lines -->
        <div v-if="showResults && skipped.length > 0"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-3 flex items-center gap-2">
                <AlertCircle class="w-4 h-4 text-slate-400" />
                Skipped Lines ({{ skipped.length }})
            </h3>
            <div class="space-y-2">
                <div v-for="(s, i) in skipped" :key="i"
                    class="flex items-start gap-2 text-sm text-slate-500 dark:text-slate-400">
                    <X class="w-3.5 h-3.5 mt-0.5 shrink-0 text-slate-400" />
                    <div>
                        <span class="font-mono text-xs">{{ s.line }}</span>
                        <span v-if="s.reason" class="ml-2 text-slate-400 dark:text-slate-500">- {{ s.reason }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="showResults && items.length === 0 && !error"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center">
            <AlertCircle class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-3" />
            <p class="text-slate-500 dark:text-slate-400">No products could be parsed from the provided text.</p>
            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Try a different format or check your input.</p>
        </div>
    </div>
</template>
