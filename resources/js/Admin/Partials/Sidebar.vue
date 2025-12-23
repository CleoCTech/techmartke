<script setup>
import { ref, onMounted, watch, onUnmounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import SidebarLinkGroup from '@/Admin/Partials/SidebarLinkGroup.vue'
import SidebarLink from '@/Components/SidebarLink.vue'
// Props
const props = defineProps({
  sidebarOpen: Boolean,
})

const page = usePage();
const user = computed(() => page.props.auth.user);
const hasChurchManagementAccess = computed(() => {
  const permissions = [

  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});
const hasCMSAccess = computed(() => {
  const permissions = [
    'managegallery',
    'manageevents'
  ];

  return (
    user.value.roles.includes('administrator') ||
    user.value.roles.includes('superadmin') ||
    user.value.permissions.some(permission => permissions.includes(permission))
  );
});

// Refs
// const sidebarOpen = ref(true)
const trigger = ref(null)
const sidebar = ref(null)

const storedSidebarExpanded = localStorage.getItem('sidebar-expanded')
const sidebarExpanded = ref(storedSidebarExpanded === null ? false : storedSidebarExpanded === 'true')

// Methods
const clickHandler = ({ target }) => {
  if (!sidebar.value || !trigger.value) return
  if (
    !props.sidebarOpen ||
    sidebar.value.contains(target) ||
    trigger.value.contains(target)
  ) return
  emit('close-sidebar')
}

const keyHandler = ({ keyCode }) => {
  if (!props.sidebarOpen || keyCode !== 27) return
  emit('close-sidebar')
}

// Click handler for outside clicks
const handleOutsideClick = (event) => {
  // Check if sidebar is open and we're on mobile view
  if (props.sidebarOpen && window.innerWidth < 1024) { // 1024px is the lg breakpoint
    // Check if click is outside sidebar
    if (sidebar.value && !sidebar.value.contains(event.target)) {
      emit('close-sidebar')
    }
  }
}

// Lifecycle hooks
onMounted(() => {
  // console.log(page.props.auth.user)
  // Add event listeners
  document.addEventListener('mousedown', handleOutsideClick)
  document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
  // Clean up event listeners
  document.removeEventListener('mousedown', handleOutsideClick)
  document.removeEventListener('keydown', keyHandler)
})

// Watch sidebarExpanded
watch(sidebarExpanded, () => {
  localStorage.setItem('sidebar-expanded', sidebarExpanded.value)
  if (sidebarExpanded.value) {
    document.querySelector('body').classList.add('sidebar-expanded')
  } else {
    document.querySelector('body').classList.remove('sidebar-expanded')
  }
})

// Emit event
const emit = defineEmits()
const closeSidebar = () => {
  // props.sidebarOpen = false;
  // console.log(props.sidebarOpen)
  emit('close-sidebar')
};

</script>
<template>
  <div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true">
    </div>

    <!-- Sidebar -->
    <div id="sidebar" ref="sidebar"
      class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64  lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'">

      <!-- Sidebar header -->
      <div class="flex justify-between mb-10 pr-3 sm:px-2">
        <!-- Close button -->
        <button class="lg:hidden text-slate-500 hover:text-slate-400" @click="closeSidebar" 
          aria-controls="sidebar"
          :aria-expanded="sidebarOpen">
          <span class="sr-only">Close sidebar</span>
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
          </svg>
        </button>
        <!-- Logo -->
        <Link class="block" :href="route('admin.dashboard')">
          <img src="/assets/images/WENLA.png" alt="logo" class="h-10 w-10" />
        </Link>
      </div>

      <!-- Links -->
      <div class="space-y-8">
        <!-- Pages group -->
        <div>
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
              aria-hidden="true">•••</span>
            <span class="md:block lg:sidebar-expanded:block 2xl:block">Home</span>
          </h3>
          <ul class="mt-3">
            <!-- Dashboard -->
            <SidebarLinkGroup v-slot="parentLink"
              :activeCondition="$page.url === '/' || $page.url.includes('dashboard')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="($page.url === '/' || $page.url.includes('dashboard')) ? 'hover:text-slate-200' : 'hover:text-white'"
                href="#0" @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('dashboard')) ? 'text-indigo-500' : 'text-slate-400'"
                        d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z" />
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('dashboard')) ? 'text-indigo-600' : 'text-slate-600'"
                        d="M12 3c-4.963 0-9 4.037-9 9s4.037 9 9 9 9-4.037 9-9-4.037-9-9-9z" />
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('dashboard')) ? 'text-indigo-200' : 'text-slate-400'"
                        d="M12 15c-1.654 0-3-1.346-3-3 0-.462.113-.894.3-1.285L6 6l4.714 3.301A2.973 2.973 0 0112 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z" />
                    </svg>
                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                  </div>
                  <!-- Icon -->
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:bock lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <template
                    v-if="$page.props.auth.user.roles.includes('administrator') || $page.props.auth.user.roles.includes('superadmin')">
                    <SidebarLink :href="route('admin.dashboard')" :active="$page.url == '/admin/dashboard'">
                      <li class="mb-1 last:mb-0" :href="route('admin.dashboard')"
                        :active="$page.url == '/admin/dashboard'">
                        <a class="block transition duration-150 truncate"
                          :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Analytics</span>
                        </a>
                      </li>
                    </SidebarLink>
                  </template>
                  <template v-else-if="$page.props.auth.user.roles.includes('regular')">
                    <SidebarLink :href="route('guest.dashboard')" :active="$page.url == '/guest-dashboard'">
                      <li class="mb-1 last:mb-0">
                        <a class="block transition duration-150 truncate"
                          :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                            Analytics (Guest)
                          </span>
                        </a>
                      </li>
                    </SidebarLink>
                  </template>

                </ul>
              </div>
            </SidebarLinkGroup>




          </ul>
        </div>

        <!-- Setup  -->
        <div
          v-if="$page.props.auth.user.roles.includes('administrator') || $page.props.auth.user.roles.includes('superadmin')">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="md:block lg:sidebar-expanded:block 2xl:block">Setup</span>
          </h3>
          <ul class="mt-3">
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url.includes('system/company-information') ||
              $page.url.includes('system/social-media') ||
              $page.url.includes('admin/fiscal') ||
              $page.url.includes('admin/fiscal-period') ||
              $page.url.includes('/admin/staff')" :autoExpand="false">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150"
                :class="parentLink.expanded && 'hover:text-slate-200'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current text-slate-600"
                        d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
                    </svg>
                    <span class="text-sm font-medium ml-3 md:opacity-100 duration-200">Setup</span>
                  </div>
                  <div class="flex shrink-0 ml-2">
                    <svg
                      class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-200 ease-in-out"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block">
                <ul class="pl-9 mt-1 space-y-2" :class="!parentLink.expanded && 'hidden'">
                  <SidebarLink :href="route('system.company')" :active="$page.url == '/system/company-information'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="$page.url == '/system/company-information' ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                        :href="href" @click="navigate">
                        <span class="text-sm font-medium md:opacity-100 duration-200">Company
                          Information</span>
                      </a>
                    </li>
                  </SidebarLink>


                  <SidebarLink :href="route('system.social')" :active="$page.url == '/system/social-media'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="$page.url == '/system/social-media' ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                        :href="href" @click="navigate">
                        <span class="text-sm font-medium md:opacity-100 duration-200">Social
                          Media</span>
                      </a>
                    </li>
                  </SidebarLink>

                  <!-- New items -->


                  <SidebarLink :href="route('admin.staff')" :active="$page.url.includes('/admin/staff')">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="$page.url.includes('/admin/staff') ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                        :href="href" @click="navigate">
                        <span class="text-sm font-medium md:opacity-100 duration-200">Staff</span>
                      </a>
                    </li>
                  </SidebarLink>
                </ul>
              </div>
            </SidebarLinkGroup>
          </ul>
        </div>

        <div v-if="hasChurchManagementAccess">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="md:block lg:sidebar-expanded:block 2xl:block">School Management</span>
          </h3>
          <ul class="mt-3">
            <!-- Testimonies -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url.includes('testimony')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="$page.url.includes('testimony') ? 'hover:text-slate-200' : 'hover:text-white'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="$page.url.includes('testimony') ? 'text-indigo-500' : 'text-slate-600'"
                        d="M19.714 14.7l-7.007 7.007-1.414-1.414 7.007-7.007c-.195-.4-.298-.84-.3-1.286a3 3 0 113 3 2.969 2.969 0 01-1.286-.3z" />
                      <path class="fill-current"
                        :class="$page.url.includes('testimony') ? 'text-indigo-300' : 'text-slate-400'"
                        d="M10.714 18.3c.4-.195.84-.298 1.286-.3a3 3 0 11-3 3c.002-.446.105-.885.3-1.286l-6.007-6.007 1.414-1.414 6.007 6.007z" />
                    </svg>
                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Testimonies</span>
                  </div>
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <SidebarLink :href="route('admin.testimony')" :active="$page.url == '/admin/testimony'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'" :href="href"
                        @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">View
                          Testimonies</span>
                      </a>
                    </li>
                  </SidebarLink>
                  <SidebarLink :href="route('admin.testimony')" :active="$page.url == '/admin/testimonies/new'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'" :href="href"
                        @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Add
                          Testimony</span>
                      </a>
                    </li>
                  </SidebarLink>
                </ul>
              </div>
            </SidebarLinkGroup>


            <!-- Fee Management -->
          </ul>
        </div>




        <div v-if="hasCMSAccess">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <!-- <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span> -->
            <span class="md:block lg:sidebar-expanded:block 2xl:block">Website CMS</span>
          </h3>
          <ul class="mt-3">

            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url === '/' || $page.url.includes('event')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="($page.url === '/' || $page.url.includes('event')) ? 'hover:text-slate-200' : 'hover:text-white'"
                href="#0" @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('event')) ? 'text-indigo-500' : 'text-slate-600'"
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z" />
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('event')) ? 'text-indigo-300' : 'text-slate-400'"
                        d="M12 13h5v5h-5z" />
                    </svg>

                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Events</span>
                  </div>
                  <!-- Icon -->
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <template v-if="hasCMSAccess">
                    <SidebarLink :href="route('admin.event')" :active="$page.url == '/admin/event'">
                      <li class="mb-1 last:mb-0" :href="route('admin.event')" :active="$page.url == '/admin/event'">
                        <a class="block transition duration-150 truncate"
                          :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Events</span>
                        </a>
                      </li>
                    </SidebarLink>
                  </template>

                </ul>
              </div>
            </SidebarLinkGroup>

            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url === '/' || $page.url.includes('gallery')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="($page.url === '/' || $page.url.includes('gallery')) ? 'hover:text-slate-200' : 'hover:text-white'"
                href="#0" @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('gallery')) ? 'text-indigo-500' : 'text-slate-600'"
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z" />
                      <path class="fill-current"
                        :class="($page.url === '/' || $page.url.includes('gallery')) ? 'text-indigo-300' : 'text-slate-400'"
                        d="M8.5 13.5l2.5 3 3.5-4.5 4.5 6H5z" />
                    </svg>

                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Gallery</span>
                  </div>
                  <!-- Icon -->
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <template v-if="hasCMSAccess">
                    <SidebarLink :href="route('admin.gallery')" :active="$page.url == '/admin/gallery'">
                      <li class="mb-1 last:mb-0" :href="route('admin.gallery')" :active="$page.url == '/admin/gallery'">
                        <a class="block transition duration-150 truncate"
                          :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Gallery</span>
                        </a>
                      </li>
                    </SidebarLink>
                    <SidebarLink :href="route('admin.video-gallery')" :active="$page.url == '/admin/video-gallery'">
                      <li class="mb-1 last:mb-0" :href="route('admin.video-gallery')"
                        :active="$page.url == '/admin/video-gallery'">
                        <a class="block transition duration-150 truncate"
                          :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Video
                            Gallery</span>
                        </a>
                      </li>
                    </SidebarLink>
                    <SidebarLink :href="route('admin.album-collections')" :active="$page.url.includes('/admin/album-collections')">
                      <li class="mb-1 last:mb-0">
                        <a class="block transition duration-150 truncate"
                          :class="$page.url.includes('/admin/album-collections') ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                          :href="href" @click="navigate">
                          <span
                            class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Album Collections</span>
                        </a>
                      </li>
                    </SidebarLink>
                  </template>

                </ul>
              </div>
            </SidebarLinkGroup>

          </ul>
        </div>



        <div v-if="$page.props.auth.user.roles.includes('administrator')">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <!-- <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span> -->
            <span class="md:block lg:sidebar-expanded:block 2xl:block">Utility</span>
          </h3>
          <ul class="mt-3">
            <!-- Messages -->
            <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" :class="isExactActive && 'bg-slate-900'">
              <SidebarLinkGroup v-slot="parentLink"
                :activeCondition="$page.url === '/' || $page.url.includes('inquiry')">
                <a class="block text-slate-200 truncate transition duration-150"
                  :class="($page.url === '/' || $page.url.includes('inquiry')) ? 'hover:text-slate-200' : 'hover:text-white'"
                  href="#0" @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center">
                      <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                        <path class="fill-current text-slate-600"
                          d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
                        <path class="fill-current text-slate-400" d="M6 12h12v2H6v-2zm0-3h12v2H6V9zm0-3h12v2H6V6z" />
                      </svg>

                      <span
                        class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Messages</span>
                      <div class="flex flex-shrink-0 ml-2">
                        <span
                          class="inline-flex items-center justify-center h-5 text-xs font-medium text-white bg-indigo-500 px-2 rounded">1</span>
                      </div>
                    </div>

                    <div class="flex shrink-0 ml-2">
                      <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                        :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                      </svg>
                    </div>
                  </div>
                </a>
                <div class="md:block lg:sidebar-expanded:block 2xl:block">
                  <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                    <template v-if="$page.props.auth.user.roles.includes('administrator')">
                      <SidebarLink :href="route('admin.inquiry')" :active="$page.url == '/admin/inquiry'">
                        <li class="mb-1 last:mb-0" :href="route('admin.inquiry')"
                          :active="$page.url == '/admin/inquiry'">
                          <a class="block transition duration-150 truncate"
                            :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                            :href="href" @click="navigate">
                            <span
                              class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Messages</span>

                          </a>
                        </li>
                      </SidebarLink>
                    </template>

                  </ul>
                </div>
              </SidebarLinkGroup>

            </li>

          </ul>
        </div>

        <div
          v-if="$page.props.auth.user.roles.includes('administrator') || $page.props.auth.user.roles.includes('superadmin')">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="md:block lg:sidebar-expanded:block 2xl:block">SYSTEM SETTINGS</span>
          </h3>
          <ul class="mt-3">

            <!-- User  -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url.includes('user')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="$page.url.includes('user') ? 'hover:text-slate-200' : 'hover:text-white'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="$page.url.includes('user') ? 'text-indigo-500' : 'text-slate-600'"
                        d="M18.974 8H22a2 2 0 012 2v6h-2v5a1 1 0 01-1 1h-2a1 1 0 01-1-1v-5h-2v-6a2 2 0 012-2h.974zM20 7a2 2 0 11-.001-3.999A2 2 0 0120 7zM2.974 8H6a2 2 0 012 2v6H6v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5H0v-6a2 2 0 012-2h.974zM4 7a2 2 0 11-.001-3.999A2 2 0 014 7z" />
                      <path class="fill-current"
                        :class="$page.url.includes('user') ? 'text-indigo-300' : 'text-slate-400'"
                        d="M12 6a3 3 0 110-6 3 3 0 010 6zm2 18h-4a1 1 0 01-1-1v-6H6v-6a3 3 0 013-3h6a3 3 0 013 3v6h-3v6a1 1 0 01-1 1z" />
                    </svg>

                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">User
                      Management </span>
                  </div>
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <SidebarLink :href="route('system.user')" :active="$page.url == '/system/user'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'" :href="href"
                        @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Users</span>
                      </a>
                    </li>
                  </SidebarLink>
                  <SidebarLink :href="route('system.roles')" :active="$page.url == '/system/roles'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="$page.url.startsWith('/system/roles') ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                        :href="href" @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Roles</span>
                      </a>
                    </li>
                  </SidebarLink>
                  <SidebarLink :href="route('system.permissions')" :active="$page.url == '/system/permissions'">
                    <li class="mb-1 last:mb-0">
                      <a class="block transition duration-150 truncate"
                        :class="$page.url.startsWith('/system/permissions') ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'"
                        :href="href" @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Permissions</span>
                      </a>
                    </li>
                  </SidebarLink>

                </ul>
              </div>
            </SidebarLinkGroup>

            <!-- Settings -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="$page.url.includes('settings')">
              <a class="block text-slate-200 truncate transition duration-150"
                :class="$page.url.includes('settings') ? 'hover:text-slate-200' : 'hover:text-white'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                      <path class="fill-current"
                        :class="$page.url.includes('settings') ? 'text-indigo-500' : 'text-slate-600'"
                        d="M19.714 14.7l-7.007 7.007-1.414-1.414 7.007-7.007c-.195-.4-.298-.84-.3-1.286a3 3 0 113 3 2.969 2.969 0 01-1.286-.3z" />
                      <path class="fill-current"
                        :class="$page.url.includes('settings') ? 'text-indigo-300' : 'text-slate-400'"
                        d="M10.714 18.3c.4-.195.84-.298 1.286-.3a3 3 0 11-3 3c.002-.446.105-.885.3-1.286l-6.007-6.007 1.414-1.414 6.007 6.007z" />
                      <path class="fill-current"
                        :class="$page.url.includes('settings') ? 'text-indigo-500' : 'text-slate-600'"
                        d="M5.7 10.714c.195.4.298.84.3 1.286a3 3 0 11-3-3c.446.002.885.105 1.286.3l7.007-7.007 1.414 1.414L5.7 10.714z" />
                      <path class="fill-current"
                        :class="$page.url.includes('settings') ? 'text-indigo-300' : 'text-slate-400'"
                        d="M19.707 9.292a3.012 3.012 0 00-1.415 1.415L13.286 5.7c-.4.195-.84.298-1.286.3a3 3 0 113-3 2.969 2.969 0 01-.3 1.286l5.007 5.006z" />
                    </svg>
                    <span
                      class="text-sm font-medium ml-3 lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Settings</span>
                  </div>
                  <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                      :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                      <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                    </svg>
                  </div>
                </div>
              </a>
              <div class="md:block lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-9 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <SidebarLink href="#">
                    <li class="mb-1 last:mb-0">
                      <Link class="block transition duration-150 truncate"
                        :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'" :href="route('profile.show')"
                        @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Account
                          Settings</span>
                      </Link>
                    </li>
                  </SidebarLink>
                  <SidebarLink href="#">
                    <li class="mb-1 last:mb-0">
                      <Link class="block transition duration-150 truncate"
                        :class="isExactActive ? 'text-indigo-500' : 'text-slate-400 hover:text-slate-200'" :href="route('password.edit')"
                        @click="navigate">
                        <span
                          class="text-sm font-medium lg:opacity-100 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Change Password</span>
                      </Link>
                    </li>
                  </SidebarLink>

                </ul>
              </div>
            </SidebarLinkGroup>
          </ul>
        </div>

        <div>

        </div>

        <!-- More group -->
        <div>
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
            <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
              aria-hidden="true">•••</span>
            <span class="md:block lg:sidebar-expanded:block 2xl:block">More</span>
          </h3>
          <ul class="mt-3">

          </ul>
        </div>
      </div>

      <!-- Expand / collapse button -->
      <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
        <div class="px-3 py-2">
          <button @click="toggleSidebarExpanded">
            <span class="sr-only">Expand / collapse sidebar</span>
            <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
              <path class="text-slate-400" d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
              <path class="text-slate-600" d="M3 23H1V1h2z" />
            </svg>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>
