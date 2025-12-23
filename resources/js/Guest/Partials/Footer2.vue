<template>
    <footer class="bg-gray-900 text-white">
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
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { GraduationCap, MapPin, Phone, Mail } from 'lucide-vue-next'
  
  const instituteName = computed(() => $page.props.config?.appName || 'Your Organization')
  const instituteMission = 'Empowering the next generation through innovative education and practical training.'
  
  const contactInfo = computed(() => {
    const companyInfo = $page.props.footerData?.companyInfo
    return {
      address: companyInfo?.address || 'Your Address',
      phone: companyInfo?.phone_numbers || '+1 (555) 123-4567',
      email: companyInfo?.emails || 'info@example.com'
    }
  })
  
  const quickLinks = [
    { href: '/faq', label: 'FAQ' },
    { href: '/events', label: 'Events' },
    { href: '/news', label: 'News' }
  ]
  
  const resources = [
    { href: '/ethics', label: 'Computer Ethics' },
    { href: '/community', label: 'Community' },
    { href: '/faq', label: 'FAQ' },
    { href: '/contact', label: 'Contact' }
  ]
  
  const currentYear = computed(() => new Date().getFullYear())
  </script>