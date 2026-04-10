<script setup>
import { ref, onMounted, watch, onUnmounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import SidebarLinkGroup from '@/Admin/Partials/SidebarLinkGroup.vue'
import SidebarLink from '@/Components/SidebarLink.vue'

const props = defineProps({
  sidebarOpen: Boolean,
})

const page = usePage();
const user = computed(() => page.props.auth.user);

const isAdmin = computed(() => {
  if (!user.value) return false;
  // Check user_category (100 = super admin, 3 = admin) as primary check
  if (user.value.user_category >= 3) return true;
  // Also check roles array
  const roles = Array.isArray(user.value.roles) ? user.value.roles : [];
  return roles.includes('administrator') || roles.includes('superadmin');
});

const hasCMSAccess = computed(() => {
  if (isAdmin.value) return true;
  const permissions = Array.isArray(user.value?.permissions) ? user.value.permissions : [];
  return permissions.some(p => ['managegallery', 'manageevents'].includes(p));
});

const trigger = ref(null)
const sidebar = ref(null)
const storedSidebarExpanded = localStorage.getItem('sidebar-expanded')
const sidebarExpanded = ref(storedSidebarExpanded === null ? true : storedSidebarExpanded === 'true')

const handleOutsideClick = (event) => {
  if (props.sidebarOpen && window.innerWidth < 1024) {
    if (sidebar.value && !sidebar.value.contains(event.target)) {
      emit('close-sidebar')
    }
  }
}

const keyHandler = ({ keyCode }) => {
  if (!props.sidebarOpen || keyCode !== 27) return
  emit('close-sidebar')
}

onMounted(() => {
  document.addEventListener('mousedown', handleOutsideClick)
  document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
  document.removeEventListener('mousedown', handleOutsideClick)
  document.removeEventListener('keydown', keyHandler)
})

watch(sidebarExpanded, () => {
  localStorage.setItem('sidebar-expanded', sidebarExpanded.value)
  if (sidebarExpanded.value) {
    document.querySelector('body').classList.add('sidebar-expanded')
  } else {
    document.querySelector('body').classList.remove('sidebar-expanded')
  }
})

const emit = defineEmits()
const closeSidebar = () => {
  emit('close-sidebar')
};

// Helper to check if current URL matches
const isActive = (path) => page.url.includes(path);
</script>

<template>
  <div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true">
    </div>

    <!-- Sidebar -->
    <div id="sidebar" ref="sidebar"
      class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'">

      <!-- Sidebar header -->
      <div class="flex justify-between items-center mb-8 pr-3 sm:px-2">
        <button class="lg:hidden text-slate-500 hover:text-slate-400" @click="closeSidebar">
          <span class="sr-only">Close sidebar</span>
          <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
          </svg>
        </button>
        <Link class="block" :href="route('admin.dashboard')">
          <img src="/assets/images/logo.png" alt="TechMart KE" class="h-10 w-auto" />
        </Link>
      </div>

      <!-- Links -->
      <div class="space-y-6">

        <!-- ===== DASHBOARD ===== -->
        <div>
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3 mb-2">Dashboard</h3>
          <ul>
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('dashboard') && 'bg-slate-900'">
              <Link :href="route('admin.dashboard')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1" :class="isActive('dashboard') ? 'text-ablue' : 'text-slate-400'" />
                    <rect x="14" y="3" width="7" height="7" rx="1" :class="isActive('dashboard') ? 'text-ablue' : 'text-slate-400'" />
                    <rect x="3" y="14" width="7" height="7" rx="1" :class="isActive('dashboard') ? 'text-ablue' : 'text-slate-400'" />
                    <rect x="14" y="14" width="7" height="7" rx="1" :class="isActive('dashboard') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Overview</span>
                </div>
              </Link>
            </li>
          </ul>
        </div>

        <!-- ===== E-COMMERCE ===== -->
        <div v-if="isAdmin">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3 mb-2">E-Commerce</h3>
          <ul>
            <!-- Products -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('product')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="isActive('product') && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" :class="isActive('product') ? 'text-ablue' : 'text-slate-400'" />
                      <line x1="7" y1="7" x2="7.01" y2="7" :class="isActive('product') ? 'text-ablue' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">Products</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('admin.products')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url === '/admin/products' ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      All Products
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.products.create')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url.includes('products/create') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Add Product
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.bulk-upload')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url.includes('bulk-upload') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Bulk Upload
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>

            <!-- Orders -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('order') && 'bg-slate-900'">
              <Link :href="route('admin.orders')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" :class="isActive('order') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="3" y1="6" x2="21" y2="6" :class="isActive('order') ? 'text-ablue' : 'text-slate-400'" />
                    <path d="M16 10a4 4 0 01-8 0" :class="isActive('order') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Orders</span>
                </div>
              </Link>
            </li>

            <!-- Trade-In -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('trade-in') && 'bg-slate-900'">
              <Link :href="route('admin.trade-in')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 1l4 4-4 4" :class="isActive('trade-in') ? 'text-green-400' : 'text-slate-400'" />
                    <path d="M3 11V9a4 4 0 014-4h14" :class="isActive('trade-in') ? 'text-green-400' : 'text-slate-400'" />
                    <path d="M7 23l-4-4 4-4" :class="isActive('trade-in') ? 'text-green-400' : 'text-slate-400'" />
                    <path d="M21 13v2a4 4 0 01-4 4H3" :class="isActive('trade-in') ? 'text-green-400' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Trade-In</span>
                </div>
              </Link>
            </li>

            <!-- Customer Reviews -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('reviews') && 'bg-slate-900'">
              <Link href="/admin/reviews" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" :class="isActive('reviews') ? 'text-yellow-400' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Reviews</span>
                </div>
              </Link>
            </li>

            <!-- VIP Subscribers -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('vip')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="isActive('vip') && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" :class="isActive('vip') ? 'text-yellow-400' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">VIP Program</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('admin.vip')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url === '/admin/vip' ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Subscribers
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.vip.campaigns')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('vip/campaigns') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Campaigns
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.vip.notifications')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('vip/notifications') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Notifications
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>
          </ul>
        </div>

        <!-- ===== CONTENT MANAGEMENT ===== -->
        <div v-if="hasCMSAccess">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3 mb-2">Content</h3>
          <ul>
            <!-- News -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('news') && 'bg-slate-900'">
              <Link :href="route('admin.news')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2" :class="isActive('news') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="10" y1="6" x2="18" y2="6" :class="isActive('news') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="10" y1="10" x2="18" y2="10" :class="isActive('news') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="10" y1="14" x2="14" y2="14" :class="isActive('news') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">News / Blog</span>
                </div>
              </Link>
            </li>

            <!-- Events -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('event') && 'bg-slate-900'">
              <Link :href="route('admin.event')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" :class="isActive('event') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="16" y1="2" x2="16" y2="6" :class="isActive('event') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="8" y1="2" x2="8" y2="6" :class="isActive('event') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="3" y1="10" x2="21" y2="10" :class="isActive('event') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Events</span>
                </div>
              </Link>
            </li>

            <!-- FAQs -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('faq') && 'bg-slate-900'">
              <Link :href="route('admin.faq')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" :class="isActive('faq') ? 'text-ablue' : 'text-slate-400'" />
                    <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3" :class="isActive('faq') ? 'text-ablue' : 'text-slate-400'" />
                    <line x1="12" y1="17" x2="12.01" y2="17" :class="isActive('faq') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">FAQs</span>
                </div>
              </Link>
            </li>

            <!-- Testimonials -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('testimony') && 'bg-slate-900'">
              <Link :href="route('admin.testimony')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" :class="isActive('testimony') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Testimonials</span>
                </div>
              </Link>
            </li>

            <!-- Community -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('community') && 'bg-slate-900'">
              <Link :href="route('admin.community')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" :class="isActive('community') ? 'text-ablue' : 'text-slate-400'" />
                    <circle cx="9" cy="7" r="4" :class="isActive('community') ? 'text-ablue' : 'text-slate-400'" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" :class="isActive('community') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Community</span>
                </div>
              </Link>
            </li>

            <!-- Gallery -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('gallery') || isActive('video-gallery') || isActive('album-collections')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="(isActive('gallery') || isActive('album')) && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2" :class="isActive('gallery') || isActive('album') ? 'text-ablue' : 'text-slate-400'" />
                      <circle cx="8.5" cy="8.5" r="1.5" :class="isActive('gallery') || isActive('album') ? 'text-ablue' : 'text-slate-400'" />
                      <polyline points="21 15 16 10 5 21" :class="isActive('gallery') || isActive('album') ? 'text-ablue' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">Media</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('admin.gallery')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url === '/admin/gallery' ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Photo Gallery
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.video-gallery')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('video-gallery') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Video Gallery
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.album-collections')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('album-collections') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Albums
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>
          </ul>
        </div>

        <!-- ===== CRM / COMMUNICATION ===== -->
        <div v-if="isAdmin">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3 mb-2">CRM</h3>
          <ul>
            <!-- Messages / Inquiries -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('inquiry') && 'bg-slate-900'">
              <Link :href="route('admin.inquiry')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" :class="isActive('inquiry') ? 'text-ablue' : 'text-slate-400'" />
                    <polyline points="22,6 12,13 2,6" :class="isActive('inquiry') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Messages</span>
                </div>
              </Link>
            </li>

            <!-- Feedback -->
            <li class="px-3 py-2 rounded-lg mb-0.5" :class="isActive('feedback') && 'bg-slate-900'">
              <Link :href="route('admin.feedback')" class="block text-slate-200 hover:text-white truncate transition duration-150" @click="closeSidebar">
                <div class="flex items-center">
                  <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3H14z" :class="isActive('feedback') ? 'text-ablue' : 'text-slate-400'" />
                    <path d="M7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3" :class="isActive('feedback') ? 'text-ablue' : 'text-slate-400'" />
                  </svg>
                  <span class="text-sm font-medium ml-3">Feedback</span>
                </div>
              </Link>
            </li>
          </ul>
        </div>

        <!-- ===== SETUP & SETTINGS ===== -->
        <div v-if="isAdmin">
          <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3 mb-2">Settings</h3>
          <ul>
            <!-- Company Setup -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('company-information') || isActive('social-media') || isActive('staff')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="(isActive('company-information') || isActive('social-media') || isActive('staff')) && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" :class="isActive('company') || isActive('social') || isActive('staff') ? 'text-ablue' : 'text-slate-400'" />
                      <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" :class="isActive('company') || isActive('social') || isActive('staff') ? 'text-ablue' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">Store Setup</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('system.company')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('company-information') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Company Info
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('system.social')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('social-media') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Social Media
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('admin.staff')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('/admin/staff') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Staff
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>

            <!-- User Management -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('/system/user') || isActive('/system/roles') || isActive('/system/permissions')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="(isActive('/system/user') || isActive('/system/roles') || isActive('/system/permissions')) && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" :class="isActive('/system/user') || isActive('/system/roles') ? 'text-ablue' : 'text-slate-400'" />
                      <circle cx="9" cy="7" r="4" :class="isActive('/system/user') || isActive('/system/roles') ? 'text-ablue' : 'text-slate-400'" />
                      <path d="M23 21v-2a4 4 0 00-3-3.87" :class="isActive('/system/user') || isActive('/system/roles') ? 'text-ablue' : 'text-slate-400'" />
                      <path d="M16 3.13a4 4 0 010 7.75" :class="isActive('/system/user') || isActive('/system/roles') ? 'text-ablue' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">Users & Roles</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('system.user')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="$page.url === '/system/user' ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Users
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('system.roles')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('/system/roles') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Roles
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('system.permissions')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('/system/permissions') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Permissions
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>

            <!-- Account -->
            <SidebarLinkGroup v-slot="parentLink" :activeCondition="isActive('settings') || isActive('profile')">
              <a class="block text-slate-200 hover:text-white truncate transition duration-150 px-3 py-2 rounded-lg"
                :class="(isActive('settings') || isActive('profile')) && 'bg-slate-900'" href="#0"
                @click.prevent="parentLink.handleClick(); sidebarExpanded = true">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" :class="isActive('settings') || isActive('profile') ? 'text-ablue' : 'text-slate-400'" />
                      <circle cx="12" cy="7" r="4" :class="isActive('settings') || isActive('profile') ? 'text-ablue' : 'text-slate-400'" />
                    </svg>
                    <span class="text-sm font-medium ml-3">Account</span>
                  </div>
                  <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="parentLink.expanded && 'rotate-180'" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                  </svg>
                </div>
              </a>
              <div class="lg:sidebar-expanded:block 2xl:block">
                <ul class="pl-11 mt-1" :class="!parentLink.expanded && 'hidden'">
                  <li class="mb-1">
                    <Link :href="route('profile.show')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('profile') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Profile
                    </Link>
                  </li>
                  <li class="mb-1">
                    <Link :href="route('password.edit')" class="block text-sm py-1 transition duration-150 truncate"
                      :class="isActive('change-password') ? 'text-ablue' : 'text-slate-400 hover:text-slate-200'" @click="closeSidebar">
                      Change Password
                    </Link>
                  </li>
                </ul>
              </div>
            </SidebarLinkGroup>
          </ul>
        </div>

      </div>

      <!-- Expand / collapse button -->
      <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
        <div class="px-3 py-2">
          <button @click="sidebarExpanded = !sidebarExpanded">
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
