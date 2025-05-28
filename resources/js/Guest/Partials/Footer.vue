<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted,computed } from 'vue';
import { format } from 'date-fns';
import { enUS } from 'date-fns/locale'

import {useNotify} from "@/Composables/useNotify";
import { GraduationCap, MapPin, Phone, Mail } from 'lucide-vue-next'
const {notification} = useNotify();

const footerData = ref({});
const isLoading = ref(true);
const instituteName = 'Novus Institute of Technology'
const instituteMission = 'Empowering the next generation of technology leaders through innovative education and practical training.'

const contactInfo = {
  address: '123 Technology Drive, Innovation City',
  phone: '+1 (555) 123-4567',
  email: 'info@novusinstitute.edu'
}

const quickLinks = [
  { href: '/about', label: 'About Us' },
  { href: '/courses', label: 'Courses' },
  { href: '/admissions', label: 'Admissions' },
  { href: '/events', label: 'Events' },
  { href: '/awards', label: 'Awards' }
]

const resources = [
  { href: '/ethics', label: 'Computer Ethics' },
  { href: '/community', label: 'Community' },
  { href: '/faq', label: 'FAQ' },
  { href: '/contact', label: 'Contact' }
]

const currentYear = computed(() => new Date().getFullYear())

onMounted(() => {
    //tFooterData();
})

function formatDate(time) {
  // console.log(time);
  const date = new Date()
  const [hours, minutes, seconds] = time.split(':')
  date.setHours(hours)
  date.setMinutes(minutes)
  date.setSeconds(seconds)
  const hour = date.getHours()
  const meridiem = hour < 12 ? 'AM' : 'PM'
  return format(date, `h:mm a`, { locale: enUS })

  // const [hours, minutes, seconds] = time.split(':')
  // date.setHours(hours)
  // date.setMinutes(minutes)
  // date.setSeconds(seconds)
  // const hour = date.getHours()
  // console.log(hour);
  // const meridiem = hour < 12 ? 'AM' : 'PM'
  // const bufferDate = format(date, `h:mm`, { locale: enUS })
  // console.log(bufferDate);
  // return bufferDate + meridiem;
}
function getFooterData(){
  axios.get('/footer-data')
    .then(response => {
      // console.log(response.data)
      footerData.value = response.data
      isLoading.value = false;
    })
    .catch(error => {
      console.error(error)
      notification(error, 'error');
      isLoading.value = false;
    })
}
</script>
<template>
<div class="min-h-screen flex flex-col">
    <div class="flex-grow">
        <!-- This div will push the footer to the bottom -->
    </div>
    <div v-if="isLoading" class="preloader">
      <div class="loader">
        <div class="sbl-half-circle-spin"></div>
      </div>
    </div>
    <footer v-else class="bg-gray-900 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <GraduationCap class="h-8 w-8" />
            <span class="font-bold text-xl">{{ instituteName }}</span>
          </div>
          <p class="text-gray-300 mb-4">{{ instituteMission }}</p>
          <div class="space-y-2">
            <div class="flex items-center space-x-2">
              <MapPin class="h-4 w-4" />
              <span class="text-sm">{{ contactInfo.address }}</span>
            </div>
            <div class="flex items-center space-x-2">
              <Phone class="h-4 w-4" />
              <span class="text-sm">{{ contactInfo.phone }}</span>
            </div>
            <div class="flex items-center space-x-2">
              <Mail class="h-4 w-4" />
              <span class="text-sm">{{ contactInfo.email }}</span>
            </div>
          </div>
        </div>

        <div>
          <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li v-for="link in quickLinks" :key="link.href">
              <a :href="link.href" class="text-gray-300 hover:text-white">
                {{ link.label }}
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-lg mb-4">Resources</h3>
          <ul class="space-y-2">
            <li v-for="resource in resources" :key="resource.href">
              <a :href="resource.href" class="text-gray-300 hover:text-white">
                {{ resource.label }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-8 text-center">
        <p class="text-gray-300">
          © {{ currentYear }} {{ instituteName }}. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
  </div>
</template>

<style scoped>
.min-h-screen {
    min-height: 100vh;
}

.flex {
    display: flex;
}

.flex-col {
    flex-direction: column;
}

.flex-grow {
    flex-grow: 1;
}

.mt-auto {
    margin-top: auto;
}
</style>
