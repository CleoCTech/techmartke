<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { ChevronRight, ChevronLeft, Check, ShoppingCart, Minus, Plus, ZoomIn, X } from 'lucide-vue-next';
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
const zoomed = ref(false);
const galleryRef = ref(null);
let touchStartX = 0;
let touchEndX = 0;

// Filter out images with empty/blank URLs so the gallery never shows a broken slot
const images = computed(() => {
    const raw = Array.isArray(props.product?.images) ? props.product.images : [];
    const usable = raw.filter(img => {
        const url = img?.image_url || img?.url || img?.path;
        return url && String(url).trim() !== '';
    });
    if (usable.length) return usable;
    return [{ image_url: props.product?.image || '/assets/img/placeholder.jpg' }];
});

// Runtime fallback when an image fails to load
const handleImgError = (e) => {
    const tried = e.target.dataset.tried ? JSON.parse(e.target.dataset.tried) : [];
    tried.push(e.target.src);
    for (const img of images.value) {
        const url = img?.image_url || img?.url || img?.path;
        if (url && !tried.some(t => t.endsWith(url))) {
            e.target.dataset.tried = JSON.stringify(tried);
            e.target.src = url;
            return;
        }
    }
    e.target.src = '/assets/img/placeholder.jpg';
    e.target.onerror = null;
};

const nextImage = () => {
    selectedImage.value = (selectedImage.value + 1) % images.value.length;
};
const prevImage = () => {
    selectedImage.value = (selectedImage.value - 1 + images.value.length) % images.value.length;
};

const onTouchStart = (e) => { touchStartX = e.changedTouches[0].screenX; };
const onTouchEnd = (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const diff = touchStartX - touchEndX;
    if (Math.abs(diff) > 50) {
        diff > 0 ? nextImage() : prevImage();
    }
};

const openZoom = () => { zoomed.value = true; };
const closeZoom = () => { zoomed.value = false; };

const onKeydown = (e) => {
    if (zoomed.value) {
        if (e.key === 'Escape') closeZoom();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
    }
};

onMounted(() => window.addEventListener('keydown', onKeydown));
onUnmounted(() => window.removeEventListener('keydown', onKeydown));

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
    <Head :title="`${product.name} - ${formatPrice(product.base_price)} | Buy Now`" />
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

            <div class="grid md:grid-cols-2 gap-8 md:gap-12">
                <!-- Image Gallery -->
                <div>
                    <!-- Main Image -->
                    <div
                        ref="galleryRef"
                        class="relative bg-gray-50 rounded-2xl overflow-hidden mb-3 group cursor-zoom-in"
                        @click="openZoom"
                        @touchstart="onTouchStart"
                        @touchend="onTouchEnd"
                    >
                        <img
                            :src="images[selectedImage]?.image_url || images[selectedImage]?.url"
                            :alt="product.name"
                            class="w-full h-80 sm:h-96 md:h-[480px] object-contain p-4 transition-transform duration-300"
                            @error="handleImgError"
                        />

                        <!-- Zoom hint -->
                        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <ZoomIn class="w-3.5 h-3.5" /> Click to zoom
                        </div>

                        <!-- Arrow Navigation -->
                        <template v-if="images.length > 1">
                            <button
                                @click.stop="prevImage"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100"
                            >
                                <ChevronLeft class="w-5 h-5 text-gray-700" />
                            </button>
                            <button
                                @click.stop="nextImage"
                                class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100"
                            >
                                <ChevronRight class="w-5 h-5 text-gray-700" />
                            </button>
                        </template>

                        <!-- Image Counter -->
                        <div v-if="images.length > 1" class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-xs font-medium">
                            {{ selectedImage + 1 }} / {{ images.length }}
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div v-if="images.length > 1" class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                        <button
                            v-for="(img, idx) in images"
                            :key="idx"
                            @click="selectedImage = idx"
                            :class="[
                                'w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden border-2 transition flex-shrink-0',
                                selectedImage === idx ? 'border-black ring-1 ring-black' : 'border-gray-200 hover:border-gray-400'
                            ]"
                        >
                            <img
                                :src="img.image_url || img.url"
                                :alt="`${product.name} ${idx + 1}`"
                                class="w-full h-full object-cover"
                                @error="handleImgError"
                            />
                        </button>
                    </div>

                    <!-- Fullscreen Zoom Modal -->
                    <Teleport to="body">
                        <Transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <div
                                v-if="zoomed"
                                class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center"
                                @click.self="closeZoom"
                                @touchstart="onTouchStart"
                                @touchend="onTouchEnd"
                            >
                                <!-- Close -->
                                <button
                                    @click="closeZoom"
                                    class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10"
                                >
                                    <X class="w-5 h-5" />
                                </button>

                                <!-- Counter -->
                                <div v-if="images.length > 1" class="absolute top-4 left-4 text-white/70 text-sm font-medium z-10">
                                    {{ selectedImage + 1 }} / {{ images.length }}
                                </div>

                                <!-- Arrows -->
                                <template v-if="images.length > 1">
                                    <button
                                        @click="prevImage"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10"
                                    >
                                        <ChevronLeft class="w-6 h-6" />
                                    </button>
                                    <button
                                        @click="nextImage"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10"
                                    >
                                        <ChevronRight class="w-6 h-6" />
                                    </button>
                                </template>

                                <!-- Zoomed Image -->
                                <img
                                    :src="images[selectedImage]?.image_url || images[selectedImage]?.url"
                                    :alt="product.name"
                                    class="max-w-[90vw] max-h-[85vh] object-contain select-none"
                                    draggable="false"
                                    @error="handleImgError"
                                />
                            </div>
                        </Transition>
                    </Teleport>
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

            <!-- Full Description -->
            <div v-if="product.description" class="mt-12 md:mt-16">
                <h2 class="text-2xl font-bold mb-4">About This Product</h2>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ product.description }}
                </div>
            </div>

            <!-- Specifications -->
            <div v-if="Object.keys(groupedSpecs).length" class="mt-12 md:mt-16">
                <h2 class="text-2xl font-bold mb-6">Specifications</h2>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div v-for="(specs, group) in groupedSpecs" :key="group">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">{{ group }}</h3>
                        </div>
                        <table class="w-full">
                            <tbody>
                                <tr
                                    v-for="spec in specs"
                                    :key="spec.id || spec.name"
                                    class="border-b border-gray-100 last:border-0"
                                >
                                    <td class="px-6 py-3 text-sm text-gray-500 w-1/3">{{ spec.spec_name || spec.name || spec.key }}</td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ spec.spec_value || spec.value }}</td>
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

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
