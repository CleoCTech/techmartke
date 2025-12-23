<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    Code, 
    Shield, 
    LayoutDashboard, 
    Puzzle, 
    Sparkles, 
    Rocket,
    CheckCircle2,
    Zap,
    Layers,
    Users,
    Lock,
    Database,
    Palette,
    GitBranch,
    BookOpen,
    Github,
    Mail,
    Key,
    Phone,
    User,
    Menu,
    X,
    Twitter
} from 'lucide-vue-next';

import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};
</script>

<template>
    <Head title="VILT Starter Kit - Accelerate Your Development" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <Code class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xl font-bold text-slate-900 dark:text-white">VILT Starter Kit</span>
                    </div>

                    <!-- Desktop Navigation -->
                    <div v-if="canLogin" class="hidden md:flex items-center space-x-4">
                        <a href="#documentation" 
                            class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors flex items-center">
                            <BookOpen class="h-4 w-4 mr-2" />
                            Documentation
                        </a>
                        <Link v-if="$page.props.auth.user" 
                            :href="route('dashboard')" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" 
                                class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">
                                Log in
                            </Link>
                            <Link v-if="canRegister" 
                                :href="route('register')" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                Get Started
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div v-if="canLogin" class="md:hidden flex items-center">
                        <button 
                            @click="toggleMobileMenu"
                            class="p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            aria-label="Toggle menu"
                        >
                            <Menu v-if="!mobileMenuOpen" class="h-6 w-6" />
                            <X v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div 
                    v-if="canLogin && mobileMenuOpen" 
                    class="md:hidden border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900"
                >
                    <div class="px-2 pt-2 pb-4 space-y-1">
                        <!-- Breadcrumb indicator -->
                        <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Menu
                        </div>
                        
                        <a 
                            href="#documentation" 
                            @click="closeMobileMenu"
                            class="flex items-center px-3 py-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            <BookOpen class="h-5 w-5 mr-3" />
                            <span>Documentation</span>
                        </a>
                        
                        <a 
                            href="#features" 
                            @click="closeMobileMenu"
                            class="flex items-center px-3 py-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            <Sparkles class="h-5 w-5 mr-3" />
                            <span>Features</span>
                        </a>

                        <div class="border-t border-slate-200 dark:border-slate-700 my-2"></div>

                        <Link 
                            v-if="$page.props.auth.user" 
                            :href="route('dashboard')" 
                            @click="closeMobileMenu"
                            class="flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                        >
                            <LayoutDashboard class="h-5 w-5 mr-2" />
                            <span>Dashboard</span>
                        </Link>
            <template v-else>
                            <Link 
                                :href="route('login')" 
                                @click="closeMobileMenu"
                                class="flex items-center justify-center px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors font-medium"
                            >
                                <span>Log in</span>
                            </Link>
                            <Link 
                                v-if="canRegister" 
                                :href="route('register')" 
                                @click="closeMobileMenu"
                                class="flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                            >
                                <Rocket class="h-5 w-5 mr-2" />
                                <span>Get Started</span>
                            </Link>
            </template>
        </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
                <div class="text-center">
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mb-8">
                        <Sparkles class="h-4 w-4 text-indigo-600 dark:text-indigo-400 mr-2" />
                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Production-Ready Starter Kit</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 dark:text-white mb-6">
                        Build Faster with
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">VILT</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                        A powerful, modern starter kit combining <strong>Vue.js</strong>, <strong>Inertia.js</strong>, <strong>Laravel</strong>, and <strong>Tailwind CSS</strong> to accelerate your development workflow.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link v-if="canRegister && !$page.props.auth.user" 
                            :href="route('register')" 
                            class="px-8 py-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-semibold text-lg shadow-lg shadow-indigo-500/50 flex items-center">
                            <Rocket class="h-5 w-5 mr-2" />
                            Start Building Now
                        </Link>
                        <a href="#features" 
                            class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg border-2 border-slate-200 dark:border-slate-700 hover:border-indigo-600 dark:hover:border-indigo-500 transition-all font-semibold text-lg">
                            Explore Features
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Showcase Section -->
        <section class="py-20 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 relative overflow-hidden">
            <!-- Glowing background effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 blur-3xl"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">See It In Action</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">Experience the beautiful, ready-to-use dashboard</p>
                </div>
                
                <div class="flex justify-center items-center">
                    <div class="relative group">
                        <!-- Glowing effect wrapper -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl blur-2xl opacity-75 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                        
                        <!-- Image container with paint/fade borders -->
                        <div class="relative dashboard-image-container">
                            <img 
                                src="/assets/samples/dashboard.png" 
                                alt="VILT Starter Kit Dashboard" 
                                class="relative z-10 rounded-2xl shadow-2xl transform group-hover:scale-[1.02] transition-transform duration-500"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white dark:bg-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">Everything You Need to Build</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">Pre-configured, production-ready features that save you weeks of development time</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Authentication -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <Shield class="h-7 w-7 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Complete Authentication</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Built-in authentication system powered by Laravel Jetstream with email verification, password reset, two-factor authentication, and session management.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Login & Registration
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Email Verification
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Password Reset
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Role-Based Access Control
                            </li>
                        </ul>
                    </div>

                    <!-- Dashboard -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <LayoutDashboard class="h-7 w-7 text-purple-600 dark:text-purple-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Ready Dashboard</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Beautiful, responsive admin dashboard with analytics, user management, and customizable widgets. Start managing your application immediately.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Analytics Dashboard
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                User Management
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Responsive Design
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Dark Mode Support
                            </li>
                        </ul>
                    </div>

                    <!-- Reusable Components -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <Puzzle class="h-7 w-7 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Reusable Components</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Extensive library of pre-built Vue components including forms, modals, tables, charts, and more. All styled with Tailwind CSS and fully customizable.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Form Components
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Data Tables
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Charts & Graphs
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                UI Elements
                            </li>
                        </ul>
            </div>

                    <!-- Technology Stack -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <Layers class="h-7 w-7 text-green-600 dark:text-green-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Modern Tech Stack</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Built with the latest and greatest technologies. Laravel 10, Vue 3, Inertia.js, and Tailwind CSS for a seamless development experience.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Laravel 10 Framework
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Vue 3 Composition API
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Inertia.js SPA
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Tailwind CSS
                            </li>
                        </ul>
                            </div>

                    <!-- Adaptable Patterns -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <GitBranch class="h-7 w-7 text-orange-600 dark:text-orange-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Adaptable Patterns</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Clean, maintainable code architecture with proven patterns. Easy to extend, modify, and scale according to your project needs.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                MVC Architecture
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Service Layer Pattern
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Repository Pattern
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Component-Based UI
                            </li>
                        </ul>
                    </div>

                    <!-- Development Benefits -->
                    <div class="group p-8 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-all hover:shadow-xl">
                        <div class="h-14 w-14 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <Zap class="h-7 w-7 text-red-600 dark:text-red-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Faster Development</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                            Save weeks of development time. Focus on building features instead of setting up infrastructure, authentication, and common components.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Pre-configured Setup
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Best Practices Included
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Comprehensive Documentation
                            </li>
                            <li class="flex items-center">
                                <CheckCircle2 class="h-4 w-4 text-green-500 mr-2 flex-shrink-0" />
                                Production Ready
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technology Stack Details -->
        <section class="py-20 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-slate-900 dark:to-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">Powered by Modern Technologies</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">Built with industry-leading tools and frameworks</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <span class="text-3xl font-bold text-red-500">L</span>
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Laravel</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">v{{ laravelVersion }}</p>
                    </div>
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <span class="text-3xl font-bold text-green-500">V</span>
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Vue 3</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Composition API</p>
                    </div>
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <Database class="h-10 w-10 text-indigo-500" />
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Inertia.js</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">SPA Experience</p>
                    </div>
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <Palette class="h-10 w-10 text-cyan-500" />
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Tailwind CSS</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Utility First</p>
                    </div>
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <Lock class="h-10 w-10 text-purple-500" />
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Jetstream</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Authentication</p>
                    </div>
                    <div class="text-center group">
                        <div class="h-20 w-20 mx-auto bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                            <Users class="h-10 w-10 text-blue-500" />
                        </div>
                        <h4 class="font-semibold text-slate-900 dark:text-white">Laratrust</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Roles & Permissions</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="py-20 bg-white dark:bg-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">Why Choose VILT Starter Kit?</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">Accelerate your development process and deliver faster</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">⚡</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Save Time</h3>
                        <p class="text-slate-600 dark:text-slate-300">Skip weeks of setup and boilerplate code. Start building features from day one.</p>
                    </div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">🛡️</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Production Ready</h3>
                        <p class="text-slate-600 dark:text-slate-300">Built with security, performance, and scalability in mind. Deploy with confidence.</p>
                    </div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">🎨</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Beautiful UI</h3>
                        <p class="text-slate-600 dark:text-slate-300">Modern, responsive design with dark mode support. Impress your users from the start.</p>
                    </div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">📚</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Well Documented</h3>
                        <p class="text-slate-600 dark:text-slate-300">Comprehensive documentation to help you understand and extend the codebase.</p>
                    </div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">🔧</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Easy to Customize</h3>
                        <p class="text-slate-600 dark:text-slate-300">Clean code architecture makes it easy to modify and extend according to your needs.</p>
                    </div>
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-xl">
                        <div class="text-4xl mb-4">🚀</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Fast Performance</h3>
                        <p class="text-slate-600 dark:text-slate-300">Optimized for speed with modern best practices and efficient code patterns.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Documentation Section -->
        <section id="documentation" class="py-20 bg-white dark:bg-slate-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mb-6">
                        <BookOpen class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" />
                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Getting Started</span>
                    </div>
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">Documentation</h2>
                    <p class="text-xl text-slate-600 dark:text-slate-300">Learn how to get started with VILT Starter Kit</p>
                        </div>

                <div class="space-y-8">
                    <!-- GitHub Repository -->
                    <div class="bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-8">
                        <div class="flex items-center mb-4">
                            <Github class="h-6 w-6 text-slate-900 dark:text-white mr-3" />
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">GitHub Repository</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-4">
                            The complete source code is available on GitHub. Clone the repository to get started:
                        </p>
                        <div class="bg-slate-900 dark:bg-slate-950 rounded-lg p-4 mb-4">
                            <code class="text-green-400 text-sm font-mono break-all">
                                git clone https://github.com/CleoCTech/VILT-STARTER-KIT.git
                            </code>
                        </div>
                        <a href="https://github.com/CleoCTech/VILT-STARTER-KIT" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center px-6 py-3 bg-slate-900 dark:bg-slate-700 text-white rounded-lg hover:bg-slate-800 dark:hover:bg-slate-600 transition-colors font-medium">
                            <Github class="h-5 w-5 mr-2" />
                            View on GitHub
                        </a>
                    </div>

                    <!-- Quick Start -->
                    <div class="bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Quick Start Guide</h3>
                        
                        <div class="space-y-6">
                        <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                                    <span class="h-8 w-8 bg-indigo-600 text-white rounded-full flex items-center justify-center mr-3 text-sm font-bold">1</span>
                                    Installation
                                </h4>
                                <div class="ml-11 space-y-2 text-slate-600 dark:text-slate-300">
                                    <p>1. Clone the repository</p>
                                    <p>2. Install dependencies: <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">composer install</code> and <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">npm install</code></p>
                                    <p>3. Copy <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">.env.example</code> to <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">.env</code></p>
                                    <p>4. Generate application key: <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">php artisan key:generate</code></p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                                    <span class="h-8 w-8 bg-indigo-600 text-white rounded-full flex items-center justify-center mr-3 text-sm font-bold">2</span>
                                    Database Setup
                                </h4>
                                <div class="ml-11 space-y-2 text-slate-600 dark:text-slate-300">
                                    <p>1. Configure your database in <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">.env</code></p>
                                    <p>2. Run migrations: <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">php artisan migrate</code></p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                                    <span class="h-8 w-8 bg-indigo-600 text-white rounded-full flex items-center justify-center mr-3 text-sm font-bold">3</span>
                                    <span class="text-red-600 dark:text-red-400">⚠️ Seed Database (IMPORTANT)</span>
                                </h4>
                                <div class="ml-11 space-y-3">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded">
                                        <p class="text-slate-800 dark:text-slate-200 font-medium mb-2">⚠️ Important: Seed the database before accessing the dashboard!</p>
                                        <p class="text-slate-600 dark:text-slate-300 text-sm mb-3">
                                            The database must be seeded to create the default admin user and initial data. Run the following command:
                                        </p>
                                        <div class="bg-slate-900 dark:bg-slate-950 rounded-lg p-3 mb-3">
                                            <code class="text-green-400 text-sm font-mono">php artisan db:seed</code>
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-300 text-sm">
                                            This will create the default admin user and populate the database with essential data.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-3 flex items-center">
                                    <span class="h-8 w-8 bg-indigo-600 text-white rounded-full flex items-center justify-center mr-3 text-sm font-bold">4</span>
                                    Build Assets
                                </h4>
                                <div class="ml-11 space-y-2 text-slate-600 dark:text-slate-300">
                                    <p>Build frontend assets: <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">npm run build</code> or <code class="bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded">npm run dev</code></p>
                                </div>
                            </div>
                        </div>
                        </div>

                    <!-- Default Credentials -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl border-2 border-indigo-200 dark:border-indigo-800 p-8">
                        <div class="flex items-center mb-4">
                            <Key class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mr-3" />
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Default Dashboard Credentials</h3>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 mb-6">
                            After seeding the database, you can log in to the dashboard using these default credentials:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center mb-2">
                                    <Mail class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" />
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Email:</span>
                                </div>
                                <code class="text-indigo-600 dark:text-indigo-400 font-mono text-lg">admin@example.com</code>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center mb-2">
                                    <Key class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" />
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Password:</span>
                                </div>
                                <code class="text-indigo-600 dark:text-indigo-400 font-mono text-lg">password</code>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Note:</strong> Please change the default password after your first login for security purposes.
                            </p>
                        </div>
                        </div>

                    <!-- How It Works -->
                    <div class="bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 p-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">How It Works</h3>
                        
                        <div class="space-y-6">
                        <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Architecture Overview</h4>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                                    VILT Starter Kit uses a modern stack combining <strong>Laravel</strong> as the backend framework, 
                                    <strong>Vue 3</strong> with Composition API for the frontend, <strong>Inertia.js</strong> for seamless SPA-like experience 
                                    without the complexity of a separate API, and <strong>Tailwind CSS</strong> for rapid UI development.
                                </p>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Key Features</h4>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                                    <li class="flex items-start">
                                        <CheckCircle2 class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" />
                                        <span><strong>Authentication:</strong> Complete authentication system with Laravel Jetstream including login, registration, email verification, and password reset.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <CheckCircle2 class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" />
                                        <span><strong>Role-Based Access Control:</strong> Built-in roles and permissions system using Laratrust for managing user access levels.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <CheckCircle2 class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" />
                                        <span><strong>Admin Dashboard:</strong> Ready-to-use admin dashboard with analytics, user management, and customizable widgets.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <CheckCircle2 class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" />
                                        <span><strong>Reusable Components:</strong> Extensive library of Vue components for forms, tables, modals, charts, and more.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <CheckCircle2 class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" />
                                        <span><strong>Adaptable Patterns:</strong> Clean code architecture following MVC, Service Layer, and Repository patterns for easy customization.</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Development Workflow</h4>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                                    The starter kit follows Laravel conventions and best practices. Controllers handle HTTP requests, 
                                    Services contain business logic, Models represent database entities, and Vue components handle 
                                    the user interface. Inertia.js bridges the gap, allowing you to use Laravel routes and controllers 
                                    while enjoying a modern SPA experience.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Build Something Amazing?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Get started with VILT Starter Kit today and accelerate your development workflow. 
                    Everything you need is already configured and ready to use.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link v-if="canRegister && !$page.props.auth.user" 
                        :href="route('register')" 
                        class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-all transform hover:scale-105 font-semibold text-lg shadow-xl">
                        Get Started Free
                    </Link>
                    <Link v-if="$page.props.auth.user" 
                        :href="route('dashboard')" 
                        class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-all transform hover:scale-105 font-semibold text-lg shadow-xl">
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Mobile: Single Column, Desktop: 3 Columns -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-8 mb-6 md:mb-8">
                    <!-- Brand Section -->
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start space-x-2 mb-4">
                            <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <Code class="h-5 w-5 text-white" />
                            </div>
                            <span class="text-lg md:text-xl font-bold text-white">VILT Starter Kit</span>
                        </div>
                        <p class="text-xs md:text-sm mb-2 text-slate-500">Built with Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</p>
                        <p class="text-xs md:text-sm text-slate-500 mb-4">
                            Powered by <a href="https://wenlasystems.com/" target="_blank" rel="noopener noreferrer" class="text-white font-semibold hover:text-indigo-400 transition-colors">Wenla Systems</a>
                        </p>
                        
                        <!-- Social Media Links -->
                        <div class="flex items-center justify-center md:justify-start space-x-3">
                            <a href="https://x.com/cleo_hacker" target="_blank" rel="noopener noreferrer" 
                                class="h-9 w-9 rounded-lg bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all group"
                                aria-label="Follow on X (Twitter)">
                                <Twitter class="h-5 w-5 group-hover:scale-110 transition-transform" />
                            </a>
                            <a href="https://github.com/CleoCTech" target="_blank" rel="noopener noreferrer" 
                                class="h-9 w-9 rounded-lg bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all group"
                                aria-label="GitHub Profile">
                                <Github class="h-5 w-5 group-hover:scale-110 transition-transform" />
                            </a>
                            <a href="https://comwengas.com" target="_blank" rel="noopener noreferrer" 
                                class="h-9 w-9 rounded-lg bg-slate-800 hover:bg-indigo-600 flex items-center justify-center text-slate-400 hover:text-white transition-all group"
                                aria-label="Personal Website">
                                <User class="h-5 w-5 group-hover:scale-110 transition-transform" />
                            </a>
                        </div>
                    </div>

                    <!-- Support Section -->
                    <div class="text-center md:text-left border-t md:border-t-0 border-slate-800 pt-6 md:pt-0">
                        <h3 class="text-white font-semibold mb-4 text-base md:text-lg">Support</h3>
                        <div class="space-y-3">
                            <a href="mailto:cleoctech@gmail.com" class="flex items-center justify-center md:justify-start text-slate-400 hover:text-white transition-colors group py-2 md:py-0">
                                <Mail class="h-5 w-5 md:h-4 md:w-4 mr-3 md:mr-2 group-hover:text-indigo-400 flex-shrink-0" />
                                <span class="text-sm md:text-sm break-all">cleoctech@gmail.com</span>
                            </a>
                            <a href="tel:+254727057310" class="flex items-center justify-center md:justify-start text-slate-400 hover:text-white transition-colors group py-2 md:py-0">
                                <Phone class="h-5 w-5 md:h-4 md:w-4 mr-3 md:mr-2 group-hover:text-indigo-400 flex-shrink-0" />
                                <span class="text-sm md:text-sm">+254 727 057 310</span>
                            </a>
                            <a href="https://x.com/cleo_hacker" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center md:justify-start text-slate-400 hover:text-white transition-colors group py-2 md:py-0">
                                <Twitter class="h-5 w-5 md:h-4 md:w-4 mr-3 md:mr-2 group-hover:text-indigo-400 flex-shrink-0" />
                                <span class="text-sm md:text-sm">@cleo_hacker</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="text-center md:text-left border-t md:border-t-0 border-slate-800 pt-6 md:pt-0">
                        <h3 class="text-white font-semibold mb-4 text-base md:text-lg">Quick Links</h3>
                        <div class="space-y-3 md:space-y-2">
                            <a href="#features" class="block text-slate-400 hover:text-white transition-colors text-sm py-2 md:py-1 hover:translate-x-1 md:hover:translate-x-0 transition-transform">Features</a>
                            <a href="#documentation" class="block text-slate-400 hover:text-white transition-colors text-sm py-2 md:py-1 hover:translate-x-1 md:hover:translate-x-0 transition-transform">Documentation</a>
                            <a href="https://github.com/CleoCTech/VILT-STARTER-KIT" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center md:justify-start text-slate-400 hover:text-white transition-colors text-sm py-2 md:py-1 group">
                                <Github class="h-4 w-4 mr-2 group-hover:text-indigo-400 hidden md:inline" />
                                <span>GitHub Repository</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="border-t border-slate-800 pt-6 md:pt-8">
                    <p class="text-xs md:text-sm text-center text-slate-500">© {{ new Date().getFullYear() }} VILT Starter Kit. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Dashboard image with paint/fade border effect */
