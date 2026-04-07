<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    Smartphone, Search, ChevronRight, ChevronLeft, Battery, Shield, Wrench,
    AlertTriangle, CheckCircle, MessageCircle, ArrowRight, Zap, X, RefreshCw
} from 'lucide-vue-next';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const page = usePage();
const { whatsappUrl: companyWhatsappUrl } = useCompanyInfo();

const props = defineProps({
    products: { type: Array, default: () => [] },
});

// Multi-step form
const step = ref(1);
const totalSteps = 4;

// Step 1: Device selection
const deviceSearch = ref('');
const showDeviceDropdown = ref(false);
const selectedDevice = ref(null);
const customDevice = ref('');
const isCustomDevice = ref(false);

const filteredDevices = computed(() => {
    const q = deviceSearch.value.toLowerCase();
    if (!q) return props.products.slice(0, 12);
    return props.products.filter(p =>
        p.name.toLowerCase().includes(q) || (p.brand || '').toLowerCase().includes(q)
    ).slice(0, 12);
});

const selectDevice = (product) => {
    selectedDevice.value = product;
    deviceSearch.value = product.name;
    showDeviceDropdown.value = false;
    isCustomDevice.value = false;
};

const switchToCustom = () => {
    isCustomDevice.value = true;
    selectedDevice.value = null;
    deviceSearch.value = '';
};

// Step 2: Device condition
const condition = ref('good');
const storageCapacity = ref('128GB');
const batteryHealth = ref(85);

const conditions = [
    { value: 'flawless', label: 'Flawless', desc: 'No scratches, like new', icon: '✨', color: 'green' },
    { value: 'good', label: 'Good', desc: 'Minor scratches, fully functional', icon: '👍', color: 'blue' },
    { value: 'fair', label: 'Fair', desc: 'Visible wear, works fine', icon: '🔧', color: 'yellow' },
    { value: 'broken', label: 'Broken', desc: 'Cracked/faulty parts', icon: '💔', color: 'red' },
];

const storageOptions = ['64GB', '128GB', '256GB', '512GB', '1TB'];

// Step 3: Issues
const hasCracks = ref(false);
const hasRepairs = ref(false);
const problemsDescription = ref('');

// Step 4: Contact & Submit
const form = useForm({
    product_id: '',
    custom_device_name: '',
    customer_name: '',
    customer_phone: '',
    storage_capacity: '',
    condition: '',
    battery_health: null,
    has_cracks: false,
    has_repairs: false,
    problems_description: '',
    estimated_value: 0,
});

// Valuation
const estimatedValue = ref(0);
const breakdown = ref({});
const isValuing = ref(false);

const fetchValuation = async () => {
    if (!selectedDevice.value) return;
    isValuing.value = true;
    try {
        const res = await fetch('/trade-in/estimate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                product_id: selectedDevice.value.id,
                condition: condition.value,
                battery_health: batteryHealth.value,
                storage_capacity: storageCapacity.value,
                has_cracks: hasCracks.value,
                has_repairs: hasRepairs.value,
                problems_description: problemsDescription.value,
            }),
        });
        const data = await res.json();
        estimatedValue.value = data.estimated_value || 0;
        breakdown.value = data.breakdown || {};
    } catch {
        estimatedValue.value = 0;
    } finally {
        isValuing.value = false;
    }
};

// Auto-fetch valuation when moving to step 3+
watch([step], () => {
    if (step.value >= 3 && selectedDevice.value) fetchValuation();
});

const formatPrice = (p) => 'KSh ' + Number(p).toLocaleString();

const canProceed = computed(() => {
    if (step.value === 1) return selectedDevice.value || (isCustomDevice.value && customDevice.value.trim());
    if (step.value === 2) return true;
    if (step.value === 3) return true;
    return true;
});

const nextStep = () => {
    if (step.value < totalSteps && canProceed.value) step.value++;
};
const prevStep = () => {
    if (step.value > 1) step.value--;
};

