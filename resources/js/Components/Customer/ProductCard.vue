<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, MessageCircle, Clock, Share2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { whatsappUrl: companyWhatsappUrl } = useCompanyInfo();
import { computed } from 'vue';

const props = defineProps({
    product: { type: Object, required: true },
});

const emit = defineEmits(['addToCart']);

const isOutOfStock = computed(() => {
    if (props.product.stock_status === 'in_stock') return false;
    if (props.product.stock_status === 'out_of_stock' || props.product.stock_status === 'out of stock') return true;
    // Check variants
    const variants = props.product.variants || [];
    if (variants.length > 0) {
        return variants.every(v => (v.stock_quantity || 0) <= 0);
    }
    return true;
});

const formatPrice = (price) => 'KSh ' + Number(price).toLocaleString();

const stockBadge = (status) => {
    if (isOutOfStock.value) return { label: 'Sold Out', class: 'bg-red-500' };
    const map = {
        in_stock: { label: 'In Stock', class: 'bg-emerald-500' },
        pre_order: { label: 'Pre Order', class: 'bg-amber-500' },
    };
    return map[status?.toLowerCase()] || { label: 'In Stock', class: 'bg-emerald-500' };
};

// Monochrome badges — grayscale only, no color splashes
const conditionBadge = (condition) => {
    const map = {
        new: { label: 'New', class: 'bg-black text-white border-black' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]' },
        'ex_uk': { label: 'Ex-UK', class: 'bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]' },
        'ex-us': { label: 'Ex-US', class: 'bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]' },
        'ex_us': { label: 'Ex-US', class: 'bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]' },
        refurbished: { label: 'Refurbished', class: 'bg-[#F5F5F7] text-[#86868B] border-[#E5E5EA]' },
    };
    return map[condition?.toLowerCase()] || { label: condition || '', class: 'bg-[#F5F5F7] text-[#86868B] border-[#E5E5EA]' };
};

// Detect if any variant of this product is an eSIM variant — used to show
// an "eSIM" pill next to the brand row (replacing the duplicate condition tag)
const hasEsim = computed(() => {
    const variants = props.product?.variants || [];
    return variants.some(v => {
        const s = (v?.sim_type || '').toString().toLowerCase();
        return s.includes('esim') || s === 'e-sim';
    });
});

const savingsAmount = (product) => {
    if (product.original_price && product.original_price > (product.base_price || product.price)) {
        return product.original_price - (product.base_price || product.price);
    }
    return 0;
};

const handleAddToCart = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (isOutOfStock.value) return;
    emit('addToCart', props.product);
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        const existing = cart.find((item) => item.id === props.product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: props.product.id,
                name: props.product.name,
                price: props.product.base_price || props.product.price,
                slug: props.product.slug,
                image: props.product.images?.[0]?.image_url || props.product.image,
                condition: props.product.condition,
                quantity: 1,
            });
        }
        localStorage.setItem('techmart_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    } catch { /* silently fail */ }
};

// Build a deduped list of all usable image URLs for this product (skip empty/null)
const imageUrls = computed(() => {
    const list = [];
    const seen = new Set();
    const images = Array.isArray(props.product?.images) ? props.product.images : [];
    for (const img of images) {
        const url = img?.image_url || img?.url || img?.path;
        if (url && String(url).trim() !== '' && !seen.has(url)) {
            seen.add(url);
            list.push(url);
        }
    }
    if (props.product?.image && !seen.has(props.product.image)) {
        list.push(props.product.image);
    }
    return list;
});

// Reactive index of the currently displayed image. Advances on @error.
const imgIdx = ref(0);
const currentImage = computed(() => imageUrls.value[imgIdx.value] || '/assets/img/placeholder.jpg');

const onImgError = () => {
    if (imgIdx.value < imageUrls.value.length - 1) {
        imgIdx.value++; // try next image
    } else {
        imgIdx.value = imageUrls.value.length; // out of range -> placeholder via computed
    }
};

const shareSuccess = ref(false);
const shareProduct = async (e, product) => {
    e.preventDefault();
    e.stopPropagation();
    const url = `${window.location.origin}/products/${product.slug || product.id}`;
    const price = Number(product.base_price || product.price || 0).toLocaleString();
    const text = `Check out ${product.name} at KSh ${price} on TechMart KE!`;
    if (navigator.share) {
        try { await navigator.share({ title: product.name, text, url }); } catch {}
    } else {
        try { await navigator.clipboard.writeText(`${text}\n${url}`); } catch {}
    }
    shareSuccess.value = true;
    setTimeout(() => { shareSuccess.value = false; }, 2000);
};

const whatsappUrl = (product) => {
    const price = Number(product.base_price || product.price || 0).toLocaleString();
    const msg = `Hi TechMart KE! I'm interested in the *${product.name}* at KSh ${price}. Is it available?`;
    return companyWhatsappUrl(msg);
};
</script>

