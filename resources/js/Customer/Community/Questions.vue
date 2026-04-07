<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import {
    MessageCircle, Search, ChevronRight, ChevronDown, Plus, Send,
    User, Calendar, Check, ThumbsUp, Award, X
} from 'lucide-vue-next';

const props = defineProps({
    questions: { type: Object, required: true },
    products: { type: Array, default: () => [] },
    filters: {
        type: Object,
        default: () => ({ search: '', product: '' }),
    },
});

const showForm = ref(false);
const expandedQuestion = ref(null);
const answeringQuestion = ref(null);
const searchQuery = ref(props.filters?.search || '');
const filterProduct = ref(props.filters?.product || '');
const productSearch = ref('');
const showProductDropdown = ref(false);
const selectedProductName = ref('');

const filteredProducts = computed(() => {
    const q = productSearch.value.toLowerCase();
    if (!q) return props.products.slice(0, 10);
    return props.products.filter(p => p.name.toLowerCase().includes(q)).slice(0, 10);
});

const selectProduct = (product) => {
    form.product_id = product.id;
    selectedProductName.value = product.name;
    productSearch.value = product.name;
    showProductDropdown.value = false;
};

const clearProduct = () => {
    form.product_id = '';
    selectedProductName.value = '';
    productSearch.value = '';
};

const form = useForm({
    question: '',
    asked_by: '',
    product_id: '',
});

const answerForm = useForm({
    answer: '',
    answered_by: '',
});

const toggleQuestion = (id) => {
    expandedQuestion.value = expandedQuestion.value === id ? null : id;
};

const toggleAnswerForm = (id) => {
    answeringQuestion.value = answeringQuestion.value === id ? null : id;
    answerForm.reset();
};

