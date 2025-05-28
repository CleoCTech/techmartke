<script setup>
import { ref,getCurrentInstance, onMounted } from 'vue'
import {useNotify} from "@/Composables/useNotify";
// import '../../../css/app.css';
// import '../../../css/style.css';

let {notification} = useNotify();

let isLoading = ref(false);
const context = getCurrentInstance().appContext.config.globalProperties;

context.$showLoading = () => {
    isLoading.value = true
}
context.$hideLoading = () => {
    isLoading.value = false
}

onMounted(() => {
    // notification('Test error message', 'error');

})
</script>

<template>
    <div id="app">
        <notifications />
        <loading v-model:active ="isLoading"
                 :can-cancel="false"
                 :is-full-page="fullPage"/>

        <div class="flex h-[100dvh] overflow-hidden">
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <main class="grow">
                <slot />
            </main>
            </div>
        </div>     
    </div>
</template>

