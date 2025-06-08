<template>
    <div>
        <Head title="Staff" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Name</Th>
                        <Th>Position</Th>
                        <Th>Expertise</Th>
                        <Th>Email</Th>
                        <Th>Phone No.</Th>
                        <Th>Sequence</Th>
                        <Th>Created At</Th>
                        <Th>Status</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record,index) in listData.data" v-bind:key="index" :row="record.uuid+'#'+index" :isSelected="isSelected(record.uuid+'#'+index)" :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        <!-- <x-tabletd-checkbox v-if="isMultipleSelect" :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <!-- <TdCheckbox  :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <Td>{{record.name}} </Td> 
                        <Td>{{record.title}} </Td> 
                        <Td>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="(expertise, idx) in record.expertise?.slice(0, 2)" 
                                      :key="idx"
                                      class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ expertise }}
                                </span>
                                <span v-if="record.expertise?.length > 2" class="text-gray-500 text-xs">
                                    +{{ record.expertise.length - 2 }} more
                                </span>
                            </div>
                        </Td>
                        <Td>{{record.email}} </Td> 
                        <Td>{{record.phone_no}} </Td> 
                        <Td>{{record.sequence}} </Td> 
                        <Td>{{record.created_at}} </Td>
                        <Td>
                            <Badge 
                                :class="setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].color">
                                {{ setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].caption }}
                            </Badge>
                        </Td>

                    </Tr>
                </template>
            </Table>
        </x-index-template>
        <!-- <button @click="showLoading" style="color:#fff">click to test show loading</button> -->
    </div>
</template>
<script setup>
    // import IndexMixin from '@/System/Mixins/CRUD/IndexMixin.js'
    import { provide, getCurrentInstance } from 'vue'
    import { indexProps, useIndex } from '@/Composables/useIndex'
    import Badge from '@/Components/Mosaic/Badge.vue'

    const context = getCurrentInstance()?.appContext.config.globalProperties;
    
    // const props = defineProps(indexProps)
    const props = defineProps({...indexProps})

    const { 
        selected,
        onCheck,
        onRowClick,
        isSelected,
        xTable,
        xTabletd,
        xTabletdCheckbox,
        xTablethCheckbox,
        xTableth,
        xTabletr,
        xBadge,
        xIndexTemplate, 
        isMultipleSelect, 
        destroy,

        ThCheckbox, 
        TdCheckbox,
        Td, 
        Tr, 
        selectedItems, 
        selectedItemsMap,
        handleToggleSelectAll,
        Th,
        Table
    } = useIndex(props)
   
    const showLoading = () => {
        context.$showLoading()
        setTimeout(() => {
            context.$hideLoading()
        }, 2000)
    }
    
    provide('onRowClick', onRowClick);
    provide('isMultipleSelect', isMultipleSelect);
    
</script>
