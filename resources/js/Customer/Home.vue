<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { Search, Zap, Shield, Truck, ArrowRight, Star, Smartphone, Monitor, Laptop, Tablet, Headphones, Flame, Clock, ShoppingCart, Sparkles, MessageCircle } from 'lucide-vue-next';
import TrustActions from '@/Components/Customer/TrustActions.vue';

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
});

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
            opacity: Math.random() * 0.5 + 0.1,
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
                    ctx.strokeStyle = `rgba(0, 0, 0, ${0.06 * (1 - d / 100)})`;
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

        <!-- Hero Section with Particle Background -->
        <section class="relative overflow-hidden flex items-center pt-6 pb-10 md:pt-16 md:pb-20">
            <!-- Particle Canvas -->
            <canvas
                ref="canvasRef"
                class="absolute inset-0 pointer-events-none z-0"
            />

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-5 md:mb-6 text-sm font-medium text-gray-700 animate-fade-in-down">
                        <Zap class="w-4 h-4 text-black" />
                        AI-Powered Smart Shopping
                    </div>

                    <h2 class="text-3xl sm:text-5xl md:text-7xl font-extrabold mb-3 md:mb-5 tracking-tight animate-fade-in-up text-black leading-tight">
                        Find Your
                        <br />
                        Perfect Device
                    </h2>
                    <p class="text-sm sm:text-base md:text-lg mb-6 md:mb-10 text-gray-600 max-w-lg mx-auto leading-relaxed animate-fade-in-up animation-delay-100 px-2">
                        Enter your budget and let our AI find the best phones and computers with smart side-by-side comparisons
                    </p>

                    <!-- Smart Search Card -->
                    <div class="bg-white rounded-2xl md:rounded-3xl p-5 sm:p-6 md:p-8 shadow-xl border border-gray-100 animate-fade-in-up animation-delay-200 relative z-20">
                        <!-- Search Input with Autocomplete -->
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center gap-1.5 z-10">
                                <Sparkles class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                v-model="query"
                                type="text"
                                class="w-full pl-12 pr-28 sm:pr-36 py-4 sm:py-5 text-base sm:text-lg border-2 border-gray-200 rounded-2xl focus:border-black focus:outline-none focus:ring-4 focus:ring-black/5 transition-all cursor-text relative z-10"
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
                                class="absolute right-2 top-1/2 -translate-y-1/2 px-4 sm:px-6 py-2.5 sm:py-3 bg-black text-white rounded-xl hover:bg-gray-800 active:scale-[0.97] transition-all font-semibold flex items-center gap-2 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed text-sm sm:text-base z-10"
                            >
                                <Search class="w-4 h-4" />
                                <span class="hidden sm:inline">Search</span>
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

                        <!-- Quick Actions -->
                        <div class="mt-4 flex flex-col gap-3">
                            <!-- Budget shortcuts -->
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-400">Budget:</span>
                                <button
                                    v-for="b in quickBudgets"
                                    :key="b"
                                    @click="query = `Best phone under ${(b/1000)}K`; smartSearch()"
                                    class="px-3 py-1.5 text-xs font-medium bg-gray-50 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-all cursor-pointer border border-gray-200 hover:border-gray-300"
                                >
                                    Under {{ (b/1000) }}K
                                </button>
                            </div>
                            <!-- Try asking -->
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-400">Try:</span>
                                <button
                                    @click="quickSearch('Best Samsung under 25K')"
                                    class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-black bg-gray-50 hover:bg-gray-100 rounded-lg transition-all cursor-pointer border border-gray-100"
                                >
                                    Best Samsung under 25K
                                </button>
                                <button
                                    @click="quickSearch('iPhone with good camera')"
                                    class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-black bg-gray-50 hover:bg-gray-100 rounded-lg transition-all cursor-pointer border border-gray-100"
                                >
                                    iPhone with good camera
                                </button>
                                <button
                                    @click="quickSearch('Laptop for school work')"
                                    class="hidden sm:inline-block px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-black bg-gray-50 hover:bg-gray-100 rounded-lg transition-all cursor-pointer border border-gray-100"
                                >
                                    Laptop for school work
                                </button>
                            </div>
                            <!-- Trade-In CTA -->
                            <div class="pt-2 border-t border-gray-100">
                                <Link href="/trade-in" class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 hover:text-green-800 transition">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 014-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 01-4 4H3"/></svg>
                                    Have a phone to trade in? <span class="underline">Get its value</span>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Hot Flash Deals (horizontal scroll) -->
                    <div v-if="flashSaleProducts.length" class="mt-8 md:mt-10 animate-fade-in-up animation-delay-200">
                        <h3 class="text-lg md:text-xl font-bold mb-4 text-left">Hot Flash Deals</h3>
                        <div class="flex gap-3 overflow-x-auto pb-3 -mx-1 px-1 snap-x snap-mandatory scrollbar-hide">
                            <Link
                                v-for="product in flashSaleProducts"
                                :key="product.id"
                                :href="`/products/${product.slug || product.id}`"
                                class="group flex-shrink-0 w-[140px] sm:w-[160px] bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all snap-start block overflow-hidden"
                            >
                                <!-- Image -->
                                <div class="relative aspect-square bg-gray-50 p-3">
                                    <img
                                        :src="product.images?.[0]?.image_url || product.images?.[0]?.url || product.image || '/assets/img/placeholder.jpg'"
                                        :alt="product.name"
                                        class="w-full h-full object-contain"
                                        loading="lazy"
                                    />
                                    <!-- Limited Time badge -->
                                    <div
                                        v-if="product.flash_sale_price && product.base_price > product.flash_sale_price"
                                        class="absolute bottom-1.5 right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded"
                                    >
                                        Limited Time
                                    </div>
                                </div>
                                <!-- Price + Add -->
                                <div class="px-2.5 pb-2.5 pt-2">
                                    <p class="text-xs font-bold text-gray-900 truncate mb-1">{{ formatFlashPrice(product.flash_sale_price || product.base_price) }}</p>
                                    <button
                                        @click="addFlashToCart($event, product)"
                                        class="w-full flex items-center justify-center gap-1 bg-black text-white text-[10px] font-semibold py-1.5 rounded-md hover:bg-gray-800 transition-colors"
                                    >
                                        <ShoppingCart class="w-3 h-3" />
                                        Add to Cart
                                    </button>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Popular Categories -->
                    <div class="mt-10 md:mt-14 animate-fade-in-up animation-delay-200">
                        <h3 class="text-xl md:text-2xl font-bold mb-6">Popular Categories</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">
                            <Link
                                href="/products?category=phones"
                                class="group bg-white rounded-2xl p-5 md:p-6 text-center shadow-sm hover:shadow-lg border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                            >
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 group-hover:bg-black/5 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                                    <Smartphone class="w-6 h-6 md:w-7 md:h-7 text-gray-500 group-hover:text-black transition-colors duration-300" />
                                </div>
                                <h4 class="font-bold text-sm md:text-base mb-1">Phones</h4>
                                <p class="text-[11px] md:text-xs text-gray-400 mb-3 leading-snug">Navigate on phones and mobile phones.</p>
                                <span class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Explore</span>
                            </Link>
                            <Link
                                href="/products?category=computers"
                                class="group bg-white rounded-2xl p-5 md:p-6 text-center shadow-sm hover:shadow-lg border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                            >
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 group-hover:bg-black/5 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                                    <Laptop class="w-6 h-6 md:w-7 md:h-7 text-gray-500 group-hover:text-black transition-colors duration-300" />
                                </div>
                                <h4 class="font-bold text-sm md:text-base mb-1">Laptops</h4>
                                <p class="text-[11px] md:text-xs text-gray-400 mb-3 leading-snug">Browse smart laptops, and track your laptops.</p>
                                <span class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Explore</span>
                            </Link>
                            <Link
                                href="/products?category=tablets"
                                class="group bg-white rounded-2xl p-5 md:p-6 text-center shadow-sm hover:shadow-lg border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                            >
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 group-hover:bg-black/5 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                                    <Tablet class="w-6 h-6 md:w-7 md:h-7 text-gray-500 group-hover:text-black transition-colors duration-300" />
                                </div>
                                <h4 class="font-bold text-sm md:text-base mb-1">Tablets</h4>
                                <p class="text-[11px] md:text-xs text-gray-400 mb-3 leading-snug">Deliver screen with tablets and tablets.</p>
                                <span class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Explore</span>
                            </Link>
                            <Link
                                href="/products?category=accessories"
                                class="group bg-white rounded-2xl p-5 md:p-6 text-center shadow-sm hover:shadow-lg border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                            >
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 group-hover:bg-black/5 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                                    <Headphones class="w-6 h-6 md:w-7 md:h-7 text-gray-500 group-hover:text-black transition-colors duration-300" />
                                </div>
                                <h4 class="font-bold text-sm md:text-base mb-1">Accessories</h4>
                                <p class="text-[11px] md:text-xs text-gray-400 mb-3 leading-snug">Scenecats new phones and technicians accessories.</p>
                                <span class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Explore</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Badges -->
        <section class="py-8 md:py-12 border-b border-gray-200 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                    <div class="flex items-center gap-3 justify-center p-3 md:p-0">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black/5 rounded-xl flex items-center justify-center flex-shrink-0">
                            <Shield class="w-5 h-5 md:w-6 md:h-6 text-black" />
                        </div>
                        <div>
                            <p class="font-bold text-xs md:text-sm text-gray-900">Verified Devices</p>
                            <p class="text-[10px] md:text-xs text-gray-500">Quality tested</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-center p-3 md:p-0">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black/5 rounded-xl flex items-center justify-center flex-shrink-0">
                            <Truck class="w-5 h-5 md:w-6 md:h-6 text-black" />
                        </div>
                        <div>
                            <p class="font-bold text-xs md:text-sm text-gray-900">Fast Delivery</p>
                            <p class="text-[10px] md:text-xs text-gray-500">Nairobi & beyond</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-center p-3 md:p-0">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black/5 rounded-xl flex items-center justify-center flex-shrink-0">
                            <Star class="w-5 h-5 md:w-6 md:h-6 text-black" />
                        </div>
                        <div>
                            <p class="font-bold text-xs md:text-sm text-gray-900">3-Month Warranty</p>
                            <p class="text-[10px] md:text-xs text-gray-500">On all devices</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-center p-3 md:p-0">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-black/5 rounded-xl flex items-center justify-center flex-shrink-0">
                            <Zap class="w-5 h-5 md:w-6 md:h-6 text-black" />
                        </div>
                        <div>
                            <p class="font-bold text-xs md:text-sm text-gray-900">M-Pesa Ready</p>
                            <p class="text-[10px] md:text-xs text-gray-500">Pay instantly</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-10 md:py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8 md:mb-10">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-bold">Featured Products</h3>
                        <p class="text-gray-500 text-sm md:text-base mt-1">Handpicked deals for you</p>
                    </div>
                    <Link
                        href="/products"
                        class="hidden sm:flex items-center gap-1 text-sm font-semibold text-gray-600 hover:text-black transition cursor-pointer group"
                    >
                        View All
                        <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </Link>
                </div>

                <div
                    v-if="featuredProducts.length"
                    class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5"
                >
                    <ProductCard
                        v-for="product in featuredProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
                <div v-else class="text-center py-16 text-gray-400">
                    <Smartphone class="w-16 h-16 mx-auto mb-4 opacity-30" />
                    <p class="text-lg font-medium">No featured products yet</p>
                    <p class="text-sm mt-1">Check back soon for amazing deals!</p>
                </div>

                <!-- Mobile "View All" -->
                <div class="sm:hidden mt-6 text-center">
                    <Link
                        href="/products"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-xl font-semibold cursor-pointer hover:bg-gray-800 transition-all active:scale-[0.98]"
                    >
                        View All Products
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Trust / Contact Section -->
        <section class="py-10 md:py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8 md:mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Ready to Buy?</h3>
                    <p class="text-gray-600 text-sm md:text-base max-w-lg mx-auto">
                        See it. Touch it. Trust it. Visit our shop, call us, or chat on WhatsApp — we're here for you
                    </p>
                </div>
                <div class="max-w-3xl mx-auto">
                    <TrustActions layout="cards" />
                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="py-10 md:py-20">
            <div class="container mx-auto px-4">
                <div class="bg-black rounded-2xl md:rounded-3xl p-8 md:p-16 text-center text-white relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2" />
                    <div class="absolute bottom-0 right-0 w-60 h-60 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3" />
                    <div class="absolute top-1/2 left-1/4 w-2 h-2 bg-white/20 rounded-full" />
                    <div class="absolute top-1/3 right-1/3 w-3 h-3 bg-white/10 rounded-full" />

                    <div class="relative z-10">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 md:mb-4">
                            Not sure which device to pick?
                        </h3>
                        <p class="text-gray-400 text-sm md:text-lg mb-6 md:mb-8 max-w-xl mx-auto">
                            Our AI assistant will help you find the perfect match based on your budget and needs
                        </p>
                        <button
                            @click="document.querySelector('input[placeholder]')?.focus(); window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="inline-flex items-center gap-2 px-6 md:px-8 py-3 md:py-4 bg-white text-black rounded-xl font-bold hover:bg-gray-100 transition-all cursor-pointer active:scale-[0.98] shadow-lg"
                        >
                            <Sparkles class="w-5 h-5" />
                            Try AI Smart Search
                        </button>
                    </div>
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
