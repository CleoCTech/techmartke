<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Plus,
    Trash2,
    Upload,
} from 'lucide-vue-next';

const props = defineProps({
    brands: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});

const form = useForm({
    name: '',
    brand_id: '',
    category_id: '',
    condition: 'new',
    base_price: '',
    original_price: '',
    cost_price: '',
    base_sku: '',
    short_description: '',
    description: '',
    meta_title: '',
    meta_description: '',
    variants: [],
    images: [],
    specifications: [],
    advantages: [],
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

const imageFiles = ref([]);

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);
    imageFiles.value.push(...files);
    form.images = imageFiles.value;
};

const removeImage = (index) => {
    imageFiles.value.splice(index, 1);
    form.images = imageFiles.value;
};

const submit = () => {
    form.post('/admin/products', {
        forceFormData: true,
    });
};
</script>

<template>
    <AppLayout title="Create Product">
        <template #header>
            <div class="flex items-center">
                <Link href="/admin/products" class="mr-4 text-gray-500 hover:text-gray-700">
                    <ArrowLeft class="h-5 w-5" />
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create Product
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                <select
                                    v-model="form.brand_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                >
                                    <option value="">Select Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                                </select>
                                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-600">{{ form.errors.brand_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select
                                    v-model="form.category_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                >
                                    <option value="">Select Category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                                <select
                                    v-model="form.condition"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                >
                                    <option value="new">New</option>
                                    <option value="ex-uk">Ex-UK</option>
                                    <option value="refurbished">Refurbished</option>
                                </select>
                                <p v-if="form.errors.condition" class="mt-1 text-sm text-red-600">{{ form.errors.condition }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Base SKU</label>
                                <input
                                    v-model="form.base_sku"
                                    type="text"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                                <p v-if="form.errors.base_sku" class="mt-1 text-sm text-red-600">{{ form.errors.base_sku }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (KSh)</label>
                                <input
                                    v-model="form.base_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                />
                                <p v-if="form.errors.base_price" class="mt-1 text-sm text-red-600">{{ form.errors.base_price }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Original Price (KSh)</label>
                                <input
                                    v-model="form.original_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                                <p v-if="form.errors.original_price" class="mt-1 text-sm text-red-600">{{ form.errors.original_price }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price (KSh)</label>
                                <input
                                    v-model="form.cost_price"
                                    type="number"
                                    step="0.01"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                                <p v-if="form.errors.cost_price" class="mt-1 text-sm text-red-600">{{ form.errors.cost_price }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                                <input
                                    v-model="form.short_description"
                                    type="text"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                                <p v-if="form.errors.short_description" class="mt-1 text-sm text-red-600">{{ form.errors.short_description }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="4"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input
                                    v-model="form.meta_title"
                                    type="text"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea
                                    v-model="form.meta_description"
                                    rows="2"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Variants -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Variants</h3>
                            <button
                                type="button"
                                @click="addVariant"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Variant
                            </button>
                        </div>
                        <div v-if="form.variants.length === 0" class="text-sm text-gray-500 text-center py-4">
                            No variants added. Click "Add Variant" to add product variants.
                        </div>
                        <div v-for="(variant, index) in form.variants" :key="index" class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-gray-700">Variant {{ index + 1 }}</span>
                                <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-700">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Storage</label>
                                    <input v-model="variant.storage" type="text" placeholder="e.g. 128GB" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">SIM Type</label>
                                    <input v-model="variant.sim_type" type="text" placeholder="e.g. Single" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Color</label>
                                    <input v-model="variant.color" type="text" placeholder="e.g. Black" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Price (KSh)</label>
                                    <input v-model="variant.price" type="number" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Cost Price (KSh)</label>
                                    <input v-model="variant.cost_price" type="number" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Stock Qty</label>
                                    <input v-model="variant.stock_quantity" type="number" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Images</h3>
                        <div class="flex flex-wrap gap-4 mb-4">
                            <div
                                v-for="(file, index) in imageFiles"
                                :key="index"
                                class="relative w-24 h-24 border border-gray-200 rounded-lg overflow-hidden"
                            >
                                <img :src="URL.createObjectURL(file)" class="w-full h-full object-cover" :alt="file.name" />
                                <button
                                    type="button"
                                    @click="removeImage(index)"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600"
                                >
                                    <Trash2 class="h-3 w-3" />
                                </button>
                            </div>
                        </div>
                        <label class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 cursor-pointer">
                            <Upload class="h-4 w-4 mr-2" />
                            Upload Images
                            <input type="file" multiple accept="image/*" @change="handleImageUpload" class="hidden" />
                        </label>
                    </div>

                    <!-- Specifications -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Specifications</h3>
                            <button
                                type="button"
                                @click="addSpecification"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Specification
                            </button>
                        </div>
                        <div v-if="form.specifications.length === 0" class="text-sm text-gray-500 text-center py-4">
                            No specifications added.
                        </div>
                        <div v-for="(spec, index) in form.specifications" :key="index" class="flex items-end gap-4 mb-3">
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-gray-500 mb-1">Group</label>
                                <input v-model="spec.spec_group" type="text" placeholder="e.g. Display" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                            </div>
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                                <input v-model="spec.spec_name" type="text" placeholder="e.g. Screen Size" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                            </div>
                            <div class="flex-1">
                                <label v-if="index === 0" class="block text-xs font-medium text-gray-500 mb-1">Value</label>
                                <input v-model="spec.spec_value" type="text" placeholder="e.g. 6.1 inches" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                            </div>
                            <button type="button" @click="removeSpecification(index)" class="text-red-500 hover:text-red-700 pb-2">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Advantages -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Advantages</h3>
                            <button
                                type="button"
                                @click="addAdvantage"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition"
                            >
                                <Plus class="h-3 w-3 mr-1" />
                                Add Advantage
                            </button>
                        </div>
                        <div v-if="form.advantages.length === 0" class="text-sm text-gray-500 text-center py-4">
                            No advantages added.
                        </div>
                        <div v-for="(_, index) in form.advantages" :key="index" class="flex items-center gap-4 mb-3">
                            <input
                                v-model="form.advantages[index]"
                                type="text"
                                placeholder="Enter an advantage..."
                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            />
                            <button type="button" @click="removeAdvantage(index)" class="text-red-500 hover:text-red-700">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <Link href="/admin/products" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                        >
                            {{ form.processing ? 'Creating...' : 'Create Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
