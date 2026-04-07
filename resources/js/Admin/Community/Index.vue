<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Camera, BookOpen, MessageCircle, CheckCircle, XCircle, Star, Eye, Trash2, AlertCircle,
    Pencil, Send, Globe, GlobeLock
} from 'lucide-vue-next';

const page = usePage();

const props = defineProps({
    tab: { type: String, default: 'photos' },
    pendingPhotos: { type: Array, default: () => [] },
    approvedPhotos: { type: Array, default: () => [] },
    pendingStories: { type: Array, default: () => [] },
    approvedStories: { type: Array, default: () => [] },
    pendingQuestions: { type: Array, default: () => [] },
    answeredQuestions: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({}) },
});

const activeTab = ref(props.tab);

const switchTab = (tab) => {
    activeTab.value = tab;
};

const approvePhoto = (id) => router.post(`/admin/community/photos/${id}/approve`);
const rejectPhoto = (id) => { if (confirm('Delete this photo?')) router.delete(`/admin/community/photos/${id}/reject`); };
const featurePhoto = (id) => router.post(`/admin/community/photos/${id}/feature`);

const approveStory = (id) => router.post(`/admin/community/stories/${id}/approve`);
const rejectStory = (id) => { if (confirm('Delete this story?')) router.delete(`/admin/community/stories/${id}/reject`); };
const featureStory = (id) => router.post(`/admin/community/stories/${id}/feature`);

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-KE', { month: 'short', day: 'numeric', year: 'numeric' }) : '';

// Q&A management
const editingQuestion = ref(null);
const editForm = ref({ question: '', admin_answer: '' });

const startEditQuestion = (q) => {
    editingQuestion.value = q.id;
    editForm.value = { question: q.question, admin_answer: q.admin_answer || '' };
};
const cancelEditQuestion = () => { editingQuestion.value = null; };

const saveQuestion = (id) => {
    router.put(`/admin/community/questions/${id}`, editForm.value, {
        preserveScroll: true,
        onSuccess: () => { editingQuestion.value = null; },
    });
};
const publishQuestion = (id) => router.post(`/admin/community/questions/${id}/publish`, {}, { preserveScroll: true });
const deleteQuestion = (id) => { if (confirm('Delete this question?')) router.delete(`/admin/community/questions/${id}`, { preserveScroll: true }); };
</script>

