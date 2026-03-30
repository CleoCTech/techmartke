<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    BookOpen, Star, ChevronRight, Plus, Send, User, Calendar, X
} from 'lucide-vue-next';

const props = defineProps({
    stories: { type: Object, required: true },
    products: { type: Array, default: () => [] },
});

const showForm = ref(false);

const form = useForm({
    title: '',
    content: '',
    customer_name: '',
    rating: 5,
    product_id: '',
});

const setRating = (val) => {
    form.rating = val;
};

const submitStory = () => {
    form.post('/community/stories', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <CustomerLayout>
        <!-- Breadcrumb -->
        <section class="bg-white border-b border-gray-100">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center gap-1 text-sm text-gray-500">
                    <Link href="/" class="hover:text-black transition cursor-pointer">Home</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <Link href="/community" class="hover:text-black transition cursor-pointer">Community</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <span class="text-black font-medium">Success Stories</span>
                </nav>
            </div>
        </section>

        <section class="py-8 md:py-12 bg-white">
            <div class="container mx-auto px-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-black">Success Stories</h2>
                    <button
                        @click="showForm = !showForm"
                        class="flex items-center gap-2 px-4 py-2 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97]"
                    >
                        <Plus class="w-4 h-4" />
                        Share Your Story
                    </button>
                </div>

                <!-- Submit Form (Collapsible) -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-if="showForm" class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-8">
                        <h3 class="font-bold text-black mb-4">Tell Your Story</h3>
                        <form @submit.prevent="submitStory" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="My experience with..."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                    required
                                />
                                <p v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Story</label>
                                <textarea
                                    v-model="form.content"
                                    rows="5"
                                    placeholder="Tell us about your experience..."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition resize-none cursor-text"
                                    required
                                ></textarea>
                                <p v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                    <input
                                        v-model="form.customer_name"
                                        type="text"
                                        placeholder="John Doe"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                        required
                                    />
                                    <p v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product (Optional)</label>
                                    <select
                                        v-model="form.product_id"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-pointer bg-white"
                                    >
                                        <option value="">Select a product</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <div class="flex gap-1">
                                    <button
                                        v-for="i in 5"
                                        :key="i"
                                        type="button"
                                        @click="setRating(i)"
                                        class="cursor-pointer transition-transform hover:scale-110"
                                    >
                                        <Star
                                            class="w-6 h-6"
                                            :class="i <= form.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
                                        />
                                    </button>
                                </div>
                                <p v-if="form.errors.rating" class="text-red-500 text-xs mt-1">{{ form.errors.rating }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97] disabled:opacity-50 flex items-center gap-2"
                                >
                                    <Send class="w-4 h-4" />
                                    {{ form.processing ? 'Submitting...' : 'Submit Story' }}
                                </button>
                                <button
                                    type="button"
                                    @click="showForm = false"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition cursor-pointer"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>

                <!-- Stories Grid -->
                <div v-if="stories.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="story in stories.data"
                        :key="story.id"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
                    >
                        <!-- Star Rating -->
                        <div class="flex gap-0.5 mb-3">
                            <Star
                                v-for="i in 5"
                                :key="i"
                                class="w-4 h-4"
                                :class="i <= story.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200'"
                            />
                        </div>
                        <h4 class="font-bold text-lg text-black mb-2 line-clamp-1">{{ story.title }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-4 mb-4">{{ story.content }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center">
                                    <User class="w-3.5 h-3.5 text-gray-500" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ story.customer_name }}</p>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <Calendar class="w-3 h-3" />
                                        {{ formatDate(story.purchase_date || story.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <span
                                v-if="story.product"
                                class="px-2 py-1 bg-gray-100 rounded-lg text-[11px] font-medium text-gray-600 truncate max-w-[100px]"
                            >
                                {{ story.product.name }}
                            </span>
                        </div>
                        <Link
                            :href="`/community/stories/${story.id}`"
                            class="inline-flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer"
                        >
                            Read Full Story
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
                <div v-else class="text-center py-16 text-gray-400">
                    <BookOpen class="w-16 h-16 mx-auto mb-4 opacity-30" />
                    <p class="text-lg font-medium">No stories yet</p>
                    <p class="text-sm mt-1">Share your experience with the community!</p>
                </div>

                <!-- Pagination -->
                <div v-if="stories.links?.length > 3" class="flex items-center justify-center gap-1 mt-8">
                    <template v-for="link in stories.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-3 py-2 text-sm rounded-lg transition cursor-pointer"
                            :class="link.active
                                ? 'bg-black text-white font-semibold'
                                : 'text-gray-600 hover:bg-gray-100'"
                            v-html="link.label"
                            preserve-scroll
                        />
                        <span
                            v-else
                            class="px-3 py-2 text-sm text-gray-300"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
