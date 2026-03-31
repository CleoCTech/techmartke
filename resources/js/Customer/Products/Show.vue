<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { ChevronRight, Check, ShoppingCart, Minus, Plus } from 'lucide-vue-next';
import TrustActions from '@/Components/Customer/TrustActions.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    relatedProducts: {
        type: Array,
        default: () => [],
    },
});

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};

const selectedImage = ref(0);
const selectedVariant = ref(null);
const quantity = ref(1);

const images = computed(() => {
    return props.product.images?.length ? props.product.images : [{ image_url: props.product.image || '/assets/img/placeholder.jpg' }];
});

const variants = computed(() => {
    return props.product.variants || [];
});

const currentPrice = computed(() => {
    if (selectedVariant.value) {
        const variant = variants.value.find((v) => v.id === selectedVariant.value);
        return variant?.price || props.product.base_price || props.product.price;
    }
    return props.product.base_price || props.product.price;
});

const groupedSpecs = computed(() => {
    const specs = props.product.specifications || [];
    const groups = {};
    specs.forEach((spec) => {
        const group = spec.spec_group || spec.group || 'General';
        if (!groups[group]) groups[group] = [];
        groups[group].push(spec);
    });
    return groups;
});

const advantages = computed(() => {
    return props.product.advantages || [];
});

const conditionLabel = computed(() => {
    const map = { new: 'New', 'ex-uk': 'Ex-UK', ex_uk: 'Ex-UK', refurbished: 'Refurbished' };
    return map[props.product.condition?.toLowerCase()] || props.product.condition || '';
});

const conditionColor = computed(() => {
    const map = { new: 'bg-yellow-500', 'ex-uk': 'bg-blue-500', ex_uk: 'bg-blue-500', refurbished: 'bg-purple-500' };
    return map[props.product.condition?.toLowerCase()] || 'bg-green-500';
});

const addToCart = () => {
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        const variantInfo = selectedVariant.value
            ? variants.value.find((v) => v.id === selectedVariant.value)
            : null;

        const cartId = selectedVariant.value
            ? `${props.product.id}-${selectedVariant.value}`
            : String(props.product.id);

        const existing = cart.find((item) => item.id === cartId);
        if (existing) {
            existing.quantity += quantity.value;
        } else {
            cart.push({
                id: cartId,
                product_id: props.product.id,
                name: props.product.name,
                variant: variantInfo?.label || variantInfo?.storage || null,
                price: currentPrice.value,
                image: images.value[0]?.image_url || images.value[0]?.url,
                condition: props.product.condition,
                quantity: quantity.value,
            });
        }
        localStorage.setItem('techmart_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    } catch {
        // silently fail
    }
};
</script>

