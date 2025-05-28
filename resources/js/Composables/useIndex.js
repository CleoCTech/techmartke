import { usePage, router } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted, getCurrentInstance} from 'vue';

//components
import xTable from '@/Components/Table/Table.vue'
import xTabletd from '@/Components/Table/Td.vue'
import xTabletdCheckbox from '@/Components/Table/TdCheckbox.vue'
import xTableth from '@/Components/Table/Th.vue'
import xTablethCheckbox from '@/Components/Table/ThCheckbox.vue'
import xTabletr from '@/Components/Table/Tr.vue'
import xBadge from '@/Components/Badge.vue'
import xIndexTemplate from '@/System/Pages/Templates/CRUD/Index.vue'

//Mosaic Components
import ThCheckbox from '@/Components/Mosaic/Table/ThCheckbox.vue'; 
import TdCheckbox from '@/Components/Mosaic/Table/TdCheckbox.vue'; 
import Th from '@/Components/Mosaic/Table/Th.vue'; 
import Td from '@/Components/Mosaic/Table/Td.vue'; 
import Tr from '@/Components/Mosaic/Table/Tr.vue'; 
import Table from '@/Components/Mosaic/Table/Table.vue'
import {useNotify} from "@/Composables/useNotify";

let {notification} = useNotify();
export const indexProps = {
    setup:Object,
    listData:Object,
}

const selected = ref([]);
const isMultipleSelect = ref(false);

const selectedItems = ref([]);
const selectedItemsMap  = ref([]);

export const useIndex = (props) => {
    
    const context = getCurrentInstance()?.appContext.config.globalProperties;

    function onCheck(item){
        if(selected.value.indexOf(item) === -1){
            selected.value.push(item);
        }else{
            selected.value.splice(selected.value.indexOf(item),1);
        }
    }
    function handleToggleSelectAll(value) {
        if (value) {
            // Select all items
            selectAll.value = !selectAll.value;
            emits('toggle-select-all', selectAll.value);
        } else {
            // Deselect all items
            selectedItems.value = [];
        }
    }
    function onRowClick(item){
        selected.value = [];
        selected.value.push(item);
    }
    function isSelected(item){
        // return selected.value.indexOf(item) === -1
        if(selected.value.indexOf(item) === -1){
            return false;
        }else{
            return true;
        }
    }
    function truncateDescription(text, maxLength) {
        if (text.length > maxLength) {
            return text.substring(0, maxLength) + '...';
        }
        return text;
    }

    function destroy(data){
        context.$showLoading();
        if(selected.value.length > 0){
            if(confirm("Do you really want to delete this record(s)?")){
                console.log('Deleting selected...'); 
                selected.value.forEach(element => {
                    let uuid = removeHash(element);

                    axios.delete(`${props.setup.settings.indexRoute}/delete/${uuid}`).then(response => {
                        if(response.status == 200){
                            notification(response.data.message, response.data.type);
                            router.visit(props.setup.settings.indexRoute);
                        }
                        else{
                            notification(usePage().props.defaultErrors.default, 'error');
                        }
                        // isLoading = false;
                        context.$hideLoading()
                    }).catch((error) => {
                        console.log(error);
                        if(error.response.status == 422){
                            var errors= [];
                            errors = error.response.data.errors;
                            for (let field of Object.keys(errors)) {
                                notification(errors[field][0], 'error');
                            }
                        }else{
                            notification(usePage().props.defaultErrors.default, 'error');
                        }            
                        // isLoading = false;
                        context.$hideLoading()
                    })
                });
                
            }
        } else{
            console.log('length : 0');
        }
    }

    function resetSelected() {
        selected.value = [];
    }

    const removeHash = (str) => {
        return str.split('#')[0];
    };
    onMounted(() => {
        // console.log(props.listData);
        
        resetSelected();
    });

    onUnmounted(() => {
        resetSelected();
    });
    watch(isMultipleSelect, (newV, oldV) => {
        if(newV == false){
            selected.value = [];
        }
    });

    return {
        onCheck, 
        onRowClick,
        isSelected,
        xTable,
        xTabletd,
        xTabletdCheckbox,
        xTableth,
        xTabletr,
        xBadge,
        xIndexTemplate,
        selected,
        isMultipleSelect,
        destroy,
        resetSelected,

        ThCheckbox,
        TdCheckbox,
        selectedItemsMap,
        selectedItems,
        handleToggleSelectAll,
        truncateDescription,
        Th,
        Td,
        Tr,
        Table
    }
}