<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

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
        in_stock: { label: 'In Stock', class: 'bg-green-500' },
        in_Stock: { label: 'In Stock', class: 'bg-green-500' },
        'in stock': { label: 'In Stock', class: 'bg-green-500' },
        out_of_stock: { label: 'Out of Stock', class: 'bg-red-500' },
        'out of stock': { label: 'Out of Stock', class: 'bg-red-500' },
        pre_order: { label: 'Pre Order', class: 'bg-yellow-500' },
        'pre order': { label: 'Pre Order', class: 'bg-yellow-500' },
    };
    return map[status?.toLowerCase()] || { label: status || 'In Stock', class: 'bg-green-500' };
};

const conditionLabel = (condition) => {
    const map = {
        new: 'New',
        'ex-uk': 'Ex-UK',
        'ex_uk': 'Ex-UK',
        refurbished: 'Refurbished',
    };
    return map[condition?.toLowerCase()] || condition || '';
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
                image: props.product.images?.[0]?.url || props.product.image,
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
    return product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg';
};
</script>

<template>
    <Link
        :href="`/products/${product.slug || product.id}`"
        class="product-card bg-white rounded-2xl overflow-hidden shadow-lg block transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.15)]"
    >
        <div class="relative h-64 bg-gray-100 overflow-hidden group">
            <img
                :src="productImage(product)"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div
                :class="stockBadge(product.stock_status).class"
                class="absolute top-4 right-4 text-white px-3 py-1 rounded-full text-sm font-medium"
            >
                {{ stockBadge(product.stock_status).label }}
            </div>
        </div>
        <div class="p-6">
            <h4 class="text-xl font-bold mb-2">{{ product.name }}</h4>
            <p class="text-gray-600 text-sm mb-4">
                <span v-if="product.condition">{{ conditionLabel(product.condition) }}</span>
                <span v-if="product.condition && product.brand"> &bull; </span>
                <span v-if="product.brand">{{ product.brand }}</span>
            </p>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ formatPrice(product.base_price || product.price) }}</p>
                    <p
                        v-if="product.original_price && product.original_price > (product.base_price || product.price)"
                        class="text-sm text-gray-400 line-through"
                    >
                        {{ formatPrice(product.original_price) }}
                    </p>
                </div>
                <button
                    @click="handleAddToCart"
                    class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium"
                >
                    View Details
                </button>
            </div>
        </div>
    </Link>
</template>
