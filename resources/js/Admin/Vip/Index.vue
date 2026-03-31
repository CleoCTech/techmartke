<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import {
    Search,
    Users,
    Crown,
    Clock,
    DollarSign,
    ToggleLeft,
    ToggleRight,
} from 'lucide-vue-next';

const props = defineProps({
    subscribers: Object,
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            active: 0,
            pending: 0,
            revenue: 0,
        }),
    },
    activeCampaign: { type: Object, default: null },
    filters: { type: Object, default: () => ({}) },
});

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get('/admin/vip', {
        search: search.value || undefined,
        status: statusFilter.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});

watch(statusFilter, applyFilters);

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-KE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const statusBadge = (status) => {
    const badges = {
        active: { label: 'Active', class: 'bg-green-100 text-green-800' },
        pending: { label: 'Pending', class: 'bg-yellow-100 text-yellow-800' },
        expired: { label: 'Expired', class: 'bg-red-100 text-red-800' },
    };
    return badges[status] || { label: status, class: 'bg-gray-100 text-gray-800' };
};

const toggleStatus = (subscriber) => {
    const newStatus = subscriber.status === 'active' ? 'pending' : 'active';
    router.put(`/admin/vip/${subscriber.id}/toggle`, { status: newStatus }, {
        preserveState: true,
    });
};
</script>

<template>
    <AppLayout title="VIP Subscribers">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                VIP Subscribers
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <Users class="w-5 h-5 text-indigo-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Subscribers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.total.toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <Crown class="w-5 h-5 text-green-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Active VIP</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.active.toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <Clock class="w-5 h-5 text-yellow-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.pending.toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <DollarSign class="w-5 h-5 text-emerald-600" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(stats.revenue) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <!-- Filters -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search by name, email, or phone..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                            </div>
                            <select
                                v-model="statusFilter"
                                class="py-2 px-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            >
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promo Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrals</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="sub in subscribers.data" :key="sub.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ sub.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ sub.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ sub.phone }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-amber-700 font-medium">
                                        {{ sub.promo_code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="statusBadge(sub.status).class"
                                        >
                                            {{ statusBadge(sub.status).label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ sub.activation_method || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ sub.referral_count || 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ sub.payment_amount ? formatCurrency(sub.payment_amount) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(sub.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button
                                            @click="toggleStatus(sub)"
                                            class="inline-flex items-center gap-1 text-sm font-medium transition"
                                            :class="sub.status === 'active' ? 'text-green-600 hover:text-yellow-600' : 'text-yellow-600 hover:text-green-600'"
                                            :title="sub.status === 'active' ? 'Set to Pending' : 'Set to Active'"
                                        >
                                            <ToggleRight v-if="sub.status === 'active'" class="h-5 w-5" />
                                            <ToggleLeft v-else class="h-5 w-5" />
                                            {{ sub.status === 'active' ? 'Active' : 'Pending' }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="subscribers.data.length === 0">
                                    <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500">
                                        No subscribers found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="subscribers.last_page > 1" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ subscribers.from }}</span> to
                            <span class="font-medium">{{ subscribers.to }}</span> of
                            <span class="font-medium">{{ subscribers.total }}</span> results
                        </p>
                        <div class="flex items-center space-x-1">
                            <template v-for="link in subscribers.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-3 py-1 text-sm rounded-md"
                                    :class="link.active ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100'"
                                    v-html="link.label"
                                    preserve-state
                                />
                                <span
                                    v-else
                                    class="px-3 py-1 text-sm text-gray-400"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
