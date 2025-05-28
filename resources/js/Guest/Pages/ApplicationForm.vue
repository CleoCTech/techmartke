<template>
    <Head>
        <title>Application Form</title>
    </Head>
    <div class="min-h-screen py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ pageData.title }}</h1>
          <p class="text-xl text-gray-600">{{ pageData.subtitle }}</p>
        </div>
  
        <!-- Progress Bar -->
        <div class="mb-8">
          <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span>Step {{ currentStep }} of {{ totalSteps }}</span>
            <span>{{ Math.round(progress) }}% Complete</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
              class="bg-blue-900 h-2 rounded-full transition-all duration-300 ease-in-out" 
              :style="{ width: `${progress}%` }"
            ></div>
          </div>
        </div>
  
        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <form @submit.prevent="handleSubmit">
            <!-- Step 1: Personal Information -->
            <div v-if="currentStep === 1" class="space-y-6">
              <div class="text-center mb-6">
                <User class="h-12 w-12 text-blue-900 mx-auto mb-4" />
                <h2 class="text-2xl font-bold">Personal Information</h2>
                <p class="text-gray-600">Tell us about yourself</p>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                  <input
                    id="firstName"
                    v-model="formData.firstName"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                  <input
                    id="lastName"
                    v-model="formData.lastName"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                  <input
                    id="email"
                    v-model="formData.email"
                    type="email"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                  <input
                    id="phone"
                    v-model="formData.phone"
                    type="tel"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
  
              <div>
                <label for="dateOfBirth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                <input
                  id="dateOfBirth"
                  v-model="formData.dateOfBirth"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
  
            <!-- Step 2: Program Selection -->
            <div v-if="currentStep === 2" class="space-y-6">
              <div class="text-center mb-6">
                <GraduationCap class="h-12 w-12 text-blue-900 mx-auto mb-4" />
                <h2 class="text-2xl font-bold">Program Selection</h2>
                <p class="text-gray-600">Choose your path</p>
              </div>
  
              <div>
                <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Preferred Program *</label>
                <select
                  id="program"
                  v-model="formData.program"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select a program</option>
                  <option v-for="program in availablePrograms" :key="program" :value="program">
                    {{ program }}
                  </option>
                </select>
              </div>
  
              <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Preferred Start Date *</label>
                <select
                  id="startDate"
                  v-model="formData.startDate"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select start date</option>
                  <option value="fall-2024">Fall 2024 (September)</option>
                  <option value="spring-2025">Spring 2025 (January)</option>
                  <option value="summer-2025">Summer 2025 (May)</option>
                </select>
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Study Format *</label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="formData.studyFormat"
                      type="radio"
                      value="in-person"
                      class="mr-2"
                    />
                    In-Person
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="formData.studyFormat"
                      type="radio"
                      value="online"
                      class="mr-2"
                    />
                    Online
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="formData.studyFormat"
                      type="radio"
                      value="hybrid"
                      class="mr-2"
                    />
                    Hybrid (Online + In-Person)
                  </label>
                </div>
              </div>
  
              <div>
                <label for="careerGoals" class="block text-sm font-medium text-gray-700 mb-1">Career Goals *</label>
                <textarea
                  id="careerGoals"
                  v-model="formData.careerGoals"
                  required
                  rows="4"
                  placeholder="What are your career goals and how will this program help you achieve them?"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
              </div>
            </div>
  
            <!-- Step 3: Final Details -->
            <div v-if="currentStep === 3" class="space-y-6">
              <div class="text-center mb-6">
                <Send class="h-12 w-12 text-blue-900 mx-auto mb-4" />
                <h2 class="text-2xl font-bold">Final Details</h2>
                <p class="text-gray-600">Complete your application</p>
              </div>
  
              <div>
                <label for="hearAboutUs" class="block text-sm font-medium text-gray-700 mb-1">How did you hear about us?</label>
                <select
                  id="hearAboutUs"
                  v-model="formData.hearAboutUs"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select an option</option>
                  <option value="search-engine">Search Engine</option>
                  <option value="social-media">Social Media</option>
                  <option value="friend-referral">Friend/Family Referral</option>
                  <option value="advertisement">Advertisement</option>
                  <option value="career-fair">Career Fair</option>
                  <option value="other">Other</option>
                </select>
              </div>
  
              <div>
                <label for="additionalInfo" class="block text-sm font-medium text-gray-700 mb-1">Additional Information</label>
                <textarea
                  id="additionalInfo"
                  v-model="formData.additionalInfo"
                  rows="3"
                  placeholder="Any additional information you'd like to share"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
              </div>
  
              <div class="space-y-4 border-t pt-6">
                <label class="flex items-start space-x-2">
                  <input
                    v-model="formData.termsAccepted"
                    type="checkbox"
                    required
                    class="mt-1"
                  />
                  <span class="text-sm">I agree to the Terms and Conditions and Privacy Policy *</span>
                </label>
  
                <label class="flex items-start space-x-2">
                  <input
                    v-model="formData.marketingConsent"
                    type="checkbox"
                    class="mt-1"
                  />
                  <span class="text-sm">I consent to receive marketing communications from Novus Institute</span>
                </label>
              </div>
  
              <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold mb-2">Application Review Process</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                  <li>• Your application will be reviewed within 2-3 business days</li>
                  <li>• You will receive an email confirmation upon submission</li>
                  <li>• Our admissions team may contact you for additional information</li>
                  <li>• You will be notified of the admission decision via email</li>
                </ul>
              </div>
            </div>
  
            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8 pt-6 border-t">
              <button
                type="button"
                @click="previousStep"
                :disabled="currentStep === 1"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Previous
              </button>
  
              <button
                v-if="currentStep === totalSteps"
                type="submit"
                :disabled="!formData.termsAccepted"
                class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Submit Application
              </button>
              <button
                v-else
                type="button"
                @click="nextStep"
                class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-800"
              >
                Next
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { User, GraduationCap, Send } from 'lucide-vue-next'
  
  // Props that would come from Laravel-Inertia
  const props = defineProps({
    pageData: {
      type: Object,
      default: () => ({
        title: 'Application Form',
        subtitle: 'Start your journey with Novus Institute of Technology'
      })
    },
    availablePrograms: {
      type: Array,
      default: () => [
        'Software Development',
        'Data Science & Analytics',
        'Cybersecurity',
        'Mobile App Development',
        'Artificial Intelligence',
        'UI/UX Design'
      ]
    }
  })
  
  // Form state
  const currentStep = ref(1)
  const totalSteps = 3
  
  const formData = ref({
    // Personal Information
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    dateOfBirth: '',
    
    // Program Selection
    program: '',
    startDate: '',
    studyFormat: '',
    careerGoals: '',
    
    // Final Details
    hearAboutUs: '',
    additionalInfo: '',
    termsAccepted: false,
    marketingConsent: false
  })
  
  // Computed
  const progress = computed(() => (currentStep.value / totalSteps) * 100)
  
  // Methods
  const nextStep = () => {
    if (currentStep.value < totalSteps) {
      currentStep.value++
    }
  }
  
  const previousStep = () => {
    if (currentStep.value > 1) {
      currentStep.value--
    }
  }
  
  const handleSubmit = () => {
    // In a real Laravel-Inertia app, you would use Inertia.post() here
    console.log('Application submitted:', formData.value)
    alert('Application submitted successfully! We will contact you within 2-3 business days.')
    
    // Reset form
    formData.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      dateOfBirth: '',
      program: '',
      startDate: '',
      studyFormat: '',
      careerGoals: '',
      hearAboutUs: '',
      additionalInfo: '',
      termsAccepted: false,
      marketingConsent: false
    }
    currentStep.value = 1
  }
  </script>