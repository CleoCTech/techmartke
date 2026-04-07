<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    Camera, BookOpen, MessageCircle, Star, Heart,
    ChevronRight, ChevronDown, Award, ThumbsUp, Plus,
    ArrowRight, User, Calendar
} from 'lucide-vue-next';

const props = defineProps({
    featuredPhotos: { type: Array, default: () => [] },
    recentPhotos: { type: Array, default: () => [] },
    featuredStories: { type: Array, default: () => [] },
    recentQuestions: { type: Array, default: () => [] },
    stats: {
        type: Object,
        default: () => ({
            total_photos: 0,
            total_stories: 0,
            total_questions: 0,
            total_answers: 0,
        }),
    },
});

const expandedQuestion = ref(null);

const toggleQuestion = (id) => {
    expandedQuestion.value = expandedQuestion.value === id ? null : id;
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const allPhotos = [...(props.featuredPhotos || []), ...(props.recentPhotos || [])];
</script>

<template>
    <Head title="Community — Photos, Stories & Q&A" />
    <CustomerLayout>
        <!-- Hero Banner -->
        <section class="bg-white py-8 md:py-12 border-b border-gray-100">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl md:text-4xl font-bold text-black mb-2">Community</h2>
                    <p class="text-gray-600 text-sm md:text-base mb-6">
                        See what our customers are saying
                    </p>
                    <div class="flex items-center justify-center gap-6 md:gap-10 flex-wrap">
                        <div class="text-center">
                            <p class="text-xl md:text-2xl font-bold text-black">{{ stats.total_photos }}</p>
                            <p class="text-xs text-gray-500">Total Photos</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl md:text-2xl font-bold text-black">{{ stats.total_stories }}</p>
                            <p class="text-xs text-gray-500">Success Stories</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl md:text-2xl font-bold text-black">{{ stats.total_questions }}</p>
                            <p class="text-xs text-gray-500">Questions</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl md:text-2xl font-bold text-black">{{ stats.total_answers }}</p>
                            <p class="text-xs text-gray-500">Answers</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Photo Gallery Section -->
        <section class="py-8 md:py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl md:text-2xl font-bold text-black">Customer Gallery</h3>
                    <Link
                        href="/community/photos"
                        class="flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer group"
                    >
                        View All
                        <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>
                <div v-if="allPhotos.length" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div
                        v-for="photo in allPhotos.slice(0, 8)"
                        :key="photo.id"
                        class="group relative rounded-xl overflow-hidden cursor-pointer aspect-square"
                    >
                        <img
                            :src="photo.image_url"
                            :alt="photo.caption || 'Customer photo'"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex items-end p-3">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white w-full">
                                <p v-if="photo.caption" class="text-sm font-medium truncate">{{ photo.caption }}</p>
                                <p class="text-xs text-white/70">{{ photo.customer_name }}</p>
                                <span
                                    v-if="photo.product"
                                    class="inline-block mt-1 px-2 py-0.5 bg-white/20 rounded text-[10px] font-medium truncate max-w-full"
                                >
                                    {{ photo.product.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 text-gray-400">
                    <Camera class="w-12 h-12 mx-auto mb-3 opacity-30" />
                    <p class="text-sm">No photos yet. Be the first to share!</p>
                </div>
            </div>
        </section>

        <!-- Success Stories Section -->
        <section class="py-8 md:py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl md:text-2xl font-bold text-black">Success Stories</h3>
                    <Link
                        href="/community/stories"
                        class="flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer group"
                    >
                        View All
                        <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>
                <div v-if="featuredStories.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="story in featuredStories.slice(0, 3)"
                        :key="story.id"
                        class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
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
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ story.content }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ story.customer_name }}</p>
                                <p class="text-xs text-gray-500">{{ formatDate(story.purchase_date) }}</p>
                            </div>
                            <span
                                v-if="story.product"
                                class="px-2 py-1 bg-gray-100 rounded-lg text-[11px] font-medium text-gray-600 truncate max-w-[120px]"
                            >
                                {{ story.product.name }}
                            </span>
                        </div>
                        <Link
                            :href="`/community/stories/${story.id}`"
                            class="inline-flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black mt-4 transition cursor-pointer"
                        >
                            Read More
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
                <div v-else class="text-center py-12 text-gray-400">
                    <BookOpen class="w-12 h-12 mx-auto mb-3 opacity-30" />
                    <p class="text-sm">No stories yet. Share yours!</p>
                </div>
            </div>
        </section>

        <!-- Q&A Section -->
        <section class="py-8 md:py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl md:text-2xl font-bold text-black">Community Q&A</h3>
                    <Link
                        href="/community/questions"
                        class="flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer group"
                    >
                        View All
                        <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>
                <div v-if="recentQuestions.length" class="space-y-3 max-w-3xl">
                    <div
                        v-for="question in recentQuestions.slice(0, 5)"
                        :key="question.id"
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden transition-all duration-200"
                    >
                        <button
                            @click="toggleQuestion(question.id)"
                            class="w-full text-left p-5 flex items-start gap-3 cursor-pointer hover:bg-gray-50 transition"
                        >
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-black text-sm md:text-base">{{ question.question }}</p>
                                <div class="flex items-center gap-3 mt-2 flex-wrap">
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <User class="w-3 h-3" />
                                        {{ question.asked_by }}
                                    </span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                        {{ question.answers_count || question.answers?.length || 0 }} answers
                                    </span>
                                    <span
                                        v-if="question.product"
                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium truncate max-w-[140px]"
                                    >
                                        {{ question.product.name }}
                                    </span>
                                </div>
                            </div>
                            <ChevronDown
                                class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5 transition-transform duration-200"
                                :class="{ 'rotate-180': expandedQuestion === question.id }"
                            />
                        </button>
                        <div v-if="expandedQuestion === question.id && question.answers?.length" class="px-5 pb-4 border-t border-gray-100">
                            <div
                                v-for="answer in question.answers.slice(0, 1)"
                                :key="answer.id"
                                class="ml-4 border-l-2 border-gray-200 pl-4 py-3"
                            >
                                <p class="text-sm text-gray-700 line-clamp-3">{{ answer.answer }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ answer.answered_by }}</p>
                            </div>
                            <Link
                                :href="`/community/questions?expanded=${question.id}`"
                                class="inline-block text-xs font-semibold text-gray-500 hover:text-black mt-2 ml-4 transition cursor-pointer"
                            >
                                View all answers
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 text-gray-400">
                    <MessageCircle class="w-12 h-12 mx-auto mb-3 opacity-30" />
                    <p class="text-sm">No questions yet. Ask the community!</p>
                </div>
            </div>
        </section>

        <!-- Submit CTA -->
        <section class="py-8 md:py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                    <Link
                        href="/community/photos"
                        class="bg-white rounded-2xl border border-gray-100 p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer group"
                    >
                        <div class="w-12 h-12 bg-gray-50 group-hover:bg-black rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                            <Camera class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-black mb-1">Share a Photo</h4>
                        <p class="text-xs text-gray-500">Show off your new device</p>
                    </Link>
                    <Link
                        href="/community/stories"
                        class="bg-white rounded-2xl border border-gray-100 p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer group"
                    >
                        <div class="w-12 h-12 bg-gray-50 group-hover:bg-black rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                            <BookOpen class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-black mb-1">Tell Your Story</h4>
                        <p class="text-xs text-gray-500">Share your experience</p>
                    </Link>
                    <Link
                        href="/community/questions"
                        class="bg-white rounded-2xl border border-gray-100 p-6 text-center hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer group"
                    >
                        <div class="w-12 h-12 bg-gray-50 group-hover:bg-black rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                            <MessageCircle class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-black mb-1">Ask a Question</h4>
                        <p class="text-xs text-gray-500">Get help from the community</p>
                    </Link>
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
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