.dashboard-image-container {
    position: relative;
    padding: 12px;
    background: 
        radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.4) 0%, transparent 50%),
        radial-gradient(circle at 100% 0%, rgba(168, 85, 247, 0.4) 0%, transparent 50%),
        radial-gradient(circle at 100% 100%, rgba(236, 72, 153, 0.4) 0%, transparent 50%),
        radial-gradient(circle at 0% 100%, rgba(99, 102, 241, 0.4) 0%, transparent 50%);
    border-radius: 24px;
    background-size: 200% 200%;
    animation: paintBorder 10s ease infinite;
}

.dashboard-image-container::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 28px;
    background: 
        radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.8) 0%, transparent 40%),
        radial-gradient(circle at 80% 20%, rgba(168, 85, 247, 0.8) 0%, transparent 40%),
        radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.8) 0%, transparent 40%),
        radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.8) 0%, transparent 40%);
    background-size: 150% 150%;
    animation: paintGlow 12s ease infinite;
    z-index: -1;
    filter: blur(20px);
    opacity: 0.8;
}

.dashboard-image-container::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 24px;
    padding: 12px;
    background: linear-gradient(135deg, 
        rgba(99, 102, 241, 0.2) 0%, 
        rgba(168, 85, 247, 0.15) 30%,
        rgba(236, 72, 153, 0.2) 60%,
        rgba(168, 85, 247, 0.15) 100%
    );
    background-size: 300% 300%;
    animation: paintBorder 8s ease infinite;
    mask: 
        linear-gradient(#fff 0 0) content-box, 
        linear-gradient(#fff 0 0);
    mask-composite: exclude;
    -webkit-mask-composite: xor;
    pointer-events: none;
}

.dashboard-image-container img {
    display: block;
    width: 100%;
    max-width: 1200px;
    height: auto;
    border-radius: 16px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(99, 102, 241, 0.1),
        0 0 120px -30px rgba(99, 102, 241, 0.4),
        0 0 200px -50px rgba(168, 85, 247, 0.3);
    position: relative;
    z-index: 1;
}

@keyframes paintBorder {
    0%, 100% {
        background-position: 0% 0%, 100% 0%, 100% 100%, 0% 100%;
    }
    25% {
        background-position: 50% 0%, 100% 50%, 50% 100%, 0% 50%;
    }
    50% {
        background-position: 100% 0%, 50% 100%, 0% 100%, 50% 0%;
    }
    75% {
        background-position: 50% 100%, 0% 50%, 50% 0%, 100% 50%;
    }
}

@keyframes paintGlow {
    0%, 100% {
        background-position: 0% 0%, 100% 0%, 100% 100%, 0% 100%;
        opacity: 0.6;
    }
    50% {
        background-position: 100% 100%, 0% 100%, 0% 0%, 100% 0%;
        opacity: 1;
    }
}

/* Enhanced glow on hover */
.group:hover .dashboard-image-container::before {
    opacity: 1;
    filter: blur(25px);
    animation-duration: 6s;
}

.group:hover .dashboard-image-container::after {
    opacity: 0.4;
}
</style>
