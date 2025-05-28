<template>
    <div>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="onApproval(3)" :isDisabled="this.selected.length == 1? false:true"  :iconName="'check'">Approve</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" @click="onApproval(4)" :isDisabled="this.selected.length == 1? false:true"  :iconName="'times'">Reject</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" @click="getApprovalDocuments()" :isDisabled="false"  :iconName="'bars'">Document Types</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" href="/system/approval/status/2" :isDisabled="false"  :iconName="'list-ol'">Pending Approval</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" href="/system/approval/status/3" :isDisabled="false"  :iconName="'check-circle'">Approved Documents</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" href="/system/approval/status/4" :isDisabled="false"  :iconName="'times'">Rejected Documents</x-menu-bar-item>
        </div>
        <!--confirm Approval-->
        <GDialog v-model="isOpen" :max-width="'500'" :height="'h-full'">
           <div class="h-full shadow-lg">
           <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">Approval</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="onClose"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min h-full">
                    <p>{{form.status == 3? 'Approval comments':'Why are you rejecting this document?'}}</p>
                    <x-textarea v-model="form.comments"  class="text-sm"></x-textarea>
                </div>
                 <!--fotter-->
                <div class="text-center flex justify-center pb-2">
                    <x-button @click="storeApproval" class="text-sm">Submit</x-button>
                </div>
           </div>
        </GDialog>
        <!--Approval Documents-->
        <GDialog v-model="isModal2" :max-width="'500'" :height="'h-full'">
           <div class="h-full shadow-lg">
           <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">Approval Documents</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="isModal2 = false"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min h-full">
                    <table class="w-full">
                        <thead class="bg-gray-100 text-gray-600 text-sm font-semibold">
                            <th class="text-left">Document</th>
                            <th class="text-left">Workflow</th>
                        </thead>
                        <tbody class="text-xs sm:text-sm border">
                            <tr v-for="(type,index) in documentTypes" :key="index" class="border-b px-2">
                                <td>{{type.caption}}</td>
                                <td>
                                    <x-select v-model="type.record.workflow"  class="text-sm w-full">
                                        <option value="">--select--</option>
                                        <option v-for="(workflow,index) in workflows" :key="index" :value="workflow.uuid">{{workflow.description}}</option>
                                    </x-select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                 <!--fotter-->
                <div class="text-center flex justify-center pb-2">
                    <span v-if="isUpdatingDocTypes" class="italic flex justify-center w-full">
                        <x-loading/>
                    </span>
                    <x-button v-if="!isUpdatingDocTypes" @click="updateApprovalDocuments" class="text-sm">Update</x-button>
                </div>
           </div>
        </GDialog>
    </div>
</template>
<script>
    import "gitart-vue-dialog/dist/style.css";
    import { GDialog} from "gitart-vue-dialog";
    import xButton from '@/Components/Button.vue'
    import xTextarea from '@/Components/Textarea.vue'
    import xSelect from '@/Components/Select.vue'
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    import { usePage } from '@inertiajs/vue3'
   import { router } from '@inertiajs/vue3'
    import VueLoadingMixin from '@/System/Mixins/VueLoadingMixin.js'
    export default{
        components:{xButton,GDialog,xTextarea,xSelect},
        mixins:[MenuBarTabsMixin,VueLoadingMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                form:{
                    uuid:null,
                    status:null,
                },
                isOpen:false,
                isModal2:false,
                isLoading:false,
                isUpdatingDocTypes:false,
                documentTypes:[],
                workflows:[],
                form2:{
                    documentTypes:[]
                },
           }
        },
        methods:{
            onApproval(status){
                this.form.status = status;
                this.isOpen = true;
            },
            storeApproval(){
                this.form.uuid = this.selected[0].split('#')[0];
                this.isOpen = false;
                this.loadingShowFullPage();
                axios.post('/system/approval/document/approval',this.form).then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.loadingHideFullPage();
                    Inertia.visit('/system/approval/status/2');
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.isOpen = true;
                    this.loadingHideFullPage();
                })
            },
            getApprovalDocuments(){
                this.loadingShowFullPage();
                axios.get('/system/approval/document/type').then(response => {
                    this.documentTypes = response.data.types;
                    this.workflows = response.data.workflows;
                    this.form2.documentTypes = this.documentTypes;
                    this.loadingHideFullPage();
                    this.isModal2 = true;
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.loadingHideFullPage();
                })
            },
             updateApprovalDocuments(){
                this.isUpdatingDocTypes = true;
                axios.post('/system/approval/document/type/update',this.form2).then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.isUpdatingDocTypes = false;
                    this.isModal2 = true;
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.isUpdatingDocTypes = false;
                })
            },
        }
    }
</script>
