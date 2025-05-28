<template>
    <div>
        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
        <x-default-report v-if="filterPage != '' && filterPage == 'default'" ref="xReportModal" :reportData="reportData"/>
        <slot></slot>
    </div>
</template>
<script>
    import "gitart-vue-dialog/dist/style.css";
    import { GDialog, plugin } from "gitart-vue-dialog";
    import xDefaultReport from '@/System/Reports/FilterPages/Default.vue'
    export default{
        props:{indexRoute:{default:''}},
        components: {
            GDialog,xDefaultReport
        },
        data() {
            return{
                filterPage:'',
                reportData:[],
                isLoading:false,
            }
        },
        methods: {
            onReport(url){
                this.isLoading = true;
                axios.get(url).then(response => {
                    this.reportData = response.data;
                    this.filterPage = this.reportData.filterPage;
                    this.isLoading = false;
                }).catch((error) => {
                    this.isLoading = false;
                })
            }
        },
    }
</script>
