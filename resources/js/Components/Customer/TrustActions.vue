<script setup>
import { MessageCircle, Phone, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const props = defineProps({
    product: { type: Object, default: null },
    layout: { type: String, default: 'buttons' }, // 'buttons' | 'cards' | 'inline'
    shopAddress: { type: String, default: '' },
    shopMapUrl: { type: String, default: 'https://www.google.com/maps/search/?api=1&query=Sawa+Mall+Moi+Avenue+Nairobi' },
});

const { whatsappUrl: companyWhatsappUrl, callUrl, phoneDisplay, address } = useCompanyInfo();

const displayAddress = computed(() => props.shopAddress || address.value || 'Nairobi, Kenya');

const whatsappMessage = computed(() => {
    if (props.product) {
        const price = Number(props.product.base_price || props.product.price || 0).toLocaleString();
        const variant = props.product.selectedVariant || '';
        return `Hi TechMart KE! I'm interested in the *${props.product.name}*${variant ? ' (' + variant + ')' : ''} listed at KSh ${price}. Is it available? I'd like to know more.`;
    }
    return "Hi TechMart KE! I'm looking for a phone/laptop. Can you help me find the best deal?";
});

const whatsappUrl = computed(() => companyWhatsappUrl(whatsappMessage.value));
const formatPhone = computed(() => phoneDisplay.value);
</script>

<template>
    <!-- Full CTA Buttons (for product page / sections) -->
    <div v-if="layout === 'buttons'" class="space-y-3">
        <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener"
            class="flex items-center justify-center gap-3 w-full py-3.5 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-xl font-semibold transition-all active:scale-[0.98] cursor-pointer shadow-md shadow-green-500/20 hover:shadow-lg hover:shadow-green-500/30 text-sm sm:text-base"
        >
            <MessageCircle class="w-5 h-5" />
            Inquire on WhatsApp
        </a>
        <a
            :href="callUrl"
            class="flex items-center justify-center gap-3 w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all active:scale-[0.98] cursor-pointer shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 text-sm sm:text-base"
        >
            <Phone class="w-5 h-5" />
            Call Us Now
        </a>
        <a
            :href="shopMapUrl"
            target="_blank"
            rel="noopener"
            class="flex items-center justify-center gap-3 w-full py-3.5 bg-gray-900 hover:bg-black text-white rounded-xl font-semibold transition-all active:scale-[0.98] cursor-pointer shadow-md shadow-black/20 hover:shadow-lg hover:shadow-black/30 text-sm sm:text-base"
        >
            <MapPin class="w-5 h-5" />
            Visit Shop &mdash; Get Directions
        </a>
    </div>

    <!-- Card Layout (for homepage section) -->
    <div v-else-if="layout === 'cards'" class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener"
            class="group bg-white rounded-2xl border border-gray-100 hover:border-green-200 p-5 sm:p-6 text-center transition-all hover:shadow-xl hover:-translate-y-1 cursor-pointer"
        >
            <div class="w-14 h-14 bg-[#25D366]/10 group-hover:bg-[#25D366] rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                <MessageCircle class="w-7 h-7 text-[#25D366] group-hover:text-white transition-colors duration-300" />
            </div>
            <h4 class="font-bold text-sm sm:text-base text-gray-900 mb-1">Inquire on WhatsApp</h4>
            <p class="text-xs text-gray-500">Chat with us instantly</p>
        </a>
        <a
            :href="callUrl"
            class="group bg-white rounded-2xl border border-gray-100 hover:border-blue-200 p-5 sm:p-6 text-center transition-all hover:shadow-xl hover:-translate-y-1 cursor-pointer"
        >
            <div class="w-14 h-14 bg-blue-50 group-hover:bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                <Phone class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors duration-300" />
            </div>
            <h4 class="font-bold text-sm sm:text-base text-gray-900 mb-1">Call Us Now</h4>
            <p class="text-xs text-gray-500">{{ formatPhone }}</p>
        </a>
        <a
            :href="shopMapUrl"
            target="_blank"
            rel="noopener"
            class="group bg-white rounded-2xl border border-gray-100 hover:border-gray-300 p-5 sm:p-6 text-center transition-all hover:shadow-xl hover:-translate-y-1 cursor-pointer"
        >
            <div class="w-14 h-14 bg-gray-100 group-hover:bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                <MapPin class="w-7 h-7 text-gray-700 group-hover:text-white transition-colors duration-300" />
            </div>
            <h4 class="font-bold text-sm sm:text-base text-gray-900 mb-1">Visit Our Shop</h4>
            <p class="text-xs text-gray-500">Luthuli Ave, Nairobi</p>
        </a>
    </div>

    <!-- Inline compact (for product cards, smaller spaces) -->
    <div v-else-if="layout === 'inline'" class="flex gap-2">
        <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener"
            class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-[#25D366] hover:bg-[#1fb855] text-white rounded-lg text-xs font-semibold transition-all cursor-pointer"
            @click.stop
        >
            <MessageCircle class="w-3.5 h-3.5" />
            WhatsApp
        </a>
        <a
            :href="callUrl"
            class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-all cursor-pointer"
            @click.stop
        >
            <Phone class="w-3.5 h-3.5" />
            Call
        </a>
        <a
            :href="shopMapUrl"
            target="_blank"
            rel="noopener"
            class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-xs font-semibold transition-all cursor-pointer"
            @click.stop
        >
            <MapPin class="w-3.5 h-3.5" />
            Visit
        </a>
    </div>
</template>
