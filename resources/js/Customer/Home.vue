<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { Search, Zap, Shield, Truck, ArrowRight, Star, Smartphone, Monitor, Laptop, Tablet, Headphones, Flame, Clock, ShoppingCart, Sparkles, MessageCircle } from 'lucide-vue-next';
import TrustActions from '@/Components/Customer/TrustActions.vue';
import TestimonialCarousel from '@/Components/Customer/TestimonialCarousel.vue';

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    flashSaleProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    searchHints: {
        type: Array,
        default: () => [],
    },
    brandNames: {
        type: Array,
        default: () => [],
    },
    categoryNames: {
        type: Array,
        default: () => [],
    },
    reviews: {
        type: Array,
        default: () => [],
    },
    reviewCount: {
        type: Number,
        default: 0,
    },
});

// Review form — uses fetch instead of Inertia to avoid full page reload + loader
const showReviewForm = ref(false);
const reviewSubmitting = ref(false);
const reviewSuccess = ref(false);
const reviewError = ref('');
const reviewForm = ref({
    customer_name: '',
    location: '',
    rating: 5,
    review: '',
});
const submitReview = async () => {
    reviewSubmitting.value = true;
    reviewError.value = '';
    try {
        const res = await fetch('/reviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(reviewForm.value),
        });
        if (res.ok || res.status === 302) {
            reviewForm.value = { customer_name: '', location: '', rating: 5, review: '' };
            showReviewForm.value = false;
            reviewSuccess.value = true;
            setTimeout(() => { reviewSuccess.value = false; }, 5000);
        } else {
            const data = await res.json().catch(() => null);
            reviewError.value = data?.message || 'Something went wrong. Please try again.';
        }
    } catch {
        reviewError.value = 'Network error. Please check your connection.';
    } finally {
        reviewSubmitting.value = false;
    }
};

// Flash sale countdown
const now = ref(Date.now());
let countdownInterval = null;

const earliestSaleEnd = computed(() => {
    if (!props.flashSaleProducts?.length) return null;
    const ends = props.flashSaleProducts
        .filter(p => p.flash_sale_ends_at)
        .map(p => new Date(p.flash_sale_ends_at).getTime());
    return ends.length ? Math.min(...ends) : null;
});

const countdown = computed(() => {
    if (!earliestSaleEnd.value) return null;
    const diff = earliestSaleEnd.value - now.value;
    if (diff <= 0) return { days: 0, hours: 0, minutes: 0, seconds: 0 };
    return {
        days: Math.floor(diff / 86400000),
        hours: Math.floor((diff % 86400000) / 3600000),
        minutes: Math.floor((diff % 3600000) / 60000),
        seconds: Math.floor((diff % 60000) / 1000),
    };
});

const pad = (n) => String(n).padStart(2, '0');

const formatFlashPrice = (price) => 'KSh ' + Number(price).toLocaleString();

const showFlashBanner = ref(true);

const addFlashToCart = (e, product) => {
    e.preventDefault();
    e.stopPropagation();
    try {
        const cart = JSON.parse(localStorage.getItem('techmart_cart') || '[]');
        const existing = cart.find((item) => item.id === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.flash_sale_price || product.base_price,
                slug: product.slug,
                image: product.images?.[0]?.image_url || product.images?.[0]?.url || product.image,
                condition: product.condition,
                quantity: 1,
            });
        }
        localStorage.setItem('techmart_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    } catch {}
};

const query = ref('');
const canvasRef = ref(null);
let animationId = null;
let particles = [];
let mouse = { x: 0, y: 0 };

const exampleQueries = [
    'Best phone under 30K',
    'Laptop for school work',
    'iPhone with good camera',
    'Gaming laptop under 80K',
    'Samsung under 25K',
    'MacBook for coding',
];
const placeholderIdx = ref(0);
let placeholderInterval = null;
const currentPlaceholder = computed(() => exampleQueries[placeholderIdx.value]);

const smartSearch = () => {
    const q = query.value.trim();
    if (!q) return;
    // If it's purely a number, treat as budget
    const numOnly = q.replace(/[,\s]/g, '');
    if (/^\d+$/.test(numOnly)) {
        router.get('/compare', { budget: numOnly });
    } else {
        router.get('/compare', { q });
    }
};

const quickSearch = (text) => {
    query.value = text;
    showSuggestions.value = false;
    smartSearch();
};

