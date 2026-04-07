<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Trash2, Minus, Plus, ShoppingBag, MessageCircle, Phone } from 'lucide-vue-next';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { whatsappUrl, callUrl } = useCompanyInfo();

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

const whatsappOrderUrl = computed(() => {
    if (!cartItems.value.length) return '#';
    let msg = `Hi TechMart KE! I'd like to order the following:\n\n`;
    cartItems.value.forEach((item, i) => {
        const price = Number(item.price).toLocaleString();
        msg += `${i + 1}. *${item.name}*`;
        if (item.variant) msg += ` (${item.variant})`;
        if (item.condition) msg += ` - ${item.condition}`;
        msg += `\n   Qty: ${item.quantity} × KSh ${price}\n`;
    });
    msg += `\n*Total: KSh ${Number(total.value).toLocaleString()}*\n\nPlease confirm availability and payment details. Thank you!`;
    return whatsappUrl(msg);
});

const callOrderUrl = computed(() => callUrl.value);

onMounted(() => {
    loadCart();
});
</script>

<template>
    <Head title="Shopping Cart" />
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
                <div class="lg:w-96">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between text-sm" v-for="item in cartItems" :key="item.id">
                                <span class="text-gray-500 truncate mr-2">{{ item.name }} × {{ item.quantity }}</span>
                                <span class="font-medium whitespace-nowrap">{{ formatPrice(item.price * item.quantity) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between">
                                <span class="font-bold">Total</span>
                                <span class="font-bold text-xl">{{ formatPrice(total) }}</span>
                            </div>
                        </div>

                        <!-- Primary: WhatsApp Order -->
                        <a
                            :href="whatsappOrderUrl"
                            target="_blank"
                            rel="noopener"
                            class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-xl font-semibold transition-all active:scale-[0.98] shadow-md shadow-green-500/20 hover:shadow-lg hover:shadow-green-500/30 text-base"
                        >
                            <MessageCircle class="w-5 h-5" />
                            Order via WhatsApp
                        </a>
                        <p class="text-center text-[11px] text-gray-400 mt-2 mb-4">Fastest way — get instant confirmation</p>

                        <!-- Secondary: Call to Order -->
                        <a
                            :href="callOrderUrl"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-200 hover:border-gray-300 text-gray-700 rounded-xl font-medium transition-all text-sm"
                        >
                            <Phone class="w-4 h-4" />
                            Call to Order
                        </a>

                        <!-- Tertiary: Checkout -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <Link
                                href="/checkout"
                                class="block w-full text-center px-6 py-2.5 text-sm text-gray-500 hover:text-black rounded-xl hover:bg-gray-50 transition font-medium"
                            >
                                Or proceed to online checkout →
                            </Link>
                        </div>

                        <!-- Trust note -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-[11px] text-gray-500 text-center leading-relaxed">
                                We confirm availability & agree on delivery before payment. Pay via M-Pesa on delivery or pickup from our Nairobi shop.
                            </p>
                        </div>
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
