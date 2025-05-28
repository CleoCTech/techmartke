<template>
    <div>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="rolePermissions" :isDisabled="this.selected.length == 1? false:true"  :iconName="'bars'" target="_blank">Permissions</x-menu-bar-item>
        </div>
        <GDialog v-model="isOpen" :max-width="'500'" :height="'h-full'">
           <div class="h-full shadow-lg">
           <!--title-->
                <div class="border-b px-1 py-1 flex justify-between items-center">
                    <span></span>
                    <h3 class="text-1xl text-center font-semibold">User Role Permissions</h3>
                    <unicon name="times" fill="red" class="icon-xs cursor-pointer" @click="onClose"/>
                </div>
                <!--body-->
                <div class="px-2 py-2 min-w-min h-full">
                    <div class="flex gap-1 flex-col sm:flex-row items-center">
                        <x-select v-model="form.module" @change="onModuleChange()"  class="text-sm">
                            <option value="">--Module--</option>
                            <option v-for="(modul,index) in modules" :key="index" :value="modul.uuid">{{modul.module}}</option>
                        </x-select>
                        <x-select v-if="isModuleActions" v-model="form.action"  class="text-sm">
                            <option value="">--Action--</option>
                            <option value="all">All</option>
                            <option v-for="(action,index) in moduleActions" :key="index" :value="action.uuid">{{action.action}}</option>
                        </x-select>
                        <x-loading v-if="!isModuleActions && form.module != null" class=" h-4"/>
                        <button v-if="form.action != null && !isUpdating" @click="updatePermission()" class="bg-blue-800 rounded-none px-2 text-white">+</button>
                    </div>
                    <h3 class="py-2 text-xs underline">Assigned Permissions</h3>
                    <table v-if="permissions.length > 0 && !isUpdating" class="w-full">
                        <thead class="bg-gray-100 text-gray-600 text-sm font-semibold">
                            <th class="text-left">Module</th>
                            <th class="text-left">Action</th>
                            <th class="text-left"></th>
                        </thead>
                        <tbody class="text-xs sm:text-sm border">
                            <tr v-for="(permission,index) in permissions" :key="index" class="border-b">
                                <td>{{permission.module}}</td>
                                <td>{{permission.action}}</td>
                                <td class="flex items-center"><span  class="flex items-center cursor-pointer text-red-600 text-xs" @click="revokePermission(permission.uuid)"><unicon name="times" fill="red" /> Revoke</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <span v-if="permissions.length == 0" class="italic text-xs">
                        *** No permissions assigned ***
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
    import { usePage } from '@inertiajs/vue3'
    import VueLoadingMixin from '@/System/Mixins/VueLoadingMixin.js'
    export default{
        components:{GDialog,xButton,xSelect},
        mixins:[MenuBarTabsMixin,VueLoadingMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                isOpen:false,
                form:{
                    uuid:null,
                    module:null,
                    action:null,
                    role:null,
                    type:'create',
                },
                modules:[],
                moduleActions:[],
                permissions:[],
                isModuleActions:false,
                isUpdating:false,
            }
        },
        methods:{
            rolePermissions(){
                var roleId = this.selected[0].split('#')[0];
                this.loadingShowFullPage();
                axios.get('/system/user-role/permissions/'+roleId).then(response => {
                    this.modules = response.data.modules;
                    this.permissions = response.data.permissions;
                    this.loadingHideFullPage();
                    this.isUpdating = false;
                    this.isOpen = true;
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.loadingHideFullPage();
                })
            },
            onModuleChange(){
                this.isModuleActions = false;
                this.loadingShowFullPage();
                axios.get('/system/system-action/module-actions/'+this.form.module).then(response => {
                    this.moduleActions = response.data;
                    this.loadingHideFullPage();
                    this.isModuleActions = true;
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.loadingHideFullPage();
                })
            },
            updatePermission(){
                this.isUpdating = true;
                this.form.role = this.selected[0].split('#')[0];
                axios.post('/system/user-role/permission/store',this.form).then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.form.module = null;
                    this.form.action = null;
                    this.form.uuid = null;
                    this.isModuleActions = false;
                    this.rolePermissions();
                }).catch((error) => {
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                    this.isLoading = false;
                })
            },
            onClose(){
                this.isOpen = false;
            },
            onFileChange(event) {
                let files = event.target.files;
                if (files.length)
                    this.form.dataFile = files[0];
            },
            revokePermission(action){
                this.form.action = action;
                this.form.type = 'delete';
                this.updatePermission();
            }

        }
    }
</script>