<template>
    <Link
        :href="`/products/${product.slug || product.id}`"
        class="product-card group bg-white rounded-2xl overflow-hidden block transition-all duration-300 cursor-pointer"
        :class="isOutOfStock
            ? 'opacity-60 grayscale-[40%] hover:opacity-80 hover:grayscale-[20%]'
            : 'hover:bg-[#F5F5F7] hover:-translate-y-0.5'"
    >
        <!-- Image -->
        <div class="relative aspect-square bg-[#F5F5F7] overflow-hidden p-4 sm:p-6">
            <img
                :src="currentImage"
                :alt="product.name"
                class="w-full h-full object-contain transition-transform duration-500"
                :class="isOutOfStock ? '' : 'group-hover:scale-105'"
                loading="lazy"
                @error="onImgError"
            />

            <!-- Badges — uniform small rectangles, top-left aligned -->
            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                <span v-if="product.condition"
                    :class="conditionBadge(product.condition).class"
                    class="px-2 py-0.5 rounded text-[10px] font-semibold border leading-none">
                    {{ conditionBadge(product.condition).label }}
                </span>
                <span
                    v-if="product.stock_status && product.stock_status !== 'in_stock'"
                    :class="stockBadge(product.stock_status).class"
                    class="text-white px-2 py-0.5 rounded text-[10px] font-semibold leading-none"
                >
                    {{ stockBadge(product.stock_status).label }}
                </span>
            </div>

            <!-- Share button -->
            <button
                @click="shareProduct($event, product)"
                class="absolute bottom-3 right-3 w-8 h-8 bg-white/80 hover:bg-white backdrop-blur-sm rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 shadow-sm z-10"
                :class="shareSuccess ? 'bg-green-50 text-green-600' : 'text-gray-500 hover:text-black'"
                title="Share"
            >
                <Share2 class="w-3.5 h-3.5" />
            </button>

            <!-- Out of stock overlay -->
            <div v-if="isOutOfStock"
                class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 text-center shadow-sm">
                    <Clock class="w-5 h-5 text-gray-400 mx-auto mb-1" />
                    <p class="text-xs font-semibold text-gray-600">Coming Soon</p>
                    <p class="text-[10px] text-gray-400">Check back shortly</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-4 pb-4 pt-2 sm:px-5 sm:pb-5">
            <div class="flex items-center gap-2 mb-1">
                <span v-if="product.brand" class="text-[11px] sm:text-xs font-semibold text-[#86868B] uppercase tracking-wider">
                    {{ product.brand?.name || product.brand }}
                </span>
                <span v-if="hasEsim"
                    class="px-2 py-0.5 rounded text-[10px] font-bold border bg-[#F5F5F7] text-[#1D1D1F] border-[#E5E5EA]">
                    eSIM
                </span>
            </div>

            <h4 class="text-sm sm:text-base font-bold mb-2 leading-snug line-clamp-1 transition-colors"
                :class="isOutOfStock ? 'text-gray-500' : 'text-gray-900 group-hover:text-black'">
                {{ product.name }}
            </h4>

            <div class="flex items-end justify-between gap-2">
                <div>
                    <p class="text-lg sm:text-xl md:text-2xl font-extrabold font-price"
                        :class="isOutOfStock ? 'text-gray-400' : 'text-gray-900'">
                        {{ formatPrice(product.base_price || product.price) }}
                    </p>
                    <p v-if="savingsAmount(product) && !isOutOfStock" class="text-xs text-gray-400 mt-0.5">
                        Save {{ formatPrice(savingsAmount(product)) }}
                    </p>
                </div>
                <div class="flex gap-1.5 flex-shrink-0">
                    <!-- WhatsApp — the only emerald accent in the card -->
                    <a :href="whatsappUrl(product)" target="_blank" rel="noopener" @click.stop
                        class="w-9 h-9 sm:w-10 sm:h-10 bg-[#1A531B] hover:bg-[#006241] text-white rounded-full active:scale-95 transition-all cursor-pointer flex items-center justify-center"
                        title="Ask on WhatsApp">
                        <MessageCircle class="w-4 h-4" />
                    </a>
                    <!-- Add to cart — icon-only on mobile, icon+text on sm+ -->
                    <button v-if="!isOutOfStock"
                        @click="handleAddToCart"
                        class="w-9 h-9 sm:w-auto sm:h-10 sm:px-4 bg-black text-white rounded-full hover:bg-[#1D1D1F] active:scale-95 transition-all text-sm font-semibold cursor-pointer flex items-center justify-center gap-1.5">
                        <ShoppingCart class="w-4 h-4 sm:w-3.5 sm:h-3.5" />
                        <span class="hidden sm:inline">Add</span>
                    </button>
                    <span v-else
                        class="w-9 h-9 sm:w-auto sm:h-10 sm:px-4 bg-[#F5F5F7] text-[#86868B] rounded-full text-sm font-semibold flex items-center justify-center gap-1.5 cursor-not-allowed">
                        <Clock class="w-4 h-4 sm:w-3.5 sm:h-3.5" />
                        <span class="hidden sm:inline">Soon</span>
                    </span>
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
