<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Eye,
    Search,
} from 'lucide-vue-next';

const props = defineProps({
    orders: Object,
    filters: { type: Object, default: () => ({}) },
});

const statusFilter = ref(props.filters?.status || '');
const search = ref(props.filters?.search || '');

const statuses = [
    { value: '', label: 'All' },
    { value: 'pending', label: 'Pending' },
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'processing', label: 'Processing' },
    { value: 'shipped', label: 'Shipped' },
    { value: 'delivered', label: 'Delivered' },
    { value: 'cancelled', label: 'Cancelled' },
];

const applyFilters = () => {
    router.get('/admin/orders', {
        status: statusFilter.value || undefined,
        search: search.value || undefined,
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

const orderStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
        confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400',
        processing: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-400',
        shipped: 'bg-purple-100 text-purple-800 dark:bg-purple-500/20 dark:text-purple-400',
        delivered: 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
    };
    return colors[status?.toLowerCase()] || 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400';
};

const paymentStatusColor = (status) => {
    const colors = {
        paid: 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400',
        unpaid: 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
        partial: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
        refunded: 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400',
    };
    return colors[status?.toLowerCase()] || 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400';
};
</script>

<template>
    <Head title="Orders" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <!-- Filters -->
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-500 dark:text-slate-400" />
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search orders..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="status in statuses"
                                    :key="status.value"
                                    @click="statusFilter = status.value"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition"
                                    :class="statusFilter === status.value
                                        ? 'bg-ablue text-white'
                                        : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600'"
                                >
                                    {{ status.label }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200 dark:divide-slate-700">
                                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50 dark:bg-slate-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ order.order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ order.customer_name || order.user?.name || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ order.items_count || order.items?.length || 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100 font-medium">
                                        {{ formatCurrency(order.total) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="paymentStatusColor(order.payment_status)"
                                        >
                                            {{ order.payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="orderStatusColor(order.status)"
                                        >
                                            {{ order.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ formatDate(order.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <Link
                                            :href="`/admin/orders/${order.id}`"
                                            class="text-ablue hover:text-blue-700 inline-flex items-center"
                                        >
                                            <Eye class="h-4 w-4 mr-1" />
                                            View
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="orders.data.length === 0">
                                    <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No orders found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="orders.last_page > 1" class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            Showing <span class="font-medium">{{ orders.from }}</span> to
                            <span class="font-medium">{{ orders.to }}</span> of
                            <span class="font-medium">{{ orders.total }}</span> results
                        </p>
                        <div class="flex items-center space-x-1">
                            <template v-for="link in orders.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-3 py-1 text-sm rounded-md"
                                    :class="link.active ? 'bg-ablue text-white' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600'"
                                    v-html="link.label"
                                    preserve-state
                                />
                                <span
                                    v-else
                                    class="px-3 py-1 text-sm text-slate-500 dark:text-slate-400"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
    </div>
</template>
