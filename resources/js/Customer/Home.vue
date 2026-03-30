<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import ProductCard from '@/Components/Customer/ProductCard.vue';
import { Search, Zap, Shield, Truck, ArrowRight, Star, Smartphone, Monitor } from 'lucide-vue-next';

defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
});

const budget = ref('');
const canvasRef = ref(null);
let animationId = null;
let particles = [];
let mouse = { x: 0, y: 0 };

const compareNow = () => {
    if (budget.value) {
        router.get('/compare', { budget: budget.value });
    }
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
});

onUnmounted(() => {
    if (animationId) cancelAnimationFrame(animationId);
    window.removeEventListener('mousemove', handleMouseMove);
});

const quickBudgets = [30000, 50000, 80000, 120000];
</script>

<template>
    <CustomerLayout>
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

                    <!-- Budget Search Card -->
                    <div class="bg-white rounded-2xl md:rounded-3xl p-5 sm:p-6 md:p-8 shadow-xl border border-gray-100 animate-fade-in-up animation-delay-200">
                        <label class="block text-left font-semibold mb-3 text-gray-700 text-sm md:text-base">
                            Your Budget (KES)
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-1">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-lg">KSh</span>
                                <input
                                    v-model="budget"
                                    type="number"
                                    class="w-full pl-14 pr-6 py-3 sm:py-4 text-xl sm:text-2xl border-2 border-gray-200 rounded-xl focus:border-black focus:outline-none focus:ring-4 focus:ring-black/5 transition-all cursor-text"
                                    placeholder="48,000"
                                    @keyup.enter="compareNow"
                                />
                            </div>
                            <button
                                @click="compareNow"
                                class="px-6 sm:px-8 py-3 sm:py-4 bg-black text-white rounded-xl hover:bg-gray-800 active:scale-[0.98] transition-all font-semibold flex items-center justify-center gap-2 cursor-pointer shadow-lg shadow-black/20 hover:shadow-xl hover:shadow-black/30 text-base sm:text-lg"
                            >
                                <Search class="w-5 h-5" />
                                Compare Now
                            </button>
                        </div>

                        <!-- Quick Budget Buttons -->
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="text-xs text-gray-400 self-center mr-1">Quick:</span>
                            <button
                                v-for="b in quickBudgets"
                                :key="b"
                                @click="budget = b; compareNow()"
                                class="px-3 py-1.5 text-xs sm:text-sm font-medium bg-gray-100 hover:bg-gray-200 active:bg-gray-300 rounded-lg transition-all cursor-pointer border border-gray-200 hover:border-gray-300"
                            >
                                KSh {{ b.toLocaleString() }}
                            </button>
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

        <!-- Category Quick Links -->
        <section class="py-10 md:py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8 md:mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Shop by Category</h3>
                    <p class="text-gray-500 text-sm md:text-base">Find exactly what you're looking for</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 max-w-4xl mx-auto">
                    <Link
                        href="/products?category=phones"
                        class="group bg-white rounded-2xl p-5 md:p-8 text-center shadow-sm hover:shadow-xl border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                    >
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 group-hover:bg-black rounded-2xl flex items-center justify-center mx-auto mb-3 md:mb-4 transition-colors duration-300">
                            <Smartphone class="w-7 h-7 md:w-8 md:h-8 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-sm md:text-base">Phones</h4>
                        <p class="text-xs text-gray-400 mt-1">iPhone, Samsung & more</p>
                    </Link>
                    <Link
                        href="/products?category=computers"
                        class="group bg-white rounded-2xl p-5 md:p-8 text-center shadow-sm hover:shadow-xl border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                    >
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 group-hover:bg-black rounded-2xl flex items-center justify-center mx-auto mb-3 md:mb-4 transition-colors duration-300">
                            <Monitor class="w-7 h-7 md:w-8 md:h-8 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-sm md:text-base">Computers</h4>
                        <p class="text-xs text-gray-400 mt-1">Laptops & Desktops</p>
                    </Link>
                    <Link
                        href="/products?condition=new"
                        class="group bg-white rounded-2xl p-5 md:p-8 text-center shadow-sm hover:shadow-xl border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                    >
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 group-hover:bg-black rounded-2xl flex items-center justify-center mx-auto mb-3 md:mb-4 transition-colors duration-300">
                            <Star class="w-7 h-7 md:w-8 md:h-8 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-sm md:text-base">Brand New</h4>
                        <p class="text-xs text-gray-400 mt-1">Sealed & warranted</p>
                    </Link>
                    <Link
                        href="/products?condition=ex-uk"
                        class="group bg-white rounded-2xl p-5 md:p-8 text-center shadow-sm hover:shadow-xl border border-gray-100 hover:border-gray-200 transition-all duration-300 cursor-pointer hover:-translate-y-1"
                    >
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 group-hover:bg-black rounded-2xl flex items-center justify-center mx-auto mb-3 md:mb-4 transition-colors duration-300">
                            <Shield class="w-7 h-7 md:w-8 md:h-8 text-gray-600 group-hover:text-white transition-colors duration-300" />
                        </div>
                        <h4 class="font-bold text-sm md:text-base">Ex-UK</h4>
                        <p class="text-xs text-gray-400 mt-1">Premium, tested</p>
                    </Link>
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
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8"
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

        <!-- CTA Banner -->
        <section class="py-10 md:py-20 bg-gray-50">
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
                            @click="$el.closest('section').previousElementSibling?.previousElementSibling?.previousElementSibling?.querySelector('input')?.focus()"
                            class="inline-flex items-center gap-2 px-6 md:px-8 py-3 md:py-4 bg-white text-black rounded-xl font-bold hover:bg-gray-100 transition-all cursor-pointer active:scale-[0.98] shadow-lg"
                        >
                            <Search class="w-5 h-5" />
                            Try Budget Comparison
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
</style>
