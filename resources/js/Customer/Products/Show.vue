<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { ChevronRight, ChevronLeft, Check, ShoppingCart, Minus, Plus, ZoomIn, X, Shield, Truck, Star, Zap } from 'lucide-vue-next';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { whatsappUrl: companyWhatsappUrl } = useCompanyInfo();

const props = defineProps({
    product: { type: Object, required: true },
    relatedProducts: { type: Array, default: () => [] },
});

const formatPrice = (price) => 'KSh ' + Number(price).toLocaleString();

const selectedImage = ref(0);
const selectedVariant = ref(null);
const quantity = ref(1);
const zoomed = ref(false);
let touchStartX = 0;

// Image handling with broken URL tracking
const brokenUrls = ref(new Set());
const imgUrl = (img) => img?.image_url || img?.url || img?.path || '';
const images = computed(() => {
    const raw = Array.isArray(props.product?.images) ? props.product.images : [];
    const usable = raw.filter(img => {
        const url = imgUrl(img);
        return url && String(url).trim() !== '' && !brokenUrls.value.has(url);
    });
    if (usable.length) return usable;
    return [{ image_url: props.product?.image || '/assets/img/placeholder.jpg' }];
});
const handleImgError = (e) => {
    const failedSrc = e.target?.currentSrc || e.target?.src || '';
    const raw = Array.isArray(props.product?.images) ? props.product.images : [];
    for (const img of raw) {
        const url = imgUrl(img);
        if (url && (failedSrc === url || failedSrc.endsWith(url))) {
            brokenUrls.value.add(url);
            break;
        }
    }
    if (selectedImage.value >= images.value.length) selectedImage.value = 0;
};

const nextImage = () => { selectedImage.value = (selectedImage.value + 1) % images.value.length; };
const prevImage = () => { selectedImage.value = (selectedImage.value - 1 + images.value.length) % images.value.length; };
const onTouchStart = (e) => { touchStartX = e.changedTouches[0].screenX; };
const onTouchEnd = (e) => {
    const diff = touchStartX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 50) { diff > 0 ? nextImage() : prevImage(); }
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

const variants = computed(() => props.product.variants || []);
const currentPrice = computed(() => {
    if (selectedVariant.value) {
        const v = variants.value.find((v) => v.id === selectedVariant.value);
        return v?.price || props.product.base_price;
    }
    return props.product.base_price || props.product.price;
});

const groupedSpecs = computed(() => {
    const specs = props.product.specifications || [];
    const groups = {};
    specs.forEach((spec) => {
        const group = spec.spec_group || 'General';
        if (!groups[group]) groups[group] = [];
        groups[group].push(spec);
    });
    return groups;
});

const specsList = computed(() => {
    const specs = props.product.specifications || [];
    return specs.slice(0, 6);
});

const advantages = computed(() => props.product.advantages || []);

const conditionLabel = computed(() => {
    const map = { new: 'New', 'ex-uk': 'Ex-UK', 'ex-us': 'Ex-US', ex_uk: 'Ex-UK', refurbished: 'Refurbished' };
    return map[props.product.condition?.toLowerCase()] || props.product.condition || '';
});

// WhatsApp with product context
const whatsappOrderUrl = computed(() => {
    const variant = selectedVariant.value ? variants.value.find(v => v.id === selectedVariant.value)?.storage : '';
    const msg = `Hi TechMart KE! I want to order the *${props.product.name}*${variant ? ' (' + variant + ')' : ''} at KSh ${Number(currentPrice.value).toLocaleString()}. Please confirm availability.`;
    return companyWhatsappUrl(msg);
});

