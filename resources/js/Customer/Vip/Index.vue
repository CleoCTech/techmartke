<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    Crown,
    Bell,
    Tag,
    TrendingDown,
    Headphones,
    UserPlus,
    CreditCard,
    Share2,
    Copy,
    Check,
    ChevronDown,
    ChevronUp,
    Users,
    MessageCircle,
    Zap,
    ArrowRight,
    Mail,
    Phone,
    User,
    Gift,
    Shield,
    Clock,
} from 'lucide-vue-next';

const props = defineProps({
    activeCampaign: { type: Object, default: null },
    vipCount: { type: Number, default: 0 },
});

// Registration form
const registerForm = useForm({
    name: '',
    email: '',
    phone: '',
    promo_code: '',
    notify_new_stock: true,
    notify_deals: true,
    notify_price_drops: true,
});

const showPromoInput = ref(false);
const registrationSuccess = ref(false);
const registeredPromoCode = ref('');

const submitRegistration = () => {
    registerForm.post('/vip/register', {
        preserveScroll: true,
        onSuccess: (page) => {
            registrationSuccess.value = true;
            registeredPromoCode.value = page.props?.flash?.promo_code || 'TM-XXXXX';
            registerForm.reset();
        },
    });
};

// Activation form
const activateForm = useForm({
    email: '',
    payment_reference: '',
});

const submitActivation = () => {
    activateForm.post('/vip/activate', {
        preserveScroll: true,
    });
};

// Status check
const statusEmail = ref('');
const statusResult = ref(null);
const statusLoading = ref(false);

const checkStatus = async () => {
    statusLoading.value = true;
    try {
        const response = await fetch('/vip/check-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
            body: JSON.stringify({ email: statusEmail.value }),
        });
        statusResult.value = await response.json();
    } catch (e) {
        statusResult.value = { error: 'Unable to check status. Please try again.' };
    } finally {
        statusLoading.value = false;
    }
};

// Copy promo code
const copied = ref(false);
const copyCode = (code) => {
    navigator.clipboard.writeText(code);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
};

// WhatsApp share
const shareWhatsApp = (code) => {
    const message = encodeURIComponent(`Join TechMart KE VIP Early Access! Get first dibs on new stock, exclusive deals & price drop alerts. Use my code: ${code} to sign up at ${window.location.origin}/vip`);
    window.open(`https://wa.me/?text=${message}`, '_blank');
};

const vipPrice = computed(() => props.activeCampaign?.vip_price || 500);
const referralsNeeded = computed(() => props.activeCampaign?.referrals_to_activate || 2);

const statusBadgeClass = (status) => {
    const classes = {
        active: 'bg-black text-white',
        pending: 'bg-[#F5F5F7] text-[#86868B] border border-[#E5E5EA]',
        expired: 'bg-[#F5F5F7] text-[#86868B] border border-[#E5E5EA]',
    };
    return classes[status] || 'bg-[#F5F5F7] text-[#86868B]';
};
</script>

