<template>
  <v-chart :class="[maxWidth,maxHeight]" :option="option" />
</template>

<script>
    import { use } from "echarts/core";
    import { CanvasRenderer } from "echarts/renderers";
    import { LineChart } from "echarts/charts";
    import {TitleComponent,TooltipComponent,LegendComponent,GridComponent} from "echarts/components";
    import VChart, { THEME_KEY } from "vue-echarts";
    import { ref, defineComponent } from "vue";
    use([
        CanvasRenderer,
        LineChart,
        TitleComponent,
        TooltipComponent,
        LegendComponent,
        GridComponent
    ]);
    export default defineComponent({
        props: {
            chartData:Object,
            maxWidth:{default:'w-screen'},
            maxHeight:{default:'h-screen'},
        },
        components: {
            VChart
        },
        provide: {
            [THEME_KEY]: ""
        },
        // setup () {
        //     const option = ref(chartData);

        //     return { option };
        // },
        data(){
            return{
                option:{}
            }
        },
        mounted(){
            this.option = this.chartData;
        }
    });
</script>
