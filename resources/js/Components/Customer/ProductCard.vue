<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, Eye, MessageCircle } from 'lucide-vue-next';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['addToCart']);

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};

const stockBadge = (status) => {
    const map = {
        in_stock: { label: 'In Stock', class: 'bg-emerald-500' },
        in_Stock: { label: 'In Stock', class: 'bg-emerald-500' },
        'in stock': { label: 'In Stock', class: 'bg-emerald-500' },
        out_of_stock: { label: 'Sold Out', class: 'bg-red-500' },
        'out of stock': { label: 'Sold Out', class: 'bg-red-500' },
        pre_order: { label: 'Pre Order', class: 'bg-amber-500' },
        'pre order': { label: 'Pre Order', class: 'bg-amber-500' },
    };
    return map[status?.toLowerCase()] || { label: status || 'In Stock', class: 'bg-emerald-500' };
};

const conditionBadge = (condition) => {
    const map = {
        new: { label: 'New', class: 'bg-blue-50 text-blue-700 border-blue-200' },
        'ex-uk': { label: 'Ex-UK', class: 'bg-orange-50 text-orange-700 border-orange-200' },
        'ex_uk': { label: 'Ex-UK', class: 'bg-orange-50 text-orange-700 border-orange-200' },
        refurbished: { label: 'Refurbished', class: 'bg-gray-50 text-gray-700 border-gray-200' },
    };
    return map[condition?.toLowerCase()] || { label: condition || '', class: 'bg-gray-50 text-gray-600 border-gray-200' };
};

const discount = (product) => {
    if (product.original_price && product.original_price > (product.base_price || product.price)) {
        return Math.round((1 - (product.base_price || product.price) / product.original_price) * 100);
    }
    return 0;
};

const handleAddToCart = (e) => {
    e.preventDefault();
    e.stopPropagation();
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
                image: props.product.images?.[0]?.image_url || props.product.images?.[0]?.url || props.product.image,
                condition: props.product.condition,
                quantity: 1,
            });
        }
        localStorage.setItem('techmart_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    } catch {
        // silently fail
    }
};

const productImage = (product) => {
    return product.images?.[0]?.image_url || product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg';
};

const whatsappUrl = (product) => {
    const price = Number(product.base_price || product.price || 0).toLocaleString();
    const msg = encodeURIComponent(`Hi TechMart KE! I'm interested in the *${product.name}* at KSh ${price}. Is it available?`);
    return `https://wa.me/254700000000?text=${msg}`;
};
</script>

<template>
    <Link
        :href="`/products/${product.slug || product.id}`"
        class="product-card group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl block transition-all duration-300 hover:-translate-y-1.5 cursor-pointer border border-gray-100 hover:border-gray-200"
    >
        <!-- Image -->
        <div class="relative aspect-[4/3] sm:h-56 md:h-64 bg-gray-50 overflow-hidden">
            <img
                :src="productImage(product)"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            />

            <!-- Overlay on hover -->
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center">
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 backdrop-blur-sm text-black px-4 py-2 rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-lg">
                    <Eye class="w-3.5 h-3.5" />
                    Quick View
                </span>
            </div>

            <!-- Badges -->
            <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                <div class="flex flex-col gap-1.5">
                    <span
                        v-if="product.condition"
                        :class="conditionBadge(product.condition).class"
                        class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold border inline-block"
                    >
                        {{ conditionBadge(product.condition).label }}
                    </span>
                    <span
                        v-if="discount(product)"
                        class="bg-red-500 text-white px-2.5 py-0.5 rounded-md text-[11px] font-bold inline-block"
                    >
                        -{{ discount(product) }}%
                    </span>
                </div>
                <span
                    :class="stockBadge(product.stock_status).class"
                    class="text-white px-2.5 py-1 rounded-full text-[11px] font-semibold shadow-sm"
                >
                    {{ stockBadge(product.stock_status).label }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-5 md:p-6">
            <!-- Brand -->
            <p v-if="product.brand" class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                {{ product.brand?.name || product.brand }}
            </p>

            <!-- Name -->
            <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-3 leading-snug group-hover:text-black transition-colors line-clamp-2">
                {{ product.name }}
            </h4>

            <!-- Price -->
            <div class="flex items-end justify-between gap-2">
                <div>
                    <p class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900">
                        {{ formatPrice(product.base_price || product.price) }}
                    </p>
                    <p
                        v-if="product.original_price && product.original_price > (product.base_price || product.price)"
                        class="text-xs sm:text-sm text-gray-400 line-through mt-0.5"
                    >
                        {{ formatPrice(product.original_price) }}
                    </p>
                </div>
                <div class="flex gap-1.5 flex-shrink-0">
                    <a
                        :href="whatsappUrl(product)"
                        target="_blank"
                        rel="noopener"
                        @click.stop
                        class="w-10 h-10 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-xl active:scale-95 transition-all cursor-pointer flex items-center justify-center shadow-sm"
                        title="Ask on WhatsApp"
                    >
                        <MessageCircle class="w-4 h-4" />
                    </a>
                    <button
                        @click="handleAddToCart"
                        class="w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 bg-black text-white rounded-xl hover:bg-gray-800 active:scale-95 transition-all text-sm font-semibold cursor-pointer flex items-center justify-center gap-2 shadow-sm hover:shadow-md"
                    >
                        <ShoppingCart class="w-4 h-4" />
                        <span class="hidden sm:inline">Add</span>
                    </button>
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