// Autocomplete suggestions
const showSuggestions = ref(false);
const selectedSuggestionIdx = ref(-1);

const suggestions = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (q.length < 2) return [];

    const results = [];
    const seen = new Set();

    // Match brands
    props.brandNames.forEach(b => {
        if (b.toLowerCase().includes(q) && !seen.has('brand:' + b)) {
            seen.add('brand:' + b);
            results.push({ type: 'brand', text: b, label: `${b} phones & laptops`, icon: 'brand' });
        }
    });

    // Match categories
    props.categoryNames.forEach(c => {
        if (c.toLowerCase().includes(q) && !seen.has('cat:' + c)) {
            seen.add('cat:' + c);
            results.push({ type: 'category', text: c, label: `Browse ${c}`, icon: 'category' });
        }
    });

    // Match products
    props.searchHints.forEach(p => {
        if (p.name.toLowerCase().includes(q) && !seen.has('prod:' + p.name)) {
            seen.add('prod:' + p.name);
            const price = 'KSh ' + Number(p.price).toLocaleString();
            results.push({ type: 'product', text: p.name, label: `${p.name}`, sub: `${p.brand || ''} · ${price}`, icon: 'product' });
        }
    });

    // Smart query suggestions based on what they typed
    if (results.length > 0 && results.length < 6) {
        const topBrand = results.find(r => r.type === 'brand');
        if (topBrand) {
            results.push({ type: 'query', text: `Best ${topBrand.text} under 30K`, label: `Best ${topBrand.text} under 30K`, icon: 'sparkle' });
            results.push({ type: 'query', text: `${topBrand.text} with good camera`, label: `${topBrand.text} with good camera`, icon: 'sparkle' });
        }
    }

    return results.slice(0, 7);
});

const onInput = () => {
    showSuggestions.value = query.value.trim().length >= 2;
    selectedSuggestionIdx.value = -1;
};

const pickSuggestion = (suggestion) => {
    if (suggestion.type === 'product') {
        query.value = suggestion.text;
    } else if (suggestion.type === 'brand') {
        query.value = `Best ${suggestion.text} phone`;
    } else if (suggestion.type === 'category') {
        query.value = suggestion.text;
    } else {
        query.value = suggestion.text;
    }
    showSuggestions.value = false;
    smartSearch();
};

const onKeydown = (e) => {
    if (!showSuggestions.value || !suggestions.value.length) return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedSuggestionIdx.value = Math.min(selectedSuggestionIdx.value + 1, suggestions.value.length - 1);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedSuggestionIdx.value = Math.max(selectedSuggestionIdx.value - 1, -1);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedSuggestionIdx.value >= 0) {
            pickSuggestion(suggestions.value[selectedSuggestionIdx.value]);
        } else {
            showSuggestions.value = false;
            smartSearch();
        }
    } else if (e.key === 'Escape') {
        showSuggestions.value = false;
    }
};

const closeSuggestions = () => {
    setTimeout(() => { showSuggestions.value = false; }, 150);
};

