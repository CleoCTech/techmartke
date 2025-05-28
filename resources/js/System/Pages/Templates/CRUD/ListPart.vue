<template>
    <div>
        <x-menu-bar v-bind="{setup,selected}" @showCreateEditPanel="showCreateEditPanel"></x-menu-bar>
        <!-- <div class="flex flex-col sm:flex-row gap-1 sm:gap-0 mt-1 sm:mt-16 bg-gray-800"> -->
        <div class="px-4 sm:px-6 lg:px-8 py-4 w-full max-w-9xl mx-auto">
            <x-filter v-if="setup.settings.isSearch" :columns="setup.columns" ref='filter'/>
            <!-- Page header -->
            <div class="sm:flex sm:justify-between sm:items-center mb-8">

                <!-- Left: Title -->
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold">{{listPageTitle}}</h1>
                </div>

                <!-- Right: Actions  -->
                <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Delete button -->
                <DeleteButton :selectedItems="selected" @destroy="destroy" />
                <!-- Dropdown -->
                <DateSelect />
                <!-- Filter button -->
                <FilterButton align="right" />
                <!-- Add order button -->
                <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden xs:block ml-2">Add Order</span>
                </button>              
            </div>

            </div>
            <Table :setup="setup" :listData="listData" :listPageTitle="listPageTitle">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <!-- <x-tableth-checkbox v-if="isMultipleSelect"/> -->
                        <Th>Name</Th>
                        <Th>Occupied</Th>
                        <Th>Status</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record,index) in listData.data" v-bind:key="index" :row="record.uuid+'#'+index" :isSelected="isSelected(record.uuid+'#'+index)" :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <Td>{{record.unit_name}} </Td> 
                        <Td>{{record.is_occupied}} </Td>
                        <Td>
                            <Badge :class="setup.statuses[setup.statuses.findIndex(x => x.id ==record.status)].color">{{setup.statuses[setup.statuses.findIndex(x => x.id ==record.status)].caption}}</Badge>
                        </Td>
                    </Tr>
                </template>
            </Table>

            <CreateEditPanel
                v-if="createEditPanelOpen"
                :createEditPanelOpen="createEditPanelOpen"
                :setup="setup"
                :selected="selected"
                :cardData="transactionData?.cardData"
                :relationships="''"
                :transactionData="transactionData"
                @close-transactionpanel="createEditPanelOpen = false"
            /> 

            <form method="POST" action="/admin/data-export" ref="exportData" target="_blank">
                <input type="hidden" name="_token" :value="$page.props.csrf_token"/>
                <input type="hidden" name="data" :value="JSON.stringify(listData.data !== undefined? listData.data:listData)"/>
                <input type="hidden" name="fileName" :value="setup.settings.caption+'.csv'"/>
                <input type="hidden" name="model" :value="setup.settings.model"/>
            </form>

            <!-- Pagination -->
          <div v-if="listData.data !== undefined && listData.data.length > 0" class="mt-8">
            <PaginationClassic :links="listData.links" :data="listData.data" />
          </div>  
        </div>
        
        <span  v-if="listData.data !== undefined && listData.data.length == 0" class="flex justify-center text-sm p-2"><em>** No records found **</em></span>
    </div>
</template>
<script setup>
    import { ref, onMounted, provide, defineEmits } from 'vue';

    import xFilter from '@/Components/Filter.vue'
    import xMenuBar from '@/Components/ListPart/MenuBar.vue'
    import DeleteButton from '@/Admin/Partials/Actions/DeleteButton.vue'
    import DateSelect from '@/Components/Mosaic/DateSelect.vue'
    import FilterButton from '@/Components/Mosaic/DropdownFilter.vue'
    import PaginationClassic from '@/Components/Mosaic/PaginationClassic.vue'
    import CreateEditPanel from '@/Components/ListPart/CreateEditPanel.vue'

    import { listPartProps, useListPart } from '@/Composables/useListPart'


    const props = defineProps({...listPartProps})

    const createEditPanelOpen = ref(false);

    const transactionData = ref(null);

    const closeTransactionPanel = () => {
        console.log('Closing transaction panel');
        createEditPanelOpen.value = false;
    };

    onMounted(() => {
        console.log('ListPart');
        createEditPanelOpen.value = false;
        console.log(props.listData);
    })

    const emits = defineEmits(['PanelData']); 

    const showCreateEditPanel = async (value) => {
        
        try {
            // Perform an asynchronous API call here
            // console.log(props.setup);
            // createEditPanelOpen.value = value;
            // return ;
            const responseData = await axios.get(props.setup.settings.ListPart.units.indexRoute+ '/'+selected._rawValue[0]);
            // Once the API call is complete, set createEditPanelOpen.value
           
            // console.log(responseData);
            transactionData.value = responseData;
            if ( transactionData.value) {
                createEditPanelOpen.value = value;
            }

            
            // console.log( transactionData.value);
            // Optionally, you can emit an event with the fetched data
            emits('PanelData', { value, responseData });
        } catch (error) {
            // Handle any errors that occurred during the API call
            console.error('API error:', error);
        }
    }

    const { 
        selected,
        onCheck,
        onRowClick,
        isSelected,
        destroy,
        isMultipleSelect,

        ThCheckbox, 
        TdCheckbox,
        Td, 
        Tr, 
        selectedItems, 
        selectedItemsMap,
        handleToggleSelectAll,
        Th,
        Table
    } = useListPart(props)

    
    provide('onRowClick', onRowClick);
    provide('isMultipleSelect', isMultipleSelect);
   
</script>
