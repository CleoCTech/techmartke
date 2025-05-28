import { usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue';

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
import ThCheckbox from '@/Components/Mosaic/Table/ListPart/ThCheckbox.vue'; 
import TdCheckbox from '@/Components/Mosaic/Table/ListPart/TdCheckbox.vue'; 
import Th from '@/Components/Mosaic/Table/ListPart/Th.vue'; 
import Td from '@/Components/Mosaic/Table/ListPart/Td.vue'; 
import Tr from '@/Components/Mosaic/Table/ListPart/Tr.vue'; 
import Table from '@/Components/Mosaic/Table/ListPart/Table.vue'

export const listPartProps = {
    setup:Object,
    listData:Object,
    listPageTitle:String,
    selected: {
        type: Array,
        default: null,
    },
}

const selected = ref([]);
const isMultipleSelect = ref(false);

const selectedItems = ref([]);
const selectedItemsMap  = ref([]);

export const useListPart = (props) => {
    
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

    function destroy(data){
        if(selected.value.length > 0){
            if(confirm("Do you really want to delete this record(s)?")){
                console.log('Deleting selected...');
            }
        } else{
            console.log('length : 0');
        }
    }
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

        ThCheckbox,
        TdCheckbox,
        selectedItemsMap,
        selectedItems,
        handleToggleSelectAll,
        Th,
        Td,
        Tr,
        Table
    }
}