<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    Star, ChevronRight, User, Calendar, ArrowLeft, ShoppingCart
} from 'lucide-vue-next';

const props = defineProps({
    story: { type: Object, required: true },
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};
</script>

<template>
    <Head :title="story.title ? `${story.title} — TechMart Community` : 'Story — TechMart Community'" />
    <CustomerLayout>
        <!-- Breadcrumb -->
        <section class="bg-white border-b border-gray-100">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center gap-1 text-sm text-gray-500">
                    <Link href="/" class="hover:text-black transition cursor-pointer">Home</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <Link href="/community" class="hover:text-black transition cursor-pointer">Community</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <Link href="/community/stories" class="hover:text-black transition cursor-pointer">Success Stories</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <span class="text-black font-medium truncate max-w-[200px]">{{ story.title }}</span>
                </nav>
            </div>
        </section>

        <section class="py-8 md:py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto">
                    <!-- Story Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-10">
                        <!-- Star Rating -->
                        <div class="flex gap-1 mb-4">
                            <Star
                                v-for="i in 5"
                                :key="i"
                                class="w-5 h-5 md:w-6 md:h-6"
                                :class="i <= story.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200'"
                            />
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl md:text-3xl font-bold text-black mb-6">{{ story.title }}</h1>

                        <!-- Content -->
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line text-sm md:text-base mb-8">
                            {{ story.content }}
                        </div>

                        <!-- Author Info -->
                        <div class="border-t border-gray-100 pt-6 flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <User class="w-5 h-5 text-gray-500" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ story.customer_name }}</p>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <Calendar class="w-3.5 h-3.5" />
                                    {{ formatDate(story.purchase_date || story.created_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Product Link Card -->
                        <div
                            v-if="story.product"
                            class="mt-6 bg-gray-50 rounded-xl border border-gray-200 p-4 flex items-center justify-between gap-4"
                        >
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 mb-0.5">Related Product</p>
                                <p class="font-semibold text-black truncate">{{ story.product.name }}</p>
                                <p v-if="story.product.base_price || story.product.price" class="text-sm font-bold text-gray-900 mt-0.5">
                                    {{ formatPrice(story.product.base_price || story.product.price) }}
                                </p>
                            </div>
                            <Link
                                :href="`/products/${story.product.slug || story.product.id}`"
                                class="flex-shrink-0 flex items-center gap-2 px-4 py-2 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97]"
                            >
                                <ShoppingCart class="w-4 h-4" />
                                View Product
                            </Link>
                        </div>
                    </div>

                    <!-- Back Link -->
                    <Link
                        href="/community/stories"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-black mt-6 transition cursor-pointer"
                    >
                        <ArrowLeft class="w-4 h-4" />
                        Back to Stories
                    </Link>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>
