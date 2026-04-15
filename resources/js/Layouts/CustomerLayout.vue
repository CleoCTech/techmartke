<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Zap, ShoppingCart, Menu, X, Phone, Mail, MapPin } from 'lucide-vue-next';
import FloatingContactBar from '@/Components/Customer/FloatingContactBar.vue';
import LogoLoader from '@/Components/Customer/LogoLoader.vue';
import PwaPrompt from '@/Components/Customer/PwaPrompt.vue';
import { ref, computed, onMounted } from 'vue';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const { phoneDisplay, email, address, whatsappUrl } = useCompanyInfo();
const contactWhatsApp = computed(() => whatsappUrl("Hi TechMart KE! I'd like to get in touch."));

const page = usePage();
const currentUrl = computed(() => page.url);

const mobileMenuOpen = ref(false);
const cartCount = ref(0);
const scrolled = ref(false);

const updateCartCount = () => {
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        cartCount.value = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    } catch {
        cartCount.value = 0;
    }
};

onMounted(() => {
    updateCartCount();
    window.addEventListener('cart-updated', updateCartCount);
    window.addEventListener('scroll', () => {
        scrolled.value = window.scrollY > 10;
    });
});

const navLinks = [
    { label: 'Phones', href: '/products?category=phones', match: 'category=phones' },
    { label: 'Computers', href: '/products?category=computers', match: 'category=computers' },
    { label: 'New', href: '/products?condition=new', match: 'condition=new' },
    { label: 'Ex-UK', href: '/products?condition=ex-uk', match: 'condition=ex-uk' },
    { label: 'Trade-In', href: '/trade-in', match: '/trade-in', highlight: true },
    { label: 'Community', href: '/community', match: '/community' },
    { label: 'VIP', href: '/vip', match: '/vip' },
];

const isActive = (link) => {
    const url = currentUrl.value;
    if (link.match.startsWith('/')) {
        return url.startsWith(link.match);
    }
    return url.includes(link.match);
};
</script>

