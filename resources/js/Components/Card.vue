<template>
<div>
    <div class="w-full borderx bg-white rounded-lg shadow-md shadow-teal-600 mb-2" style="width:100% !important">
        <div v-if="isImage" class="flex justify-center border-b" style="height:170px !important">
            <slot name="image"></slot>
        </div>
        <div class="px-6 py-2" style="min-height:170px !important">
            <div class="font-bold text-xl mb-2 text-center text-amber-500">
                <slot name="title"></slot>
            </div>
            <hr>
            <slot name="content"></slot>
        </div>
        <div v-if="isFooter" class="px-6 pt-4 pb-2 text-center">
            <slot name="footer"></slot>
        </div>
    </div>
</div>
</template>
<script>
    export default{
        props:{
            isImage:{default:true},
            isFooter:{default:true},
        },
    }
</script>