<template>
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
                <Link href="/" class="hover:text-black transition">Home</Link>
                <ChevronRight class="w-4 h-4" />
                <Link
                    v-if="product.category"
                    :href="`/products?category=${product.category.slug || product.category.id}`"
                    class="hover:text-black transition"
                >
                    {{ product.category.name }}
                </Link>
                <ChevronRight v-if="product.category" class="w-4 h-4" />
                <span class="text-black font-medium">{{ product.name }}</span>
            </nav>

            <div class="grid md:grid-cols-2 gap-12">
                <!-- Image Gallery -->
                <div>
                    <div class="bg-gray-100 rounded-2xl overflow-hidden mb-4">
                        <img
                            :src="images[selectedImage]?.image_url || images[selectedImage]?.url"
                            :alt="product.name"
                            class="w-full h-96 object-cover"
                        />
                    </div>
                    <div v-if="images.length > 1" class="flex gap-3">
                        <button
                            v-for="(img, idx) in images"
                            :key="idx"
                            @click="selectedImage = idx"
                            :class="[
                                'w-20 h-20 rounded-lg overflow-hidden border-2 transition',
                                selectedImage === idx ? 'border-black' : 'border-gray-200 hover:border-gray-400'
                            ]"
                        >
                            <img
                                :src="img.image_url || img.url"
                                :alt="`${product.name} thumbnail ${idx + 1}`"
                                class="w-full h-full object-cover"
                            />
                        </button>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                        <span
                            v-if="conditionLabel"
                            :class="conditionColor"
                            class="text-white px-3 py-1 rounded-full text-sm font-medium"
                        >
                            {{ conditionLabel }}
                        </span>
                    </div>

                    <p class="text-4xl font-bold mb-6">{{ formatPrice(currentPrice) }}</p>

                    <p v-if="product.short_description || product.description" class="text-gray-600 mb-6">
                        {{ product.short_description || product.description }}
                    </p>

                    <!-- Storage Variants -->
                    <div v-if="variants.length" class="mb-6">
                        <h3 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Storage</h3>
                        <div class="flex flex-wrap gap-3">
                            <button
                                v-for="variant in variants"
                                :key="variant.id"
                                @click="selectedVariant = variant.id"
                                :class="[
                                    'px-5 py-2 rounded-lg border-2 text-sm font-medium transition',
                                    selectedVariant === variant.id
                                        ? 'border-black bg-black text-white'
                                        : 'border-gray-300 hover:border-black'
                                ]"
                            >
                                {{ variant.label || variant.storage }}
                            </button>
                        </div>
                    </div>

                    <!-- SIM Type -->
                    <div v-if="product.sim_type" class="mb-6">
                        <h3 class="font-semibold mb-2 text-sm text-gray-700 uppercase tracking-wide">SIM Type</h3>
                        <p class="text-gray-800">{{ product.sim_type }}</p>
                    </div>

                    <!-- Advantages -->
                    <div v-if="advantages.length" class="mb-6">
                        <h3 class="font-semibold mb-3 text-sm text-gray-700 uppercase tracking-wide">Key Advantages</h3>
                        <div class="space-y-2">
                            <div
                                v-for="(advantage, idx) in advantages"
                                :key="idx"
                                class="flex items-center gap-2"
                            >
                                <Check class="w-5 h-5 text-green-500 shrink-0" />
                                <span class="text-sm text-gray-700">{{ advantage.advantage || advantage.text || advantage }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button
                                @click="quantity = Math.max(1, quantity - 1)"
                                class="px-3 py-2 hover:bg-gray-100 transition cursor-pointer"
                            >
                                <Minus class="w-4 h-4" />
                            </button>
                            <span class="px-4 py-2 font-medium">{{ quantity }}</span>
                            <button
                                @click="quantity++"
                                class="px-3 py-2 hover:bg-gray-100 transition cursor-pointer"
                            >
                                <Plus class="w-4 h-4" />
                            </button>
                        </div>
                        <button
                            @click="addToCart"
                            class="flex-1 px-8 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <ShoppingCart class="w-5 h-5" />
                            Add to Cart
                        </button>
                    </div>

                    <!-- Trust Actions: WhatsApp, Call, Visit -->
                    <div class="mb-8 pt-5 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Prefer to see it first?</p>
                        <TrustActions
                            :product="{
                                name: product.name,
                                base_price: currentPrice,
                                selectedVariant: selectedVariant ? variants.find(v => v.id === selectedVariant)?.storage : null
                            }"
                            layout="buttons"
                        />
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div v-if="Object.keys(groupedSpecs).length" class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Specifications</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div v-for="(specs, group) in groupedSpecs" :key="group">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700">{{ group }}</h3>
                        </div>
                        <table class="w-full">
                            <tbody>
                                <tr
                                    v-for="spec in specs"
                                    :key="spec.id || spec.name"
                                    class="border-b border-gray-100 last:border-0"
                                >
                                    <td class="px-6 py-3 text-sm text-gray-500 w-1/3">{{ spec.spec_name || spec.name || spec.key }}</td>
                                    <td class="px-6 py-3 text-sm font-medium">{{ spec.spec_value || spec.value }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div v-if="relatedProducts.length" class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <ProductCard
                        v-for="related in relatedProducts"
                        :key="related.id"
                        :product="related"
                    />
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
