<template>
	
	<div v-if="isLoading">
	  <Loader />
	</div>
	<nav class="bg-blue-900 text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <Link href="/" class="flex items-center space-x-2">
            <!-- <GraduationCap class="h-8 w-8" /> -->
            <img src="/assets/images/WENLA.png" alt="logo" class="h-8 w-8" />
            <span class="font-bold text-xl">{{ $page.props.config.appName }}</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-4">
          <Link
            v-for="item in navigationItems"
            :key="item.href"
            :href="item.href"
            :class="[
              'px-3 py-2 rounded-md text-sm font-medium transition-colors',
              $page.url === item.href || ($page.url.startsWith(item.href) && item.href !== '/')
                ? 'bg-blue-800 text-white shadow-md'
                : 'hover:bg-blue-800'
            ]"
          >
            {{ item.label }}
          </Link>
          
          <!-- More Dropdown -->
          <div class="relative group" @mouseenter="openMoreDropdown" @mouseleave="scheduleCloseMoreDropdown">
            <button
              @click="toggleMoreDropdown"
              class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 transition-colors"
            >
              More
              <ChevronDown class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': isMoreDropdownOpen }" />
            </button>
            
            <!-- Dropdown Menu -->
            <div
              v-show="isMoreDropdownOpen"
              @mouseenter="cancelCloseMoreDropdown"
              @mouseleave="scheduleCloseMoreDropdown"
              class="absolute right-0 mt-1 w-48 bg-blue-900 rounded-md shadow-xl py-1 z-50 border border-blue-700 backdrop-blur-sm"
            >
              <Link
                href="/gallery"
                :class="[
                  'flex items-center px-4 py-2 text-sm transition-colors duration-200',
                  $page.url === '/gallery' || $page.url.startsWith('/gallery')
                    ? 'bg-blue-800 text-white'
                    : 'text-white hover:bg-blue-800'
                ]"
                @click="closeMoreDropdown"
              >
                <Camera class="mr-3 h-4 w-4 text-blue-300" />
                Gallery
              </Link>
              <Link
                href="/news"
                :class="[
                  'flex items-center px-4 py-2 text-sm transition-colors duration-200',
                  $page.url === '/news' || $page.url.startsWith('/news')
                    ? 'bg-blue-800 text-white'
                    : 'text-white hover:bg-blue-800'
                ]"
                @click="closeMoreDropdown"
              >
                <FileText class="mr-3 h-4 w-4 text-blue-300" />
                News
              </Link>
              <Link
                href="/events"
                :class="[
                  'flex items-center px-4 py-2 text-sm transition-colors duration-200',
                  $page.url === '/events' || $page.url.startsWith('/events')
                    ? 'bg-blue-800 text-white'
                    : 'text-white hover:bg-blue-800'
                ]"
                @click="closeMoreDropdown"
              >
                <Calendar class="mr-3 h-4 w-4 text-blue-300" />
                Events
              </Link>
            </div>
          </div>
          
          <Link
            href="/dashboard"
            class="bg-white text-blue-900 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors"
          >
            Get Started
          </Link>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button @click="toggleMobileMenu" class="p-2 rounded-md hover:bg-blue-800">
            <Menu v-if="!isMobileMenuOpen" class="h-6 w-6" />
            <X v-else class="h-6 w-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="isMobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <Link
            v-for="item in navigationItems"
            :key="item.href"
            :href="item.href"
            :class="[
              'block px-3 py-2 rounded-md text-base font-medium transition-colors',
              $page.url === item.href || ($page.url.startsWith(item.href) && item.href !== '/')
                ? 'bg-blue-800 text-white shadow-md'
                : 'hover:bg-blue-800'
            ]"
            @click="closeMobileMenu"
          >
            {{ item.label }}
          </Link>
          
          <!-- Mobile More Menu Items -->
          <div class="border-t border-blue-800 pt-2 mt-2">
            <div class="text-xs font-semibold text-blue-200 px-3 py-1 uppercase tracking-wide">More</div>
            <Link
              href="/gallery"
              :class="[
                'flex items-center px-6 py-2 rounded-md text-base font-medium transition-colors',
                $page.url === '/gallery' || $page.url.startsWith('/gallery')
                  ? 'bg-blue-800 text-white shadow-md'
                  : 'hover:bg-blue-800'
              ]"
              @click="closeMobileMenu"
            >
              <Camera class="mr-3 h-5 w-5" />
              Gallery
            </Link>
            <Link
              href="/news"
              :class="[
                'flex items-center px-6 py-2 rounded-md text-base font-medium transition-colors',
                $page.url === '/news' || $page.url.startsWith('/news')
                  ? 'bg-blue-800 text-white shadow-md'
                  : 'hover:bg-blue-800'
              ]"
              @click="closeMobileMenu"
            >
              <FileText class="mr-3 h-5 w-5" />
              News
            </Link>
            <Link
              href="/events"
              :class="[
                'flex items-center px-6 py-2 rounded-md text-base font-medium transition-colors',
                $page.url === '/events' || $page.url.startsWith('/events')
                  ? 'bg-blue-800 text-white shadow-md'
                  : 'hover:bg-blue-800'
              ]"
              @click="closeMobileMenu"
            >
              <Calendar class="mr-3 h-5 w-5" />
              Events
            </Link>
          </div>
          
          <Link
            href="/dashboard"
            class="block px-3 py-2 rounded-md text-base font-medium bg-blue-700 hover:bg-blue-600"
            @click="closeMobileMenu"
          >
            Get Started
          </Link>
        </div>
      </div>
    </div>
  </nav>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted  } from 'vue'
  import { useNotify } from "@/Composables/useNotify"
  import Loader from '@/Guest/Partials/Preloader.vue'
  import { 
	MenuIcon, BookOpen, Video, Disc, Mic, FileText, Files,
	ListChecks, Calendar, Users, Globe2, GraduationCap, Church, Heart, Menu, X, ChevronDown, Camera
  } from 'lucide-vue-next'
  