<template>
    <Head title="VIP Early Access — Exclusive Deals & Priority Restocking" />
    <CustomerLayout>
        <!-- Hero Section -->
        <section class="pt-8 pb-6 md:pt-12 md:pb-10">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="inline-flex items-center gap-2 bg-[#F5F5F7] border border-[#E5E5EA] rounded-full px-4 py-1.5 mb-4 text-sm font-medium text-[#1D1D1F]">
                        <Crown class="w-4 h-4" />
                        Get First Access
                    </div>

                    <h2 class="text-3xl md:text-4xl font-bold text-black mb-3 leading-tight">
                        Be the First to Know
                    </h2>
                    <p class="text-gray-600 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
                        Join our VIP list and get notified before anyone else when new stock drops, exclusive deals land, and prices drop
                    </p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="py-8 md:py-12 bg-[#F5F5F7]">
            <div class="container mx-auto px-4">
                <h3 class="text-xl md:text-2xl font-bold text-center mb-8">How It Works</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <UserPlus class="w-7 h-7 text-white" />
                        </div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Step 1</div>
                        <h4 class="font-bold text-base mb-1">Sign Up</h4>
                        <p class="text-sm text-gray-500">Enter your email & phone</p>
                    </div>
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F5F5F7]0 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <Crown class="w-7 h-7 text-white" />
                        </div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Step 2</div>
                        <h4 class="font-bold text-base mb-1">Unlock VIP</h4>
                        <p class="text-sm text-gray-500">Pay KSh {{ vipPrice.toLocaleString() }} OR refer a friend</p>
                    </div>
                    <div class="text-center">
                        <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <Bell class="w-7 h-7 text-white" />
                        </div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Step 3</div>
                        <h4 class="font-bold text-base mb-1">Get Notified First</h4>
                        <p class="text-sm text-gray-500">New stock, deals, price drops before anyone</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Cards -->
        <section class="py-8 md:py-12">
            <div class="container mx-auto px-4">
                <h3 class="text-xl md:text-2xl font-bold text-center mb-8">VIP Benefits</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-3xl mx-auto">
                    <div class="bg-white rounded-xl border border-[#E5E5EA] p-5  hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center mb-3">
                            <Bell class="w-5 h-5 text-[#1D1D1F]" />
                        </div>
                        <h4 class="font-bold text-sm mb-1">First to Know</h4>
                        <p class="text-sm text-gray-500">Get notified before anyone when new stock arrives</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E5E5EA] p-5  hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center mb-3">
                            <Tag class="w-5 h-5 text-black" />
                        </div>
                        <h4 class="font-bold text-sm mb-1">Exclusive Deals</h4>
                        <p class="text-sm text-gray-500">VIP-only discounts and flash sales</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E5E5EA] p-5  hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center mb-3">
                            <TrendingDown class="w-5 h-5 text-[#1D1D1F]" />
                        </div>
                        <h4 class="font-bold text-sm mb-1">Price Drop Alerts</h4>
                        <p class="text-sm text-gray-500">Instant notification when prices drop</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#E5E5EA] p-5  hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center mb-3">
                            <Headphones class="w-5 h-5 text-[#1D1D1F]" />
                        </div>
                        <h4 class="font-bold text-sm mb-1">Priority Support</h4>
                        <p class="text-sm text-gray-500">Direct WhatsApp line to our team</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration Form -->
        <section class="py-8 md:py-12 bg-[#F5F5F7]">
            <div class="container mx-auto px-4">
                <div class="max-w-lg mx-auto">
                    <!-- Success State -->
                    <div v-if="registrationSuccess" class="bg-white rounded-2xl shadow-lg border border-[#E5E5EA] p-6 md:p-8 text-center">
                        <div class="w-16 h-16 bg-[#F5F5F7] rounded-full flex items-center justify-center mx-auto mb-4">
                            <Check class="w-8 h-8 text-black" />
                        </div>
                        <h3 class="text-xl font-bold mb-2">You're In!</h3>
                        <p class="text-gray-600 text-sm mb-6">Welcome to TechMart KE VIP. Share your code to unlock full access.</p>

                        <div class="bg-[#F5F5F7] border border-[#E5E5EA] rounded-xl p-4 mb-4">
                            <p class="text-xs text-[#86868B] font-medium uppercase tracking-wider mb-1">Your Promo Code</p>
                            <div class="flex items-center justify-center gap-3">
                                <span class="text-2xl font-bold text-[#1D1D1F] tracking-wider">{{ registeredPromoCode }}</span>
                                <button
                                    @click="copyCode(registeredPromoCode)"
                                    class="p-2 rounded-lg hover:bg-[#F5F5F7] transition"
                                >
                                    <Check v-if="copied" class="w-5 h-5 text-black" />
                                    <Copy v-else class="w-5 h-5 text-[#86868B]" />
                                </button>
                            </div>
                            <p class="text-xs text-[#86868B] mt-2">Share it with friends!</p>
                        </div>

                        <button
                            @click="shareWhatsApp(registeredPromoCode)"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-black text-white rounded-full font-semibold hover:bg-[#1D1D1F] transition-all active:scale-[0.98]"
                        >
                            <MessageCircle class="w-5 h-5" />
                            Share on WhatsApp
                        </button>
                    </div>

                    <!-- Registration Form -->
                    <div v-else class="bg-white rounded-2xl shadow-lg border border-[#E5E5EA] p-6 md:p-8">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold mb-1">Join VIP Early Access</h3>
                            <p class="text-sm text-gray-500">Sign up in 30 seconds</p>
                        </div>

                        <form @submit.prevent="submitRegistration" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <User class="w-3.5 h-3.5 inline mr-1" />
                                    Name
                                </label>
                                <input
                                    v-model="registerForm.name"
                                    type="text"
                                    required
                                    placeholder="Your name"
                                    class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm transition"
                                />
                                <p v-if="registerForm.errors.name" class="text-red-500 text-xs mt-1">{{ registerForm.errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <Mail class="w-3.5 h-3.5 inline mr-1" />
                                    Email
                                </label>
                                <input
                                    v-model="registerForm.email"
                                    type="email"
                                    required
                                    placeholder="you@example.com"
                                    class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm transition"
                                />
                                <p v-if="registerForm.errors.email" class="text-red-500 text-xs mt-1">{{ registerForm.errors.email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <Phone class="w-3.5 h-3.5 inline mr-1" />
                                    Phone
                                </label>
                                <input
                                    v-model="registerForm.phone"
                                    type="tel"
                                    required
                                    placeholder="+254 7XX XXX XXX"
                                    class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm transition"
                                />
                                <p v-if="registerForm.errors.phone" class="text-red-500 text-xs mt-1">{{ registerForm.errors.phone }}</p>
                            </div>

                            <!-- Promo Code Collapsible -->
                            <div>
                                <button
                                    type="button"
                                    @click="showPromoInput = !showPromoInput"
                                    class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition"
                                >
                                    <Gift class="w-3.5 h-3.5" />
                                    Referred? Enter promo code
                                    <ChevronDown v-if="!showPromoInput" class="w-3.5 h-3.5" />
                                    <ChevronUp v-else class="w-3.5 h-3.5" />
                                </button>
                                <Transition
                                    enter-active-class="transition-all duration-200 ease-out"
                                    enter-from-class="opacity-0 max-h-0"
                                    enter-to-class="opacity-100 max-h-20"
                                    leave-active-class="transition-all duration-150 ease-in"
                                    leave-from-class="opacity-100 max-h-20"
                                    leave-to-class="opacity-0 max-h-0"
                                >
                                    <div v-if="showPromoInput" class="mt-2 overflow-hidden">
                                        <input
                                            v-model="registerForm.promo_code"
                                            type="text"
                                            placeholder="e.g. TM-ABCDE"
                                            class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm transition uppercase"
                                        />
                                        <p v-if="registerForm.errors.promo_code" class="text-red-500 text-xs mt-1">{{ registerForm.errors.promo_code }}</p>
                                    </div>
                                </Transition>
                            </div>

                            <!-- Notification Preferences -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Notify me about:</p>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model="registerForm.notify_new_stock" type="checkbox" class="rounded border-[#E5E5EA] text-black focus:ring-black/10" />
                                        <span class="text-sm text-gray-600">New Stock Arrivals</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model="registerForm.notify_deals" type="checkbox" class="rounded border-[#E5E5EA] text-black focus:ring-black/10" />
                                        <span class="text-sm text-gray-600">Exclusive Deals</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model="registerForm.notify_price_drops" type="checkbox" class="rounded border-[#E5E5EA] text-black focus:ring-black/10" />
                                        <span class="text-sm text-gray-600">Price Drops</span>
                                    </label>
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="registerForm.processing"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-black text-white rounded-full font-bold hover:bg-[#1D1D1F] transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed  text-base"
                            >
                                <Crown class="w-5 h-5" />
                                <span v-if="registerForm.processing">Signing up...</span>
                                <span v-else>Join VIP List — Free to Sign Up</span>
                            </button>

                            <p class="text-xs text-gray-400 text-center leading-relaxed">
                                Sign up is free. To unlock full VIP access, pay KSh {{ vipPrice.toLocaleString() }} or refer {{ referralsNeeded }} friends.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Activation Section -->
        <section class="py-8 md:py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-8">
                        <h3 class="text-xl md:text-2xl font-bold mb-1">Already signed up? Activate your VIP status</h3>
                        <p class="text-sm text-gray-500">Choose your path to full VIP access</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Path 1: Pay -->
                        <div class="bg-white rounded-2xl border border-[#E5E5EA] p-6 ">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center">
                                    <CreditCard class="w-5 h-5 text-[#1D1D1F]" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-base">Pay KSh {{ vipPrice.toLocaleString() }}</h4>
                                    <p class="text-xs text-gray-500">Instant activation</p>
                                </div>
                            </div>

                            <div class="bg-[#F5F5F7] rounded-xl p-4 mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">M-Pesa Instructions:</p>
                                <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                                    <li>Go to M-Pesa &gt; Lipa na M-Pesa &gt; Buy Goods</li>
                                    <li>Enter Till Number: <span class="font-bold text-black">XXXXXX</span></li>
                                    <li>Amount: <span class="font-bold text-black">KSh {{ vipPrice.toLocaleString() }}</span></li>
                                    <li>Enter your M-Pesa PIN and confirm</li>
                                </ol>
                            </div>

                            <form @submit.prevent="submitActivation" class="space-y-3">
                                <input
                                    v-model="activateForm.email"
                                    type="email"
                                    required
                                    placeholder="Email used during sign up"
                                    class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm"
                                />
                                <input
                                    v-model="activateForm.payment_reference"
                                    type="text"
                                    required
                                    placeholder="M-Pesa confirmation code (e.g. SHJ3XXXXXX)"
                                    class="w-full px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm"
                                />
                                <p v-if="activateForm.errors.email" class="text-red-500 text-xs">{{ activateForm.errors.email }}</p>
                                <p v-if="activateForm.errors.payment_reference" class="text-red-500 text-xs">{{ activateForm.errors.payment_reference }}</p>
                                <button
                                    type="submit"
                                    :disabled="activateForm.processing"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-full font-semibold hover:bg-[#1D1D1F] transition-all active:scale-[0.98] disabled:opacity-50 text-sm"
                                >
                                    <Zap class="w-4 h-4" />
                                    <span v-if="activateForm.processing">Activating...</span>
                                    <span v-else>Activate VIP</span>
                                </button>
                            </form>
                        </div>

                        <!-- Path 2: Refer -->
                        <div class="bg-white rounded-2xl border border-[#E5E5EA] p-6 ">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-[#F5F5F7] rounded-lg flex items-center justify-center">
                                    <Share2 class="w-5 h-5 text-[#86868B]" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-base">Refer {{ referralsNeeded }} Friends</h4>
                                    <p class="text-xs text-gray-500">Free activation</p>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">Share your promo code with friends. When they sign up using your code, you get closer to full VIP access.</p>

                            <!-- Code Display (shown when status result has a code) -->
                            <div class="bg-[#F5F5F7] border border-[#E5E5EA] rounded-xl p-4 mb-4">
                                <p class="text-xs text-[#86868B] font-medium mb-1">Your Promo Code</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-bold text-[#1D1D1F] tracking-wider flex-1">
                                        {{ statusResult?.promo_code || registeredPromoCode || 'Sign up to get your code' }}
                                    </span>
                                    <button
                                        v-if="statusResult?.promo_code || registeredPromoCode"
                                        @click="copyCode(statusResult?.promo_code || registeredPromoCode)"
                                        class="p-2 rounded-lg hover:bg-[#F5F5F7] transition"
                                    >
                                        <Check v-if="copied" class="w-4 h-4 text-black" />
                                        <Copy v-else class="w-4 h-4 text-[#86868B]" />
                                    </button>
                                </div>
                            </div>

                            <!-- Referral Progress -->
                            <div v-if="statusResult?.referral_count !== undefined" class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Referral Progress</span>
                                    <span class="font-bold">{{ statusResult.referral_count }}/{{ referralsNeeded }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div
                                        class="bg-[#F5F5F7]0 h-2.5 rounded-full transition-all duration-500"
                                        :style="{ width: Math.min((statusResult.referral_count / referralsNeeded) * 100, 100) + '%' }"
                                    ></div>
                                </div>
                            </div>

                            <button
                                v-if="statusResult?.promo_code || registeredPromoCode"
                                @click="shareWhatsApp(statusResult?.promo_code || registeredPromoCode)"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-black text-white rounded-full font-semibold hover:bg-[#1D1D1F] transition-all active:scale-[0.98] text-sm"
                            >
                                <MessageCircle class="w-4 h-4" />
                                Share on WhatsApp
                            </button>
                            <p v-else class="text-sm text-gray-400 text-center">Check your status below to see your promo code</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Check Status Section -->
        <section class="py-8 md:py-10 bg-[#F5F5F7]">
            <div class="container mx-auto px-4">
                <div class="max-w-md mx-auto text-center">
                    <h3 class="text-lg font-bold mb-3">Check your VIP status</h3>
                    <div class="flex gap-2">
                        <input
                            v-model="statusEmail"
                            type="email"
                            placeholder="Enter your email"
                            class="flex-1 px-4 py-2.5 border border-[#E5E5EA] rounded-xl focus:ring-2 focus:ring-black/10 focus:border-black text-sm"
                            @keyup.enter="checkStatus"
                        />
                        <button
                            @click="checkStatus"
                            :disabled="statusLoading || !statusEmail"
                            class="px-5 py-2.5 bg-black text-white rounded-full font-semibold hover:bg-gray-800 transition-all active:scale-[0.98] disabled:opacity-50 text-sm"
                        >
                            <span v-if="statusLoading">...</span>
                            <span v-else>Check</span>
                        </button>
                    </div>

                    <!-- Status Result -->
                    <div v-if="statusResult && !statusResult.error" class="mt-4 bg-white rounded-xl border border-[#E5E5EA] p-4 text-left">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-500">Status</span>
                                <div class="mt-0.5">
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                        :class="statusBadgeClass(statusResult.status)"
                                    >
                                        {{ statusResult.status }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500">Promo Code</span>
                                <p class="font-bold text-[#1D1D1F] mt-0.5">{{ statusResult.promo_code }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Referrals</span>
                                <p class="font-medium mt-0.5">{{ statusResult.referral_count || 0 }} / {{ referralsNeeded }}</p>
                            </div>
                            <div v-if="statusResult.expires_at">
                                <span class="text-gray-500">VIP Expires</span>
                                <p class="font-medium mt-0.5">{{ new Date(statusResult.expires_at).toLocaleDateString('en-KE', { year: 'numeric', month: 'short', day: 'numeric' }) }}</p>
                            </div>
                        </div>
                    </div>
                    <p v-if="statusResult?.error" class="mt-3 text-sm text-red-500">{{ statusResult.error }}</p>
                </div>
            </div>
        </section>

        <!-- Social Proof -->
        <section class="py-8 md:py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-md mx-auto text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <Users class="w-5 h-5 text-gray-700" />
                        <span class="text-2xl font-bold text-black">{{ vipCount.toLocaleString() }}+</span>
                    </div>
                    <p class="text-base font-semibold text-gray-700">VIP members already joined</p>
                    <p class="text-sm text-gray-500 mt-1">Join them and never miss a deal</p>
                    <a
                        href="#"
                        @click.prevent="document.querySelector('form')?.scrollIntoView({ behavior: 'smooth' })"
                        class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-black text-white rounded-full font-bold hover:bg-[#1D1D1F] transition-all active:scale-[0.98] "
                    >
                        <Crown class="w-5 h-5" />
                        Join VIP Now
                        <ArrowRight class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>
