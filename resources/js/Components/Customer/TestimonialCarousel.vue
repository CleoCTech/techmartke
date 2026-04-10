<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Star, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    reviews: { type: Array, default: () => [] },
    totalCount: { type: Number, default: 0 },
});

const current = ref(0);
let autoplayTimer = null;

const next = () => {
    if (props.reviews.length > 0) {
        current.value = (current.value + 1) % props.reviews.length;
    }
};
const prev = () => {
    if (props.reviews.length > 0) {
        current.value = (current.value - 1 + props.reviews.length) % props.reviews.length;
    }
};

const activeReview = computed(() => props.reviews[current.value] || null);
const stars = (n) => Array.from({ length: 5 }, (_, i) => i < n);

// Touch swipe support
let touchStartX = 0;
const onTouchStart = (e) => { touchStartX = e.changedTouches[0].screenX; };
const onTouchEnd = (e) => {
    const diff = touchStartX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 40) { diff > 0 ? next() : prev(); }
};

onMounted(() => {
    if (props.reviews.length > 1) {
        autoplayTimer = setInterval(next, 5000);
    }
});
onUnmounted(() => {
    if (autoplayTimer) clearInterval(autoplayTimer);
});
</script>

<template>
    <div v-if="reviews.length" class="text-center">
        <!-- Heading -->
        <h3 class="text-lg md:text-2xl font-extrabold text-black mb-1">
            Trusted by {{ totalCount > 100 ? totalCount.toLocaleString() + '+' : '' }} Happy Customers
        </h3>
        <p class="text-[#86868B] text-xs md:text-sm mb-6">
            See it. Touch it. Trust it. Visit, call, or chat with us
        </p>

        <!-- Carousel Card -->
        <div
            class="relative max-w-md mx-auto"
            @touchstart="onTouchStart"
            @touchend="onTouchEnd"
        >
            <Transition
                mode="out-in"
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 translate-x-4"
                enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 -translate-x-4"
            >
                <div
                    v-if="activeReview"
                    :key="current"
                    class="bg-white rounded-2xl shadow-lg border border-[#E5E5EA] p-6 md:p-8"
                >
                    <!-- Stars -->
                    <div class="flex justify-center gap-0.5 mb-4">
                        <Star
                            v-for="(filled, i) in stars(activeReview.rating)"
                            :key="i"
                            class="w-5 h-5"
                            :class="filled ? 'text-black fill-black' : 'text-[#E5E5EA]'"
                        />
                    </div>

                    <!-- Quote -->
                    <p class="text-base md:text-lg font-bold text-black leading-snug mb-4 italic">
                        "{{ activeReview.review }}"
                    </p>

                    <!-- Author -->
                    <p class="text-sm font-semibold text-[#1D1D1F]">{{ activeReview.customer_name }}</p>
                    <p v-if="activeReview.location" class="text-xs text-[#86868B]">{{ activeReview.location }}</p>
                </div>
            </Transition>
        </div>

        <!-- Dots — uniform circles, opacity-based active state -->
        <div v-if="reviews.length > 1" class="flex justify-center gap-2.5 mt-5">
            <button
                v-for="(_, i) in reviews"
                :key="i"
                @click="current = i"
                class="w-2 h-2 rounded-full bg-black transition-opacity duration-300 cursor-pointer"
                :class="current === i ? 'opacity-100' : 'opacity-20 hover:opacity-40'"
            />
        </div>
    </div>
</template>
