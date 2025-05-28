<template>
	
	<div v-if="isLoading">
	  <Loader />
	</div>
	<nav class="bg-blue-900 text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <Link href="/" class="flex items-center space-x-2">
            <GraduationCap class="h-8 w-8" />
            <span class="font-bold text-xl">Novus Institute of Technology</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-4">
          <Link
            v-for="item in navigationItems"
            :key="item.href"
            :href="item.href"
            class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 transition-colors"
          >
            {{ item.label }}
          </Link>
          <Link
            href="/application"
            class="bg-white text-blue-900 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors"
          >
            Apply Now
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
            class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-800"
            @click="closeMobileMenu"
          >
            {{ item.label }}
          </Link>
          <Link
            href="/application"
            class="block px-3 py-2 rounded-md text-base font-medium bg-blue-700 hover:bg-blue-600"
            @click="closeMobileMenu"
          >
            Apply Now
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
	ListChecks, Calendar, Users, Globe2, GraduationCap, Church, Heart, Menu, X
  } from 'lucide-vue-next'
  

const navigationItems = [
  { href: '/', label: 'Home' },
  { href: '/about', label: 'About' },
  { href: '/courses', label: 'Courses' },
//   { href: '/events', label: 'Events' },
  { href: '/community', label: 'Community' },
  { href: '/training-fees', label: 'Fees' },
// //   { href: '/admissions', label: 'Admissions' },
  { href: '/faq', label: 'FAQ' },
  { href: '/contact-us', label: 'Contact' }
]

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
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
  </style>