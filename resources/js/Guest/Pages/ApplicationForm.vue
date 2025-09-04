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
                    :class="{ 'border-red-500': errors.firstName }"
                  />
                  <p v-if="errors.firstName" class="text-red-500 text-sm mt-1">{{ errors.firstName }}</p>
                </div>
                <div>
                  <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                  <input
                    id="lastName"
                    v-model="formData.lastName"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.lastName }"
                  />
                  <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
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
                    :class="{ 'border-red-500': errors.email }"
                  />
                  <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                </div>
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                  <input
                    id="phone"
                    v-model="formData.phone"
                    type="tel"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.phone }"
                  />
                  <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
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
                  :class="{ 'border-red-500': errors.dateOfBirth }"
                />
                <p v-if="errors.dateOfBirth" class="text-red-500 text-sm mt-1">{{ errors.dateOfBirth }}</p>
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
                  :class="{ 'border-red-500': errors.program }"
                >
                  <option value="">Select a program</option>
                  <option v-for="program in availablePrograms" :key="program" :value="program">
                    {{ program }}
                  </option>
                </select>
                <p v-if="errors.program" class="text-red-500 text-sm mt-1">{{ errors.program }}</p>
              </div>
  
              <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Preferred Start Date *</label>
                <select
                  id="startDate"
                  v-model="formData.startDate"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.startDate }"
                >
                  <option value="">Select start date</option>
                  <option value="fall-2024">Fall 2024 (September)</option>
                  <option value="spring-2025">Spring 2025 (January)</option>
                  <option value="summer-2025">Summer 2025 (May)</option>
                </select>
                <p v-if="errors.startDate" class="text-red-500 text-sm mt-1">{{ errors.startDate }}</p>
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
                <p v-if="errors.studyFormat" class="text-red-500 text-sm mt-1">{{ errors.studyFormat }}</p>
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
                  :class="{ 'border-red-500': errors.careerGoals }"
                ></textarea>
                <p v-if="errors.careerGoals" class="text-red-500 text-sm mt-1">{{ errors.careerGoals }}</p>
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
                  <div class="text-sm">
                    <span>I agree to the </span>
                    <a href="/assets/docs/Terms and Conditions.pdf" 
                       download="Terms_and_Conditions_Novus_Institute.pdf"
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 underline font-medium inline-flex items-center">
                      <Download class="h-3 w-3 mr-1" />
                      Terms and Conditions
                    </a>
                    <span> and </span>
                    <a href="/privacy-policy" 
                       class="text-blue-600 hover:text-blue-800 underline font-medium">
                      Privacy Policy
                    </a>
                    <span> *</span>
                  </div>
                </label>
                <p v-if="errors.termsAccepted" class="text-red-500 text-sm">{{ errors.termsAccepted }}</p>
  
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
                :disabled="!formData.termsAccepted || isSubmitting"
                class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
              >
                <span v-if="isSubmitting" class="mr-2">
                  <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                {{ isSubmitting ? 'Submitting...' : 'Submit Application' }}
              </button>
              <button
                v-else
                type="button"
                @click="nextStep"
                :disabled="!canProceedToNext"
                class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed"
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
import { ref, computed, watch } from 'vue'
import { User, GraduationCap, Send, Download } from 'lucide-vue-next'

