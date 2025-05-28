<template>
    <tr class="text-gray-400 hover:bg-gray-700 focus:bg-gray-700 border-b" :class="[rowNo % 2 == 0? 'bg-gray-800':'bg-gray-800',url != ''? 'cursor-pointer':'',isSelected? 'bg-gray-700':'']" @click="onclick" @dblclick="onDblClick">
        <slot></slot>
    </tr>
</template>
<script>
   import { router } from '@inertiajs/vue3'
    export default{
        props:{row:{default:0},url:{default:''},isSelected:{default:false}},
        components:{router},
        inject: ['onRowClick'],
        data(){
            return{
                rowNo:0,
                recordId:0,
            }
        },
        mounted(){
            var rowKeys = this.row.split('#');
            this.recordId = rowKeys[0];
            this.rowNo = rowKeys[1];
        },
        methods:{
            onclick(){
                if(this.url != ''){
                    this.onRowClick(this.row)
                }
            },
            onDblClick(){
                if(this.url != ''){
                    router.visit(this.url);
                }
            }
        }
    }
</script>
