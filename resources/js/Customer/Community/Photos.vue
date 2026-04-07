<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    Camera, Heart, ChevronRight, Plus, Send, User, X, Upload, Image as ImageIcon
} from 'lucide-vue-next';

const props = defineProps({
    photos: { type: Object, required: true },
});

const showForm = ref(false);
const showFlash = ref(false);
const photoPreview = ref(null);
const fileError = ref('');

const form = useForm({
    photo: null,
    caption: '',
    customer_name: '',
});

const handleFileSelect = (e) => {
    const file = e.target.files[0];
    fileError.value = '';
    if (!file) return;

    // Validate type
    if (!file.type.startsWith('image/')) {
        fileError.value = 'Please select a valid image file.';
        e.target.value = '';
        return;
    }
    // Validate size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        fileError.value = `File is too large (${(file.size / 1024 / 1024).toFixed(1)}MB). Maximum size is 5MB.`;
        e.target.value = '';
        return;
    }

    form.photo = file;
    photoPreview.value = URL.createObjectURL(file);
};

const removePhoto = () => {
    form.photo = null;
    photoPreview.value = null;
    fileError.value = '';
};

const submitPhoto = () => {
    if (!form.photo) {
        fileError.value = 'Please select a photo to upload.';
        return;
    }
    form.post('/community/photos', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
            showForm.value = false;
            showFlash.value = true;
            setTimeout(() => (showFlash.value = false), 4000);
        },
    });
};
</script>

<template>
    <Head title="Photo Gallery — TechMart Community" />
    <CustomerLayout>
        <!-- Breadcrumb -->
        <section class="bg-white border-b border-gray-100">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center gap-1 text-sm text-gray-500">
                    <Link href="/" class="hover:text-black transition cursor-pointer">Home</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <Link href="/community" class="hover:text-black transition cursor-pointer">Community</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <span class="text-black font-medium">Photos</span>
                </nav>
            </div>
        </section>

        <section class="py-8 md:py-12 bg-white">
            <div class="container mx-auto px-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-black">Customer Gallery</h2>
                    <button
                        @click="showForm = !showForm"
                        class="flex items-center gap-2 px-4 py-2 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97]"
                    >
                        <Plus class="w-4 h-4" />
                        Share Photo
                    </button>
                </div>

                <!-- Flash Message -->
                <div
                    v-if="showFlash"
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center justify-between"
                >
                    <span>Photo submitted successfully! It will appear after review.</span>
                    <button @click="showFlash = false" class="cursor-pointer">
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <!-- Submit Form (Collapsible) -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2 max-h-0"
                    enter-to-class="opacity-100 translate-y-0 max-h-96"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0 max-h-96"
                    leave-to-class="opacity-0 -translate-y-2 max-h-0"
                >
                    <div v-if="showForm" class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-8">
                        <h3 class="font-bold text-black mb-4">Share Your Photo</h3>
                        <form @submit.prevent="submitPhoto" class="space-y-4">
                            <!-- Photo Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                <div v-if="!photoPreview" class="relative">
                                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-black hover:bg-gray-100 transition-all">
                                        <Upload class="w-8 h-8 text-gray-400 mb-2" />
                                        <span class="text-sm font-medium text-gray-600">Click to upload photo</span>
                                        <span class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WebP — max 5MB</span>
                                        <input
                                            type="file"
                                            accept="image/*"
                                            @change="handleFileSelect"
                                            class="hidden"
                                        />
                                    </label>
                                </div>
                                <div v-else class="relative w-40 h-40 rounded-xl overflow-hidden border-2 border-gray-200">
                                    <img :src="photoPreview" class="w-full h-full object-cover" alt="Preview" />
                                    <button
                                        type="button"
                                        @click="removePhoto"
                                        class="absolute top-1.5 right-1.5 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition"
                                    >
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>
                                <p v-if="fileError" class="text-red-500 text-xs mt-1">{{ fileError }}</p>
                                <p v-if="form.errors.photo" class="text-red-500 text-xs mt-1">{{ form.errors.photo }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                                <textarea
                                    v-model="form.caption"
                                    rows="2"
                                    placeholder="Tell us about this photo..."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition resize-none cursor-text"
                                ></textarea>
                                <p v-if="form.errors.caption" class="text-red-500 text-xs mt-1">{{ form.errors.caption }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                <input
                                    v-model="form.customer_name"
                                    type="text"
                                    placeholder="John Doe"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                    required
                                />
                                <p v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing || !form.photo"
                                    class="px-6 py-2.5 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97] disabled:opacity-50 flex items-center gap-2"
                                >
                                    <Send class="w-4 h-4" />
                                    {{ form.processing ? 'Uploading...' : 'Submit Photo' }}
                                </button>
                                <button
                                    type="button"
                                    @click="showForm = false; removePhoto()"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition cursor-pointer"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>

                <!-- Photo Grid -->
                <div v-if="photos.data?.length" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <div
                        v-for="photo in photos.data"
                        :key="photo.id"
                        class="group relative rounded-xl overflow-hidden cursor-pointer aspect-square"
                    >
                        <img
                            :src="photo.image_url"
                            :alt="photo.caption || 'Customer photo'"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex flex-col justify-end p-3">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white">
                                <p v-if="photo.caption" class="text-sm font-medium truncate">{{ photo.caption }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-white/70 flex items-center gap-1">
                                        <User class="w-3 h-3" />
                                        {{ photo.customer_name }}
                                    </span>
                                    <span class="text-xs text-white/70 flex items-center gap-1">
                                        <Heart class="w-3 h-3" />
                                        {{ photo.likes_count || 0 }}
                                    </span>
                                </div>
                                <span
                                    v-if="photo.product"
                                    class="inline-block mt-1 px-2 py-0.5 bg-white/20 rounded text-[10px] font-medium truncate max-w-full"
                                >
                                    {{ photo.product.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16 text-gray-400">
                    <Camera class="w-16 h-16 mx-auto mb-4 opacity-30" />
                    <p class="text-lg font-medium">No photos yet</p>
                    <p class="text-sm mt-1">Be the first to share a photo!</p>
                </div>

                <!-- Pagination -->
                <div v-if="photos.links?.length > 3" class="flex items-center justify-center gap-1 mt-8">
                    <template v-for="link in photos.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="px-3 py-2 text-sm rounded-lg transition cursor-pointer"
                            :class="link.active
                                ? 'bg-black text-white font-semibold'
                                : 'text-gray-600 hover:bg-gray-100'"
                            v-html="link.label"
                            preserve-scroll
                        />
                        <span
                            v-else
                            class="px-3 py-2 text-sm text-gray-300"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>
