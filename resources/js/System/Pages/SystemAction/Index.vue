<template>
    <div>
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <x-table>
                <template #thead>
                    <x-tableth-checkbox v-if="this.isMultipleSelect"/>
                    <x-tableth>Module</x-tableth>
                    <x-tableth>Action</x-tableth>
                </template>
                <template #tbody>
                    <x-tabletr v-for="(record,index) in listData.data" v-bind:key="index" :row="record.uuid+'#'+index" :isSelected="isSelected(record.uuid+'#'+index)" :url="''">
                        <x-tabletd-checkbox v-if="this.isMultipleSelect" :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        <x-tabletd>{{record.module}}</x-tabletd>
                        <x-tabletd>{{record.action}}</x-tabletd>
                    </x-tabletr>
                </template>
            </x-table>
        </x-index-template>
    </div>
</template>
<script>
    import IndexMixin from '@/System/Mixins/CRUD/IndexMixin.js'
    export default{
        mixins:[IndexMixin],
    }
</script>