<template>
    <div class="min-h-screen bg-white">
        <LogoLoader />

        <!-- Header — monochrome, minimal -->
        <header
            :class="[
                'sticky top-0 z-30 transition-all duration-300 border-b',
                scrolled ? 'bg-white/95 backdrop-blur-xl border-gray-200/80' : 'bg-white border-transparent'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 md:py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-2.5 cursor-pointer group">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-black rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                            <Zap class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                        </div>
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight text-black">
                            TechMart<span class="text-[#86868B]">KE</span>
                        </span>
                    </Link>

                    <!-- Desktop Nav — monochrome with emerald Trade-In -->
                    <nav class="hidden lg:flex items-center gap-0.5">
                        <Link
                            v-for="link in navLinks"
                            :key="link.label"
                            :href="link.href"
                            class="px-4 py-2 text-[13px] font-medium rounded-full transition-all cursor-pointer"
                            :class="link.highlight
                                ? 'bg-black hover:bg-[#1D1D1F] text-white font-bold'
                                : (isActive(link)
                                    ? 'text-black font-semibold'
                                    : 'text-[#86868B] hover:text-black')"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <Link
                            href="/cart"
                            class="relative flex items-center gap-2 px-3 sm:px-4 py-2 bg-black text-white rounded-full hover:bg-[#1D1D1F] active:scale-[0.97] transition-all text-[13px] font-medium cursor-pointer"
                        >
                            <ShoppingCart class="w-4 h-4" />
                            <span class="hidden sm:inline">Cart</span>
                            <span
                                v-if="cartCount > 0"
                                class="bg-white text-black text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center"
                            >
                                {{ cartCount }}
                            </span>
                        </Link>

                        <button
                            class="lg:hidden p-2 hover:bg-[#F5F5F7] rounded-full transition cursor-pointer"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <X v-if="mobileMenuOpen" class="w-5 h-5 text-black" />
                            <Menu v-else class="w-5 h-5 text-black" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu — clean monochrome -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <nav v-if="mobileMenuOpen" class="lg:hidden mt-3 pb-3 border-t border-[#F5F5F7] pt-4">
                        <div class="flex flex-col gap-1">
                            <Link
                                v-for="link in navLinks"
                                :key="link.label"
                                :href="link.href"
                                class="px-4 py-3 text-[15px] rounded-xl transition-all cursor-pointer"
                                :class="link.highlight
                                    ? 'bg-black text-white font-bold text-center'
                                    : (isActive(link)
                                        ? 'text-black font-semibold bg-[#F5F5F7]'
                                        : 'text-[#1D1D1F] hover:bg-[#F5F5F7]')"
                                @click="mobileMenuOpen = false"
                            >
                                {{ link.label }}
                            </Link>
                        </div>
                    </nav>
                </Transition>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Floating Contact Bar -->
        <FloatingContactBar />

        <!-- PWA install & update prompts -->
        <PwaPrompt />

        <!-- Footer — dark, minimal. pb-20 on mobile to clear the sticky bar -->
        <footer class="bg-[#1D1D1F] text-white pt-12 md:pt-16 pb-20 md:pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 md:gap-8 mb-10 md:mb-12">
                    <!-- Brand -->
                    <div class="col-span-2 md:col-span-1 mb-2 md:mb-0">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                <Zap class="w-5 h-5 text-black" />
                            </div>
                            <span class="text-lg font-bold">TechMart<span class="text-[#86868B]">KE</span></span>
                        </div>
                        <p class="text-sm text-[#86868B] leading-relaxed">
                            Kenya's trusted phone & computer marketplace.
                        </p>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-3 md:mb-4 text-sm text-[#F5F5F7]">Phones</h5>
                        <ul class="space-y-2.5 text-sm text-[#86868B]">
                            <li><Link href="/products?brand=apple" class="hover:text-white transition cursor-pointer">iPhone</Link></li>
                            <li><Link href="/products?brand=samsung" class="hover:text-white transition cursor-pointer">Samsung</Link></li>
                            <li><Link href="/products?brand=google" class="hover:text-white transition cursor-pointer">Google Pixel</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-3 md:mb-4 text-sm text-[#F5F5F7]">Computers</h5>
                        <ul class="space-y-2.5 text-sm text-[#86868B]">
                            <li><Link href="/products?category=macbooks" class="hover:text-white transition cursor-pointer">MacBooks</Link></li>
                            <li><Link href="/products?category=windows-laptops" class="hover:text-white transition cursor-pointer">Windows Laptops</Link></li>
                            <li><Link href="/products?category=computers" class="hover:text-white transition cursor-pointer">All Computers</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-3 md:mb-4 text-sm text-[#F5F5F7]">Quick Links</h5>
                        <ul class="space-y-2.5 text-sm text-[#86868B]">
                            <li><Link href="/trade-in" class="hover:text-white transition cursor-pointer">Trade-In</Link></li>
                            <li><Link href="/community" class="hover:text-white transition cursor-pointer">Community</Link></li>
                            <li><Link href="/vip" class="hover:text-white transition cursor-pointer">VIP Program</Link></li>
                            <li>
                                <a :href="contactWhatsApp" target="_blank" rel="noopener" class="hover:text-white transition cursor-pointer">
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-3 md:mb-4 text-sm text-[#F5F5F7]">Contact</h5>
                        <ul class="space-y-2.5 text-sm text-[#86868B]">
                            <li class="flex items-center gap-2">
                                <Phone class="w-3.5 h-3.5 flex-shrink-0" />
                                {{ phoneDisplay }}
                            </li>
                            <li class="flex items-center gap-2">
                                <Mail class="w-3.5 h-3.5 flex-shrink-0" />
                                {{ email }}
                            </li>
                            <li class="flex items-start gap-2">
                                <MapPin class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" />
                                {{ address }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#86868B]">
                    <p>&copy; 2025 TechMart KE. All rights reserved.</p>
                    <p>Smart Shopping, Better Decisions</p>
                </div>
            </div>
        </footer>
    </div>
</template>
