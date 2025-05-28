<template>
    <div>
        <x-report-template ref="reportPage">
            <x-menu-bar-item :type="'button'" @click="this.$refs.reportPage.onReport(indexRoute+'/report/services')"  :iconName="'bars'">Services</x-menu-bar-item>
        </x-report-template>
    </div>
</template>
<script>
    import xReportTemplate from '@/System/Reports/Templates/Default.vue'
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    export default{
        components:{xReportTemplate},
        mixins:[MenuBarTabsMixin],
    }
</script>
