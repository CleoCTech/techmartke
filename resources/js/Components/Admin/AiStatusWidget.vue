<script setup>
import { ref, onMounted } from 'vue';
import { Sparkles, CheckCircle2, XCircle, Loader2, RefreshCw, Zap } from 'lucide-vue-next';

const status = ref('loading'); // loading | ok | error
const data = ref(null);
const isChecking = ref(false);

const runTest = async () => {
    isChecking.value = true;
    status.value = 'loading';
    try {
        const res = await fetch('/admin/ai-status/test', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });
        const json = await res.json();
        data.value = json;
        status.value = json.status === 'ok' ? 'ok' : 'error';
    } catch (e) {
        data.value = {
            status: 'error',
            message: 'Network error: ' + e.message,
            fix: 'Check your connection and try again.',
        };
        status.value = 'error';
    } finally {
        isChecking.value = false;
    }
};

onMounted(() => {
    runTest();
});
</script>

<template>
    <div
        :class="[
            'rounded-2xl border p-5 transition-all',
            status === 'ok' && 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/30',
            status === 'error' && 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/30',
            status === 'loading' && 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700',
        ]"
    >
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-3">
                <div
                    :class="[
                        'w-10 h-10 rounded-xl flex items-center justify-center',
                        status === 'ok' && 'bg-emerald-500',
                        status === 'error' && 'bg-red-500',
                        status === 'loading' && 'bg-slate-400',
                    ]"
                >
                    <Loader2 v-if="status === 'loading'" class="w-5 h-5 text-white animate-spin" />
                    <CheckCircle2 v-else-if="status === 'ok'" class="w-5 h-5 text-white" />
                    <XCircle v-else class="w-5 h-5 text-white" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <Sparkles class="w-4 h-4 text-slate-500 dark:text-slate-400" />
                        <h3 class="font-bold text-slate-800 dark:text-slate-100">AI Service Status</h3>
                    </div>
                    <p
                        :class="[
                            'text-sm font-semibold mt-0.5',
                            status === 'ok' && 'text-emerald-700 dark:text-emerald-400',
                            status === 'error' && 'text-red-700 dark:text-red-400',
                            status === 'loading' && 'text-slate-500 dark:text-slate-400',
                        ]"
                    >
                        <span v-if="status === 'loading'">Testing connection...</span>
                        <span v-else-if="status === 'ok'">Connected & Ready</span>
                        <span v-else>Disconnected</span>
                    </p>
                </div>
            </div>
            <button
                @click="runTest"
                :disabled="isChecking"
                class="p-2 rounded-lg hover:bg-white/60 dark:hover:bg-slate-700/50 transition cursor-pointer disabled:opacity-50"
                title="Re-run test"
            >
                <RefreshCw :class="['w-4 h-4 text-slate-600 dark:text-slate-300', isChecking && 'animate-spin']" />
            </button>
        </div>

        <div v-if="data" class="space-y-2">
            <!-- Success details -->
            <div v-if="status === 'ok'" class="space-y-1.5 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Model</span>
                    <code class="text-slate-700 dark:text-slate-200 font-mono text-[10px] bg-white/50 dark:bg-slate-800/50 px-2 py-0.5 rounded">{{ data.model }}</code>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Latency</span>
                    <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ data.latency_ms }}ms</span>
                </div>
                <div v-if="data.input_tokens" class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Tokens (in/out)</span>
                    <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ data.input_tokens }} / {{ data.output_tokens }}</span>
                </div>
                <p class="text-xs text-emerald-700 dark:text-emerald-400 pt-2 border-t border-emerald-200/60 dark:border-emerald-500/20 mt-2">
                    <Zap class="w-3 h-3 inline" /> Bulk upload, content generation & smart features are working.
                </p>
            </div>

            <!-- Error details -->
            <div v-else-if="status === 'error'" class="space-y-2">
                <p class="text-sm text-red-700 dark:text-red-400 leading-relaxed">{{ data.message }}</p>
                <div v-if="data.error_type" class="text-[10px] uppercase tracking-wider text-red-600/70 dark:text-red-400/60 font-mono">
                    {{ data.error_type }}
                </div>
                <div v-if="data.fix" class="bg-white/60 dark:bg-slate-800/60 rounded-lg p-2.5 border border-red-200/60 dark:border-red-500/20">
                    <p class="text-[11px] font-semibold text-red-800 dark:text-red-300 mb-0.5">How to fix:</p>
                    <p class="text-xs text-red-700 dark:text-red-400">{{ data.fix }}</p>
                </div>
                <p class="text-xs text-amber-700 dark:text-amber-400 pt-1">
                    Note: Bulk upload still works via regex fallback parser, but no AI-generated descriptions, specs, or SEO content will be created.
                </p>
            </div>
        </div>
    </div>
</template>
