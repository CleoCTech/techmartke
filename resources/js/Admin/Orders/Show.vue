<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Package,
    User,
    MapPin,
    CreditCard,
    FileText,
} from 'lucide-vue-next';

const props = defineProps({
    order: Object,
});

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-KE', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
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

const statusForm = useForm({
    status: props.order.status || '',
});

const notesForm = useForm({
    admin_notes: props.order.admin_notes || '',
});

const updateStatus = () => {
    statusForm.post(`/admin/orders/${props.order.id}/status`, {
        preserveScroll: true,
    });
};

const saveNotes = () => {
    notesForm.post(`/admin/orders/${props.order.id}/notes`, {
        preserveScroll: true,
    });
};

const statuses = [
    'pending',
    'confirmed',
    'processing',
    'shipped',
    'delivered',
    'cancelled',
];
</script>

<template>
    <Head title="Order Details" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto space-y-6">
                <!-- Order Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Order Info -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center mb-4">
                            <FileText class="h-5 w-5 text-slate-500 dark:text-slate-400 mr-2" />
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Order Info</h3>
                        </div>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Order Number</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100 font-medium">{{ order.order_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Date</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ formatDate(order.created_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Order Status</dt>
                                <dd>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="orderStatusColor(order.status)"
                                    >
                                        {{ order.status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Payment Status</dt>
                                <dd>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="paymentStatusColor(order.payment_status)"
                                    >
                                        {{ order.payment_status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center mb-4">
                            <User class="h-5 w-5 text-slate-500 dark:text-slate-400 mr-2" />
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Customer</h3>
                        </div>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Name</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ order.customer_name || order.user?.name || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Email</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ order.customer_email || order.user?.email || '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400">Phone</dt>
                                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ order.customer_phone || '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center mb-4">
                            <MapPin class="h-5 w-5 text-slate-500 dark:text-slate-400 mr-2" />
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Shipping Address</h3>
                        </div>
                        <div v-if="order.shipping_address" class="text-sm text-slate-800 dark:text-slate-100 space-y-1">
                            <p>{{ order.shipping_address.street || order.shipping_address.address_line_1 }}</p>
                            <p v-if="order.shipping_address.address_line_2">{{ order.shipping_address.address_line_2 }}</p>
                            <p>{{ order.shipping_address.city }}<span v-if="order.shipping_address.state">, {{ order.shipping_address.state }}</span></p>
                            <p v-if="order.shipping_address.postal_code">{{ order.shipping_address.postal_code }}</p>
                            <p v-if="order.shipping_address.country">{{ order.shipping_address.country }}</p>
                        </div>
                        <p v-else class="text-sm text-slate-500 dark:text-slate-400">No shipping address provided.</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center">
                            <Package class="h-5 w-5 text-slate-500 dark:text-slate-400 mr-2" />
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Order Items</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Variant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200 dark:divide-slate-700">
                                <tr v-for="item in order.items" :key="item.id" class="hover:bg-slate-50 dark:bg-slate-700">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div v-if="item.product?.primary_image" class="flex-shrink-0 h-10 w-10 mr-3">
                                                <img :src="item.product.primary_image" class="h-10 w-10 rounded-md object-cover" :alt="item.product_name || item.product?.name" />
                                            </div>
                                            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">
                                                {{ item.product_name || item.product?.name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        <span v-if="item.variant_description || item.variant">
                                            {{ item.variant_description || [item.variant?.storage, item.variant?.sim_type, item.variant?.color].filter(Boolean).join(' / ') }}
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">
                                        {{ item.quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">
                                        {{ formatCurrency(item.unit_price || item.price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ formatCurrency(item.subtotal || (item.quantity * (item.unit_price || item.price))) }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-700">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-slate-800 dark:text-slate-100">
                                        Total
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 dark:text-slate-100">
                                        {{ formatCurrency(order.total) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Update Status -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Update Status</h3>
                        <form @submit.prevent="updateStatus" class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Order Status</label>
                                <select
                                    v-model="statusForm.status"
                                    class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm"
                                >
                                    <option v-for="status in statuses" :key="status" :value="status" class="capitalize">
                                        {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                                    </option>
                                </select>
                            </div>
                            <button
                                type="submit"
                                :disabled="statusForm.processing"
                                class="inline-flex items-center px-4 py-2 bg-ablue border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ablue focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                            >
                                {{ statusForm.processing ? 'Updating...' : 'Update' }}
                            </button>
                        </form>
                    </div>

                    <!-- Admin Notes -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Admin Notes</h3>
                        <form @submit.prevent="saveNotes">
                            <textarea
                                v-model="notesForm.admin_notes"
                                rows="3"
                                placeholder="Add internal notes about this order..."
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue shadow-sm mb-3"
                            ></textarea>
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="notesForm.processing"
                                    class="inline-flex items-center px-4 py-2 bg-ablue border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ablue focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
                                >
                                    {{ notesForm.processing ? 'Saving...' : 'Save Notes' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
    </div>
</template>