const submitTradeIn = () => {
    form.product_id = selectedDevice.value?.id || '';
    form.custom_device_name = isCustomDevice.value ? customDevice.value : '';
    form.customer_name = form.customer_name;
    form.storage_capacity = storageCapacity.value;
    form.condition = condition.value;
    form.battery_health = batteryHealth.value;
    form.has_cracks = hasCracks.value;
    form.has_repairs = hasRepairs.value;
    form.problems_description = problemsDescription.value;
    form.estimated_value = estimatedValue.value;

    form.post('/trade-in', {
        preserveScroll: true,
        onSuccess: () => {
            step.value = 1;
            selectedDevice.value = null;
            estimatedValue.value = 0;
        },
    });
};

const whatsappTradeIn = computed(() => {
    const device = selectedDevice.value?.name || customDevice.value || 'Unknown device';
    const value = estimatedValue.value ? ` (estimated ${formatPrice(estimatedValue.value)})` : '';
    let msg = `Hi TechMart KE! I'd like to trade in my device:\n\n`;
    msg += `*Device:* ${device}\n`;
    msg += `*Storage:* ${storageCapacity.value}\n`;
    msg += `*Condition:* ${condition.value}\n`;
    msg += `*Battery:* ${batteryHealth.value}%\n`;
    if (hasCracks.value) msg += `*Cracks:* Yes\n`;
    if (hasRepairs.value) msg += `*Previous Repairs:* Yes\n`;
    if (problemsDescription.value) msg += `*Issues:* ${problemsDescription.value}\n`;
    msg += `\n${value}\n\nPlease let me know the final offer!`;
    return companyWhatsappUrl(msg);
});
</script>

