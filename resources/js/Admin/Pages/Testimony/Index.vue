<template>
    <div>
        <Head title="Testimonies" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Testifier Name</Th>
                        <Th>Testimony</Th>
                        <Th>Service</Th>
                        <Th>Remarks</Th>
                        <Th>Date Created</Th>
                        <Th>Status</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record,index) in listData.data" v-bind:key="index" :row="record.uuid+'#'+index" :isSelected="isSelected(record.uuid+'#'+index)" :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        <!-- <x-tabletd-checkbox v-if="isMultipleSelect" :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <!-- <TdCheckbox  :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <Td>{{record.testifier_name}} </Td> 
                        <Td v-html="truncateText(record.testimony)"></Td>
                        <Td>{{record.service?.title}} </Td> 
                        <Td v-html="truncateText(record.remarks)"></Td>
                        <Td>{{record.created_at}} </Td>
                        <Td>
                            <Badge 
                                v-if="setup.statuses && Array.isArray(setup.statuses)"
                                :class="getStatusColor(record.status)">
                                {{ getStatusCaption(record.status) }}
                            </Badge>
                            <Badge v-else class="bg-gray-400">
                                Unknown
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
    
    const getStatusColor = (statusId) => {
        if (!props.setup?.statuses || !Array.isArray(props.setup.statuses)) {
            return 'bg-gray-400'
        }
        const status = props.setup.statuses.find(x => x.id === statusId)
        return status?.color || 'bg-gray-400'
    }
    
    const getStatusCaption = (statusId) => {
        if (!props.setup?.statuses || !Array.isArray(props.setup.statuses)) {
            return 'Unknown'
        }
        const status = props.setup.statuses.find(x => x.id === statusId)
        return status?.caption || 'Unknown'
    }

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
    const truncateText = (text, length = 30) => {
        if (!text || typeof text !== 'string') {
            return '';
        }
        if (text.length > length) {
            return text.substring(0, length) + '...';
        }
        return text;
    };
    
    provide('onRowClick', onRowClick);
    provide('isMultipleSelect', isMultipleSelect);
    
</script>
