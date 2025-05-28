<template>
    <div>
        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
        <GDialog v-if="!isLoading" v-model="isOpen" fullscreen>
            <div class="flex flex-col">
                <!--title-->
                <div class="px-1 py-1 flex justify-end items-center gap-4  cursor-pointer"  @click="onClose">
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer"/>
                    <span class="text-red-500 text-xs font-semibold">Close</span>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min">
                    <x-line-chart v-if="chartData.series[0].type == 'line'" :chartData="chartData"/>
                </div>
            </div>
        </GDialog>
    </div>
</template>
<script>
    import ChartsMixin from '@/System/Mixins/ChartsMixin.js'
    export default{
        props:{url:{default:''}},
        mixins:[ChartsMixin],
    }
</script>
