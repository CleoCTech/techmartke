<template>
    <div>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="downloadTemplate" :isDisabled="this.selected.length == 1? false:true"  :iconName="'bars'" target="_blank">Download Template</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" @click="this.isOpen = true" :isDisabled="this.selected.length == 1? false:true"  :iconName="'bars'">Import From Template</x-menu-bar-item>
        </div>
        <GDialog v-model="isOpen" :max-width="'500'" persistent>
            <div class="flex flex-col">
                <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">Import Data</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="onClose"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min">
                    <p class="text-red-500 text-xs pb-2 ">Please note it must be a .csv file and must follow the template format shared</p>
                    <form method="POST" @submit.prevent="importData" target="_blank" enctype="multipart/form-data" id='previewForm'>
                        <input type="hidden" name="_token" :value="$page.props.csrf_token"/>
                        <input type="file" @input="onFileChange($event)" accept=".csv" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40" required/>
                        <div class=" py-2 text-center">
                            <x-button  :type="'submit'" class="bg-green-500" form="previewForm">Import</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </GDialog>
    </div>
</template>
<script>
    import "gitart-vue-dialog/dist/style.css";
    import { GDialog} from "gitart-vue-dialog";
    import xButton from '@/Components/Button.vue'
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    import CrudMethodsMixin from '@/System/Mixins/CrudMethodsMixin.js'
    import VueLoadingMixin from '@/System/Mixins/VueLoadingMixin.js'
    export default{
        components:{GDialog,xButton},
        mixins:[MenuBarTabsMixin,CrudMethodsMixin,VueLoadingMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                isOpen:false,
                form:{
                    dataFile:null,
                    tableIndex:null,
                },
                templateData : [],
            }
        },
        methods:{
            importData(){
                this.form.tableIndex = this.selected[0].split('#')[0];
                this.fnPost('/system/data-import/store',this.form,this.parentIndex);
                this.onClose();
            },
            downloadTemplate(){
                this.form.tableIndex = this.selected[0].split('#')[0];
                window.open('/system/data-import/template/'+this.form.tableIndex, '_blank').focus();
            },
            onClose(){
                this.isOpen = false;
            },
            onFileChange(event) {
                let files = event.target.files;
                if (files.length)
                    this.form.dataFile = files[0];
            }
        }
    }
</script>