<template>
    <Head title="Trade-In Your Device — Get Instant Value" />
    <CustomerLayout>
        <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
            <div class="container mx-auto px-4 py-8 md:py-12">
                <div class="max-w-2xl mx-auto">

                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 rounded-full px-4 py-1.5 mb-4 text-sm font-semibold text-green-700">
                            <RefreshCw class="w-4 h-4" />
                            Value Trade-In
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2">Trade In Your Device</h1>
                        <p class="text-gray-500">Get an instant valuation and upgrade to something better</p>
                    </div>

                    <!-- Flash -->
                    <div v-if="page.props.flash?.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
                        <CheckCircle class="w-5 h-5 shrink-0" />
                        {{ page.props.flash.success }}
                    </div>

                    <!-- Progress Bar -->
                    <div class="flex items-center gap-2 mb-6">
                        <template v-for="s in totalSteps" :key="s">
                            <div class="flex-1 h-1.5 rounded-full transition-colors" :class="s <= step ? 'bg-black' : 'bg-gray-200'" />
                        </template>
                    </div>

                    <!-- Selected Device Banner (steps 2-4) -->
                    <div v-if="step > 1 && (selectedDevice || (isCustomDevice && customDevice))"
                        class="mb-4 flex items-center justify-between gap-3 bg-black text-white rounded-2xl px-4 py-3"
                    >
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <Smartphone class="w-4 h-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-medium">Trading in</p>
                                <p class="text-sm font-bold truncate">{{ selectedDevice?.name || customDevice }}</p>
                            </div>
                        </div>
                        <button
                            @click="step = 1"
                            class="text-[10px] uppercase tracking-wider font-semibold text-gray-300 hover:text-white transition px-2 py-1 rounded-lg hover:bg-white/10 flex-shrink-0"
                        >
                            Change
                        </button>
                    </div>

                    <!-- Step 1: Select Device -->
                    <div v-if="step === 1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-1">What device are you trading in?</h2>
                        <p class="text-sm text-gray-500 mb-6">Search our catalog or enter your device manually</p>

                        <div v-if="!isCustomDevice" class="relative mb-4">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                            <input
                                v-model="deviceSearch"
                                type="text"
                                placeholder="Search... e.g. iPhone 14, Samsung S24"
                                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl text-base focus:border-black focus:outline-none focus:ring-4 focus:ring-black/5 transition"
                                @focus="showDeviceDropdown = true"
                                @input="showDeviceDropdown = true"
                                autocomplete="off"
                            />

                            <!-- Selected indicator -->
                            <div v-if="selectedDevice" class="absolute right-4 top-1/2 -translate-y-1/2">
                                <CheckCircle class="w-5 h-5 text-green-500" />
                            </div>

                            <!-- Dropdown -->
                            <div v-if="showDeviceDropdown && filteredDevices.length" class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-2xl border border-gray-200 max-h-64 overflow-y-auto z-30">
                                <button
                                    v-for="p in filteredDevices"
                                    :key="p.id"
                                    @mousedown.prevent="selectDevice(p)"
                                    class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 last:border-0 text-left"
                                    :class="selectedDevice?.id === p.id ? 'bg-gray-50' : ''"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ p.name }}</p>
                                        <p class="text-xs text-gray-400">{{ p.brand }} · {{ formatPrice(p.price) }}</p>
                                    </div>
                                    <CheckCircle v-if="selectedDevice?.id === p.id" class="w-4 h-4 text-green-500 shrink-0" />
                                </button>
                            </div>
                        </div>

                        <!-- Custom device input -->
                        <div v-if="isCustomDevice" class="mb-4">
                            <input
                                v-model="customDevice"
                                type="text"
                                placeholder="Enter device name (e.g. Huawei P30 Pro)"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl text-base focus:border-black focus:outline-none transition"
                            />
                            <button @click="isCustomDevice = false" class="text-sm text-gray-500 hover:text-black mt-2 flex items-center gap-1">
                                <ChevronLeft class="w-3 h-3" /> Back to catalog search
                            </button>
                        </div>

                        <button v-if="!isCustomDevice" @click="switchToCustom" class="text-sm text-gray-500 hover:text-black transition flex items-center gap-1">
                            Can't find your device? <span class="font-semibold underline">Enter manually</span>
                        </button>
                    </div>

                    <!-- Step 2: Condition -->
                    <div v-if="step === 2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-1">Device condition</h2>
                        <p class="text-sm text-gray-500 mb-6">Be honest — it helps us give you the best offer</p>

                        <!-- Condition cards -->
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <button
                                v-for="c in conditions"
                                :key="c.value"
                                @click="condition = c.value"
                                class="p-4 rounded-xl border-2 text-left transition-all"
                                :class="condition === c.value
                                    ? 'border-black bg-gray-50 ring-1 ring-black'
                                    : 'border-gray-200 hover:border-gray-300'"
                            >
                                <span class="text-2xl mb-2 block">{{ c.icon }}</span>
                                <p class="font-bold text-sm">{{ c.label }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ c.desc }}</p>
                            </button>
                        </div>

                        <!-- Storage -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Storage Capacity</label>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="s in storageOptions"
                                    :key="s"
                                    @click="storageCapacity = s"
                                    class="px-4 py-2 rounded-lg text-sm font-medium border-2 transition-all"
                                    :class="storageCapacity === s
                                        ? 'border-black bg-black text-white'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-300'"
                                >
                                    {{ s }}
                                </button>
                            </div>
                        </div>

                        <!-- Battery Health -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <Battery class="w-4 h-4" />
                                Battery Health: <span class="text-black">{{ batteryHealth }}%</span>
                            </label>
                            <input
                                v-model.number="batteryHealth"
                                type="range"
                                min="0"
                                max="100"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black"
                            />
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>Poor (0%)</span>
                                <span>Good (85%+)</span>
                                <span>New (100%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Issues & Valuation -->
                    <div v-if="step === 3" class="space-y-4">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                            <h2 class="text-xl font-bold mb-1">Any issues?</h2>
                            <p class="text-sm text-gray-500 mb-6">Tell us about any problems with the device</p>

                            <div class="space-y-3 mb-6">
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                    :class="hasCracks ? 'border-red-300 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input v-model="hasCracks" type="checkbox" class="rounded border-gray-300 text-red-500 focus:ring-red-500" @change="fetchValuation" />
                                    <AlertTriangle class="w-5 h-5 text-red-400 shrink-0" />
                                    <div>
                                        <p class="text-sm font-medium">Screen cracks or damage</p>
                                        <p class="text-xs text-gray-400">Any cracks, chips, or screen issues</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                    :class="hasRepairs ? 'border-amber-300 bg-amber-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input v-model="hasRepairs" type="checkbox" class="rounded border-gray-300 text-amber-500 focus:ring-amber-500" @change="fetchValuation" />
                                    <Wrench class="w-5 h-5 text-amber-400 shrink-0" />
                                    <div>
                                        <p class="text-sm font-medium">Previous repairs</p>
                                        <p class="text-xs text-gray-400">Screen replacement, battery swap, etc.</p>
                                    </div>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Other problems (optional)</label>
                                <textarea
                                    v-model="problemsDescription"
                                    rows="2"
                                    placeholder="e.g. Speaker not working, charging port loose..."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none transition resize-none"
                                    @blur="fetchValuation"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Valuation Card -->
                        <div v-if="selectedDevice" class="bg-gradient-to-br from-black to-gray-800 rounded-2xl p-6 md:p-8 text-white">
                            <p class="text-sm text-gray-400 mb-1">Estimated Trade-In Value</p>
                            <div v-if="isValuing" class="flex items-center gap-2">
                                <RefreshCw class="w-5 h-5 animate-spin" />
                                <span class="text-lg">Calculating...</span>
                            </div>
                            <p v-else class="text-4xl font-extrabold mb-3">{{ formatPrice(estimatedValue) }}</p>
                            <p class="text-xs text-gray-400">
                                For {{ selectedDevice.name }} · {{ storageCapacity }} · {{ condition }}
                            </p>
                            <p class="text-[10px] text-gray-500 mt-2">* Final offer may vary after physical inspection</p>
                        </div>

                        <div v-if="isCustomDevice" class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700 flex items-start gap-2">
                            <AlertTriangle class="w-5 h-5 shrink-0 mt-0.5" />
                            <p>We can't auto-calculate a value for custom devices. Submit your request and we'll send you an offer via WhatsApp within 24 hours.</p>
                        </div>
                    </div>

                    <!-- Step 4: Contact & Submit -->
                    <div v-if="step === 4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-1">Almost done!</h2>
                        <p class="text-sm text-gray-500 mb-6">How should we reach you?</p>

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                <input v-model="form.customer_name" type="text" placeholder="John Doe" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none transition" />
                                <p v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number (WhatsApp)</label>
                                <input v-model="form.customer_phone" type="tel" placeholder="0712 345 678" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none transition" />
                                <p v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</p>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Device</span><span class="font-medium">{{ selectedDevice?.name || customDevice }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Storage</span><span>{{ storageCapacity }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Condition</span><span class="capitalize">{{ condition }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Battery</span><span>{{ batteryHealth }}%</span></div>
                            <div v-if="hasCracks" class="flex justify-between"><span class="text-gray-500">Cracks</span><span class="text-red-500">Yes</span></div>
                            <div v-if="hasRepairs" class="flex justify-between"><span class="text-gray-500">Repairs</span><span class="text-amber-500">Yes</span></div>
                            <div v-if="estimatedValue" class="flex justify-between pt-2 border-t border-gray-200"><span class="font-bold">Estimated Value</span><span class="font-bold text-green-600">{{ formatPrice(estimatedValue) }}</span></div>
                        </div>

                        <div class="space-y-3">
                            <button
                                @click="submitTradeIn"
                                :disabled="form.processing || !form.customer_name || !form.customer_phone"
                                class="w-full py-3.5 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2"
                            >
                                <CheckCircle class="w-5 h-5" />
                                {{ form.processing ? 'Submitting...' : 'Submit Trade-In Request' }}
                            </button>
                            <a
                                :href="whatsappTradeIn"
                                target="_blank"
                                class="w-full py-3.5 bg-[#25D366] text-white rounded-xl font-semibold hover:bg-[#1fb855] transition active:scale-[0.98] flex items-center justify-center gap-2"
                            >
                                <MessageCircle class="w-5 h-5" />
                                Or Send via WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between mt-6">
                        <button
                            v-if="step > 1"
                            @click="prevStep"
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-black transition"
                        >
                            <ChevronLeft class="w-4 h-4" /> Back
                        </button>
                        <div v-else />

                        <button
                            v-if="step < totalSteps"
                            @click="nextStep"
                            :disabled="!canProceed"
                            class="flex items-center gap-2 px-6 py-2.5 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition active:scale-[0.97] disabled:opacity-40"
                        >
                            Next <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Step Labels -->
                    <div class="flex justify-between mt-4 text-[10px] text-gray-400 font-medium uppercase tracking-wide">
                        <span :class="step >= 1 ? 'text-black' : ''">Device</span>
                        <span :class="step >= 2 ? 'text-black' : ''">Condition</span>
                        <span :class="step >= 3 ? 'text-black' : ''">Issues</span>
                        <span :class="step >= 4 ? 'text-black' : ''">Submit</span>
                    </div>

                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
