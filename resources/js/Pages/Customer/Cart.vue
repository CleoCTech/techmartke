<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Trash2, Minus, Plus, ShoppingBag } from 'lucide-vue-next';

const cartItems = ref([]);

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};

const loadCart = () => {
    try {
        cartItems.value = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
    } catch {
        cartItems.value = [];
    }
};

const saveCart = () => {
    localStorage.setItem('techmart_cart', JSON.stringify(cartItems.value));
    window.dispatchEvent(new Event('cart-updated'));
};

const updateQuantity = (index, delta) => {
    const newQty = cartItems.value[index].quantity + delta;
    if (newQty < 1) return;
    cartItems.value[index].quantity = newQty;
    saveCart();
};

const removeItem = (index) => {
    cartItems.value.splice(index, 1);
    saveCart();
};

const subtotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0);
});

const total = computed(() => subtotal.value);

onMounted(() => {
    loadCart();
});
</script>

<template>
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold mb-8">Shopping Cart</h2>

            <div v-if="cartItems.length" class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1 space-y-4">
                    <div
                        v-for="(item, index) in cartItems"
                        :key="item.id"
                        class="bg-white rounded-2xl shadow-lg p-6 flex gap-6"
                    >
                        <img
                            :src="item.image || '/assets/img/placeholder.jpg'"
                            :alt="item.name"
                            class="w-24 h-24 object-cover rounded-lg shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg truncate">{{ item.name }}</h3>
                            <p v-if="item.variant" class="text-sm text-gray-500 mt-1">{{ item.variant }}</p>
                            <p v-if="item.condition" class="text-sm text-gray-500">{{ item.condition }}</p>
                            <p class="text-xl font-bold mt-2">{{ formatPrice(item.price) }}</p>
                        </div>
                        <div class="flex flex-col items-end justify-between">
                            <button
                                @click="removeItem(index)"
                                class="text-gray-400 hover:text-red-500 transition"
                            >
                                <Trash2 class="w-5 h-5" />
                            </button>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button
                                    @click="updateQuantity(index, -1)"
                                    class="px-3 py-1 hover:bg-gray-100 transition"
                                >
                                    <Minus class="w-4 h-4" />
                                </button>
                                <span class="px-3 py-1 font-medium text-sm">{{ item.quantity }}</span>
                                <button
                                    @click="updateQuantity(index, 1)"
                                    class="px-3 py-1 hover:bg-gray-100 transition"
                                >
                                    <Plus class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-80">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between">
                                <span class="font-bold">Total</span>
                                <span class="font-bold text-xl">{{ formatPrice(total) }}</span>
                            </div>
                        </div>
                        <Link
                            href="/checkout"
                            class="block w-full text-center px-8 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium"
                        >
                            Proceed to Checkout
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty Cart -->
            <div v-else class="text-center py-20">
                <ShoppingBag class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Start adding some products to your cart.</p>
                <Link
                    href="/products"
                    class="px-8 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium inline-block"
                >
                    Browse Products
                </Link>
            </div>
        </div>
    </CustomerLayout>
</template>
