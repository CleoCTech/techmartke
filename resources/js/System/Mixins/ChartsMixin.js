import { ref } from 'vue'
import "gitart-vue-dialog/dist/style.css";
import { GDialog, plugin } from "gitart-vue-dialog";
import xLineChart from '@/Components/Charts/LineChart.vue'

export default{
    components: {
        GDialog,
        xLineChart
    },
    data(){
        return{
            isLoading:true,
            isOpen:false,
            chartData:[],
        }
    },
    mounted() {
        this.getChartsData(this.url);
    },
    methods:{
        getChartsData(url){
            var self = this;
            this.isLoading = true;
            axios.get(url).then(response => {
                if(response.data != null){
                    var data = response.data;
                    self.chartData = data.options;
                    this.isLoading = false;
                    this.isOpen = true;
                }
            }).catch((error) => {
                //
            })
        },
        onClose(){
            this.isOpen = false;
            this.$parent.isChart = false;
        }
    }
}