// Props that would come from Laravel-Inertia
const props = defineProps({
  pageData: {
    type: Object,
    default: () => ({
      title: 'Application Form',
      subtitle: 'Start your journey with Novus Computer Training Institute'
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
const isSubmitting = ref(false)

// Load saved data from localStorage on component mount
const savedFormData = localStorage.getItem('applicationFormData')
const initialFormData = savedFormData ? JSON.parse(savedFormData) : {
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
}

const formData = ref(initialFormData)

// Load saved step from localStorage
const savedStep = localStorage.getItem('applicationFormStep')
if (savedStep) {
  currentStep.value = parseInt(savedStep)
}

// Save form data to localStorage whenever it changes
watch(formData, (newData) => {
  localStorage.setItem('applicationFormData', JSON.stringify(newData))
}, { deep: true })

// Save current step to localStorage
watch(currentStep, (newStep) => {
  localStorage.setItem('applicationFormStep', newStep.toString())
})

// Form validation errors
const errors = ref({})

// Computed
const progress = computed(() => (currentStep.value / totalSteps) * 100)

// Validation functions
const validateStep1 = () => {
  errors.value = {}
  let isValid = true

  if (!formData.value.firstName.trim()) {
    errors.value.firstName = 'First name is required'
    isValid = false
  }

  if (!formData.value.lastName.trim()) {
    errors.value.lastName = 'Last name is required'
    isValid = false
  }

  if (!formData.value.email.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!formData.value.phone.trim()) {
    errors.value.phone = 'Phone number is required'
    isValid = false
  }

  if (!formData.value.dateOfBirth) {
    errors.value.dateOfBirth = 'Date of birth is required'
    isValid = false
  }

  return isValid
}

const validateStep2 = () => {
  errors.value = {}
  let isValid = true

  if (!formData.value.program) {
    errors.value.program = 'Please select a program'
    isValid = false
  }

  if (!formData.value.startDate) {
    errors.value.startDate = 'Please select a start date'
    isValid = false
  }

  if (!formData.value.studyFormat) {
    errors.value.studyFormat = 'Please select a study format'
    isValid = false
  }

  if (!formData.value.careerGoals.trim()) {
    errors.value.careerGoals = 'Career goals are required'
    isValid = false
  }

  return isValid
}

const validateStep3 = () => {
  errors.value = {}
  let isValid = true

  if (!formData.value.termsAccepted) {
    errors.value.termsAccepted = 'You must accept the terms and conditions'
    isValid = false
  }

  return isValid
}

// Check if user can proceed to next step
const canProceedToNext = computed(() => {
  if (currentStep.value === 1) {
    return validateStep1()
  } else if (currentStep.value === 2) {
    return validateStep2()
  }
  return true
})

// Methods
const nextStep = () => {
  let isValid = false
  
  if (currentStep.value === 1) {
    isValid = validateStep1()
  } else if (currentStep.value === 2) {
    isValid = validateStep2()
  }

  if (isValid && currentStep.value < totalSteps) {
    currentStep.value++
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const clearFormData = () => {
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
  localStorage.removeItem('applicationFormData')
  localStorage.removeItem('applicationFormStep')
}

const handleSubmit = async () => {
  // Validate final step
  if (!validateStep3()) {
    return
  }

  isSubmitting.value = true
  errors.value = {}

  try {
    // Create FormData for submission
    const formDataToSubmit = new FormData();
    
    // Personal Information
    formDataToSubmit.append('first_name', formData.value.firstName);
    formDataToSubmit.append('last_name', formData.value.lastName);
    formDataToSubmit.append('email', formData.value.email);
    formDataToSubmit.append('phone', formData.value.phone);
    formDataToSubmit.append('date_of_birth', formData.value.dateOfBirth);
    
    // Program Selection
    formDataToSubmit.append('program', formData.value.program);
    formDataToSubmit.append('start_date', formData.value.startDate);
    formDataToSubmit.append('study_format', formData.value.studyFormat);
    formDataToSubmit.append('career_goals', formData.value.careerGoals);
    
    // Final Details
    formDataToSubmit.append('hear_about_us', formData.value.hearAboutUs);
    formDataToSubmit.append('additional_info', formData.value.additionalInfo);
    formDataToSubmit.append('terms_accepted', formData.value.termsAccepted ? '1' : '0');
    formDataToSubmit.append('marketing_consent', formData.value.marketingConsent ? '1' : '0');
    
    // Get CSRF token with fallback
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value ||
                     '';
    
    // Submit to Laravel backend
    const response = await fetch('/application', {
      method: 'POST',
      body: formDataToSubmit,
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    })

    const data = await response.json()

    if (data.success) {
      alert(data.success);
      // Clear form data and localStorage
      clearFormData();
    } else if (data.error) {
      if (data.errors) {
        // Handle validation errors
        errors.value = data.errors;
        alert('Please fix the validation errors and try again.');
      } else {
        alert('Error: ' + data.error);
      }
    }
  } catch (error) {
    console.error('Error:', error);
    alert('An error occurred while submitting your application. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
}
</script>