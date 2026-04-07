<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-50 to-blue-50">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-xl border border-slate-200">
            <slot />
        </div>

        <p class="mt-6 text-xs text-slate-400">&copy; {{ new Date().getFullYear() }} TechMart Kenya. All rights reserved.</p>
    </div>
</template>
