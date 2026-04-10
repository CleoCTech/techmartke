<script setup>
import { MessageCircle, Phone, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';
import { useCompanyInfo } from '@/Composables/useCompanyInfo';

const props = defineProps({
    shopMapUrl: { type: String, default: 'https://www.google.com/maps/search/?api=1&query=Sawa+Mall+Moi+Avenue+Nairobi' },
});

const { whatsappUrl: companyWhatsappUrl, callUrl } = useCompanyInfo();

const whatsappUrl = computed(() => companyWhatsappUrl("Hi TechMart KE! I'm interested in buying a device. Can you help?"));
</script>

<template>
    <!-- Mobile Bottom Sticky Bar — sleek, minimal, thin-line icons -->
    <div class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white/95 backdrop-blur-xl border-t border-[#E5E5EA] safe-bottom">
        <div class="grid grid-cols-3 divide-x divide-[#E5E5EA]">
            <a
                :href="whatsappUrl"
                target="_blank"
                rel="noopener"
                class="flex flex-col items-center gap-0.5 py-2.5 text-[#1A531B] active:bg-[#F5F5F7] transition cursor-pointer"
            >
                <MessageCircle class="w-5 h-5" :stroke-width="1.5" />
                <span class="text-[10px] font-semibold">WhatsApp</span>
            </a>
            <a
                :href="callUrl"
                class="flex flex-col items-center gap-0.5 py-2.5 text-[#1D1D1F] active:bg-[#F5F5F7] transition cursor-pointer"
            >
                <Phone class="w-5 h-5" :stroke-width="1.5" />
                <span class="text-[10px] font-semibold">Call Now</span>
            </a>
            <a
                :href="shopMapUrl"
                target="_blank"
                rel="noopener"
                class="flex flex-col items-center gap-0.5 py-2.5 text-[#1D1D1F] active:bg-[#F5F5F7] transition cursor-pointer"
            >
                <MapPin class="w-5 h-5" :stroke-width="1.5" />
                <span class="text-[10px] font-semibold">Visit Shop</span>
            </a>
        </div>
    </div>

    <!-- Desktop Floating — only WhatsApp icon (the only emerald accent) -->
    <div class="hidden md:flex fixed right-5 bottom-6 z-40 flex-col gap-2.5">
        <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener"
            class="w-14 h-14 bg-[#1A531B] hover:bg-[#006241] text-white rounded-full flex items-center justify-center transition-all cursor-pointer hover:scale-110 shadow-lg"
            title="WhatsApp Us"
        >
            <MessageCircle class="w-6 h-6" />
        </a>
    </div>
</template>

<style scoped>
.safe-bottom {
    padding-bottom: env(safe-area-inset-bottom, 0);
}
</style>
