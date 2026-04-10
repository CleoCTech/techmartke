<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Trash2, Minus, Plus, ShoppingBag, MessageCircle, Phone, Info } from 'lucide-vue-next';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { whatsappUrl, callUrl } = useCompanyInfo();

const cartItems = ref([]);

const formatPrice = (price) => 'KSh ' + Number(price).toLocaleString();

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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8">
            <h2 class="text-2xl md:text-3xl font-extrabold text-black mb-6">Shopping Cart</h2>

            <div v-if="cartItems.length" class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="flex-1 space-y-3">
                    <div
                        v-for="(item, index) in cartItems"
                        :key="item.id"
                        class="bg-white rounded-xl border border-[#E5E5EA] p-4 flex gap-4"
                    >
                        <!-- Thumbnail with consistent bg -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-[#F9F9F9] flex-shrink-0 flex items-center justify-center">
                            <img
                                :src="item.image || '/assets/img/placeholder.jpg'"
                                :alt="item.name"
                                class="w-full h-full object-contain p-1"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <!-- Name — allow wrap, no truncate -->
                            <h3 class="font-bold text-sm sm:text-base text-[#1D1D1F] leading-snug">{{ item.name }}</h3>
                            <p v-if="item.variant" class="text-xs text-[#86868B] mt-0.5">{{ item.variant }}</p>
                            <p v-if="item.condition" class="text-xs text-[#86868B]">{{ item.condition }}</p>
                            <p class="text-base sm:text-lg font-extrabold text-black font-price mt-1.5">{{ formatPrice(item.price) }}</p>
                        </div>
                        <div class="flex flex-col items-end justify-between flex-shrink-0">
                            <button
                                @click="removeItem(index)"
                                class="text-[#C7C7CC] hover:text-red-500 transition cursor-pointer p-1"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                            <div class="flex items-center border border-[#E5E5EA] rounded-full overflow-hidden">
                                <button
                                    @click="updateQuantity(index, -1)"
                                    class="w-8 h-8 flex items-center justify-center hover:bg-[#F5F5F7] transition cursor-pointer"
                                >
                                    <Minus class="w-3.5 h-3.5 text-[#1D1D1F]" />
                                </button>
                                <span class="w-6 text-center text-xs font-semibold text-[#1D1D1F]">{{ item.quantity }}</span>
                                <button
                                    @click="updateQuantity(index, 1)"
                                    class="w-8 h-8 flex items-center justify-center hover:bg-[#F5F5F7] transition cursor-pointer"
                                >
                                    <Plus class="w-3.5 h-3.5 text-[#1D1D1F]" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-96">
                    <div class="bg-white rounded-xl border border-[#E5E5EA] p-5 sticky top-24">
                        <h3 class="text-base font-bold text-black mb-4">Order Summary</h3>

                        <!-- Line items -->
                        <div class="space-y-2.5 mb-4">
                            <div class="flex justify-between text-sm" v-for="item in cartItems" :key="item.id">
                                <span class="text-[#86868B] mr-2">{{ item.name }} × {{ item.quantity }}</span>
                                <span class="font-medium text-[#1D1D1F] whitespace-nowrap font-price">{{ formatPrice(item.price * item.quantity) }}</span>
                            </div>
                        </div>

                        <!-- Divider + Total (receipt-style separation) -->
                        <div class="border-t border-[#E5E5EA] pt-3 mb-5">
                            <div class="flex justify-between items-baseline">
                                <span class="text-sm font-bold text-black">Total</span>
                                <span class="text-2xl font-extrabold text-black font-price">{{ formatPrice(total) }}</span>
                            </div>
                        </div>

                        <!-- Primary: WhatsApp Order (deep forest green) -->
                        <a
                            :href="whatsappOrderUrl"
                            target="_blank"
                            rel="noopener"
                            class="w-full flex items-center justify-center gap-2.5 px-6 py-3.5 bg-[#1A531B] hover:bg-[#006241] text-white rounded-full font-bold transition-all active:scale-[0.98] text-sm"
                        >
                            <MessageCircle class="w-5 h-5" />
                            Order via WhatsApp
                        </a>
                        <p class="text-center text-[10px] text-[#86868B] mt-2 mb-4">Fastest way — get instant confirmation</p>

                        <!-- Secondary: Call to Order (black border ghost button) -->
                        <a
                            :href="callOrderUrl"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 border border-black hover:bg-black hover:text-white text-[#1D1D1F] rounded-full font-semibold transition-all text-sm cursor-pointer"
                        >
                            <Phone class="w-4 h-4" />
                            Call to Order
                        </a>

                        <!-- Tertiary: Online Checkout (black button) -->
                        <Link
                            href="/checkout"
                            class="mt-3 w-full flex items-center justify-center gap-2 px-6 py-3 bg-black text-white rounded-full font-semibold text-sm hover:bg-[#1D1D1F] transition-all cursor-pointer active:scale-[0.98]"
                        >
                            Proceed to Checkout
                        </Link>

                        <!-- Trust note — 1px border + info icon, not grey box -->
                        <div class="mt-4 flex items-start gap-2 py-3 px-3 border border-[#E5E5EA] rounded-xl">
                            <Info class="w-4 h-4 text-[#86868B] flex-shrink-0 mt-0.5" :stroke-width="1.5" />
                            <p class="text-[11px] text-[#86868B] leading-relaxed">
                                We confirm availability & agree on delivery before payment. Pay via M-Pesa on delivery or pickup from our Nairobi shop.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Cart -->
            <div v-else class="text-center py-20">
                <ShoppingBag class="w-16 h-16 text-[#E5E5EA] mx-auto mb-4" />
                <h3 class="text-2xl font-extrabold text-black mb-2">Your cart is empty</h3>
                <p class="text-[#86868B] text-sm mb-6">Start adding some products to your cart.</p>
                <Link
                    href="/products"
                    class="px-8 py-3 bg-black text-white rounded-full hover:bg-[#1D1D1F] transition font-semibold inline-block text-sm cursor-pointer"
                >
                    Browse Products
                </Link>
            </div>
        </div>
    </CustomerLayout>
</template>
