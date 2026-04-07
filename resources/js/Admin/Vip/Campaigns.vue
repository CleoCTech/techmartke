<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Ticket,
    Calendar,
    ToggleLeft,
    ToggleRight,
    ChevronDown,
    ChevronUp,
} from 'lucide-vue-next';

const props = defineProps({
    campaigns: Object,
});

const showCreateForm = ref(false);

const form = useForm({
    name: '',
    code_prefix: 'TM',
    vip_price: 500,
    referral_reward: 100,
    referrals_to_activate: 2,
    discount_type: 'percentage',
    discount_value: 10,
    max_uses: '',
    is_active: true,
    starts_at: '',
    ends_at: '',
});

const submitCampaign = () => {
    form.post('/admin/vip/campaigns', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showCreateForm.value = false;
        },
    });
};

const formatCurrency = (amount) => {
    return 'KSh ' + Number(amount).toLocaleString('en-KE');
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-KE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const statusBadge = (isActive) => {
    return isActive
        ? { label: 'Active', class: 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400' }
        : { label: 'Inactive', class: 'bg-slate-100 text-slate-800 dark:bg-slate-500/20 dark:text-slate-400' };
};
</script>

<template>
    <Head title="VIP Campaigns" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">
                <!-- Create Campaign Form -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-if="showCreateForm" class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700 mb-6">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-2 mb-4">
                                <Ticket class="w-5 h-5 text-ablue" />
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">New Campaign</h3>
                            </div>

                            <form @submit.prevent="submitCampaign" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Campaign Name</label>
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            required
                                            placeholder="e.g. Launch VIP Campaign"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Code Prefix</label>
                                        <input
                                            v-model="form.code_prefix"
                                            type="text"
                                            required
                                            placeholder="e.g. TM"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm uppercase"
                                        />
                                        <p v-if="form.errors.code_prefix" class="text-red-500 text-xs mt-1">{{ form.errors.code_prefix }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">VIP Price (KSh)</label>
                                        <input
                                            v-model="form.vip_price"
                                            type="number"
                                            required
                                            min="0"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.vip_price" class="text-red-500 text-xs mt-1">{{ form.errors.vip_price }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Referral Reward (KSh)</label>
                                        <input
                                            v-model="form.referral_reward"
                                            type="number"
                                            required
                                            min="0"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.referral_reward" class="text-red-500 text-xs mt-1">{{ form.errors.referral_reward }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Referrals to Activate</label>
                                        <input
                                            v-model="form.referrals_to_activate"
                                            type="number"
                                            required
                                            min="1"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.referrals_to_activate" class="text-red-500 text-xs mt-1">{{ form.errors.referrals_to_activate }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Discount Type</label>
                                        <select
                                            v-model="form.discount_type"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        >
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed (KSh)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Discount Value</label>
                                        <input
                                            v-model="form.discount_value"
                                            type="number"
                                            required
                                            min="0"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.discount_value" class="text-red-500 text-xs mt-1">{{ form.errors.discount_value }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Max Uses (blank = unlimited)</label>
                                        <input
                                            v-model="form.max_uses"
                                            type="number"
                                            min="1"
                                            placeholder="Unlimited"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                        <p v-if="form.errors.max_uses" class="text-red-500 text-xs mt-1">{{ form.errors.max_uses }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Start Date</label>
                                        <input
                                            v-model="form.starts_at"
                                            type="date"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">End Date</label>
                                        <input
                                            v-model="form.ends_at"
                                            type="date"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg focus:ring-ablue focus:border-ablue text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                v-model="form.is_active"
                                                type="checkbox"
                                                class="rounded border-slate-300 dark:border-slate-600 text-ablue focus:ring-ablue dark:bg-slate-700"
                                            />
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-2">
                                    <button
                                        type="button"
                                        @click="showCreateForm = false"
                                        class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 transition"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-5 py-2.5 bg-ablue border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ablue focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                    >
                                        <Plus class="w-4 h-4 mr-1.5" />
                                        <span v-if="form.processing">Creating...</span>
                                        <span v-else>Create Campaign</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>

                <!-- Campaigns Table -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">All Campaigns</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">VIP Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Referral Reward</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Referrals Needed</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dates</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200 dark:divide-slate-700">
                                <tr v-for="campaign in campaigns.data" :key="campaign.id" class="hover:bg-slate-50 dark:bg-slate-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ campaign.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 dark:text-slate-100">
                                        {{ formatCurrency(campaign.vip_price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ formatCurrency(campaign.referral_reward) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ campaign.referrals_to_activate }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ campaign.used_count || 0 }}{{ campaign.max_uses ? ` / ${campaign.max_uses}` : '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="statusBadge(campaign.is_active).class"
                                        >
                                            {{ statusBadge(campaign.is_active).label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        <div>{{ formatDate(campaign.starts_at) }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">to {{ formatDate(campaign.ends_at) }}</div>
                                    </td>
                                </tr>
                                <tr v-if="campaigns.data.length === 0">
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No campaigns created yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="campaigns.last_page > 1" class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            Showing <span class="font-medium">{{ campaigns.from }}</span> to
                            <span class="font-medium">{{ campaigns.to }}</span> of
                            <span class="font-medium">{{ campaigns.total }}</span> results
                        </p>
                        <div class="flex items-center space-x-1">
                            <template v-for="link in campaigns.links" :key="link.label">
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
