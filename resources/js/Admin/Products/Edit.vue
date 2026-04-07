<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();
import {
    ArrowLeft,
    Plus,
    Trash2,
    Upload,
    Sparkles,
    Loader2,
} from 'lucide-vue-next';
import ProductMenuBar from '@/Components/Admin/ProductMenuBar.vue';

const props = defineProps({
    product: Object,
    brands: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.product.name || '',
    brand_id: props.product.brand_id || '',
    category_id: props.product.category_id || '',
    condition: props.product.condition || 'new',
    base_price: props.product.base_price || '',
    original_price: props.product.original_price || '',
    cost_price: props.product.cost_price || '',
    base_sku: props.product.base_sku || '',
    short_description: props.product.short_description || '',
    description: props.product.description || '',
    is_flash_sale: props.product.is_flash_sale || false,
    flash_sale_price: props.product.flash_sale_price || '',
    flash_sale_ends_at: props.product.flash_sale_ends_at ? props.product.flash_sale_ends_at.slice(0, 16) : '',
    meta_title: props.product.meta_title || '',
    meta_description: props.product.meta_description || '',
    variants: props.product.variants?.map(v => ({
        id: v.id,
        storage: v.storage || '',
        sim_type: v.sim_type || '',
        color: v.color || '',
        price: v.price || '',
        cost_price: v.cost_price || '',
        stock_quantity: v.stock_quantity || 0,
    })) || [],
    specifications: props.product.specifications?.map(s => ({
        id: s.id,
        spec_name: s.spec_name || '',
        spec_value: s.spec_value || '',
        spec_group: s.spec_group || '',
    })) || [],
    advantages: props.product.advantages?.map(a => a.advantage || a.text || a) || [],
    images: [],
    _method: 'PUT',
});

const addVariant = () => {
    form.variants.push({
        storage: '',
        sim_type: '',
        color: '',
        price: '',
        cost_price: '',
        stock_quantity: 0,
    });
};

const removeVariant = (index) => {
    form.variants.splice(index, 1);
};

const addSpecification = () => {
    form.specifications.push({
        spec_name: '',
        spec_value: '',
        spec_group: '',
    });
};

const removeSpecification = (index) => {
    form.specifications.splice(index, 1);
};

const addAdvantage = () => {
    form.advantages.push('');
};

const removeAdvantage = (index) => {
    form.advantages.splice(index, 1);
};

const newImageFiles = ref([]);

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);
    newImageFiles.value.push(...files);
    form.images = newImageFiles.value;
};

const createPreviewUrl = (file) => window.URL.createObjectURL(file);

const removeNewImage = (index) => {
    newImageFiles.value.splice(index, 1);
    form.images = newImageFiles.value;
};