const submitQuestion = () => {
    form.post('/community/questions', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const submitAnswer = (questionId) => {
    answerForm.post(`/community/questions/${questionId}/answers`, {
        preserveScroll: true,
        onSuccess: () => {
            answerForm.reset();
            answeringQuestion.value = null;
        },
    });
};

const performSearch = () => {
    router.get('/community/questions', {
        search: searchQuery.value || undefined,
        product: filterProduct.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (date) => {
    if (!date) return '';
    const now = new Date();
    const d = new Date(date);
    const diff = Math.floor((now - d) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <Head title="Q&A — TechMart Community" />
    <CustomerLayout>
        <!-- Breadcrumb -->
        <section class="bg-white border-b border-gray-100">
            <div class="container mx-auto px-4 py-3">
                <nav class="flex items-center gap-1 text-sm text-gray-500">
                    <Link href="/" class="hover:text-black transition cursor-pointer">Home</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <Link href="/community" class="hover:text-black transition cursor-pointer">Community</Link>
                    <ChevronRight class="w-3.5 h-3.5" />
                    <span class="text-black font-medium">Q&A</span>
                </nav>
            </div>
        </section>

        <section class="py-8 md:py-12 bg-white">
            <div class="container mx-auto px-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-black">Community Q&A</h2>
                    <button
                        @click="showForm = !showForm"
                        class="flex items-center gap-2 px-4 py-2 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97]"
                    >
                        <Plus class="w-4 h-4" />
                        Ask a Question
                    </button>
                </div>

                <!-- Search & Filter Bar -->
                <div class="flex flex-col sm:flex-row gap-3 mb-6">
                    <div class="relative flex-1">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search questions..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                            @keyup.enter="performSearch"
                        />
                    </div>
                    <select
                        v-model="filterProduct"
                        @change="performSearch"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-pointer bg-white min-w-[180px]"
                    >
                        <option value="">All Products</option>
                        <option v-for="product in products" :key="product.id" :value="product.slug">
                            {{ product.name }}
                        </option>
                    </select>
                    <button
                        @click="performSearch"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition cursor-pointer"
                    >
                        <Search class="w-4 h-4" />
                    </button>
                </div>

                <!-- Ask Question Form (Collapsible) -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-if="showForm" class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-8">
                        <h3 class="font-bold text-black mb-4">Ask a Question</h3>
                        <form @submit.prevent="submitQuestion" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Question</label>
                                <textarea
                                    v-model="form.question"
                                    rows="3"
                                    placeholder="What would you like to know?"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition resize-none cursor-text"
                                    required
                                ></textarea>
                                <p v-if="form.errors.question" class="text-red-500 text-xs mt-1">{{ form.errors.question }}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                    <input
                                        v-model="form.asked_by"
                                        type="text"
                                        placeholder="John Doe"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                        required
                                    />
                                    <p v-if="form.errors.asked_by" class="text-red-500 text-xs mt-1">{{ form.errors.asked_by }}</p>
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Related Product (Optional)</label>
                                    <div class="relative">
                                        <input
                                            v-model="productSearch"
                                            type="text"
                                            placeholder="Search products..."
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                            @focus="showProductDropdown = true"
                                            @input="showProductDropdown = true"
                                            autocomplete="off"
                                        />
                                        <button v-if="selectedProductName" @click="clearProduct" type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <!-- Product dropdown -->
                                    <div
                                        v-if="showProductDropdown && filteredProducts.length"
                                        class="absolute z-30 left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-gray-200 max-h-48 overflow-y-auto"
                                    >
                                        <button
                                            v-for="p in filteredProducts"
                                            :key="p.id"
                                            type="button"
                                            @mousedown.prevent="selectProduct(p)"
                                            class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 transition border-b border-gray-50 last:border-0"
                                            :class="form.product_id === p.id ? 'bg-gray-50 font-medium' : ''"
                                        >
                                            {{ p.name }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97] disabled:opacity-50 flex items-center gap-2"
                                >
                                    <Send class="w-4 h-4" />
                                    {{ form.processing ? 'Submitting...' : 'Submit Question' }}
                                </button>
                                <button
                                    type="button"
                                    @click="showForm = false"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition cursor-pointer"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>

                <!-- Questions List -->
                <div v-if="questions.data?.length" class="space-y-3">
                    <div
                        v-for="question in questions.data"
                        :key="question.id"
                        class="bg-white rounded-xl border border-gray-200 overflow-hidden transition-all duration-200"
                    >
                        <!-- Question Header -->
                        <button
                            @click="toggleQuestion(question.id)"
                            class="w-full text-left p-5 flex items-start gap-3 cursor-pointer hover:bg-gray-50 transition"
                        >
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-black text-sm md:text-base">{{ question.question }}</p>
                                <div class="flex items-center gap-3 mt-2 flex-wrap">
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <User class="w-3 h-3" />
                                        {{ question.asked_by }}
                                    </span>
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <Calendar class="w-3 h-3" />
                                        {{ formatDate(question.created_at) }}
                                    </span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                        {{ question.answers?.length || 0 }} {{ (question.answers?.length || 0) === 1 ? 'answer' : 'answers' }}
                                    </span>
                                    <span
                                        v-if="question.product"
                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium truncate max-w-[140px]"
                                    >
                                        {{ question.product.name }}
                                    </span>
                                </div>
                            </div>
                            <ChevronDown
                                class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5 transition-transform duration-200"
                                :class="{ 'rotate-180': expandedQuestion === question.id }"
                            />
                        </button>

                        <!-- Expanded Answers -->
                        <div v-if="expandedQuestion === question.id" class="border-t border-gray-100">
                            <!-- Staff Answer -->
                            <div v-if="question.admin_answer" class="px-5 py-4 bg-gray-50">
                                <div class="ml-4 border-l-2 border-green-500 pl-4">
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ question.admin_answer }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-[10px] bg-black text-white px-1.5 py-0.5 rounded font-semibold">TechMart KE</span>
                                        <span class="text-xs text-gray-400">Official Response</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Community Answers -->
                            <div v-if="question.answers?.length" class="px-5 py-3">
                                <div
                                    v-for="answer in question.answers"
                                    :key="answer.id"
                                    class="ml-6 border-l-2 border-gray-200 pl-4 py-3"
                                >
                                    <p class="text-sm text-gray-700">{{ answer.answer }}</p>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="text-xs text-gray-500 font-medium">{{ answer.answered_by }}</span>
                                        <span
                                            v-if="answer.is_staff"
                                            class="text-[10px] bg-black text-white px-1.5 py-0.5 rounded font-semibold"
                                        >
                                            Staff
                                        </span>
                                        <span
                                            v-if="answer.is_accepted"
                                            class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-semibold flex items-center gap-0.5"
                                        >
                                            <Check class="w-3 h-3" />
                                            Accepted
                                        </span>
                                        <span v-if="answer.helpful_count" class="text-xs text-gray-400 flex items-center gap-0.5">
                                            <ThumbsUp class="w-3 h-3" />
                                            {{ answer.helpful_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="px-5 py-4 text-center text-sm text-gray-400">
                                No answers yet. Be the first to help!
                            </div>

                            <!-- Answer This Question Form -->
                            <div class="px-5 pb-4 border-t border-gray-100 pt-4">
                                <button
                                    v-if="answeringQuestion !== question.id"
                                    @click="toggleAnswerForm(question.id)"
                                    class="text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer flex items-center gap-1"
                                >
                                    <MessageCircle class="w-4 h-4" />
                                    Answer this question
                                </button>
                                <form
                                    v-else
                                    @submit.prevent="submitAnswer(question.id)"
                                    class="space-y-3"
                                >
                                    <textarea
                                        v-model="answerForm.answer"
                                        rows="3"
                                        placeholder="Write your answer..."
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition resize-none cursor-text"
                                        required
                                    ></textarea>
                                    <p v-if="answerForm.errors.answer" class="text-red-500 text-xs">{{ answerForm.errors.answer }}</p>
                                    <input
                                        v-model="answerForm.answered_by"
                                        type="text"
                                        placeholder="Your name"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-black focus:outline-none focus:ring-2 focus:ring-black/5 transition cursor-text"
                                        required
                                    />
                                    <p v-if="answerForm.errors.answered_by" class="text-red-500 text-xs">{{ answerForm.errors.answered_by }}</p>
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="submit"
                                            :disabled="answerForm.processing"
                                            class="px-5 py-2 bg-black text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition cursor-pointer active:scale-[0.97] disabled:opacity-50 flex items-center gap-2"
                                        >
                                            <Send class="w-4 h-4" />
                                            {{ answerForm.processing ? 'Submitting...' : 'Submit Answer' }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="toggleAnswerForm(null)"
                                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition cursor-pointer"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16 text-gray-400">
                    <MessageCircle class="w-16 h-16 mx-auto mb-4 opacity-30" />
                    <p class="text-lg font-medium">No questions yet</p>
                    <p class="text-sm mt-1">Be the first to ask a question!</p>
                </div>

                <!-- Pagination -->
                <div v-if="questions.links?.length > 3" class="flex items-center justify-center gap-1 mt-8">
                    <template v-for="link in questions.links" :key="link.label">
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
