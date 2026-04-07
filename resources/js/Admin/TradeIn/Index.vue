<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Search, RefreshCw, CheckCircle, Clock, XCircle, Phone, Smartphone, Eye, Trash2, Filter
} from 'lucide-vue-next';

const page = usePage();

const props = defineProps({
    tradeIns: { type: Object, default: () => ({ data: [], links: [] }) },
    filters: { type: Object, default: () => ({}) },
    stats: { type: Object, default: () => ({}) },
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

let timeout = null;
watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(applyFilters, 400);
});
watch(statusFilter, applyFilters);

function applyFilters() {
    router.get('/admin/trade-in', {
        search: search.value || undefined,
        status: statusFilter.value || undefined,
    }, { preserveState: true, replace: true });
}

const formatPrice = (p) => p ? 'KSh ' + Number(p).toLocaleString() : '-';
const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-KE', { month: 'short', day: 'numeric', year: 'numeric' }) : '';

const statusBadge = (status) => {
    const map = {
        pending: { class: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400', label: 'Pending' },
        quoted: { class: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400', label: 'Quoted' },
        accepted: { class: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400', label: 'Accepted' },
        rejected: { class: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400', label: 'Rejected' },
        completed: { class: 'bg-slate-200 text-slate-700 dark:bg-slate-600 dark:text-slate-300', label: 'Completed' },
    };
    return map[status] || map.pending;
};

const conditionBadge = (c) => {
    const map = {
        flawless: '✨ Flawless',
        good: '👍 Good',
        fair: '🔧 Fair',
        broken: '💔 Broken',
    };
    return map[c] || c;
};
</script>

<template>
    <Head title="Trade-In Requests" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <RefreshCw class="w-5 h-5 text-green-500" />
                Trade-In Requests
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage customer trade-in requests and quote offers</p>
        </div>

        <!-- Flash -->
        <div v-if="page.props.flash?.success" class="mb-4 flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
            <CheckCircle class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0" />
            <p class="text-sm text-green-700 dark:text-green-400">{{ page.props.flash.success }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center cursor-pointer" @click="statusFilter = 'pending'">
                <div class="text-2xl font-bold" :class="stats.pending ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600'">{{ stats.pending || 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">Pending</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center cursor-pointer" @click="statusFilter = 'quoted'">
                <div class="text-2xl font-bold text-blue-500">{{ stats.quoted || 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">Quoted</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center cursor-pointer" @click="statusFilter = 'accepted'">
                <div class="text-2xl font-bold text-green-500">{{ stats.accepted || 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">Accepted</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center cursor-pointer" @click="statusFilter = 'completed'">
                <div class="text-2xl font-bold text-slate-700 dark:text-slate-200">{{ stats.completed || 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">Completed</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center cursor-pointer" @click="statusFilter = 'rejected'">
                <div class="text-2xl font-bold text-red-500">{{ stats.rejected || 0 }}</div>
                <div class="text-xs text-slate-500 mt-1">Rejected</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                    <input v-model="search" type="text" placeholder="Search by name, phone, or device..."
                        class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg text-sm focus:ring-ablue focus:border-ablue" />
                </div>
                <select v-model="statusFilter"
                    class="py-2 px-3 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg text-sm focus:ring-ablue focus:border-ablue min-w-[160px]">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="quoted">Quoted</option>
                    <option value="accepted">Accepted</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Trade-In List -->
        <div v-if="tradeIns.data.length" class="space-y-3">
            <div v-for="t in tradeIns.data" :key="t.id"
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <!-- Left: Device & customer -->
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-500/10 flex items-center justify-center flex-shrink-0">
                            <Smartphone class="w-5 h-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 truncate">
                                    {{ t.product?.name || t.custom_device_name || 'Unknown device' }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold" :class="statusBadge(t.status).class">
                                    {{ statusBadge(t.status).label }}
                                </span>
                                <span v-if="!t.product_id" class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-600 dark:bg-amber-500/10">
                                    Custom
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1"><Phone class="w-3 h-3" /> {{ t.customer_phone }}</span>
                                <span>{{ t.customer_name }}</span>
                                <span v-if="t.storage_capacity">{{ t.storage_capacity }}</span>
                                <span>{{ conditionBadge(t.condition) }}</span>
                                <span v-if="t.battery_health">🔋 {{ t.battery_health }}%</span>
                            </div>
                            <div v-if="t.has_cracks || t.has_repairs || t.problems_description" class="flex flex-wrap items-center gap-1 mt-2">
                                <span v-if="t.has_cracks" class="text-[10px] px-1.5 py-0.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded font-medium">Cracks</span>
                                <span v-if="t.has_repairs" class="text-[10px] px-1.5 py-0.5 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded font-medium">Repaired</span>
                                <span v-if="t.problems_description" class="text-[10px] px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded font-medium truncate max-w-[200px]">{{ t.problems_description }}</span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2">{{ formatDate(t.created_at) }}</p>
                        </div>
                    </div>

                    <!-- Right: Value & Actions -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-right">
                            <p v-if="t.offered_value" class="text-lg font-extrabold text-green-600 dark:text-green-400">{{ formatPrice(t.offered_value) }}</p>
                            <p v-else-if="t.estimated_value" class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ formatPrice(t.estimated_value) }}</p>
                            <p v-else class="text-sm text-slate-400">No quote</p>
                            <p class="text-[10px] text-slate-400">{{ t.offered_value ? 'Offer' : 'Estimate' }}</p>
                        </div>
                        <Link :href="`/admin/trade-in/${t.uuid}`"
                            class="px-3 py-1.5 bg-ablue hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                            <Eye class="w-3.5 h-3.5" /> Manage
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center">
            <RefreshCw class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" />
            <p class="text-slate-500 dark:text-slate-400">No trade-in requests yet</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Customer requests will appear here</p>
        </div>

        <!-- Pagination -->
        <div v-if="tradeIns.last_page > 1" class="flex items-center justify-center gap-1 mt-6">
            <template v-for="link in tradeIns.links" :key="link.label">
                <Link v-if="link.url" :href="link.url"
                    class="px-3 py-1.5 text-sm rounded-lg transition"
                    :class="link.active ? 'bg-ablue text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700'"
                    v-html="link.label" preserve-state />
                <span v-else class="px-3 py-1.5 text-sm text-slate-400" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
