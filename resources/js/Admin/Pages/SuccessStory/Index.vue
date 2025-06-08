<template>
    <div>
        <Head title="Success Stories" />
        <x-index-template :setup="setup" :listData="listData" :selected="selected">
            <Table :setup="setup" :listData="listData">
                <template #thead>
                    <tr>
                        <ThCheckbox :selected-items="selected" @toggle-select-all="handleToggleSelectAll" />
                        <Th>Title</Th>
                        <Th>Student Name</Th>
                        <Th>Course</Th>
                        <Th>Graduation Year</Th>
                        <Th>Achievement</Th>
                        <Th>Status</Th>
                        <Th>Date Created</Th>
                    </tr>
                </template>
                <template #tbody>
                    <Tr v-for="(record, index) in listData.data" :key="index" :row="record.uuid+'#'+index" 
                        :isSelected="isSelected(record.uuid+'#'+index)" 
                        :url="setup.settings.indexRoute+'/show/'+record.uuid">
                        
                        <!-- Checkbox for selection -->
                        <TdCheckbox :item="record.uuid+'#'+listData.data.indexOf(record)" @onCheck="onCheck"/>
                        
                        <!-- Title -->
                        <Td>{{ record.title }}</Td>

                        <!-- Student Name -->
                        <Td>{{ record.student_name }}</Td>

                        <!-- Course -->
                        <Td>{{ record.course }}</Td>

                        <!-- Graduation Year -->
                        <Td>{{ record.graduation_year }}</Td>

                        <!-- Achievement -->
                        <Td>{{ record.achievement ? (record.achievement.length > 50 ? record.achievement.substring(0, 50) + '...' : record.achievement) : '' }}</Td>

                        <!-- Status -->
                        <Td>
                            <x-badge :status="record.status" :statuses="setup.statuses" />
                        </Td>

                        <!-- Date Created -->
                        <Td>{{ record.created_at }}</Td>
                    </Tr>
                </template>
            </Table>
        </x-index-template>
    </div>
</template>

<script setup>
import { provide, getCurrentInstance } from 'vue';
import { indexProps, useIndex } from '@/Composables/useIndex';
import { Head } from '@inertiajs/vue3';

const context = getCurrentInstance()?.appContext.config.globalProperties;

// Define props
const props = defineProps({ ...indexProps });

// Use the index composable
const {
    xIndexTemplate,
    Table,
    Th,
    ThCheckbox,
    Td,
    TdCheckbox,
    Tr,
    xBadge,
    handleToggleSelectAll,
    isSelected,
    onCheck
} = useIndex(props);
</script> 