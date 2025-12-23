<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useNotify } from "@/Composables/useNotify";
import Loader from '@/Guest/Partials/Preloader.vue';
import axios from 'axios';

const apiKey = 'AIzaSyCLNvruqejh6c-eU0TGpk67AdW-wfZfuaI'; // Replace with your actual Google Maps API key
const latitude = -1.13711; // Replace with the latitude of your shop
const longitude = 36.96981; // Replace with the longitude of your shop

const mapLoaded = ref(false);

const page = usePage();
const config = page.props.config;
const companyLogoUrl = page.props.companyLogoUrl;

const { notification } = useNotify();

const footerData = ref({});
const isLoading = ref(true);

import {
  Facebook,
  Instagram,
  Youtube,
  Music2,
  Podcast, GraduationCap, MapPin, Phone, Mail
} from 'lucide-vue-next'

const aboutLinks = ['Team', 'Jobs', 'Give', 'Facilities', 'Login']
const visitLinks = ['Downtown', 'North', 'Northwest', 'South', 'St. John', 'West']
const involvedLinks = ['Classes', 'Events', 'Groups', 'Missions Trips', 'Programs', 'Residencies', 'Volunteer']
const wlrLinks = ['Sermons', 'Videos', 'Podcasts', 'Music', 'Articles', 'Resources']
const ministriesLinks = ['Kids', 'Students', 'College', 'Women\'s', 'Men\'s', 'Marriage', 'Recovery']
const initiativesLinks = ['DCM Counseling', 'DCM Creative', 'For the City', 'For the Nations']

const socialLinks = [
  { name: 'Facebook', icon: Facebook, url: '#' },
  { name: 'Instagram', icon: Instagram, url: '#' },
  { name: 'Vimeo', icon: Youtube, url: '#' }, // Using Youtube icon as placeholder
  { name: 'YouTube', icon: Youtube, url: '#' },
  { name: 'Spotify', icon: Music2, url: '#' },
  { name: 'Apple Podcasts', icon: Podcast, url: '#' }
]

const contactInfo = ref({ address: '', phone: '', email: '' });

onMounted(async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/footer-data');
    if (response.data && response.data.companyInfo) {
      contactInfo.value = {
        address: response.data.companyInfo.address || '',
        phone: response.data.companyInfo.phone_numbers || '',
        email: response.data.companyInfo.emails || ''
      };
    }
  } catch (error) {
    contactInfo.value = { address: '', phone: '', email: '' };
  } finally {
    isLoading.value = false;
  }
});

const getFooterData = async () => {
  isLoading.value = true; // Set loading to true before starting the request
  try {
    const response = await axios.get('/footer-data');
    footerData.value = response.data;
  } catch (error) {
    console.error(error);
    notification(error, 'error');
  } finally {
    isLoading.value = false; // Ensure loading is set to false no matter what
  }
};

const quickLinks = [
  { href: '/faq', label: 'FAQ' },
  { href: '/events', label: 'Events' },
  { href: '/news', label: 'News' },
  { href: '/login', label: 'Login' }
]

const resources = [
  { href: '/ethics', label: 'Computer Ethics' },
  { href: '/community', label: 'Community' },
  { href: '/faq', label: 'FAQ' },
  { href: '/contact', label: 'Contact' }
]

const currentYear = computed(() => new Date().getFullYear())
const instituteName = computed(() => page.props.config?.appName || 'Your Organization')
const instituteMission = 'Empowering the next generation through innovative education and practical training.'

</script>

<template>
  <div v-if="isLoading">
    <Loader />
  </div>
  <footer v-else class="bg-gray-900 text-white z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <img src="/assets/images/logo.png" alt="logo" class="h-8 w-8" />
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
</template>

<style scoped>
.clip-hexagon {
  clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
}
</style>

