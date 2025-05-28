<template>
    <div>
        <Head title="Event" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr class="">
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Title</Th>
                        <Th>Type</Th>
                        <Th>Description</Th>
                        <!-- <Th>Start Date</Th>
                        <Th>End Date</Th> -->
                        <Th>Location</Th>
                        <Th>Created At</Th>
                        <!-- <Th>Status</Th> -->
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record,index) in listData.data" v-bind:key="index" :row="record.uuid+'#'+index" :isSelected="isSelected(record.uuid+'#'+index)" :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        <!-- <x-tabletd-checkbox v-if="isMultipleSelect" :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <!-- <TdCheckbox  :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/> -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <Td>{{record.title}}</Td> 
                        <Td>{{ getEventTypeCaption(record.event_type) }}</Td>
                        <Td  v-html="trimHtml(record.description)"></Td> 
                        <Td>{{record.location}}</Td> 
                        <Td>{{record.created_at}} </Td>
                        <!-- <Td>
                            <Badge 
                                :class="setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].color">
                                {{setup.statuses[setup.statuses.findIndex(x => x.id === record.status)].caption }}
                            </Badge>
                        </Td> -->

                    </Tr>
                </template>
            </Table>
        </x-index-template>
        <!-- <button @click="showLoading" style="color:#fff">click to test show loading</button> -->
    </div>
</template>
<script setup>
    // import IndexMixin from '@/System/Mixins/CRUD/IndexMixin.js'
    import { provide, getCurrentInstance, ref,onMounted  } from 'vue'
    import { indexProps, useIndex } from '@/Composables/useIndex'
    import Badge from '@/Components/Mosaic/Badge.vue'

    const context = getCurrentInstance()?.appContext.config.globalProperties;
    
    function trimHtml(html, maxLength = 20) {
        const div = document.createElement('div');
        div.innerHTML = html;
        let text = div.textContent || div.innerText || "";

        if (text.length > maxLength) {
            return text.substring(0, maxLength) + '...';
        } else {
            return html; // return original HTML if under limit
        }
    }

    // const props = defineProps(indexProps)
    const props = defineProps({...indexProps})
    const eventTypes = ref(props.setup.event_types);

    const getEventTypeCaption = (eventTypeId) => {
        const eventType = eventTypes.value.find(type => type.id === eventTypeId);
        console.log('eventTypeId: ' +eventTypeId);
        console.log('eventType: ' +eventType);
        return eventType ? eventType.caption : 'Unknown';
    };
    
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
    onMounted(() => {
        console.log('mounted')
        console.log(eventTypes.value);
        
        
    })
    
    provide('onRowClick', onRowClick);
    provide('isMultipleSelect', isMultipleSelect);
    
</script>
