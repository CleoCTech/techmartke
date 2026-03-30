<script setup>
import { computed } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    products: {
        type: Array,
        default: () => [],
    },
    budget: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(['close']);

const formatPrice = (price) => {
    return 'KSh ' + Number(price).toLocaleString();
};

const formattedBudget = computed(() => formatPrice(props.budget));

const productImage = (product) => {
    return product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg';
};
</script>

<template>
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
                v-if="show"
                class="fixed inset-0 z-[2000] flex items-center justify-center p-5"
                style="background: rgba(0, 0, 0, 0.8)"
                @click.self="emit('close')"
            >
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="show"
                        class="bg-white rounded-2xl p-8 max-w-6xl w-full max-h-[90vh] overflow-auto"
                    >
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">Budget Comparison - {{ formattedBudget }}</h3>
                            <button
                                @click="emit('close')"
                                class="text-gray-500 hover:text-black transition"
                            >
                                <X class="w-6 h-6" />
                            </button>
                        </div>

                        <!-- Products Grid -->
                        <div class="grid md:grid-cols-2 gap-8">
                            <div
                                v-for="product in products"
                                :key="product.id"
                                class="border-2 border-black rounded-2xl p-6"
                            >
                                <img
                                    :src="productImage(product)"
                                    :alt="product.name"
                                    class="w-full h-64 object-cover rounded-xl mb-4"
                                />
                                <h4 class="text-2xl font-bold mb-2">{{ product.name }}</h4>
                                <p class="text-3xl font-bold mb-4">
                                    {{ formatPrice(product.base_price || product.price) }}
                                </p>

                                <!-- Advantages -->
                                <div
                                    v-if="product.advantages && product.advantages.length"
                                    class="space-y-3 mb-4"
                                >
                                    <div
                                        v-for="(advantage, idx) in product.advantages"
                                        :key="idx"
                                        class="bg-green-50 border border-green-200 rounded-lg p-3"
                                    >
                                        <p class="text-sm font-medium text-green-800">&#10003; {{ advantage }}</p>
                                    </div>
                                </div>

                                <!-- Specs -->
                                <div
                                    v-if="product.specs && Object.keys(product.specs).length"
                                    class="space-y-2 text-sm text-gray-600"
                                >
                                    <p v-for="(value, label) in product.specs" :key="label">
                                        <strong>{{ label }}:</strong> {{ value }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- AI Recommendation -->
                        <div
                            v-if="products.length"
                            class="mt-8 bg-gray-50 rounded-xl p-6"
                        >
                            <h4 class="font-bold mb-3">AI Recommendation</h4>
                            <p class="text-gray-700">
                                <slot name="recommendation">
                                    Based on your budget of {{ formattedBudget }}, we recommend comparing the
                                    features and specifications above to find the best match for your needs.
                                </slot>
                            </p>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-if="!products.length"
                            class="text-center py-16 text-gray-500"
                        >
                            <p class="text-lg">No products found within your budget.</p>
                            <p class="text-sm mt-2">Try adjusting your budget to see more options.</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
