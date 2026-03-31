<script setup>
import { Link } from '@inertiajs/vue3';
import { Zap, ShoppingCart, Menu, X, Phone, Mail, MapPin } from 'lucide-vue-next';
import FloatingContactBar from '@/Components/Customer/FloatingContactBar.vue';
import { ref, onMounted } from 'vue';

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
    { label: 'Phones', href: '/products?category=phones' },
    { label: 'Computers', href: '/products?category=computers' },
    { label: 'New', href: '/products?condition=new' },
    { label: 'Ex-UK', href: '/products?condition=ex-uk' },
    { label: 'Community', href: '/community' },
];
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header
            :class="[
                'sticky top-0 z-30 transition-all duration-300',
                scrolled ? 'bg-white/95 backdrop-blur-md shadow-md' : 'bg-white shadow-sm'
            ]"
        >
            <div class="container mx-auto px-4 py-3 md:py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-2 sm:gap-3 cursor-pointer group">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-black rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                            <Zap class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                            TechMart<span class="text-gray-400">KE</span>
                        </h1>
                    </Link>

                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex gap-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.label"
                            :href="link.href"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-50 rounded-lg transition-all cursor-pointer"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <Link
                            href="/cart"
                            class="relative px-3 sm:px-5 py-2 sm:py-2.5 bg-black text-white rounded-xl hover:bg-gray-800 active:scale-[0.97] transition-all text-sm font-medium flex items-center gap-2 cursor-pointer shadow-sm hover:shadow-md"
                        >
                            <ShoppingCart class="w-4 h-4" />
                            <span class="hidden sm:inline">Cart</span>
                            <span
                                v-if="cartCount > 0"
                                class="bg-white text-black text-[10px] sm:text-xs font-bold rounded-full min-w-[18px] sm:min-w-[20px] h-[18px] sm:h-[20px] flex items-center justify-center"
                            >
                                {{ cartCount }}
                            </span>
                            <span v-else class="text-xs">(0)</span>
                        </Link>

                        <button
                            class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition cursor-pointer"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <X v-if="mobileMenuOpen" class="w-5 h-5" />
                            <Menu v-else class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <nav v-if="mobileMenuOpen" class="md:hidden mt-3 pb-3 border-t pt-3">
                        <div class="grid grid-cols-2 gap-2">
                            <Link
                                v-for="link in navLinks"
                                :key="link.label"
                                :href="link.href"
                                class="px-4 py-3 text-sm font-medium text-gray-700 hover:text-black hover:bg-gray-50 rounded-xl transition-all cursor-pointer text-center border border-gray-100"
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
        <main class="pb-16 md:pb-0">
            <slot />
        </main>

        <!-- Floating Contact Bar (mobile bottom bar + desktop side buttons) -->
        <FloatingContactBar />

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-gray-900 to-black text-white pt-12 md:pt-16 pb-6 mt-12 md:mt-16">
            <div class="container mx-auto px-4">
                <!-- Top section -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 md:gap-8 mb-10 md:mb-12">
                    <!-- Brand column -->
                    <div class="col-span-2 md:col-span-1 mb-2 md:mb-0">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                <Zap class="w-5 h-5 text-black" />
                            </div>
                            <span class="text-lg font-bold">TechMart<span class="text-gray-500">KE</span></span>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Smart shopping, better decisions. Kenya's trusted phone & computer marketplace.
                        </p>
                    </div>

                    <div>
                        <h5 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Phones</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/products?brand=apple" class="hover:text-white transition cursor-pointer">iPhone</Link></li>
                            <li><Link href="/products?brand=samsung" class="hover:text-white transition cursor-pointer">Samsung</Link></li>
                            <li><Link href="/products?brand=google" class="hover:text-white transition cursor-pointer">Google Pixel</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Computers</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/products?category=macbooks" class="hover:text-white transition cursor-pointer">MacBooks</Link></li>
                            <li><Link href="/products?category=windows-laptops" class="hover:text-white transition cursor-pointer">Windows Laptops</Link></li>
                            <li><Link href="/products?category=desktops" class="hover:text-white transition cursor-pointer">Desktops</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Support</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/contact" class="hover:text-white transition cursor-pointer">Contact Us</Link></li>
                            <li><Link href="/shipping" class="hover:text-white transition cursor-pointer">Shipping Info</Link></li>
                            <li><Link href="/returns" class="hover:text-white transition cursor-pointer">Returns</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Contact</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li class="flex items-center gap-2">
                                <Phone class="w-3.5 h-3.5 flex-shrink-0" />
                                +254 700 000 000
                            </li>
                            <li class="flex items-center gap-2">
                                <Mail class="w-3.5 h-3.5 flex-shrink-0" />
                                info@techmart.ke
                            </li>
                            <li class="flex items-start gap-2">
                                <MapPin class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" />
                                Nairobi, Kenya
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                    <p>&copy; 2025 TechMart KE. All rights reserved.</p>
                    <p>Smart Shopping, Better Decisions</p>
                </div>
            </div>
        </footer>
    </div>
</template>
