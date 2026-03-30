<script setup>
import { ref, nextTick } from 'vue';
import { MessageCircle, X, Send } from 'lucide-vue-next';

const isOpen = ref(false);
const inputText = ref('');
const isLoading = ref(false);
const messagesContainer = ref(null);

const messages = ref([
    {
        role: 'bot',
        text: 'Hi! I can help you find the perfect phone or computer within your budget. What are you looking for?',
    },
]);

const toggleChat = () => {
    isOpen.value = !isOpen.value;
};

const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

const sendMessage = async () => {
    const text = inputText.value.trim();
    if (!text || isLoading.value) return;

    messages.value.push({ role: 'user', text });
    inputText.value = '';
    isLoading.value = true;
    await scrollToBottom();

    try {
        const response = await fetch('/api/ai-chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ message: text }),
        });

        const data = await response.json();
        messages.value.push({
            role: 'bot',
            text: data.message || data.response || 'Sorry, I could not process your request. Please try again.',
        });
    } catch {
        messages.value.push({
            role: 'bot',
            text: 'Sorry, something went wrong. Please try again later.',
        });
    } finally {
        isLoading.value = false;
        await scrollToBottom();
    }
};

const handleKeydown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
};
</script>

<template>
    <!-- Chat Toggle Button -->
    <button
        @click="toggleChat"
        class="fixed bottom-[30px] right-[30px] w-[60px] h-[60px] rounded-full bg-black text-white flex items-center justify-center cursor-pointer shadow-[0_4px_20px_rgba(0,0,0,0.3)] z-[1000] hover:bg-gray-800 transition"
    >
        <MessageCircle v-if="!isOpen" class="w-8 h-8" />
        <X v-else class="w-8 h-8" />
    </button>

    <!-- Chat Window -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-4 scale-95"
    >
        <div
            v-if="isOpen"
            class="fixed bottom-[100px] right-[30px] w-[380px] h-[500px] bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.2)] flex flex-col z-[1000]"
        >
            <!-- Header -->
            <div class="bg-black text-white p-4 rounded-t-2xl flex justify-between items-center shrink-0">
                <h4 class="font-bold">AI Assistant</h4>
                <button @click="toggleChat" class="text-white hover:text-gray-300 transition">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Messages -->
            <div ref="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-4">
                <div
                    v-for="(msg, idx) in messages"
                    :key="idx"
                    :class="msg.role === 'user' ? 'flex justify-end' : 'flex'"
                >
                    <div
                        :class="
                            msg.role === 'user'
                                ? 'bg-black text-white rounded-2xl rounded-br-sm'
                                : 'bg-gray-100 border border-gray-200 rounded-2xl rounded-bl-sm'
                        "
                        class="p-3 max-w-[80%]"
                    >
                        <p class="text-sm whitespace-pre-wrap">{{ msg.text }}</p>
                    </div>
                </div>

                <!-- Typing Indicator -->
                <div v-if="isLoading" class="flex">
                    <div class="bg-gray-100 border border-gray-200 rounded-2xl rounded-bl-sm p-3">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <div class="border-t border-gray-200 p-4 bg-white rounded-b-2xl shrink-0">
                <div class="flex gap-2">
                    <input
                        v-model="inputText"
                        type="text"
                        placeholder="Ask me anything..."
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black text-sm"
                        @keydown="handleKeydown"
                    />
                    <button
                        @click="sendMessage"
                        :disabled="isLoading || !inputText.trim()"
                        class="px-5 py-2.5 bg-black text-white rounded-xl hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <Send class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
