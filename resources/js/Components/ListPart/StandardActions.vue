<template>
    <div v-if="setup.settings !== undefined" class="flex flex-col sm:flex-row gap-2 mb-1">
        
        <div class="hidden sm:flex gap-2 max-h-6">
          
            <x-menu-bar-item :type="'link'"
                :href="this.setup.settings.listUrl === undefined ? this.setup.settings.ListPart.units.indexRoute : this.setup.settings.listUrl"
                :isDisabled="this.setup.pageType == 'list' ? true : false" iconName="bars">List</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" v-if="this.setup.settings.ListPart.units.isCreate"
                :href="this.setup.settings.ListPart.units.indexRoute + '/create'"
                :isDisabled="this.setup.pageType == 'create' ? true : false" iconName="plus">New</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" v-if="this.setup.settings.ListPart.units.isView"
                :href="this.selected.length == 1 ? this.setup.settings.viewUrl === undefined ? this.setup.settings.indexRoute + '/show/' + this.selected[0].split('#')[0] : this.setup.settings.viewUrl + '/' + this.selected[0].split('#')[0] : '#'"
                :isDisabled="this.setup.pageType != 'view' && this.selected.length == 1 ? false : true"
                iconName="eye">View</x-menu-bar-item>
            <x-menu-bar-item :type="'link'" v-if="this.setup.settings.ListPart.units.isEdit"
                :href="this.selected.length == 1 ? this.setup.settings.ListPart.units.indexRoute + '/edit/' + this.selected[0].split('#')[0] : '#'"
                :isDisabled="this.selected.length == 1 ? false : true" iconName="pen">Edit</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" v-if="this.setup.settings.ListPart.units.isDelete" @click="destroy"
                :isDisabled="this.selected.length > 0 ? false : true" iconName="times">Delete</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" v-if="this.setup.settings.ListPart.units.isDelete" @click="leftPanel"
                :isDisabled="this.selected.length > 0 ? false : true" iconName="times">Panel</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" @click="onAttachments"
                :isDisabled="this.setup.pageType != 'list' && this.setup.settings.isAttachments && this.selected.length == 1 ? false : true"
                iconName="paperclip">Attachments</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" v-if="this.setup.pageType == 'list'" @click="onMultipleSelect"
                iconName="check-circle">Multiselect</x-menu-bar-item>
            <!-- <x-input v-if="this.setup.pageType == 'list' && this.setup.settings.isSearch" v-model="searchKeyword" :modelValue="searchKeyword" v-on:keyup.enter="search" v-on:blur="search" placeholder="search" class="max-w-10 pyx-1 pxx-1 text-xs" style="max-width:150px;padding:2px"/> -->
            <x-menu-bar-item :type="'button'" v-if="this.setup.pageType == 'list' && this.setup.settings.isSearch"
                @click="onFilter" iconName="filter">Filter</x-menu-bar-item>
            <x-menu-bar-item :type="'button'" v-if="this.setup.pageType == 'list' && this.setup.settings.isExport"
                @click="onExport" iconName="file-export">Export</x-menu-bar-item>
        </div>

    </div>
</template>

<script>
import xButton from '@/Components/Button.vue'
import xLink from '@/Components/Link.vue'
import xMenuBarItem from '@/Components/MenuBarItem.vue'
import xInput from '@/Components/Input.vue'
import { router } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification";
import { usePage, Link } from '@inertiajs/vue3'

export default {
    components: { xButton, xLink, xInput, router, notify, xMenuBarItem },
    props: {
        setup: { default: Object },
        selected: { default: [] },
    },
    data() {
        return {
            searchKeyword: '',
            xData: {},
            isMultipleSelect: false,
            isFilter: false,
            isAttachments: false,
            showCreateEditPanel: false,
            showMobileMenu: true,
        }
    },
    mounted() {
        this.getSearch();
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        toggleMobileMenu() {
            this.showMobileMenu = !this.showMobileMenu;
        },
        search() {
            // ... (existing search method)
        },
        destroy() {
            // ... (existing destroy method)
        },
        getSearch() {
            // ... (existing getSearch method)
        },
        onMultipleSelect() {
            this.isMultipleSelect = !this.isMultipleSelect;
            this.$emit('onMultipleSelect', this.isMultipleSelect);
            this.showMobileMenu = false;
        },
        onAttachments() {
            this.isAttachments = !this.isAttachments;
            this.$emit('onAttachments', this.isAttachments);
            this.showMobileMenu = false;
        },
        leftPanel() {
            this.showCreateEditPanel = !this.showCreateEditPanel;
            this.$emit('showCreateEditPanel', this.showCreateEditPanel);
            this.showMobileMenu = false;
        },
        onFilter() {
            this.isFilter = !this.isFilter;
            this.$emit('onFilter', this.isFilter);
            this.showMobileMenu = false;
        },
        onExport() {
            this.$emit('onExport');
            this.showMobileMenu = false;
        },
        handleClickOutside(event) {
            const mobileMenu = this.$el.querySelector('.sm\\:hidden');
            if (mobileMenu && !mobileMenu.contains(event.target)) {
                this.showMobileMenu = false;
            }
        },
    }
}
</script>

<style scoped>
.mobile-menu-enter-active,
.mobile-menu-leave-active {
    transition: opacity 0.2s, transform 0.2s;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