// Particle animation
const initParticles = () => {
    const canvas = canvasRef.value;
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const resizeCanvas = () => {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
    };
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Create particles
    for (let i = 0; i < 80; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 2 + 0.5,
            speedX: (Math.random() - 0.5) * 0.4,
            speedY: (Math.random() - 0.5) * 0.4,
            opacity: Math.random() * 0.12 + 0.03,  // 3-15% — a whisper, not a distraction
        });
    }

    const animate = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        particles.forEach((p, i) => {
            // Mouse attraction
            const dx = mouse.x - p.x;
            const dy = mouse.y - p.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < 150) {
                p.x += dx * 0.008;
                p.y += dy * 0.008;
            }

            p.x += p.speedX;
            p.y += p.speedY;

            // Wrap around
            if (p.x < 0) p.x = canvas.width;
            if (p.x > canvas.width) p.x = 0;
            if (p.y < 0) p.y = canvas.height;
            if (p.y > canvas.height) p.y = 0;

            // Draw particle
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(0, 0, 0, ${p.opacity})`;
            ctx.fill();

            // Draw connections
            particles.forEach((p2, j) => {
                if (j <= i) return;
                const d = Math.sqrt((p.x - p2.x) ** 2 + (p.y - p2.y) ** 2);
                if (d < 100) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.strokeStyle = `rgba(0, 0, 0, ${0.025 * (1 - d / 100)})`;
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                }
            });
        });

        animationId = requestAnimationFrame(animate);
    };

    animate();
};

const handleMouseMove = (e) => {
    const rect = canvasRef.value?.parentElement?.getBoundingClientRect();
    if (rect) {
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
    }
};

onMounted(() => {
    initParticles();
    window.addEventListener('mousemove', handleMouseMove);
    countdownInterval = setInterval(() => { now.value = Date.now(); }, 1000);
    placeholderInterval = setInterval(() => {
        placeholderIdx.value = (placeholderIdx.value + 1) % exampleQueries.length;
    }, 3000);
});

onUnmounted(() => {
    if (animationId) cancelAnimationFrame(animationId);
    if (countdownInterval) clearInterval(countdownInterval);
    if (placeholderInterval) clearInterval(placeholderInterval);
    window.removeEventListener('mousemove', handleMouseMove);
});

const quickBudgets = [30000, 50000, 80000, 120000]; // kept for quick budget buttons
</script>

<template>
    <Head title="Buy Phones, Laptops & Accessories | AI-Powered Smart Shopping" />
    <CustomerLayout>
        <!-- Flash Sale Sticky Banner -->
        <div
            v-if="flashSaleProducts.length && countdown && showFlashBanner"
            class="bg-gray-900 text-white relative overflow-hidden"
        >
            <div class="absolute inset-0 bg-gradient-to-r from-red-600/20 via-transparent to-orange-600/20" />
            <div class="container mx-auto px-4 py-2 relative z-10">
                <div class="flex items-center justify-center gap-3 text-sm">
                    <div class="flex items-center gap-2">
                        <Zap class="w-4 h-4 text-yellow-400 animate-pulse" />
                        <span class="font-bold text-xs sm:text-sm uppercase tracking-wide">Flash Sale Ending Soon</span>
                    </div>
                    <div class="flex items-center gap-1 font-mono text-red-400 font-bold text-sm sm:text-base">
                        <span v-if="countdown.days > 0">{{ pad(countdown.days) }}d:</span>
                        <span>{{ pad(countdown.hours) }}h</span>
                        <span>:</span>
                        <span>{{ pad(countdown.minutes) }}m</span>
                        <span>:</span>
                        <span class="animate-pulse">{{ pad(countdown.seconds) }}s</span>
                    </div>
                    <button
                        @click="showFlashBanner = false"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white p-1"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hero — monochrome, generous negative space -->
        <section class="relative overflow-hidden pt-6 pb-4 md:pt-10 md:pb-6">
            <!-- Particle Canvas -->
            <canvas
                ref="canvasRef"
                class="absolute inset-0 pointer-events-none z-0"
            />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
                <div class="max-w-2xl mx-auto">
                    <!-- Heading — pure black, Manrope -->
                    <div class="text-center mb-4 md:mb-5 animate-fade-in-up">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-black tracking-tight leading-tight">
                            Find Your Perfect Device
                        </h1>
                        <p class="text-xs sm:text-sm text-[#86868B] mt-1.5">AI-powered search · Best prices · Quality verified</p>
                    </div>

                    <!-- Compact Inline Search (no big card) -->
                    <div class="animate-fade-in-up animation-delay-100 relative z-20">
                        <!-- Search Input with Autocomplete -->
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 z-10">
                                <Search class="w-4 h-4 text-gray-400" />
                            </div>
                            <input
                                v-model="query"
                                type="text"
                                class="w-full pl-10 pr-20 sm:pr-24 py-3 text-sm sm:text-base bg-[#F5F5F7] border border-[#E5E5EA] rounded-full focus:bg-white focus:border-black focus:outline-none focus:ring-1 focus:ring-black/10 transition-all cursor-text relative z-10 text-[#1D1D1F] placeholder-[#86868B]"
                                :placeholder="currentPlaceholder"
                                @input="onInput"
                                @keydown="onKeydown"
                                @blur="closeSuggestions"
                                @focus="onInput"
                                autocomplete="off"
                            />
                            <button
                                @click="showSuggestions = false; smartSearch()"
                                :disabled="!query.trim()"
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 px-3 sm:px-4 py-2 bg-black text-white rounded-full hover:bg-[#1D1D1F] active:scale-[0.97] transition-all font-semibold flex items-center gap-1.5 cursor-pointer disabled:opacity-30 disabled:cursor-not-allowed text-xs sm:text-sm z-10"
                            >
                                <Sparkles class="w-3.5 h-3.5" />
                                <span>Ask AI</span>
                            </button>

                            <!-- Suggestions Dropdown -->
                            <div
                                v-if="showSuggestions && suggestions.length"
                                class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 max-h-[280px] overflow-y-auto"
                            >
                                <button
                                    v-for="(s, i) in suggestions"
                                    :key="i"
                                    @mousedown.prevent="pickSuggestion(s)"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-colors hover:bg-gray-50 border-b border-gray-50 last:border-0"
                                    :class="selectedSuggestionIdx === i ? 'bg-gray-50' : ''"
                                >
                                    <!-- Icon -->
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                        :class="{
                                            'bg-blue-50': s.icon === 'brand',
                                            'bg-green-50': s.icon === 'category',
                                            'bg-gray-100': s.icon === 'product',
                                            'bg-purple-50': s.icon === 'sparkle',
                                        }"
                                    >
                                        <Smartphone v-if="s.icon === 'product'" class="w-4 h-4 text-gray-500" />
                                        <Star v-else-if="s.icon === 'brand'" class="w-4 h-4 text-blue-500" />
                                        <Monitor v-else-if="s.icon === 'category'" class="w-4 h-4 text-green-500" />
                                        <Sparkles v-else class="w-4 h-4 text-purple-500" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ s.label }}</p>
                                        <p v-if="s.sub" class="text-xs text-gray-400 truncate">{{ s.sub }}</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 uppercase font-medium flex-shrink-0">{{ s.type === 'query' ? 'AI' : s.type }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Inline chip row (horizontal scroll on mobile) -->
                        <div class="mt-2.5 flex gap-1.5 overflow-x-auto pb-1 -mx-1 px-1 scrollbar-hide">
                            <button
                                v-for="b in quickBudgets"
                                :key="b"
                                @click="query = `Best phone under ${(b/1000)}K`; smartSearch()"
                                class="flex-shrink-0 px-2.5 py-1 text-[11px] font-medium bg-[#F5F5F7] hover:bg-[#E5E5EA] active:bg-[#D1D1D6] rounded-full transition-all cursor-pointer text-[#1D1D1F]"
                            >
                                Under {{ (b/1000) }}K
                            </button>
                            <button
                                @click="quickSearch('Best Samsung under 25K')"
                                class="flex-shrink-0 px-2.5 py-1 text-[11px] font-medium bg-[#F5F5F7] hover:bg-[#E5E5EA] rounded-full transition-all cursor-pointer text-[#1D1D1F]"
                            >
                                Samsung under 25K
                            </button>
                            <button
                                @click="quickSearch('iPhone with good camera')"
                                class="flex-shrink-0 px-2.5 py-1 text-[11px] font-medium bg-[#F5F5F7] hover:bg-[#E5E5EA] rounded-full transition-all cursor-pointer text-[#1D1D1F]"
                            >
                                Best iPhone camera
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </section>

        <!-- Trade-In Banner — solid black, green icon for the "wink" -->
        <Link
            href="/trade-in"
            class="block bg-black hover:bg-[#1D1D1F] transition-colors cursor-pointer"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex items-center justify-center gap-2 text-white">
                <svg class="w-4 h-4 flex-shrink-0 text-[#1A531B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                <span class="text-sm font-bold">Trade in your phone — get instant value</span>
                <ArrowRight class="w-4 h-4 flex-shrink-0 text-[#86868B]" />
            </div>
        </Link>

        <!-- Category Strip -->
        <section class="border-b border-[#E5E5EA] bg-[#F5F5F7]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
                <div class="flex gap-2 overflow-x-auto scrollbar-hide -mx-1 px-1">
                    <Link
                        href="/products?category=phones"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Smartphone class="w-3.5 h-3.5" />
                        Phones
                    </Link>
                    <Link
                        href="/products?category=computers"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Laptop class="w-3.5 h-3.5" />
                        Laptops
                    </Link>
                    <Link
                        href="/products?category=tablets"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Tablet class="w-3.5 h-3.5" />
                        Tablets
                    </Link>
                    <Link
                        href="/products?category=accessories"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Headphones class="w-3.5 h-3.5" />
                        Accessories
                    </Link>
                    <Link
                        href="/products?condition=new"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Star class="w-3.5 h-3.5" />
                        Brand New
                    </Link>
                    <Link
                        href="/products?condition=ex-uk"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#E5E5EA] hover:border-black rounded-full text-xs font-semibold text-[#1D1D1F] hover:text-black transition cursor-pointer"
                    >
                        <Shield class="w-3.5 h-3.5" />
                        Ex-UK
                    </Link>
                </div>
            </div>
        </section>

        <!-- Hot Deals (compact horizontal scroll, immediately above the fold) -->
        <section v-if="flashSaleProducts.length" class="pt-4 pb-2 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-bold flex items-center gap-1.5">
                        <Flame class="w-4 h-4 text-red-500" />
                        Hot Deals
                    </h3>
                    <span v-if="countdown" class="text-[11px] font-mono text-red-500 font-bold">
                        Ends in {{ countdown.days > 0 ? countdown.days + 'd ' : '' }}{{ pad(countdown.hours) }}h:{{ pad(countdown.minutes) }}m
                    </span>
                </div>
                <div class="flex gap-2.5 overflow-x-auto pb-2 -mx-1 px-1 snap-x snap-mandatory scrollbar-hide">
                    <Link
                        v-for="product in flashSaleProducts"
                        :key="product.id"
                        :href="`/products/${product.slug || product.id}`"
                        class="group flex-shrink-0 w-[130px] sm:w-[150px] bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all snap-start block overflow-hidden"
                    >
                        <div class="relative aspect-square bg-gray-50 p-2.5">
                            <img
                                :src="product.images?.[0]?.image_url || product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg'"
                                :alt="product.name"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            />
                            <div
                                v-if="product.flash_sale_price && product.base_price > product.flash_sale_price"
                                class="absolute top-1.5 left-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded"
                            >
                                LIMITED
                            </div>
                        </div>
                        <div class="px-2 pb-2 pt-1">
                            <p class="text-[11px] text-gray-600 truncate">{{ product.name }}</p>
                            <p class="text-xs font-bold text-gray-900 truncate">{{ formatFlashPrice(product.flash_sale_price || product.base_price) }}</p>
                            <button
                                @click="addFlashToCart($event, product)"
                                class="mt-1.5 w-full flex items-center justify-center gap-1 bg-black text-white text-[10px] font-semibold py-1 rounded hover:bg-gray-800 transition-colors"
                            >
                                <ShoppingCart class="w-2.5 h-2.5" />
                                Add
                            </button>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-6 md:py-10 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <Link
                    href="/products"
                    class="flex items-center justify-between mb-4 md:mb-6 py-2 -my-2 cursor-pointer group"
                >
                    <h3 class="text-lg md:text-xl font-extrabold text-black">Featured Products</h3>
                    <span class="flex items-center gap-1 text-xs font-semibold text-[#86868B] group-hover:text-black transition px-3 py-1.5 rounded-full group-hover:bg-[#F5F5F7]">
                        View All
                        <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                    </span>
                </Link>

                <div
                    v-if="featuredProducts.length"
                    class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-px sm:gap-px bg-[#E5E5EA]"
                >
                    <div v-for="product in featuredProducts" :key="product.id" class="bg-white">
                        <ProductCard :product="product" />
                    </div>
                </div>
                <div v-else class="text-center py-10 text-[#86868B]">
                    <Smartphone class="w-12 h-12 mx-auto mb-3 opacity-30" />
                    <p class="text-sm font-medium">No featured products yet</p>
                </div>

                <div class="sm:hidden mt-4 text-center">
                    <Link
                        href="/products"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full text-sm font-semibold cursor-pointer hover:bg-[#1D1D1F] transition-all active:scale-[0.98]"
                    >
                        View All Products
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Trust Strip — subtle, doesn't compete with testimonials -->
        <section class="py-3 md:py-4 border-y border-[#E5E5EA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 text-center">
                    <div class="flex items-center justify-center gap-1.5 text-[10px] md:text-[11px]">
                        <Shield class="w-3 h-3 text-[#C7C7CC] flex-shrink-0" />
                        <span class="font-medium text-[#86868B]">Verified Devices</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 text-[10px] md:text-[11px]">
                        <Truck class="w-3 h-3 text-[#C7C7CC] flex-shrink-0" />
                        <span class="font-medium text-[#86868B]">Fast Delivery</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 text-[10px] md:text-[11px]">
                        <Star class="w-3 h-3 text-[#C7C7CC] flex-shrink-0" />
                        <span class="font-medium text-[#86868B]">3-Month Warranty</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5 text-[10px] md:text-[11px]">
                        <Zap class="w-3 h-3 text-[#C7C7CC] flex-shrink-0" />
                        <span class="font-medium text-[#86868B]">M-Pesa Ready</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials + Review -->
        <section class="py-8 md:py-12 bg-[#F5F5F7]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <!-- Carousel -->
                <TestimonialCarousel :reviews="reviews" :total-count="reviewCount" />

                <!-- Leave a Review -->
                <div class="text-center mt-6">
                    <!-- Success feedback -->
                    <Transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <div v-if="reviewSuccess" class="max-w-md mx-auto mb-4 bg-black text-white rounded-full px-5 py-2.5 text-sm font-semibold inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#2ECC71]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Thank you! Your review will appear after approval.
                        </div>
                    </Transition>

                    <button
                        v-if="!showReviewForm && !reviewSuccess"
                        @click="showReviewForm = true"
                        class="px-5 py-2 text-xs font-semibold text-[#1D1D1F] bg-white border border-[#1D1D1F] rounded-full hover:bg-black hover:text-white transition-all cursor-pointer"
                    >
                        Share your experience
                    </button>

                    <Transition
                        enter-active-class="transition-all duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition-all duration-150 ease-in"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <form
                            v-if="showReviewForm"
                            @submit.prevent="submitReview"
                            class="max-w-md mx-auto mt-4 bg-white rounded-2xl border border-[#E5E5EA] p-5 text-left space-y-3"
                        >
                            <h4 class="text-sm font-bold text-black text-center">Leave a Review</h4>

                            <!-- Error message -->
                            <p v-if="reviewError" class="text-xs text-red-500 text-center font-medium">{{ reviewError }}</p>

                            <!-- Star rating -->
                            <div class="flex justify-center gap-1">
                                <button
                                    v-for="n in 5"
                                    :key="n"
                                    type="button"
                                    @click="reviewForm.rating = n"
                                    class="cursor-pointer p-0.5"
                                >
                                    <Star
                                        class="w-6 h-6 transition-colors"
                                        :class="n <= reviewForm.rating ? 'text-black fill-black' : 'text-[#E5E5EA]'"
                                    />
                                </button>
                            </div>

                            <textarea
                                v-model="reviewForm.review"
                                required
                                rows="2"
                                class="w-full px-3 py-2 text-sm bg-[#F5F5F7] border border-[#E5E5EA] rounded-xl focus:bg-white focus:border-black focus:outline-none focus:ring-1 focus:ring-black/10 transition resize-none text-[#1D1D1F] placeholder-[#86868B]"
                                placeholder="How was your experience?"
                            />

                            <div class="grid grid-cols-2 gap-2">
                                <input
                                    v-model="reviewForm.customer_name"
                                    required
                                    type="text"
                                    class="px-3 py-2 text-sm bg-[#F5F5F7] border border-[#E5E5EA] rounded-xl focus:bg-white focus:border-black focus:outline-none transition text-[#1D1D1F] placeholder-[#86868B]"
                                    placeholder="Your name"
                                />
                                <input
                                    v-model="reviewForm.location"
                                    type="text"
                                    class="px-3 py-2 text-sm bg-[#F5F5F7] border border-[#E5E5EA] rounded-xl focus:bg-white focus:border-black focus:outline-none transition text-[#1D1D1F] placeholder-[#86868B]"
                                    placeholder="City (optional)"
                                />
                            </div>

                            <div class="flex gap-2">
                                <button
                                    type="button"
                                    @click="showReviewForm = false"
                                    class="flex-1 py-2 text-sm font-medium border border-[#E5E5EA] rounded-full hover:bg-[#F5F5F7] transition cursor-pointer text-[#1D1D1F]"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="reviewSubmitting"
                                    class="flex-1 py-2 text-sm font-bold bg-black text-white rounded-full hover:bg-[#1D1D1F] transition cursor-pointer disabled:opacity-50"
                                >
                                    {{ reviewSubmitting ? 'Sending...' : 'Submit' }}
                                </button>
                            </div>
                        </form>
                    </Transition>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>

<style scoped>
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fadeInDown 0.6s ease-out both;
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out both;
}

.animation-delay-100 {
    animation-delay: 0.1s;
}

.animation-delay-200 {
    animation-delay: 0.2s;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
