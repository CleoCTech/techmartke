<template>
    <div>
        <GDialog v-model="isOpen" :max-width="maxWidth" persistent>
            <div class="flex flex-col">
                <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">{{reportData.setup.pageTitle}}</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="onClose"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min">
                    <div v-for="(dataItem,index) in reportData.dataItems" :key="index" class="border-b mb-2">
                        <h3 class="text-sm font-semibold">{{dataItem.caption}}</h3>
                        <div v-if="dataItem.filters.length > 0" class="pb-2">
                            <div v-for="(ft,index2) in dataItem.filters" :key="index2" class="flex gap-1">
                                <span @click="removeFilter(index,index2)" class="cursor-pointer text-red-500">x</span> {{ft.column}} {{ft.operator}} {{ft.value}}
                            </div>
                        </div>
                        <div class="flex gap-1 flex-col sm:flex-row">
                            <x-select v-model="dataItem.filter.column" @change="onColumnChange(index)"  class="text-sm">
                                <option value="">--filter by--</option>
                                <option v-for="(column,index) in dataItem.columns" :key="index" :value="column.name">{{column.name}}</option>
                            </x-select>
                            <x-select v-model="dataItem.filter.operator"  class="text-sm">
                                <option v-for="(operator,index) in reportData.operators" :key="index" :value="operator.operator">{{operator.caption}}</option>
                            </x-select>
                            <x-input v-if="dataItem.filter.element == 'input' || dataItem.filter.element == 'textarea'" v-model="dataItem.filter.value" :type="dataItem.filter.dataType" class="w-full text-xs"/>
                            <button v-if="dataItem.filter.column != ''" @click="addFilter(index)" class="bg-blue-800 rounded-none px-2 text-white">+</button>
                        </div>
                    </div>
                    <span @click="clearFilter" class="cursor-pointer text-xs text-blue-500 max-h-1">Clear Filters</span>
                </div>
                <!--footer-->
                <div class="border-t flex justify-center px-1 py-1">
                    <form method="POST" :action="this.reportData.previewUrl" target="_blank" enctype="multipart/form-data" id='previewForm'>
                        <input type="hidden" name="_token" :value="$page.props.csrf_token"/>
                        <input type="hidden" name="reportData" :value="JSON.stringify(this.reportData)"/>
                        <x-button  :type="'submit'" class="bg-green-500" form="previewForm">Preview</x-button>
                    </form>
                </div>
            </div>
        </GDialog>
    </div>
</template>
<script>
    import ReportMixin from '@/System/Mixins/ReportMixin.js'
    export default{
        props:{maxWidth:{default:500},reportData:{default:[]}},
        mixins:[ReportMixin],
        data() {
            return{
                isOpen: false,
            }
        },
        mounted(){
            this.setData();
        },
        methods: {
            setData(){
                for(var i = 0; i < this.reportData.dataItems.length; i++){
                    this.reportData.dataItems[i].filter = {
                        column:'',
                        operator:'=',
                        dataType:'',
                        element:'',
                        value:'',
                    };
                    this.reportData.dataItems[i].filters = [];
                }
                this.isOpen = true
            },
            onColumnChange(index1){
                let index = this.reportData.dataItems[index1].columns.findIndex((item) => item.name === this.reportData.dataItems[index1].filter.column);
                if(index != -1){
                    this.reportData.dataItems[index1].filter.column = this.reportData.dataItems[index1].columns[index]['name'];
                    this.reportData.dataItems[index1].filter.dataType = this.reportData.dataItems[index1].columns[index]['dataType'];
                    this.reportData.dataItems[index1].filter.element = this.reportData.dataItems[index1].columns[index]['element'];
                }
            },
            addFilter(index1){
                var newFilter = {};
                newFilter.column = this.reportData.dataItems[index1].filter.column;
                newFilter.operator = this.reportData.dataItems[index1].filter.operator;
                newFilter.dataType = this.reportData.dataItems[index1].filter.dataType;
                newFilter.element = this.reportData.dataItems[index1].filter.element;
                newFilter.value = this.reportData.dataItems[index1].filter.value;
                this.reportData.dataItems[index1].filters.push(newFilter);
                this.reportData.dataItems[index1].filter.column = '';
                this.reportData.dataItems[index1].filter.operator = '=';
                this.reportData.dataItems[index1].filter.dataType = '';
                this.reportData.dataItems[index1].filter.element = '';
                this.reportData.dataItems[index1].filter.value = '';
            },
            removeFilter(index1,index2){
                this.reportData.dataItems[index1].filters.splice(index2,1);
            },
            clearFilter(){
                for(var i = 0; i < this.reportData.dataItems.length; i++){
                    this.reportData.dataItems[i].filters = [];
                }
            },
            onClose(){
                this.isOpen = false;
                this.$parent.filterPage = '';
            }
        }
    }
</script>
