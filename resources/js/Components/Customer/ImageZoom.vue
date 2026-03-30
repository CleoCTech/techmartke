<script setup>
import { ref, computed } from 'vue';
import { X, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    images: {
        type: Array,
        default: () => [],
    },
    initialIndex: {
        type: Number,
        default: 0,
    },
});

const currentIndex = ref(props.initialIndex);
const isFullscreen = ref(false);

const currentImage = computed(() => {
    if (!props.images.length) return null;
    return props.images[currentIndex.value];
});

const imageUrl = (image) => {
    if (typeof image === 'string') return image;
    return image?.url || image?.path || image?.src || '';
};

const imageAlt = (image) => {
    if (typeof image === 'string') return '';
    return image?.alt || image?.name || '';
};

const selectImage = (index) => {
    currentIndex.value = index;
};

const openFullscreen = () => {
    isFullscreen.value = true;
};

const closeFullscreen = () => {
    isFullscreen.value = false;
};

const nextImage = () => {
    if (currentIndex.value < props.images.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = props.images.length - 1;
    }
};
</script>

<template>
    <div v-if="images.length">
        <!-- Main Image -->
        <div
            class="relative w-full h-96 md:h-[500px] bg-gray-100 rounded-2xl overflow-hidden cursor-zoom-in group"
            @click="openFullscreen"
        >
            <img
                :src="imageUrl(currentImage)"
                :alt="imageAlt(currentImage)"
                class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
            />

            <!-- Navigation Arrows (main view) -->
            <button
                v-if="images.length > 1"
                @click.stop="prevImage"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow transition opacity-0 group-hover:opacity-100"
            >
                <ChevronLeft class="w-5 h-5" />
            </button>
            <button
                v-if="images.length > 1"
                @click.stop="nextImage"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow transition opacity-0 group-hover:opacity-100"
            >
                <ChevronRight class="w-5 h-5" />
            </button>
        </div>

        <!-- Thumbnail Strip -->
        <div v-if="images.length > 1" class="flex gap-3 mt-4 overflow-x-auto pb-2">
            <button
                v-for="(image, index) in images"
                :key="index"
                @click="selectImage(index)"
                :class="[
                    'w-20 h-20 rounded-xl overflow-hidden border-2 shrink-0 transition',
                    currentIndex === index
                        ? 'border-black shadow-md'
                        : 'border-gray-200 hover:border-gray-400',
                ]"
            >
                <img
                    :src="imageUrl(image)"
                    :alt="imageAlt(image)"
                    class="w-full h-full object-cover"
                />
            </button>
        </div>

        <!-- Fullscreen Overlay -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isFullscreen"
                    class="fixed inset-0 z-[3000] bg-black/90 flex items-center justify-center"
                    @click.self="closeFullscreen"
                >
                    <!-- Close Button -->
                    <button
                        @click="closeFullscreen"
                        class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <!-- Navigation -->
                    <button
                        v-if="images.length > 1"
                        @click="prevImage"
                        class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"
                    >
                        <ChevronLeft class="w-6 h-6" />
                    </button>
                    <button
                        v-if="images.length > 1"
                        @click="nextImage"
                        class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"
                    >
                        <ChevronRight class="w-6 h-6" />
                    </button>

                    <!-- Zoomed Image -->
                    <img
                        :src="imageUrl(currentImage)"
                        :alt="imageAlt(currentImage)"
                        class="max-w-[90vw] max-h-[90vh] object-contain"
                    />

                    <!-- Image Counter -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-sm">
                        {{ currentIndex + 1 }} / {{ images.length }}
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