const deletingImageId = ref(null);
const deleteExistingImage = (image) => {
    if (!confirm('Delete this image? This cannot be undone.')) return;
    deletingImageId.value = image.id;
    router.delete(`/admin/products/images/${image.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Remove from local list so the UI updates immediately
            if (props.product?.images) {
                const idx = props.product.images.findIndex(i => i.id === image.id);
                if (idx !== -1) props.product.images.splice(idx, 1);
            }
        },
        onFinish: () => {
            deletingImageId.value = null;
        },
    });
};

const isGenerating = ref(false);
const generateError = ref('');

const generateContent = async () => {
    if (!form.name) return;
    isGenerating.value = true;
    generateError.value = '';

    const brandName = props.brands.find(b => b.id === form.brand_id)?.name || '';
    const categoryName = props.categories.find(c => c.id === form.category_id)?.name || '';

    try {
        const res = await fetch('/admin/products/generate-content', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name: form.name,
                brand: brandName,
                category: categoryName,
                condition: form.condition,
                base_price: form.base_price,
                variants: form.variants,
            }),
        });
        const data = await res.json();
        if (data.error) {
            generateError.value = data.error;
        } else {
            if (data.short_description) form.short_description = data.short_description;
            if (data.description) form.description = data.description;
            if (data.meta_title) form.meta_title = data.meta_title;
            if (data.meta_description) form.meta_description = data.meta_description;
            if (data.specifications?.length) {
                form.specifications = data.specifications.map(s => ({
                    spec_group: s.spec_group || '',
                    spec_name: s.spec_name || '',
                    spec_value: s.spec_value || '',
                }));
            }
            if (data.advantages?.length) {
                form.advantages = data.advantages;
            }
        }
    } catch (e) {
        generateError.value = 'Failed to connect to AI service.';
    } finally {
        isGenerating.value = false;
    }
};

const submit = () => {
    form.post(`/admin/products/update/${props.product.uuid}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Product" />
    <ProductMenuBar active="edit" :productUuid="product.uuid" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">
                <!-- Flash Messages -->
                <div v-if="page.props.flash?.success" class="mb-4 flex items-center gap-2 p-4 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-green-700 dark:text-green-400">{{ page.props.flash.success }}</p>
                </div>
                <div v-if="page.props.flash?.error" class="mb-4 flex items-center gap-2 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ page.props.flash.error }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Product Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Brand</label>
                                <select
                                    v-model="form.brand_id"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                >
                                    <option value="">Select Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                                </select>
                                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-600">{{ form.errors.brand_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Category</label>
                                <select
                                    v-model="form.category_id"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                >
                                    <option value="">Select Category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Condition</label>
                                <select
                                    v-model="form.condition"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                >
                                    <option value="new">New</option>
                                    <option value="ex-uk">Ex-UK</option>
                                    <option value="refurbished">Refurbished</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Base SKU</label>
                                <input
                                    v-model="form.base_sku"
                                    type="text"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Base Price (KSh)</label>
                                <input
                                    v-model="form.base_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                    required
                                />
                                <p v-if="form.errors.base_price" class="mt-1 text-sm text-red-600">{{ form.errors.base_price }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Original Price (KSh)</label>
                                <input
                                    v-model="form.original_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cost Price (KSh)</label>
                                <input
                                    v-model="form.cost_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                />
                            </div>
                            <!-- AI Generate Button -->
                            <div class="md:col-span-2 pt-2">
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20">
                                    <button
                                        type="button"
                                        @click="generateContent"
                                        :disabled="isGenerating || !form.name"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <Loader2 v-if="isGenerating" class="w-4 h-4 animate-spin" />
                                        <Sparkles v-else class="w-4 h-4" />
                                        {{ isGenerating ? 'Generating...' : 'Generate with AI' }}
                                    </button>
                                    <span class="text-xs text-indigo-600 dark:text-indigo-400">
                                        Auto-fill descriptions, SEO content from product details above
                                    </span>
                                </div>
                                <p v-if="generateError" class="mt-2 text-sm text-red-500">{{ generateError }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Short Description</label>
                                <input
                                    v-model="form.short_description"
                                    type="text"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="4"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">SEO</h3>
                            <span v-if="isGenerating" class="text-xs text-indigo-500 flex items-center gap-1">
                                <Loader2 class="w-3 h-3 animate-spin" /> AI filling...
                            </span>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Meta Title</label>
                                <input
                                    v-model="form.meta_title"
                                    type="text"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Meta Description</label>
                                <textarea
                                    v-model="form.meta_description"
                                    rows="2"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Flash Sale -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-4">Flash Sale / Hot Deal</h3>
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input v-model="form.is_flash_sale" type="checkbox"
                                    class="rounded border-slate-300 text-red-500 focus:ring-red-500" />
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Enable Flash Sale</span>
                            </label>
                            <div v-if="form.is_flash_sale" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Flash Sale Price (KSh)</label>
                                    <input v-model="form.flash_sale_price" type="number" step="0.01" placeholder="Special sale price"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-red-500 focus:border-red-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sale Ends At</label>
                                    <input v-model="form.flash_sale_ends_at" type="datetime-local"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-red-500 focus:border-red-500" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Variants -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Variants</h3>
                            <button
                                type="button"
                                @click="addVariant"
                                class="inline-flex items-center px-3 py-1.5 bg-ablue text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Variant
                            </button>
                        </div>
                        <div v-if="form.variants.length === 0" class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">
                            No variants. Click "Add Variant" to add product variants.
                        </div>
                        <div v-for="(variant, index) in form.variants" :key="index" class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Variant {{ index + 1 }}</span>
                                <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-400">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Storage</label>
                                    <input v-model="variant.storage" type="text" placeholder="e.g. 128GB" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">SIM Type</label>
                                    <input v-model="variant.sim_type" type="text" placeholder="e.g. Single" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Color</label>
                                    <input v-model="variant.color" type="text" placeholder="e.g. Black" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Price (KSh)</label>
                                    <input v-model="variant.price" type="number" step="0.01" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Cost Price (KSh)</label>
                                    <input v-model="variant.cost_price" type="number" step="0.01" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Stock Qty</label>
                                    <input v-model="variant.stock_quantity" type="number" min="0" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Images -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Images</h3>
                        <div v-if="product.images && product.images.length > 0" class="mb-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Current Images</p>
                            <div class="flex flex-wrap gap-4">
                                <div
                                    v-for="image in product.images"
                                    :key="image.id"
                                    class="relative group w-24 h-24 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-slate-50 dark:bg-slate-900"
                                >
                                    <img
                                        :src="image.image_url || image.url || image.path"
                                        class="w-full h-full object-cover"
                                        :alt="product.name"
                                        @error="(e) => { e.target.style.display = 'none'; e.target.nextElementSibling?.classList.remove('hidden'); }"
                                    />
                                    <div class="hidden absolute inset-0 flex items-center justify-center text-[10px] text-slate-400 text-center px-1">
                                        Image failed to load
                                    </div>
                                    <button
                                        type="button"
                                        @click.stop.prevent="deleteExistingImage(image)"
                                        :disabled="deletingImageId === image.id"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg ring-2 ring-white dark:ring-slate-800 disabled:opacity-50 cursor-pointer z-10"
                                        title="Delete image"
                                    >
                                        <Trash2 class="h-3.5 w-3.5" />
                                    </button>
                                    <span
                                        v-if="image.is_primary"
                                        class="absolute bottom-1 left-1 bg-blue-500 text-white text-[9px] font-semibold px-1.5 py-0.5 rounded"
                                    >
                                        PRIMARY
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-if="newImageFiles.length > 0" class="mb-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">New Images</p>
                            <div class="flex flex-wrap gap-4">
                                <div
                                    v-for="(file, index) in newImageFiles"
                                    :key="index"
                                    class="relative w-24 h-24 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden"
                                >
                                    <img :src="createPreviewUrl(file)" class="w-full h-full object-cover" :alt="file.name" />
                                    <button
                                        type="button"
                                        @click="removeNewImage(index)"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600"
                                    >
                                        <Trash2 class="h-3 w-3" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <label class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-widest shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/30 cursor-pointer">
                            <Upload class="h-4 w-4 mr-2" />
                            Upload Images
                            <input type="file" multiple accept="image/*" @change="handleImageUpload" class="hidden" />
                        </label>
                    </div>

                    <!-- Specifications -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Specifications</h3>
                            <button
                                type="button"
                                @click="addSpecification"
                                class="inline-flex items-center px-3 py-1.5 bg-ablue text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Specification
                            </button>
                        </div>
                        <div v-if="form.specifications.length === 0" class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">
                            No specifications added.
                        </div>
                        <div v-for="(spec, index) in form.specifications" :key="index" class="flex items-end gap-4 mb-3">
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Group</label>
                                <input v-model="spec.spec_group" type="text" placeholder="e.g. Display" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                            </div>
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Name</label>
                                <input v-model="spec.spec_name" type="text" placeholder="e.g. Screen Size" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                            </div>
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Value</label>
                                <input v-model="spec.spec_value" type="text" placeholder="e.g. 6.1 inches" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm" />
                            </div>
                            <button type="button" @click="removeSpecification(index)" class="text-red-500 hover:text-red-400 pb-2">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Advantages -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Advantages</h3>
                            <button
                                type="button"
                                @click="addAdvantage"
                                class="inline-flex items-center px-3 py-1.5 bg-ablue text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Advantage
                            </button>
                        </div>
                        <div v-if="form.advantages.length === 0" class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">
                            No advantages added.
                        </div>
                        <div v-for="(_, index) in form.advantages" :key="index" class="flex items-center gap-4 mb-3">
                            <input
                                v-model="form.advantages[index]"
                                type="text"
                                placeholder="Enter an advantage..."
                                class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                            />
                            <button type="button" @click="removeAdvantage(index)" class="text-red-500 hover:text-red-400">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div>
                        <!-- Inline flash message near button -->
                        <div v-if="page.props.flash?.success" class="mb-3 flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-green-700 dark:text-green-400 font-medium">{{ page.props.flash.success }}</p>
                        </div>
                        <div v-if="page.props.flash?.error" class="mb-3 flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-red-700 dark:text-red-400 font-medium">{{ page.props.flash.error }}</p>
                        </div>
                        <div class="flex justify-end">
                            <Link href="/admin/products" class="mr-3 inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-widest shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-ablue border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ablue focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                            >
                                {{ form.processing ? 'Saving...' : 'Update Product' }}
                            </button>
                        </div>
                    </div>
                </form>
    </div>
</template>
