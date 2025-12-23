<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Loader from '@/Guest/Partials/Preloader.vue';
import {
  MenuIcon, BookOpen, Video, Disc, Mic, FileText, Files,
  ListChecks, Calendar, Users, Globe2, GraduationCap, Church, Heart, Menu, X
} from 'lucide-vue-next'


const navigationItems = [
  { href: '/', label: 'Home' },
  { href: '/faq', label: 'FAQ' },
  { href: '/contact', label: 'Contact' }
]

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}
const page = usePage();
const config = page.props.config;
// const companyLogoUrl = page.props.companyLogoUrl;
const companyLogoUrl = '/storage/companyinfo/logo_no_bg.png';

const isOpen = ref(false);

const toggleNavbarSticky = () => {
  const navbarSticky = document.getElementById('navbar-sticky');
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    navbarSticky.classList.remove('hidden');
    navbarSticky.classList.add('flex', 'flex-col');
  } else {
    navbarSticky.classList.add('hidden');
  }
};

const showServicesPopup = (event) => {
  const popup = document.getElementById('popover-default');
  const rect = event.target.getBoundingClientRect();
  popup.style.top = `${rect.bottom + window.scrollY}px`;
  popup.style.left = `${rect.left + window.scrollX}px`;
  popup.classList.remove('hidden', 'invisible', 'opacity-0');
  popup.classList.add('block', 'visible', 'opacity-100');
};

const hideServicesPopup = () => {
  const popup = document.getElementById('popover-default');
  popup.classList.remove('block', 'visible', 'opacity-100');
  popup.classList.add('hidden', 'invisible', 'opacity-0');
};

const typingText = ref(null);
const textContent = "For any inquiries or assistance, feel free to get in touch with us directly on the following phone numbers:";

const typeEffect = () => {
  let isTyping = false;

  const startTyping = () => {
    if (!isTyping) {
      isTyping = true;
      typingText.value.style.width = '0';
      typingText.value.textContent = '';
      let i = 0;
      const typingInterval = setInterval(() => {
        if (i < textContent.length) {
          typingText.value.textContent += textContent.charAt(i);
          typingText.value.style.width = typingText.value.textContent.length + 'ch';
          i++;
        } else {
          clearInterval(typingInterval);
          setTimeout(() => {
            typingText.value.style.width = '0';
            typingText.value.textContent = '';
            isTyping = false;
            setTimeout(startTyping, 1000); // Start typing again after 1 second
          }, 2000); // Pause for 2 seconds after typing ends
        }
      }, 100); // Adjust typing speed (lower is faster)
    }
  };

  startTyping(); // Start the typing effect initially
};

const applyStickyBehavior = () => {
  const stickyNavs = document.querySelectorAll('[data-sticky]');

  const handleScroll = () => {
    stickyNavs.forEach(stickyNav => {
      const stickyOffset = stickyNav.offsetTop;
      if (window.scrollY > stickyOffset) {
        stickyNav.classList.add('fixed', 'top-0', 'left-0', 'right-0', 'shadow-lg');
      } else {
        stickyNav.classList.remove('fixed', 'top-0', 'left-0', 'right-0', 'shadow-lg');
      }
    });
  };

  window.addEventListener('scroll', handleScroll);

  // Clean up the event listener when the component is unmounted
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
};
const isServicesPopupVisible = ref(false);

const showLocationsPopup = () => {
  isServicesPopupVisible.value = true;
};

const hideLocationsPopup = () => {
  isServicesPopupVisible.value = false;
};
onMounted(() => {

  // typeEffect();
  // applyStickyBehavior();
});


</script>
<template>
  <div v-if="isLoading">
    <Loader />
  </div>
  <div v-else>

    <nav class="bg-blue-900 text-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <a href="/" class="flex items-center space-x-2">
              <img src="/assets/images/logo.png" alt="logo" class="h-8 w-8" />
              <span class="font-bold text-xl">{{ $page.props.config.appName || 'Your App' }}</span>
            </a>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-4">
            <a v-for="item in navigationItems" :key="item.href" :href="item.href"
              class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 transition-colors">
              {{ item.label }}
            </a>
            <Link href="/dashboard"
              class="bg-white text-blue-900 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors">
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
            <a v-for="item in navigationItems" :key="item.href" :href="item.href"
              class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-800" @click="closeMobileMenu">
              {{ item.label }}
            </a>
            <Link href="/dashboard"
              class="block px-3 py-2 rounded-md text-base font-medium bg-blue-700 hover:bg-blue-600"
              @click="closeMobileMenu">
              Get Started
            </Link>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>
<style scoped>
/* Custom CSS for underline animation */
.underline-animation {
  position: relative;
  display: inline-block;
}

.underline-animation::after {
  content: '';
  position: absolute;
  width: 100%;
  transform: scaleX(0);
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: #8b5cf6;
  /* Tailwind purple-500 */
  transform-origin: bottom right;
  transition: transform 0.3s ease-in-out;
}

.underline-animation:hover::after,
.underline-animation:focus::after {
  transform: scaleX(1);
  transform-origin: bottom left;
}

@keyframes typing {
  from {
    width: 0;
  }

  to {
    width: 100%;
  }
}

@keyframes blink-caret {

  from,
  to {
    border-color: transparent;
  }

  50% {
    border-color: white;
  }
}

.typing-text {
  border-right: 2px solid;
  white-space: nowrap;
  overflow: hidden;
  width: 0;
  animation: blink-caret 0.75s step-end infinite;
}

/* Apply the font size only on medium and large screens */
@media (min-width: 768px) {
  .large-font-size {
    font-size: 5.441rem;
  }
}

@media (min-width: 1024px) {
  .large-font-size {
    font-size: 5.441rem;
  }
}

</style>