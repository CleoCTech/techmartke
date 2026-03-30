<script setup>
import { Link } from '@inertiajs/vue3';

const columns = [
    {
        title: 'Phones',
        links: [
            { label: 'iPhone', href: '/products?brand=apple' },
            { label: 'Samsung', href: '/products?brand=samsung' },
            { label: 'Google Pixel', href: '/products?brand=google' },
        ],
    },
    {
        title: 'Computers',
        links: [
            { label: 'MacBooks', href: '/products?brand=apple&category=computers' },
            { label: 'Windows Laptops', href: '/products?category=laptops' },
            { label: 'Desktops', href: '/products?category=desktops' },
        ],
    },
    {
        title: 'Support',
        links: [
            { label: 'Contact Us', href: '/contact' },
            { label: 'Shipping Info', href: '/shipping' },
            { label: 'Returns', href: '/returns' },
        ],
    },
    {
        title: 'About',
        links: [
            { label: 'Our Story', href: '/about' },
            { label: 'Warranty', href: '/warranty' },
            { label: 'Terms', href: '/terms' },
        ],
    },
];

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-gradient-to-b from-gray-900 to-black text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <!-- Links Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div v-for="column in columns" :key="column.title">
                    <h5 class="font-bold mb-4 text-lg">{{ column.title }}</h5>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li v-for="link in column.links" :key="link.href">
                            <Link :href="link.href" class="hover:text-white transition">
                                {{ link.label }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
                <p>&copy; {{ currentYear }} TechMart KE - Smart Shopping, Better Decisions</p>
            </div>
        </div>
    </footer>
</template>