<template>
    <Head title="Community Management" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Community Management</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Approve, reject and feature community content</p>
        </div>

        <!-- Flash -->
        <div v-if="page.props.flash?.success" class="mb-4 flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
            <CheckCircle class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0" />
            <p class="text-sm text-green-700 dark:text-green-400">{{ page.props.flash.success }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold" :class="stats.pendingPhotos ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">{{ stats.pendingPhotos }}</div>
                <div class="text-xs text-slate-500 mt-1">Pending Photos</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold" :class="stats.pendingStories ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">{{ stats.pendingStories }}</div>
                <div class="text-xs text-slate-500 mt-1">Pending Stories</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold" :class="stats.pendingQuestions ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">{{ stats.pendingQuestions }}</div>
                <div class="text-xs text-slate-500 mt-1">Unanswered Q&A</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ stats.totalPhotos }}</div>
                <div class="text-xs text-slate-500 mt-1">Total Photos</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ stats.totalStories }}</div>
                <div class="text-xs text-slate-500 mt-1">Total Stories</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-slate-200 dark:border-slate-700 mb-6">
            <nav class="flex gap-6">
                <button
                    v-for="t in [
                        { key: 'photos', label: 'Photos', icon: Camera, badge: stats.pendingPhotos },
                        { key: 'stories', label: 'Stories', icon: BookOpen, badge: stats.pendingStories },
                        { key: 'questions', label: 'Q&A', icon: MessageCircle, badge: stats.pendingQuestions },
                    ]"
                    :key="t.key"
                    @click="switchTab(t.key)"
                    class="relative pb-3 text-sm font-medium transition flex items-center gap-2"
                    :class="activeTab === t.key ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'"
                >
                    <component :is="t.icon" class="w-4 h-4" />
                    {{ t.label }}
                    <span v-if="t.badge" class="bg-amber-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ t.badge }}</span>
                </button>
            </nav>
        </div>

        <!-- PHOTOS TAB -->
        <div v-if="activeTab === 'photos'">
            <!-- Pending -->
            <div v-if="pendingPhotos.length" class="mb-8">
                <h3 class="text-sm font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <AlertCircle class="w-4 h-4" /> Pending Approval ({{ pendingPhotos.length }})
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="photo in pendingPhotos" :key="photo.id" class="bg-white dark:bg-slate-800 rounded-xl border-2 border-amber-200 dark:border-amber-500/30 overflow-hidden">
                        <div class="aspect-square bg-slate-100 dark:bg-slate-700">
                            <img :src="photo.image_url" :alt="photo.caption" class="w-full h-full object-cover" />
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ photo.customer_name }}</p>
                            <p v-if="photo.caption" class="text-xs text-slate-500 truncate mt-0.5">{{ photo.caption }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">{{ formatDate(photo.created_at) }}</p>
                            <div class="flex gap-2 mt-3">
                                <button @click="approvePhoto(photo.id)" class="flex-1 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1">
                                    <CheckCircle class="w-3.5 h-3.5" /> Approve
                                </button>
                                <button @click="rejectPhoto(photo.id)" class="flex-1 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1">
                                    <XCircle class="w-3.5 h-3.5" /> Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div>
                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Approved ({{ approvedPhotos.length }})</h3>
                <div v-if="approvedPhotos.length" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    <div v-for="photo in approvedPhotos" :key="photo.id" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden group relative">
                        <div class="aspect-square bg-slate-100 dark:bg-slate-700">
                            <img :src="photo.image_url" :alt="photo.caption" class="w-full h-full object-cover" />
                        </div>
                        <div class="p-2">
                            <p class="text-xs text-slate-600 dark:text-slate-300 truncate">{{ photo.customer_name }}</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <button @click="featurePhoto(photo.id)" class="text-[10px] px-2 py-0.5 rounded-full font-semibold transition"
                                    :class="photo.is_featured ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 hover:bg-yellow-50'">
                                    <Star class="w-3 h-3 inline -mt-0.5" /> {{ photo.is_featured ? 'Featured' : 'Feature' }}
                                </button>
                                <button @click="rejectPhoto(photo.id)" class="text-red-400 hover:text-red-600 transition">
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-slate-400 text-center py-8">No approved photos yet</p>
            </div>
        </div>

        <!-- STORIES TAB -->
        <div v-if="activeTab === 'stories'">
            <!-- Pending -->
            <div v-if="pendingStories.length" class="mb-8">
                <h3 class="text-sm font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <AlertCircle class="w-4 h-4" /> Pending Approval ({{ pendingStories.length }})
                </h3>
                <div class="space-y-4">
                    <div v-for="story in pendingStories" :key="story.id" class="bg-white dark:bg-slate-800 rounded-xl border-2 border-amber-200 dark:border-amber-500/30 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-yellow-500 text-sm">{{ '★'.repeat(story.rating) }}{{ '☆'.repeat(5 - story.rating) }}</span>
                                </div>
                                <h4 class="font-bold text-slate-800 dark:text-slate-100 text-base">{{ story.title }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 whitespace-pre-line line-clamp-4">{{ story.content }}</p>
                                <p class="text-xs text-slate-400 mt-2">By <strong>{{ story.customer_name }}</strong> · {{ formatDate(story.created_at) }}</p>
                            </div>
                            <div class="flex flex-col gap-2 shrink-0">
                                <button @click="approveStory(story.id)" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                    <CheckCircle class="w-3.5 h-3.5" /> Approve
                                </button>
                                <button @click="rejectStory(story.id)" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                    <XCircle class="w-3.5 h-3.5" /> Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div>
                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Approved ({{ approvedStories.length }})</h3>
                <div v-if="approvedStories.length" class="space-y-3">
                    <div v-for="story in approvedStories" :key="story.id" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h4 class="font-semibold text-sm text-slate-800 dark:text-slate-100 truncate">{{ story.title }}</h4>
                                <span class="text-yellow-500 text-xs">{{ '★'.repeat(story.rating) }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ story.customer_name }} · {{ formatDate(story.created_at) }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button @click="featureStory(story.id)" class="text-[10px] px-2 py-0.5 rounded-full font-semibold transition"
                                :class="story.is_featured ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 hover:bg-yellow-50'">
                                <Star class="w-3 h-3 inline -mt-0.5" /> {{ story.is_featured ? 'Featured' : 'Feature' }}
                            </button>
                            <button @click="rejectStory(story.id)" class="text-red-400 hover:text-red-600 transition">
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-slate-400 text-center py-8">No approved stories yet</p>
            </div>
        </div>

        <!-- Q&A TAB -->
        <div v-if="activeTab === 'questions'">
            <!-- Pending / Unpublished Questions -->
            <div v-if="pendingQuestions.length" class="mb-8">
                <h3 class="text-sm font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <AlertCircle class="w-4 h-4" /> Pending Review ({{ pendingQuestions.length }})
                </h3>
                <div class="space-y-4">
                    <div v-for="q in pendingQuestions" :key="q.id" class="bg-white dark:bg-slate-800 rounded-xl border-2 border-amber-200 dark:border-amber-500/30 p-5">
                        <!-- View mode -->
                        <template v-if="editingQuestion !== q.id">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100 mb-1">{{ q.question }}</p>
                            <p class="text-xs text-slate-400 mb-3">Asked by <strong>{{ q.asked_by }}</strong> · {{ formatDate(q.created_at) }}
                                <span v-if="q.product" class="ml-1 px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 rounded text-[10px]">{{ q.product?.name }}</span>
                            </p>
                            <div v-if="q.admin_answer" class="ml-4 pl-3 border-l-2 border-green-400 mb-3">
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ q.admin_answer }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">Staff answer</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button @click="startEditQuestion(q)" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center gap-1">
                                    <Pencil class="w-3.5 h-3.5" /> Edit & Answer
                                </button>
                                <button @click="publishQuestion(q.id)" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                    <Globe class="w-3.5 h-3.5" /> Publish
                                </button>
                                <button @click="deleteQuestion(q.id)" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                    <Trash2 class="w-3.5 h-3.5" /> Delete
                                </button>
                            </div>
                        </template>

                        <!-- Edit mode -->
                        <template v-else>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Customer Question</label>
                                    <textarea v-model="editForm.question" rows="2"
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Your Answer (Staff)</label>
                                    <textarea v-model="editForm.admin_answer" rows="3" placeholder="Type your answer here..."
                                        class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    ></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="saveQuestion(q.id)" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                                        <Send class="w-3.5 h-3.5" /> Save
                                    </button>
                                    <button @click="cancelEditQuestion" class="px-4 py-1.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Published Questions -->
            <div>
                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Published ({{ answeredQuestions.length }})</h3>
                <div v-if="answeredQuestions.length" class="space-y-3">
                    <div v-for="q in answeredQuestions" :key="q.id" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ q.question }}</p>
                                <div v-if="q.admin_answer" class="ml-4 pl-3 border-l-2 border-green-400 mt-2">
                                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ q.admin_answer }}</p>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">{{ q.asked_by }} · {{ formatDate(q.created_at) }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button @click="startEditQuestion(q)" class="text-slate-400 hover:text-indigo-500 transition">
                                    <Pencil class="w-3.5 h-3.5" />
                                </button>
                                <button @click="publishQuestion(q.id)" class="text-green-500 hover:text-amber-500 transition" title="Unpublish">
                                    <GlobeLock class="w-3.5 h-3.5" />
                                </button>
                                <button @click="deleteQuestion(q.id)" class="text-red-400 hover:text-red-600 transition">
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-slate-400 text-center py-8">No published questions yet</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