const addToCart = () => {
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        const variantInfo = selectedVariant.value ? variants.value.find(v => v.id === selectedVariant.value) : null;
        const cartId = selectedVariant.value ? `${props.product.id}-${selectedVariant.value}` : String(props.product.id);
        const existing = cart.find((item) => item.id === cartId);
        if (existing) {
            existing.quantity += quantity.value;
        } else {
            cart.push({
                id: cartId,
                product_id: props.product.id,
                name: props.product.name,
                variant: variantInfo?.storage || null,
                price: currentPrice.value,
                image: images.value[0]?.image_url || images.value[0]?.url,
                condition: props.product.condition,
                quantity: quantity.value,
            });
        }
        localStorage.setItem('techmart_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    } catch { /* silently fail */ }
};
</script>

<template>
    <Head :title="`${product.name} - ${formatPrice(product.base_price)} | TechMart KE`" />
    <CustomerLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb — minimal -->
            <nav class="flex items-center gap-1.5 text-xs text-[#86868B] px-4 sm:px-6 pt-4 pb-2">
                <Link href="/" class="hover:text-black transition cursor-pointer">Home</Link>
                <ChevronRight class="w-3 h-3" />
                <Link
                    v-if="product.category"
                    :href="`/products?category=${product.category.slug || product.category.id}`"
                    class="hover:text-black transition cursor-pointer"
                >
                    {{ product.category.name }}
                </Link>
                <ChevronRight v-if="product.category" class="w-3 h-3" />
                <span class="text-[#1D1D1F] font-medium truncate">{{ product.name }}</span>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:gap-12 px-4 sm:px-6">
                <!-- ==================== LEFT: IMAGE GALLERY ==================== -->
                <div class="mb-6 lg:mb-0">
                    <!-- Main Image with dot indicators -->
                    <div
                        class="relative bg-[#F5F5F7] rounded-2xl overflow-hidden cursor-zoom-in group"
                        @click="openZoom"
                        @touchstart="onTouchStart"
                        @touchend="onTouchEnd"
                    >
                        <img
                            :src="images[selectedImage]?.image_url || images[selectedImage]?.url"
                            :alt="product.name"
                            class="w-full aspect-square object-contain p-6 sm:p-10"
                            @error="handleImgError"
                        />

                        <!-- Dot indicators (inside image, bottom center) -->
                        <div v-if="images.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                            <button
                                v-for="(_, i) in images"
                                :key="i"
                                @click.stop="selectedImage = i"
                                class="w-2 h-2 rounded-full bg-black transition-opacity duration-300 cursor-pointer"
                                :class="selectedImage === i ? 'opacity-100' : 'opacity-20 hover:opacity-40'"
                            />
                        </div>

                        <!-- Zoom hint (desktop) -->
                        <div class="hidden sm:flex absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-white px-2 py-1 rounded-lg text-[10px] font-medium items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <ZoomIn class="w-3 h-3" /> Zoom
                        </div>
                    </div>

                    <!-- Thumbnails (desktop only) -->
                    <div v-if="images.length > 1" class="hidden sm:flex gap-2 mt-3">
                        <button
                            v-for="(img, idx) in images"
                            :key="idx"
                            @click="selectedImage = idx"
                            class="w-16 h-16 rounded-lg overflow-hidden border-2 transition flex-shrink-0"
                            :class="selectedImage === idx ? 'border-black' : 'border-[#E5E5EA] hover:border-[#86868B]'"
                        >
                            <img
                                :src="img.image_url || img.url"
                                :alt="`${product.name} ${idx + 1}`"
                                class="w-full h-full object-cover"
                                @error="handleImgError"
                            />
                        </button>
                    </div>
                </div>

                <!-- ==================== RIGHT: PRODUCT INFO ==================== -->
                <div class="lg:pt-2">
                    <!-- Condition badge -->
                    <span
                        v-if="conditionLabel"
                        class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold border mb-3"
                        :class="conditionLabel === 'New'
                            ? 'bg-black text-white border-black'
                            : 'bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]'"
                    >
                        {{ conditionLabel }}
                    </span>

                    <!-- Product Name -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black tracking-tight leading-tight mb-2">
                        {{ product.name }}
                    </h1>

                    <!-- Price -->
                    <p class="text-2xl sm:text-3xl font-extrabold text-black font-price mb-4">
                        {{ formatPrice(currentPrice) }}
                    </p>

                    <!-- Short description -->
                    <p v-if="product.short_description" class="text-sm text-[#86868B] mb-5 leading-relaxed">
                        {{ product.short_description }}
                    </p>

                    <!-- Storage Variants -->
                    <div v-if="variants.length" class="mb-5">
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="variant in variants"
                                :key="variant.id"
                                @click="selectedVariant = variant.id"
                                class="px-4 py-2 rounded-full text-sm font-medium transition cursor-pointer border"
                                :class="selectedVariant === variant.id
                                    ? 'bg-black text-white border-black'
                                    : 'bg-white text-[#1D1D1F] border-[#E5E5EA] hover:border-black'"
                            >
                                {{ variant.storage || variant.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Quantity + Add to Cart (horizontal, side by side) -->
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex items-center border border-[#E5E5EA] rounded-full overflow-hidden">
                            <button
                                @click="quantity = Math.max(1, quantity - 1)"
                                class="w-10 h-10 flex items-center justify-center hover:bg-[#F5F5F7] transition cursor-pointer"
                            >
                                <Minus class="w-4 h-4 text-[#1D1D1F]" />
                            </button>
                            <span class="w-8 text-center text-sm font-semibold text-[#1D1D1F]">{{ quantity }}</span>
                            <button
                                @click="quantity++"
                                class="w-10 h-10 flex items-center justify-center hover:bg-[#F5F5F7] transition cursor-pointer"
                            >
                                <Plus class="w-4 h-4 text-[#1D1D1F]" />
                            </button>
                        </div>
                        <button
                            @click="addToCart"
                            class="flex-1 h-10 bg-black text-white rounded-full hover:bg-[#1D1D1F] transition font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]"
                        >
                            <ShoppingCart class="w-4 h-4" />
                            Add to Cart
                        </button>
                    </div>

                    <!-- Trust Section — clean bulleted icons, no big buttons -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 mb-5 py-4 border-y border-[#E5E5EA]">
                        <div class="flex items-center gap-2">
                            <Check class="w-4 h-4 text-[#86868B]" :stroke-width="1.5" />
                            <span class="text-xs text-[#1D1D1F] font-medium">Verify Quality</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Truck class="w-4 h-4 text-[#86868B]" :stroke-width="1.5" />
                            <span class="text-xs text-[#1D1D1F] font-medium">Same Day Delivery</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Shield class="w-4 h-4 text-[#86868B]" :stroke-width="1.5" />
                            <span class="text-xs text-[#1D1D1F] font-medium">6-Month Warranty</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Zap class="w-4 h-4 text-[#86868B]" :stroke-width="1.5" />
                            <span class="text-xs text-[#1D1D1F] font-medium">M-Pesa Ready</span>
                        </div>
                    </div>

                    <!-- Quick Specs (collapsed summary, not the full table) -->
                    <div v-if="specsList.length" class="mb-5">
                        <details class="group">
                            <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-[#E5E5EA]">
                                <span class="text-sm font-semibold text-black">Specifications</span>
                                <Plus class="w-4 h-4 text-[#86868B] group-open:hidden" />
                                <Minus class="w-4 h-4 text-[#86868B] hidden group-open:block" />
                            </summary>
                            <ul class="py-3 space-y-1.5">
                                <li v-for="spec in specsList" :key="spec.id" class="flex items-start gap-2 text-sm">
                                    <span class="text-[#86868B]">{{ spec.spec_name || spec.name }}:</span>
                                    <span class="text-[#1D1D1F] font-medium">{{ spec.spec_value || spec.value }}</span>
                                </li>
                            </ul>
                        </details>
                    </div>

                    <!-- Key Advantages (if present) -->
                    <div v-if="advantages.length" class="mb-5">
                        <details class="group" open>
                            <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-[#E5E5EA]">
                                <span class="text-sm font-semibold text-black">Key Advantages</span>
                                <Plus class="w-4 h-4 text-[#86868B] group-open:hidden" />
                                <Minus class="w-4 h-4 text-[#86868B] hidden group-open:block" />
                            </summary>
                            <ul class="py-3 space-y-2">
                                <li v-for="(adv, idx) in advantages" :key="idx" class="flex items-start gap-2 text-sm">
                                    <Check class="w-4 h-4 text-black flex-shrink-0 mt-0.5" :stroke-width="2" />
                                    <span class="text-[#1D1D1F]">{{ adv.advantage || adv.text || adv }}</span>
                                </li>
                            </ul>
                        </details>
                    </div>
                </div>
            </div>

            <!-- ==================== FULL DESCRIPTION (below the fold) ==================== -->
            <div v-if="product.description" class="px-4 sm:px-6 mt-10">
                <details class="group max-w-3xl">
                    <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-[#E5E5EA]">
                        <span class="text-base font-bold text-black">About This Product</span>
                        <Plus class="w-4 h-4 text-[#86868B] group-open:hidden" />
                        <Minus class="w-4 h-4 text-[#86868B] hidden group-open:block" />
                    </summary>
                    <div class="py-4 text-sm text-[#1D1D1F] leading-relaxed whitespace-pre-line">
                        {{ product.description }}
                    </div>
                </details>
            </div>

            <!-- ==================== FULL SPECIFICATIONS (below the fold) ==================== -->
            <div v-if="Object.keys(groupedSpecs).length" class="px-4 sm:px-6 mt-6">
                <details class="group max-w-3xl">
                    <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-[#E5E5EA]">
                        <span class="text-base font-bold text-black">Full Specifications</span>
                        <Plus class="w-4 h-4 text-[#86868B] group-open:hidden" />
                        <Minus class="w-4 h-4 text-[#86868B] hidden group-open:block" />
                    </summary>
                    <div class="py-4">
                        <div v-for="(specs, group) in groupedSpecs" :key="group" class="mb-4">
                            <h4 class="text-xs font-bold text-[#86868B] uppercase tracking-wider mb-2">{{ group }}</h4>
                            <div v-for="spec in specs" :key="spec.id" class="flex justify-between py-1.5 border-b border-[#F5F5F7] text-sm">
                                <span class="text-[#86868B]">{{ spec.spec_name || spec.name }}</span>
                                <span class="text-[#1D1D1F] font-medium text-right">{{ spec.spec_value || spec.value }}</span>
                            </div>
                        </div>
                    </div>
                </details>
            </div>

            <!-- ==================== RELATED PRODUCTS (horizontal scroll) ==================== -->
            <div v-if="relatedProducts.length" class="mt-12 mb-8">
                <div class="px-4 sm:px-6 mb-4">
                    <h2 class="text-lg font-extrabold text-black">Related Products</h2>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-4 px-4 sm:px-6 snap-x snap-mandatory scrollbar-hide">
                    <div
                        v-for="related in relatedProducts"
                        :key="related.id"
                        class="flex-shrink-0 w-[160px] sm:w-[200px] snap-start"
                    >
                        <ProductCard :product="related" />
                    </div>
                </div>
            </div>
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
                    <button
                        @click="closeZoom"
                        class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10 cursor-pointer"
                    >
                        <X class="w-5 h-5" />
                    </button>
                    <div v-if="images.length > 1" class="absolute top-4 left-4 text-white/60 text-xs font-medium z-10">
                        {{ selectedImage + 1 }} / {{ images.length }}
                    </div>
                    <template v-if="images.length > 1">
                        <button @click="prevImage" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10 cursor-pointer">
                            <ChevronLeft class="w-5 h-5" />
                        </button>
                        <button @click="nextImage" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition z-10 cursor-pointer">
                            <ChevronRight class="w-5 h-5" />
                        </button>
                    </template>
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
