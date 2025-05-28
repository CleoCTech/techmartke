import xMenuBarItem from '@/Components/MenuBarItem.vue'

export default{
    props:{indexRoute:{default:''}},
    components: {
        xMenuBarItem
    },
    data(){
        return{
            isChart:false,
            chartUrl:'',
        }
    },
    methods:{
        onChartClick(url){
            this.isOpen = false;
            this.$parent.filterPage = '';
        }
    }
}
