<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, Menu, X } from 'lucide-vue-next';

const cartCount = ref(0);
const mobileMenuOpen = ref(false);

const updateCartCount = () => {
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        cartCount.value = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    } catch {
        cartCount.value = 0;
    }
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

onMounted(() => {
    updateCartCount();
    window.addEventListener('cart-updated', updateCartCount);
});

onUnmounted(() => {
    window.removeEventListener('cart-updated', updateCartCount);
});

const navLinks = [
    { label: 'Phones', href: '/products?category=phones' },
    { label: 'Computers', href: '/products?category=computers' },
    { label: 'New', href: '/products?condition=new' },
    { label: 'Ex-UK', href: '/products?condition=ex-uk' },
];
</script>

<template>
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold">TechMart<span class="text-gray-400">KE</span></h1>
                </Link>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex gap-6 text-sm font-medium">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="hover:text-gray-600 transition"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <div class="flex items-center gap-3">
                    <!-- Cart Button -->
                    <Link
                        href="/cart"
                        class="px-5 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium flex items-center gap-2 relative"
                    >
                        <ShoppingCart class="w-4 h-4" />
                        <span>Cart</span>
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"
                        >
                            {{ cartCount > 99 ? '99+' : cartCount }}
                        </span>
                    </Link>

                    <!-- Mobile Menu Toggle -->
                    <button
                        @click="toggleMobileMenu"
                        class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition"
                    >
                        <X v-if="mobileMenuOpen" class="w-6 h-6" />
                        <Menu v-else class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <nav v-if="mobileMenuOpen" class="md:hidden mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-col gap-3">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="link.href"
                            class="text-sm font-medium py-2 hover:text-gray-600 transition"
                            @click="mobileMenuOpen = false"
                        >
                            {{ link.label }}
                        </Link>
                    </div>
                </nav>
            </Transition>
        </div>
    </header>
</template>
