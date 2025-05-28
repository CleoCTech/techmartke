<template>
    <div>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="onWorkflowSteps" :isDisabled="this.selected.length == 1? false:true"  :iconName="'bars'" target="_blank">Workflow Steps</x-menu-bar-item>
        </div>
        <GDialog v-model="isOpen" :max-width="'500'" :height="'h-full'">
           <div class="h-full shadow-lg">
           <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">Workflow Steps</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="onClose"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min h-full">
                    <div class="flex gap-1 flex-col sm:flex-row items-center">
                        <x-select v-model="form.step"  class="text-sm">
                            <option value="">--step--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </x-select>
                        <x-select v-model="form.approver"  class="text-sm">
                            <option value="">--approver--</option>
                            <option v-for="(approver,index) in approvers" :key="index" :value="approver.uuid">{{approver.email}}</option>
                        </x-select>
                        <button v-if="form.step != '' && form.approver != '' && !isUpdating" @click="onAddStep()" class="bg-blue-800 rounded-none px-2 text-white">+</button>
                    </div>
                    <h3 class="py-2 text-xs underline">Steps</h3>
                    <table v-if="workflowSteps.length > 0 && !isUpdating" class="w-full">
                        <thead class="bg-gray-100 text-gray-600 text-sm font-semibold">
                            <th class="text-left">Step</th>
                            <th class="text-left">Approver</th>
                            <th class="text-left"></th>
                        </thead>
                        <tbody class="text-xs sm:text-sm border">
                            <tr v-for="(step,index) in workflowSteps" :key="index" class="border-b">
                                <td>{{step.step}}</td>
                                <td>{{step.approver}}</td>
                                <td class="flex items-center"><span  class="flex items-center cursor-pointer text-red-600 text-xs" @click="onDeleteStep(step.uuid)"><unicon name="times" fill="red" /> Remove</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <span v-if="workflowSteps.length == 0" class="italic text-xs">
                        *** No step found ***
                    </span>
                    <span v-if="isUpdating" class="italic flex justify-center w-full">
                        <x-loading/>
                    </span>
                </div>
           </div>
        </GDialog>
    </div>
</template>
<script>
    import "gitart-vue-dialog/dist/style.css";
    import { GDialog} from "gitart-vue-dialog";
    import xButton from '@/Components/Button.vue'
    import xSelect from '@/Components/Select.vue'
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    import VueLoadingMixin from '@/System/Mixins/VueLoadingMixin.js'
    import { usePage } from '@inertiajs/vue3'
    export default{
        components:{GDialog,xButton,xSelect},
        mixins:[MenuBarTabsMixin,VueLoadingMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                isOpen:false,
                form:{
                    uuid:'',
                    step:'',
                    approver:'',
                    workflow:null,
                },
                workflowSteps:[],
                approvers:[],
                isUpdating:false,
            }
        },
        methods:{
            onWorkflowSteps(){
                this.form.workflow = this.selected[0].split('#')[0];
                this.loadingShowFullPage();
                 //= true;
                axios.get('/system/approval/workflow/steps/'+this.form.workflow).then(response => {
                    this.workflowSteps = response.data.workflowSteps;
                    this.approvers = response.data.approvers;
                    this.loadingHideFullPage();
                    this.isUpdating = false;
                    this.isOpen = true;
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.isLoading = false;
                })
            },
            onAddStep(){
                this.isUpdating = true;
                this.form.uuid = this.selected[0].split('#')[0];
                axios.post('/system/approval/workflow/add-step',this.form).then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.form.step = '';
                    this.form.approver = '';
                    this.onWorkflowSteps();
                }).catch((error) => {
                    if(error.response.data.message !== undefined){
                        this.$notify({
                            text:error.response.data.message,
                            type:error.response.data.type
                        });
                    }else{
                        this.$notify({
                            text:usePage().props.value.defaultErrors.default,
                            type:'error'
                        });
                    }
                    this.onWorkflowSteps();
                })
            },
            onDeleteStep(step){
                this.isUpdating = true;
                axios.delete('/system/approval/workflow/delete-step/'+step).then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.onWorkflowSteps();
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                })
            },
            onClose(){
                this.isOpen = false;
            },
            removeStep(action){
                this.form.action = action;
                this.form.type = 'delete';
                this.onUpdateStep();
            }

        }
    }
</script>
