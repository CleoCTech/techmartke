<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { Zap } from 'lucide-vue-next';

const props = defineProps({
    minDuration: { type: Number, default: 600 }, // ms minimum so it doesn't flash
});

const visible = ref(true);
let navStartTime = 0;
let safetyTimer = null;
let removeStart = null;
let removeFinish = null;

const showLoader = () => {
    visible.value = true;
    navStartTime = Date.now();
    if (safetyTimer) clearTimeout(safetyTimer);
    // Hard cap so loader never gets stuck if a request hangs
    safetyTimer = setTimeout(() => { visible.value = false; }, 4000);
};

const hideLoader = () => {
    const elapsed = Date.now() - navStartTime;
    const remaining = Math.max(0, props.minDuration - elapsed);
    setTimeout(() => {
        visible.value = false;
        if (safetyTimer) { clearTimeout(safetyTimer); safetyTimer = null; }
    }, remaining);
};

onMounted(() => {
    // Initial page load
    navStartTime = Date.now();
    const finishInitial = () => hideLoader();

    if (document.readyState === 'complete') {
        finishInitial();
    } else {
        window.addEventListener('load', finishInitial, { once: true });
        safetyTimer = setTimeout(() => { visible.value = false; }, 4000);
    }

    // Inertia navigation between pages
    removeStart = router.on('start', () => showLoader());
    removeFinish = router.on('finish', () => hideLoader());
});

onUnmounted(() => {
    if (safetyTimer) clearTimeout(safetyTimer);
    if (removeStart) removeStart();
    if (removeFinish) removeFinish();
});
</script>

<template>
    <Transition
        enter-active-class="transition-opacity duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-500 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 scale-110"
    >
        <div
            v-if="visible"
            class="fixed inset-0 z-[9999] bg-white flex items-center justify-center"
        >
            <!-- Ambient glow rings behind the logo -->
            <div class="absolute w-72 h-72 bg-amber-300/20 rounded-full blur-3xl animate-pulse-slow" />
            <div class="absolute w-48 h-48 bg-amber-400/30 rounded-full blur-2xl animate-pulse-slower" />

            <!-- Logo container -->
            <div class="relative flex flex-col items-center gap-5">
                <!-- Logo with glow + pulse -->
                <div class="relative">
                    <!-- Outer expanding ring -->
                    <div class="absolute inset-0 rounded-2xl ring-2 ring-amber-400/50 animate-ring-expand" />
                    <div class="absolute inset-0 rounded-2xl ring-2 ring-amber-400/50 animate-ring-expand animation-delay-500" />

                    <!-- The logo box itself -->
                    <div class="relative w-20 h-20 bg-black rounded-2xl flex items-center justify-center shadow-[0_0_60px_rgba(251,191,36,0.6)] animate-logo-pulse">
                        <!-- Inner light gradient for "powered up" feel -->
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-amber-400/0 via-amber-400/0 to-amber-400/20 animate-shimmer" />
                        <Zap class="w-10 h-10 text-white relative z-10 drop-shadow-[0_0_8px_rgba(251,191,36,0.9)] animate-bolt-glow" fill="currentColor" />
                    </div>
                </div>

                <!-- Brand name with shimmer -->
                <div class="text-center">
                    <h1 class="text-xl font-bold tracking-tight text-black animate-fade-in">
                        TechMart<span class="text-gray-400">KE</span>
                    </h1>
                    <!-- Loading dots -->
                    <div class="flex items-center justify-center gap-1 mt-2">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce-dot" />
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce-dot animation-delay-200" />
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce-dot animation-delay-400" />
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Logo box pulsing power glow */
@keyframes logo-pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 40px rgba(251, 191, 36, 0.5), 0 0 80px rgba(251, 191, 36, 0.2);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 60px rgba(251, 191, 36, 0.8), 0 0 120px rgba(251, 191, 36, 0.4);
    }
}
.animate-logo-pulse {
    animation: logo-pulse 1.6s ease-in-out infinite;
}

/* Lightning bolt internal glow */
@keyframes bolt-glow {
    0%, 100% {
        filter: drop-shadow(0 0 6px rgba(251, 191, 36, 0.7)) drop-shadow(0 0 14px rgba(251, 191, 36, 0.4));
    }
    50% {
        filter: drop-shadow(0 0 12px rgba(251, 191, 36, 1)) drop-shadow(0 0 24px rgba(251, 191, 36, 0.7));
    }
}
.animate-bolt-glow {
    animation: bolt-glow 1.2s ease-in-out infinite;
}

/* Expanding rings radiating outward */
@keyframes ring-expand {
    0% {
        transform: scale(1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1.8);
        opacity: 0;
    }
}
.animate-ring-expand {
    animation: ring-expand 1.8s ease-out infinite;
}
.animation-delay-500 {
    animation-delay: 0.9s;
}

/* Inner shimmer sweep */
@keyframes shimmer {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.7; }
}
.animate-shimmer {
    animation: shimmer 1.6s ease-in-out infinite;
}

/* Ambient background pulses */
@keyframes pulse-slow {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}
.animate-pulse-slow {
    animation: pulse-slow 2.5s ease-in-out infinite;
}
@keyframes pulse-slower {
    0%, 100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.15); }
}
.animate-pulse-slower {
    animation: pulse-slower 1.8s ease-in-out infinite;
}

/* Loading dots */
@keyframes bounce-dot {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30% { transform: translateY(-4px); opacity: 1; }
}
.animate-bounce-dot {
    animation: bounce-dot 1.2s ease-in-out infinite;
}
.animation-delay-200 {
    animation-delay: 0.15s;
}
.animation-delay-400 {
    animation-delay: 0.3s;
}

/* Brand name fade */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.6s ease-out 0.2s both;
}
</style>
