<script setup>
/**
 * PWA Prompt: Handles two UX flows
 *   1) Install banner — shows when the browser fires `beforeinstallprompt`,
 *      offers a one-tap install to home screen. Dismissible.
 *   2) Update banner — shows when the service worker detects a new
 *      deployment, offers a one-tap refresh to get latest version.
 */
import { ref, onMounted, onUnmounted } from 'vue';
import { Download, RefreshCw, X, Zap } from 'lucide-vue-next';

// ===== Install prompt state =====
const installPromptEvent = ref(null);
const showInstall = ref(false);

const DISMISS_KEY = 'techmart_pwa_install_dismissed';
const DISMISS_TTL = 7 * 24 * 60 * 60 * 1000; // 7 days

const wasDismissedRecently = () => {
    try {
        const ts = parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
        return ts && (Date.now() - ts) < DISMISS_TTL;
    } catch { return false; }
};

const onBeforeInstallPrompt = (e) => {
    e.preventDefault();
    installPromptEvent.value = e;
    if (!wasDismissedRecently()) {
        // Delay a few seconds so user can orient themselves on the page first
        setTimeout(() => { showInstall.value = true; }, 3000);
    }
};

const installApp = async () => {
    if (!installPromptEvent.value) return;
    installPromptEvent.value.prompt();
    try {
        const { outcome } = await installPromptEvent.value.userChoice;
        if (outcome === 'accepted') {
            showInstall.value = false;
        }
    } catch {}
    installPromptEvent.value = null;
};

const dismissInstall = () => {
    showInstall.value = false;
    try { localStorage.setItem(DISMISS_KEY, String(Date.now())); } catch {}
};

// ===== Update prompt state =====
const showUpdate = ref(false);
let registration = null;
let wb = null;

const setupServiceWorker = async () => {
    if (!('serviceWorker' in navigator)) return;
    try {
        const { Workbox } = await import('workbox-window');
        wb = new Workbox('/sw.js');

        wb.addEventListener('waiting', () => {
            showUpdate.value = true;
        });

        wb.addEventListener('controlling', () => {
            window.location.reload();
        });

        registration = await wb.register();
    } catch (err) {
        // Silently fail — PWA is a progressive enhancement
        console.warn('Service worker registration failed:', err);
    }
};

const applyUpdate = () => {
    if (!wb) return;
    wb.messageSkipWaiting();
    showUpdate.value = false;
};

const dismissUpdate = () => { showUpdate.value = false; };

onMounted(() => {
    window.addEventListener('beforeinstallprompt', onBeforeInstallPrompt);
    setupServiceWorker();
});

onUnmounted(() => {
    window.removeEventListener('beforeinstallprompt', onBeforeInstallPrompt);
});
</script>

<template>
    <!-- Install App banner (appears at bottom on mobile) -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="showInstall"
            class="fixed bottom-16 md:bottom-6 left-4 right-4 md:left-auto md:right-6 md:max-w-sm z-50 bg-white rounded-2xl shadow-2xl border border-[#E5E5EA] p-4"
        >
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-black rounded-xl flex items-center justify-center flex-shrink-0">
                    <Zap class="w-5 h-5 text-white" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-black">Install TechMart KE</p>
                    <p class="text-xs text-[#86868B] mt-0.5 leading-snug">
                        Add to your home screen for faster access and offline browsing.
                    </p>
                    <div class="flex gap-2 mt-3">
                        <button
                            @click="installApp"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-black text-white rounded-full text-xs font-bold hover:bg-[#1D1D1F] transition cursor-pointer"
                        >
                            <Download class="w-3.5 h-3.5" />
                            Install
                        </button>
                        <button
                            @click="dismissInstall"
                            class="px-4 py-2 border border-[#E5E5EA] text-[#1D1D1F] rounded-full text-xs font-semibold hover:bg-[#F5F5F7] transition cursor-pointer"
                        >
                            Not now
                        </button>
                    </div>
                </div>
                <button
                    @click="dismissInstall"
                    class="text-[#C7C7CC] hover:text-black transition cursor-pointer p-0.5"
                    aria-label="Close"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>
        </div>
    </Transition>

    <!-- Update available banner (top of screen, urgent) -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
    >
        <div
            v-if="showUpdate"
            class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:max-w-sm z-[100] bg-black text-white rounded-2xl shadow-2xl p-4"
        >
            <div class="flex items-start gap-3">
                <RefreshCw class="w-5 h-5 text-white flex-shrink-0 mt-0.5 animate-spin-slow" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold">New version available</p>
                    <p class="text-xs text-white/70 mt-0.5 leading-snug">
                        Refresh to get the latest features and improvements.
                    </p>
                    <div class="flex gap-2 mt-3">
                        <button
                            @click="applyUpdate"
                            class="flex-1 py-2 bg-white text-black rounded-full text-xs font-bold hover:bg-[#F5F5F7] transition cursor-pointer"
                        >
                            Refresh now
                        </button>
                        <button
                            @click="dismissUpdate"
                            class="px-4 py-2 border border-white/20 text-white rounded-full text-xs font-semibold hover:bg-white/10 transition cursor-pointer"
                        >
                            Later
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@keyframes spin-slow {
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 3s linear infinite;
}
</style>