const navigationItems = [
  { href: '/', label: 'Home' },
  { href: '/faq', label: 'FAQ' }
]

const isMobileMenuOpen = ref(false)
const isMoreDropdownOpen = ref(false)
const dropdownCloseTimer = ref(null)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

const toggleMoreDropdown = () => {
  isMoreDropdownOpen.value = !isMoreDropdownOpen.value
}

const openMoreDropdown = () => {
  if (dropdownCloseTimer.value) {
    clearTimeout(dropdownCloseTimer.value)
    dropdownCloseTimer.value = null
  }
  isMoreDropdownOpen.value = true
}

const scheduleCloseMoreDropdown = () => {
  if (dropdownCloseTimer.value) {
    clearTimeout(dropdownCloseTimer.value)
  }
  dropdownCloseTimer.value = setTimeout(() => {
    isMoreDropdownOpen.value = false
    dropdownCloseTimer.value = null
  }, 300) // 300ms delay before closing
}

const cancelCloseMoreDropdown = () => {
  if (dropdownCloseTimer.value) {
    clearTimeout(dropdownCloseTimer.value)
    dropdownCloseTimer.value = null
  }
}

const closeMoreDropdown = () => {
  if (dropdownCloseTimer.value) {
    clearTimeout(dropdownCloseTimer.value)
    dropdownCloseTimer.value = null
  }
  isMoreDropdownOpen.value = false
}

  let { notification } = useNotify()

 
  const footerData = ref({})
  const navBarData = ref({})
  const isLoading = ref(true)
  const activeDropdown = ref(null)
//   const isMobileMenuOpen = ref(false)

