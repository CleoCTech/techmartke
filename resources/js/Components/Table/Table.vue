<template>
    <div class="overflow-x-auto w-full shadow-md ml-6">
        <table class="table-auto w-full relative rounded-t-lg">
            <thead class="bg-gray-800 text-gray-400 text-sm font-semibold sticky top-0 border rounded-t-lg">
                <slot name="thead"></slot>
            </thead>
            <tbody class="text-xs sm:text-sm border">
                <slot name="tbody"></slot>
            </tbody>
        </table>
    </div>
</template>
<script>
    import xCheckbox from '@/Components/Checkbox.vue'
    export default{
        components:{xCheckbox},
    }
</script>
