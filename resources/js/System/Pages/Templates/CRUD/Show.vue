<template>
    <div>
        <x-menu-bar v-bind="{setup,selected}" class="w-full"></x-menu-bar>
        <div class="px-4 sm:px-6 lg:px-8 py-4 w-full max-w-9xl mx-auto">
            <x-panel class="grow w-full">
                <template #title>Create {{setup.settings.caption}}</template>
                <template #body>
                    <slot></slot>
                </template>
            </x-panel>
            <x-attachment v-if="isAttachments" class="flex-none pr-2 md:max-w-xs" :setup="setup" :selected="selected"/>
        </div>
    </div>
</template>
<script>
    import xPanel from '@/Components/Panel.vue'
    import xButton from '@/Components/Button.vue'
    import xMenuBar from '@/Components/MenuBar.vue'
    import xAttachment from '@/Components/Attachment.vue'
    export default {
        components:{xPanel,xButton,xMenuBar,xAttachment},
        props:{
            setup:Object,
            selected:{default:[]},
        },
        data(){
            return{
                isAttachments:false,
            }
        }
    }
</script>
