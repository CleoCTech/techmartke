<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft, Smartphone, Phone, User, Battery, Calendar, AlertTriangle, Wrench,
    MessageCircle, Trash2, CheckCircle, RefreshCw
} from 'lucide-vue-next';

const page = usePage();

const props = defineProps({
    tradeIn: { type: Object, required: true },
});

const form = useForm({
    status: props.tradeIn.status,
    offered_value: props.tradeIn.offered_value || '',
    admin_notes: props.tradeIn.admin_notes || '',
});

const submit = () => {
    form.put(`/admin/trade-in/${props.tradeIn.uuid}`, {
        preserveScroll: true,
    });
};

const remove = () => {
    if (confirm('Delete this trade-in request?')) {
        useForm({}).delete(`/admin/trade-in/${props.tradeIn.uuid}`);
    }
};

const formatPrice = (p) => p ? 'KSh ' + Number(p).toLocaleString() : '-';
const formatDate = (d) => d ? new Date(d).toLocaleString('en-KE', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';

const whatsappQuote = () => {
    const device = props.tradeIn.product?.name || props.tradeIn.custom_device_name || 'your device';
    const offer = form.offered_value ? formatPrice(form.offered_value) : '[insert offer]';
    const msg = `Hi ${props.tradeIn.customer_name}! Thanks for your trade-in request for *${device}*. Our offer is *${offer}*. Reply YES to accept or call us to discuss.`;
    return `https://wa.me/${props.tradeIn.customer_phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(msg)}`;
};

const conditionLabel = {
    flawless: '✨ Flawless — No scratches, like new',
    good: '👍 Good — Minor scratches, fully functional',
    fair: '🔧 Fair — Visible wear, works fine',
    broken: '💔 Broken — Cracked/faulty parts',
};
</script>

<template>
    <Head title="Trade-In Request" />
    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-5xl mx-auto">

        <!-- Back -->
        <Link href="/admin/trade-in" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 mb-4">
            <ArrowLeft class="w-4 h-4" /> Back to all requests
        </Link>

        <!-- Flash -->
        <div v-if="page.props.flash?.success" class="mb-4 flex items-center gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20">
            <CheckCircle class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0" />
            <p class="text-sm text-green-700 dark:text-green-400">{{ page.props.flash.success }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Request Details -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Device Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center flex-shrink-0">
                            <Smartphone class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                                {{ tradeIn.product?.name || tradeIn.custom_device_name || 'Unknown device' }}
                            </h2>
                            <p v-if="!tradeIn.product_id" class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                                Custom device — not in catalog
                            </p>
                            <p class="text-xs text-slate-400 mt-1">{{ formatDate(tradeIn.created_at) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-3">
                            <p class="text-[10px] uppercase font-semibold text-slate-400 mb-1">Storage</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ tradeIn.storage_capacity || '-' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-3">
                            <p class="text-[10px] uppercase font-semibold text-slate-400 mb-1">Battery Health</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ tradeIn.battery_health }}%</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-3 col-span-2">
                            <p class="text-[10px] uppercase font-semibold text-slate-400 mb-1">Condition</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ conditionLabel[tradeIn.condition] }}</p>
                        </div>
                    </div>

                    <div v-if="tradeIn.has_cracks || tradeIn.has_repairs || tradeIn.problems_description" class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-[10px] uppercase font-semibold text-slate-400 mb-2">Issues Reported</p>
                        <div class="space-y-2">
                            <div v-if="tradeIn.has_cracks" class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                                <AlertTriangle class="w-4 h-4" /> Has cracks or screen damage
                            </div>
                            <div v-if="tradeIn.has_repairs" class="flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400">
                                <Wrench class="w-4 h-4" /> Has previous repairs
                            </div>
                            <div v-if="tradeIn.problems_description" class="text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-700/30 rounded p-2">
                                {{ tradeIn.problems_description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-3">Customer Contact</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <User class="w-4 h-4 text-slate-400" />
                            {{ tradeIn.customer_name }}
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <Phone class="w-4 h-4 text-slate-400" />
                            <a :href="`tel:${tradeIn.customer_phone}`" class="hover:text-ablue">{{ tradeIn.customer_phone }}</a>
                        </div>
                        <a :href="whatsappQuote()" target="_blank"
                            class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-[#25D366] hover:bg-[#1fb855] text-white text-xs font-semibold rounded-lg transition">
                            <MessageCircle class="w-3.5 h-3.5" />
                            Send Quote via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Status & Actions -->
            <div class="space-y-4">
                <!-- Estimated Value -->
                <div class="bg-gradient-to-br from-black to-gray-800 rounded-xl p-5 text-white">
                    <p class="text-xs text-gray-400 mb-1">Customer's Estimate</p>
                    <p class="text-2xl font-extrabold">{{ formatPrice(tradeIn.estimated_value) }}</p>
                </div>

                <!-- Update Form -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-4">Manage Request</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase">Status</label>
                            <select v-model="form.status"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm">
                                <option value="pending">Pending</option>
                                <option value="quoted">Quoted</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase">Your Offer (KSh)</label>
                            <input v-model="form.offered_value" type="number" step="500" placeholder="e.g. 18000"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm" />
                            <p class="text-[10px] text-slate-400 mt-1">After physical inspection</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase">Admin Notes</label>
                            <textarea v-model="form.admin_notes" rows="3" placeholder="Internal notes..."
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm"></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-2.5 bg-ablue hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </form>
                </div>

                <!-- Delete -->
                <button @click="remove"
                    class="w-full py-2 text-xs font-medium text-red-500 hover:text-red-700 transition flex items-center justify-center gap-1">
                    <Trash2 class="w-3.5 h-3.5" /> Delete Request
                </button>
            </div>
        </div>
    </div>
</template>
