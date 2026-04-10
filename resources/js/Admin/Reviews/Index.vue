<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Star, Check, X, Trash2, Award, Eye, EyeOff } from 'lucide-vue-next';

const props = defineProps({
    reviews: Object,
    filters: { type: Object, default: () => ({}) },
    stats: { type: Object, default: () => ({}) },
});

const statusFilter = ref(props.filters?.status || '');

watch(statusFilter, (val) => {
    router.get('/admin/reviews', { status: val || undefined }, { preserveState: true, replace: true });
});

const toggleApproval = (id) => router.put(`/admin/reviews/${id}/toggle`, {}, { preserveScroll: true });
const toggleFeature = (id) => router.put(`/admin/reviews/${id}/feature`, {}, { preserveScroll: true });
const deleteReview = (id) => {
    if (confirm('Delete this review permanently?')) {
        router.delete(`/admin/reviews/${id}`, { preserveScroll: true });
    }
};

const stars = (n) => Array.from({ length: 5 }, (_, i) => i < n);
const formatDate = (d) => new Date(d).toLocaleDateString('en-KE', { day: 'numeric', month: 'short', year: 'numeric' });
</script>

<template>
    <Head title="Customer Reviews" />

    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats.total }}</div>
                <div class="text-xs text-slate-500">Total Reviews</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-green-200 dark:border-green-500/30 p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ stats.approved }}</div>
                <div class="text-xs text-slate-500">Approved</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-amber-200 dark:border-amber-500/30 p-4 text-center">
                <div class="text-2xl font-bold text-amber-600">{{ stats.pending }}</div>
                <div class="text-xs text-slate-500">Pending</div>
            </div>
        </div>

        <!-- Filter -->
        <div class="flex items-center gap-3">
            <select v-model="statusFilter" class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm">
                <option value="">All Reviews</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <!-- Reviews List -->
        <div class="space-y-3">
            <div
                v-for="review in reviews.data"
                :key="review.id"
                class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5"
                :class="!review.is_approved && 'border-l-4 border-l-amber-400'"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <!-- Stars -->
                        <div class="flex gap-0.5 mb-2">
                            <Star
                                v-for="(filled, i) in stars(review.rating)"
                                :key="i"
                                class="w-4 h-4"
                                :class="filled ? 'text-amber-400 fill-amber-400' : 'text-slate-300'"
                            />
                        </div>

                        <!-- Review text -->
                        <p class="text-sm text-slate-700 dark:text-slate-200 mb-3 leading-relaxed">
                            "{{ review.review }}"
                        </p>

                        <!-- Author -->
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ review.customer_name }}</span>
                            <span v-if="review.location">· {{ review.location }}</span>
                            <span>· {{ formatDate(review.created_at) }}</span>
                        </div>

                        <!-- Status badges -->
                        <div class="flex items-center gap-2 mt-2">
                            <span v-if="review.is_approved" class="px-2 py-0.5 text-[10px] font-semibold rounded bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                                Approved
                            </span>
                            <span v-else class="px-2 py-0.5 text-[10px] font-semibold rounded bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                Pending
                            </span>
                            <span v-if="review.is_featured" class="px-2 py-0.5 text-[10px] font-semibold rounded bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                Featured
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-1.5">
                        <button
                            @click="toggleApproval(review.id)"
                            :title="review.is_approved ? 'Hide' : 'Approve'"
                            class="p-2 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer"
                        >
                            <Eye v-if="!review.is_approved" class="w-4 h-4 text-green-500" />
                            <EyeOff v-else class="w-4 h-4 text-slate-400" />
                        </button>
                        <button
                            @click="toggleFeature(review.id)"
                            title="Toggle featured"
                            class="p-2 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer"
                        >
                            <Award class="w-4 h-4" :class="review.is_featured ? 'text-blue-500' : 'text-slate-400'" />
                        </button>
                        <button
                            @click="deleteReview(review.id)"
                            title="Delete"
                            class="p-2 rounded-lg transition hover:bg-red-50 dark:hover:bg-red-500/10 cursor-pointer"
                        >
                            <Trash2 class="w-4 h-4 text-red-400" />
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="!reviews.data?.length" class="text-center py-10 text-slate-400 text-sm">
                No reviews yet.
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="reviews.last_page > 1" class="flex justify-center gap-1">
            <Link
                v-for="link in reviews.links"
                :key="link.label"
                :href="link.url || '#'"
                class="px-3 py-1.5 text-xs rounded-lg border"
                :class="link.active
                    ? 'bg-black text-white border-black'
                    : (link.url ? 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:bg-slate-50' : 'opacity-30 cursor-not-allowed')"
                v-html="link.label"
            />
        </div>
    </div>
</template>
