<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { CheckCircle, CreditCard, Smartphone, Truck, MessageCircle } from 'lucide-vue-next';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { whatsappUrl: companyWhatsappUrl } = useCompanyInfo();

const props = defineProps({
    order: {
        type: Object,
        default: null,
    },
});

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};

const cartItems = ref([]);
const form = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    payment_method: 'mpesa',
});
const processing = ref(false);

const paymentMethods = [
    { value: 'mpesa', label: 'M-Pesa', icon: Smartphone },
    { value: 'card', label: 'Card', icon: CreditCard },
    { value: 'cod', label: 'Cash on Delivery', icon: Truck },
];

const loadCart = () => {
    try {
        cartItems.value = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
    } catch {
        cartItems.value = [];
    }
};

const subtotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0);
});

const total = computed(() => subtotal.value);

const placeOrder = () => {
    processing.value = true;
    router.post('/checkout', {
        ...form.value,
        items: cartItems.value,
    }, {
        onSuccess: () => {
            localStorage.removeItem('techmart_cart');
            window.dispatchEvent(new Event('cart-updated'));
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        },
    });
};

// Build a clean WhatsApp message containing the full order summary
const buildWhatsappMessage = () => {
    const lines = [];
    lines.push('*New Order — TechMart KE*');
    lines.push('');

    if (form.value.name) lines.push(`*Customer:* ${form.value.name}`);
    if (form.value.phone) lines.push(`*Phone:* ${form.value.phone}`);
    if (form.value.email) lines.push(`*Email:* ${form.value.email}`);
    if (form.value.address) lines.push(`*Delivery:* ${form.value.address}`);
    if (form.value.payment_method) {
        const labelMap = { mpesa: 'M-Pesa', card: 'Card', cod: 'Cash on Delivery' };
        lines.push(`*Payment:* ${labelMap[form.value.payment_method] || form.value.payment_method}`);
    }

    lines.push('');
    lines.push('*Items:*');
    cartItems.value.forEach((item, idx) => {
        const variant = item.variant ? ` (${item.variant})` : '';
        const lineTotal = (Number(item.price) * item.quantity).toLocaleString();
        lines.push(`${idx + 1}. ${item.name}${variant} — KSh ${Number(item.price).toLocaleString()} x ${item.quantity} = KSh ${lineTotal}`);
    });

    lines.push('');
    lines.push(`*Total: KSh ${Number(total.value).toLocaleString()}*`);
    lines.push('');
    lines.push('Please confirm availability and delivery details. Thank you!');

    return lines.join('\n');
};

const orderViaWhatsapp = () => {
    if (!cartItems.value.length) return;
    const url = companyWhatsappUrl(buildWhatsappMessage());
    window.open(url, '_blank', 'noopener');
};

onMounted(() => {
    loadCart();
});
</script>

<template>
    <Head title="Checkout" />
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Order Confirmation -->
            <div v-if="order" class="max-w-2xl mx-auto text-center py-16">
                <CheckCircle class="w-20 h-20 text-green-500 mx-auto mb-6" />
                <h2 class="text-3xl font-bold mb-4">Order Confirmed!</h2>
                <p class="text-gray-600 mb-2">Thank you for your purchase.</p>
                <p class="text-lg mb-8">
                    Your order number is
                    <span class="font-bold text-black">{{ order.order_number || order.id }}</span>
                </p>
                <div class="bg-white rounded-2xl shadow-lg p-6 text-left mb-8">
                    <h3 class="font-bold mb-4">Order Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name</span>
                            <span>{{ order.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email</span>
                            <span>{{ order.email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment</span>
                            <span class="capitalize">{{ order.payment_method }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 flex justify-between font-bold">
                            <span>Total</span>
                            <span>{{ formatPrice(order.total) }}</span>
                        </div>
                    </div>
                </div>
                <Link
                    href="/"
                    class="px-8 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium inline-block"
                >
                    Continue Shopping
                </Link>
            </div>

            <!-- Checkout Form -->
            <div v-else>
                <h2 class="text-3xl font-bold mb-8">Checkout</h2>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Form -->
                    <div class="flex-1">
                        <form @submit.prevent="placeOrder" class="space-y-6">
                            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4">
                                <h3 class="text-lg font-bold mb-2">Contact Information</h3>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-black focus:outline-none"
                                        placeholder="John Doe"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-black focus:outline-none"
                                        placeholder="john@example.com"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input
                                        v-model="form.phone"
                                        type="tel"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-black focus:outline-none"
                                        placeholder="0712 345 678"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                                    <textarea
                                        v-model="form.address"
                                        required
                                        rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-black focus:outline-none resize-none"
                                        placeholder="Enter your full delivery address"
                                    />
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <h3 class="text-lg font-bold mb-4">Payment Method</h3>
                                <div class="grid grid-cols-3 gap-3">
                                    <button
                                        v-for="method in paymentMethods"
                                        :key="method.value"
                                        type="button"
                                        @click="form.payment_method = method.value"
                                        :class="[
                                            'flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition',
                                            form.payment_method === method.value
                                                ? 'border-black bg-black text-white'
                                                : 'border-gray-200 hover:border-gray-400'
                                        ]"
                                    >
                                        <component :is="method.icon" class="w-6 h-6" />
                                        <span class="text-sm font-medium">{{ method.label }}</span>
                                    </button>
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="processing || !cartItems.length"
                                class="w-full px-8 py-4 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium text-lg disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                            >
                                {{ processing ? 'Processing...' : 'Place Order' }}
                            </button>

                            <!-- OR Divider -->
                            <div class="relative flex items-center py-1">
                                <div class="flex-grow border-t border-gray-200"></div>
                                <span class="flex-shrink mx-4 text-xs text-gray-400 uppercase tracking-wider font-semibold">Or</span>
                                <div class="flex-grow border-t border-gray-200"></div>
                            </div>

                            <!-- Order via WhatsApp -->
                            <button
                                type="button"
                                @click="orderViaWhatsapp"
                                :disabled="!cartItems.length"
                                class="w-full px-8 py-4 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-xl transition font-bold text-base flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer shadow-md shadow-green-500/30 hover:shadow-lg hover:shadow-green-500/40 active:scale-[0.98]"
                            >
                                <MessageCircle class="w-5 h-5" />
                                Order via WhatsApp
                            </button>
                            <p class="text-center text-xs text-gray-500 -mt-3">
                                Fastest way — chat with us instantly to confirm and pay
                            </p>
                        </form>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:w-96">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                            <h3 class="text-lg font-bold mb-4">Order Summary</h3>

                            <div v-if="cartItems.length" class="space-y-4 mb-6">
                                <div
                                    v-for="item in cartItems"
                                    :key="item.id"
                                    class="flex gap-4"
                                >
                                    <img
                                        :src="item.image || '/assets/img/placeholder.jpg'"
                                        :alt="item.name"
                                        class="w-16 h-16 object-cover rounded-lg shrink-0"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm truncate">{{ item.name }}</p>
                                        <p v-if="item.variant" class="text-xs text-gray-500">{{ item.variant }}</p>
                                        <p class="text-sm">
                                            {{ formatPrice(item.price) }}
                                            <span class="text-gray-400">x {{ item.quantity }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-4 text-gray-500 text-sm mb-4">
                                No items in cart
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span>{{ formatPrice(subtotal) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Total</span>
                                    <span>{{ formatPrice(total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
