<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import {
    Send,
    Bell,
    Tag,
    TrendingDown,
    Package,
    Megaphone,
    Clock,
    Users,
} from 'lucide-vue-next';

const props = defineProps({
    notifications: Object,
    vipCount: { type: Number, default: 0 },
    products: { type: Array, default: () => [] },
});

const form = useForm({
    title: '',
    message: '',
    type: 'general',
    product_id: '',
    target: 'vip_only',
});

const notificationTypes = [
    { value: 'new_stock', label: 'New Stock' },
    { value: 'deal', label: 'Deal' },
    { value: 'price_drop', label: 'Price Drop' },
    { value: 'general', label: 'General' },
];

const submitNotification = () => {
    form.post('/admin/vip/notifications/send', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-KE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const typeBadge = (type) => {
    const badges = {
        new_stock: { label: 'New Stock', class: 'bg-blue-100 text-blue-800', icon: Package },
        deal: { label: 'Deal', class: 'bg-green-100 text-green-800', icon: Tag },
        price_drop: { label: 'Price Drop', class: 'bg-red-100 text-red-800', icon: TrendingDown },
        general: { label: 'General', class: 'bg-gray-100 text-gray-800', icon: Bell },
    };
    return badges[type] || { label: type, class: 'bg-gray-100 text-gray-800', icon: Bell };
};

const targetLabel = (target) => {
    return target === 'vip_only' ? 'VIP Only' : 'All Subscribers';
};
</script>

<template>
    <AppLayout title="VIP Notifications">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                VIP Notifications
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Send Notification Form -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center gap-2 mb-4">
                            <Megaphone class="w-5 h-5 text-indigo-600" />
                            <h3 class="text-lg font-semibold text-gray-800">Send Notification</h3>
                        </div>

                        <form @submit.prevent="submitNotification" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        required
                                        placeholder="Notification title"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    />
                                    <p v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select
                                            v-model="form.type"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        >
                                            <option v-for="t in notificationTypes" :key="t.value" :value="t.value">
                                                {{ t.label }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                                        <select
                                            v-model="form.target"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        >
                                            <option value="vip_only">VIP Only</option>
                                            <option value="all">All Subscribers</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea
                                    v-model="form.message"
                                    required
                                    rows="3"
                                    placeholder="Write your notification message..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                ></textarea>
                                <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
                            </div>

                            <div v-if="products.length > 0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Link to Product (optional)</label>
                                <select
                                    v-model="form.product_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                >
                                    <option value="">No product linked</option>
                                    <option v-for="product in products" :key="product.id" :value="product.id">
                                        {{ product.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <p class="text-sm text-gray-500">
                                    <Users class="w-4 h-4 inline mr-1" />
                                    Will be sent to <span class="font-semibold">{{ vipCount }}</span> VIP member{{ vipCount !== 1 ? 's' : '' }}
                                </p>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                >
                                    <Send class="w-4 h-4 mr-1.5" />
                                    <span v-if="form.processing">Sending...</span>
                                    <span v-else>Send to {{ vipCount }} VIP Member{{ vipCount !== 1 ? 's' : '' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification History -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Notification History</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="notification in notifications.data" :key="notification.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ notification.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="typeBadge(notification.type).class"
                                        >
                                            {{ typeBadge(notification.type).label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ notification.product?.name || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ targetLabel(notification.target) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ notification.sent_count || 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(notification.created_at) }}
                                    </td>
                                </tr>
                                <tr v-if="notifications.data.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        No notifications sent yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="notifications.last_page > 1" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ notifications.from }}</span> to
                            <span class="font-medium">{{ notifications.to }}</span> of
                            <span class="font-medium">{{ notifications.total }}</span> results
                        </p>
                        <div class="flex items-center space-x-1">
                            <template v-for="link in notifications.links" :key="link.label">
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
