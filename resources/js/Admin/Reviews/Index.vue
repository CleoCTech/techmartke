<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Star, Trash2, Award, Eye, EyeOff, MessageSquare } from 'lucide-vue-next';

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

    <div class="space-y-6 p-1">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-100">Customer Reviews</h1>
                <p class="text-sm text-slate-400 mt-1">Manage reviews submitted by customers</p>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-5 text-center">
                <MessageSquare class="w-5 h-5 text-slate-400 mx-auto mb-2" />
                <div class="text-3xl font-bold text-white">{{ stats.total }}</div>
                <div class="text-xs text-slate-400 mt-1">Total Reviews</div>
            </div>
            <div class="bg-slate-800 rounded-xl border border-green-500/30 p-5 text-center">
                <Eye class="w-5 h-5 text-green-400 mx-auto mb-2" />
                <div class="text-3xl font-bold text-green-400">{{ stats.approved }}</div>
                <div class="text-xs text-slate-400 mt-1">Approved</div>
            </div>
            <div class="bg-slate-800 rounded-xl border border-amber-500/30 p-5 text-center">
                <EyeOff class="w-5 h-5 text-amber-400 mx-auto mb-2" />
                <div class="text-3xl font-bold text-amber-400">{{ stats.pending }}</div>
                <div class="text-xs text-slate-400 mt-1">Pending</div>
            </div>
        </div>

        <!-- Filter -->
        <div class="flex items-center gap-3">
            <select
                v-model="statusFilter"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-sm text-slate-200 focus:border-blue-500 focus:outline-none cursor-pointer"
            >
                <option value="">All Reviews</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
            </select>
            <span class="text-xs text-slate-500">{{ reviews.data?.length || 0 }} of {{ reviews.total || 0 }} shown</span>
        </div>

        <!-- Reviews List -->
        <div class="space-y-4">
            <div
                v-for="review in reviews.data"
                :key="review.id"
                class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden"
                :class="!review.is_approved && 'border-l-4 border-l-amber-400'"
            >
                <!-- Review Content -->
                <div class="p-5">
                    <!-- Stars + Date -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex gap-0.5">
                            <Star
                                v-for="(filled, i) in stars(review.rating)"
                                :key="i"
                                class="w-4 h-4"
                                :class="filled ? 'text-amber-400 fill-amber-400' : 'text-slate-600'"
                            />
                        </div>
                        <span class="text-xs text-slate-500">{{ formatDate(review.created_at) }}</span>
                    </div>

                    <!-- Review text -->
                    <p class="text-sm text-slate-200 mb-3 leading-relaxed">
                        "{{ review.review }}"
                    </p>

                    <!-- Author + Badges -->
                    <div class="flex items-center gap-3">
                        <!-- Avatar circle -->
                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                            {{ review.customer_name?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-200">{{ review.customer_name }}</span>
                            <span v-if="review.location" class="text-xs text-slate-500 ml-1">· {{ review.location }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 ml-auto">
                            <span v-if="review.is_approved" class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-500/20 text-green-400">
                                Approved
                            </span>
                            <span v-else class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-500/20 text-amber-400">
                                Pending
                            </span>
                            <span v-if="review.is_featured" class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-500/20 text-blue-400">
                                ★ Featured
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="flex items-center border-t border-slate-700 divide-x divide-slate-700">
                    <button
                        @click="toggleApproval(review.id)"
                        class="flex-1 flex items-center justify-center gap-2 py-3 text-xs font-semibold transition cursor-pointer"
                        :class="review.is_approved
                            ? 'text-slate-400 hover:text-red-400 hover:bg-red-500/5'
                            : 'text-green-400 hover:text-green-300 hover:bg-green-500/10'"
                    >
                        <Eye v-if="!review.is_approved" class="w-4 h-4" />
                        <EyeOff v-else class="w-4 h-4" />
                        {{ review.is_approved ? 'Hide' : 'Approve' }}
                    </button>
                    <button
                        @click="toggleFeature(review.id)"
                        class="flex-1 flex items-center justify-center gap-2 py-3 text-xs font-semibold transition cursor-pointer"
                        :class="review.is_featured
                            ? 'text-blue-400 hover:text-blue-300 hover:bg-blue-500/10'
                            : 'text-slate-400 hover:text-blue-400 hover:bg-blue-500/5'"
                    >
                        <Award class="w-4 h-4" />
                        {{ review.is_featured ? 'Unfeature' : 'Feature' }}
                    </button>
                    <button
                        @click="deleteReview(review.id)"
                        class="flex-1 flex items-center justify-center gap-2 py-3 text-xs font-semibold text-slate-400 hover:text-red-400 hover:bg-red-500/5 transition cursor-pointer"
                    >
                        <Trash2 class="w-4 h-4" />
                        Delete
                    </button>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="!reviews.data?.length" class="text-center py-16 bg-slate-800 rounded-xl border border-slate-700">
                <MessageSquare class="w-10 h-10 text-slate-600 mx-auto mb-3" />
                <p class="text-sm font-medium text-slate-400">No reviews yet</p>
                <p class="text-xs text-slate-500 mt-1">Reviews will appear here when customers submit them</p>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="reviews.last_page > 1" class="flex justify-center gap-1">
            <Link
                v-for="link in reviews.links"
                :key="link.label"
                :href="link.url || '#'"
                class="px-3 py-1.5 text-xs rounded-lg border transition"
                :class="link.active
                    ? 'bg-blue-600 text-white border-blue-600'
                    : (link.url ? 'bg-slate-800 border-slate-700 text-slate-300 hover:bg-slate-700' : 'opacity-30 cursor-not-allowed border-slate-700 text-slate-500')"
                v-html="link.label"
            />
        </div>
    </div>
</template>
