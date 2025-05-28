import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

//components
import xTable from '@/Components/Table/Table.vue'
import xTabletd from '@/Components/Table/Td.vue'
import xTabletdCheckbox from '@/Components/Table/TdCheckbox.vue'
import xTableth from '@/Components/Table/Th.vue'
import xTablethCheckbox from '@/Components/Table/ThCheckbox.vue'
import xTabletr from '@/Components/Table/Tr.vue'
import xBadge from '@/Components/Badge.vue'
import xIndexTemplate from '@/System/Pages/Templates/CRUD/Index.vue'
export default {
    components:{xIndexTemplate,xTable,xTabletd,xTableth,xTabletr,xTablethCheckbox,xTabletdCheckbox,xBadge},
    props:{
        setup:Object,
        listData:Object,
    },
    data(){
        return{
            selected:[],
            isMultipleSelect: false
        }
    },
    methods:{
        onCheck(item){
            if(this.selected.indexOf(item) === -1){
                    this.selected.push(item);
            }else{
                this.selected.splice(this.selected.indexOf(item),1);
            }
        },
        onRowClick(item){
            this.selected = [];
            this.selected.push(item);
        },
        isSelected(item){
            if(this.selected.indexOf(item) === -1){
                return false;
            }else{
                return true;
            }
        }
    },
    watch: {
        isMultipleSelect: function () {
          if(this.isMultipleSelect == false){
              this.selected = [];
          }
        }
      }
  };
