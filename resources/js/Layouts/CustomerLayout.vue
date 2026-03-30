<script setup>
import { Link } from '@inertiajs/vue3';
import { Zap, ShoppingCart, Menu, X } from 'lucide-vue-next';
import { ref, onMounted, computed } from 'vue';

const mobileMenuOpen = ref(false);
const cartCount = ref(0);

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
});

const navLinks = [
    { label: 'Phones', href: '/products?category=phones' },
    { label: 'Computers', href: '/products?category=computers' },
    { label: 'New', href: '/products?condition=new' },
    { label: 'Ex-UK', href: '/products?condition=ex-uk' },
];
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <Link href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                            <Zap class="w-6 h-6 text-white" />
                        </div>
                        <h1 class="text-2xl font-bold">TechMart<span class="text-gray-400">KE</span></h1>
                    </Link>

                    <nav class="hidden md:flex gap-6 text-sm font-medium">
                        <Link
                            v-for="link in navLinks"
                            :key="link.label"
                            :href="link.href"
                            class="hover:text-gray-600 transition"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>

                    <div class="flex items-center gap-3">
                        <Link
                            href="/cart"
                            class="px-5 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium flex items-center gap-2"
                        >
                            <ShoppingCart class="w-4 h-4" />
                            Cart ({{ cartCount }})
                        </Link>

                        <button
                            class="md:hidden p-2"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <X v-if="mobileMenuOpen" class="w-6 h-6" />
                            <Menu v-else class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <nav v-if="mobileMenuOpen" class="md:hidden mt-4 pb-2 border-t pt-4 space-y-3">
                    <Link
                        v-for="link in navLinks"
                        :key="link.label"
                        :href="link.href"
                        class="block text-sm font-medium hover:text-gray-600 transition"
                        @click="mobileMenuOpen = false"
                    >
                        {{ link.label }}
                    </Link>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-gray-900 to-black text-white py-12 mt-16">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h5 class="font-bold mb-4 text-lg">Phones</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/products?brand=apple" class="hover:text-white transition">iPhone</Link></li>
                            <li><Link href="/products?brand=samsung" class="hover:text-white transition">Samsung</Link></li>
                            <li><Link href="/products?brand=google" class="hover:text-white transition">Google Pixel</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-4 text-lg">Computers</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/products?type=macbooks" class="hover:text-white transition">MacBooks</Link></li>
                            <li><Link href="/products?type=windows-laptops" class="hover:text-white transition">Windows Laptops</Link></li>
                            <li><Link href="/products?type=desktops" class="hover:text-white transition">Desktops</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-4 text-lg">Support</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/contact" class="hover:text-white transition">Contact Us</Link></li>
                            <li><Link href="/shipping" class="hover:text-white transition">Shipping Info</Link></li>
                            <li><Link href="/returns" class="hover:text-white transition">Returns</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold mb-4 text-lg">About</h5>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/about" class="hover:text-white transition">Our Story</Link></li>
                            <li><Link href="/warranty" class="hover:text-white transition">Warranty</Link></li>
                            <li><Link href="/terms" class="hover:text-white transition">Terms</Link></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm">
                    <p>&copy; 2025 TechMart KE - Smart Shopping, Better Decisions</p>
                </div>
            </div>
        </footer>
    </div>
</template>