// const toggleMobileMenu = () => {
// isMobileMenuOpen.value = !isMobileMenuOpen.value
// }

	const toggleDropdown = (itemName) => {
	if (activeDropdown.value === itemName) {
		activeDropdown.value = null
	} else {
		activeDropdown.value = itemName
	}
	}

	const closeDropdown = (event) => {
	if (!event.target.closest('.group')) {
		activeDropdown.value = null
	}
	}
	const handleOutsideClick = (event) => {
  if (!event.target.closest('.group') && !event.target.closest('.absolute')) {
    closeDropdown()
  }
}
  const menuItems = [
	{ 
	  name: 'About',
	  subItems: null
	},
	{ 
	  name: 'Watch | Listen | Read',
	  subItems: [
		{ name: 'Sermons', category: 'WATCH', icon: BookOpen },
		{ name: 'Videos', category: 'WATCH', icon: Video },
		{ name: 'Music', category: 'LISTEN', icon: Disc },
		{ name: 'Podcasts', category: 'LISTEN', icon: Mic },
		{ name: 'Articles', category: 'READ', icon: FileText },
		{ name: 'Resources', category: 'READ', icon: Files }
	  ]
	},
	{ 
	  name: 'Visit',
	  subItems: null
	},
	{ 
	  name: 'Ministries',
	  subItems: [
		{ name: 'Kids', iconColor: 'bg-red-500' },
		{ name: 'Students', iconColor: 'bg-teal-500' },
		{ name: 'College', iconColor: 'bg-yellow-500' },
		{ name: "Women's", iconColor: 'bg-purple-500' },
		{ name: "Men's", iconColor: 'bg-emerald-500' },
		{ name: 'Marriage', iconColor: 'bg-blue-500' },
		{ name: 'Recovery', iconColor: 'bg-gray-500' }
	  ]
	},
	{ 
	  name: 'Initiatives',
	  subItems: [
		{ 
		  multiLine: true,
		  firstLine: 'Austin Stone',
		  secondLine: 'Counseling',
		  iconColor: 'bg-gray-400'
		},
		{ 
		  multiLine: true,
		  firstLine: 'Austin Stone',
		  secondLine: 'Creative',
		  iconColor: 'bg-yellow-400'
		},
		{ 
		  multiLine: true,
		  firstLine: 'Austin Stone',
		  secondLine: 'Institute',
		  iconColor: 'bg-blue-400'
		},
		{ 
		  multiLine: true,
		  firstLine: 'For the',
		  secondLine: 'City',
		  iconColor: 'bg-red-400'
		},
		{ 
		  multiLine: true,
		  firstLine: 'For the',
		  secondLine: 'Nations',
		  iconColor: 'bg-emerald-400'
		}
	  ]
	},
	{
	  name: 'Get Involved',
	  subItems: [
		{ name: 'Classes', category: 'TAKE', icon: ListChecks },
		{ name: 'Events', category: 'ATTEND', icon: Calendar },
		{ name: 'Groups', category: 'JOIN', icon: Users },
		{ name: 'Mission Trips', category: 'GO', icon: Globe2 },
		{ name: 'Programs', category: 'LEARN', icon: GraduationCap },
		{ name: 'Residencies', category: 'LEAD', icon: Church },
		{ name: 'Volunteer', category: 'SERVE', icon: Heart }
	  ]
	}
  ]

  onMounted(async () => {
	document.addEventListener('click', closeDropdown)
	isLoading.value = true
	try {
	//   await getNavData()
	//   await getFooterData()
	} finally {
	  isLoading.value = false
	}
  })

  onUnmounted(() => {
  document.removeEventListener('click', closeDropdown)
  if (dropdownCloseTimer.value) {
    clearTimeout(dropdownCloseTimer.value)
  }
})
  
  const getNavData = async () => {
	try {
	  const response = await axios.get('/topbar-data')
	  navBarData.value = response.data
	} catch (error) {
	  console.error(error)
	}
  }
  
  const getFooterData = async () => {
	try {
	  const response = await axios.get('/footer-data')
	  footerData.value = response.data
	} catch (error) {
	  console.error(error)
	  notification(error, 'error')
	}
  }
  </script>
  
  <style scoped>
  .clip-hexagon {
	clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
	width: 100%;
	height: 100%;
  }
  
  .transition-all {
	transition-property: all;
	transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
	transition-duration: 200ms;
  }

  /* Dropdown animations */
  .dropdown-enter-active,
  .dropdown-leave-active {
    transition: all 0.2s ease;
  }

  .dropdown-enter-from,
  .dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
  }

  /* Chevron rotation */
  .rotate-180 {
    transform: rotate(180deg);
    transition: transform 0.2s ease;
  }
  </style>