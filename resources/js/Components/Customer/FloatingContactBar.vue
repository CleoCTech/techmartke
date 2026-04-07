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
    <!-- Mobile Bottom Sticky Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] safe-bottom">
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            <a
                :href="whatsappUrl"
                target="_blank"
                rel="noopener"
                class="flex flex-col items-center gap-1 py-2.5 text-[#25D366] active:bg-green-50 transition cursor-pointer"
            >
                <MessageCircle class="w-5 h-5" />
                <span class="text-[10px] font-semibold">WhatsApp</span>
            </a>
            <a
                :href="callUrl"
                class="flex flex-col items-center gap-1 py-2.5 text-blue-600 active:bg-blue-50 transition cursor-pointer"
            >
                <Phone class="w-5 h-5" />
                <span class="text-[10px] font-semibold">Call Now</span>
            </a>
            <a
                :href="shopMapUrl"
                target="_blank"
                rel="noopener"
                class="flex flex-col items-center gap-1 py-2.5 text-gray-700 active:bg-gray-50 transition cursor-pointer"
            >
                <MapPin class="w-5 h-5" />
                <span class="text-[10px] font-semibold">Visit Shop</span>
            </a>
        </div>
    </div>

    <!-- Desktop Floating Side Buttons -->
    <div class="hidden md:flex fixed right-4 bottom-6 z-40 flex-col gap-2">
        <a
            :href="whatsappUrl"
            target="_blank"
            rel="noopener"
            class="group flex items-center gap-2 bg-[#25D366] hover:bg-[#1fb855] text-white pl-4 pr-5 py-3 rounded-full shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all cursor-pointer hover:scale-105"
        >
            <MessageCircle class="w-5 h-5" />
            <span class="text-sm font-semibold">WhatsApp Us</span>
        </a>
        <a
            :href="callUrl"
            class="group flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white pl-4 pr-5 py-3 rounded-full shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all cursor-pointer hover:scale-105"
        >
            <Phone class="w-5 h-5" />
            <span class="text-sm font-semibold">Call Now</span>
        </a>
        <a
            :href="shopMapUrl"
            target="_blank"
            rel="noopener"
            class="group flex items-center gap-2 bg-gray-900 hover:bg-black text-white pl-4 pr-5 py-3 rounded-full shadow-lg shadow-black/30 hover:shadow-xl hover:shadow-black/40 transition-all cursor-pointer hover:scale-105"
        >
            <MapPin class="w-5 h-5" />
            <span class="text-sm font-semibold">Visit Shop</span>
        </a>
    </div>
</template>

<style scoped>
.safe-bottom {
    padding-bottom: env(safe-area-inset-bottom, 0);
}
</style>
