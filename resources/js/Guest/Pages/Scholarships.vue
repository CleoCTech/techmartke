<template>
  <Head>
    <title>Scholarship Programs - Novus Institute</title>
  </Head>
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Header Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 overflow-hidden">
      <!-- Animated Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-20 h-20 border border-white rounded-full animate-pulse"></div>
        <div class="absolute top-32 right-20 w-16 h-16 border border-white rounded-lg transform rotate-45 animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 border border-white rounded-full animate-ping"></div>
      </div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fade-in-up">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6">
            <Award class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">SCHOLARSHIP PROGRAMS</span>
          </div>
          
          <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
            Unlock Your Potential with
            <span class="block text-blue-200">Our Scholarship Programs</span>
          </h1>
          
          <p class="text-xl text-white/90 max-w-4xl mx-auto leading-relaxed mb-8">
            Novus Institute offers two distinct scholarship programs designed to support both students and professionals in their technology education journey.
          </p>

          <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
            <div class="flex items-center space-x-2 text-white/80">
              <CheckCircle class="h-5 w-5 text-green-400" />
              <span>Up to 30% Fee Reduction</span>
            </div>
            <div class="flex items-center space-x-2 text-white/80">
              <CheckCircle class="h-5 w-5 text-green-400" />
              <span>Merit-Based Selection</span>
            </div>
            <div class="flex items-center space-x-2 text-white/80">
              <CheckCircle class="h-5 w-5 text-green-400" />
              <span>Flexible Application Process</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Scholarship Programs -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Scholarship Programs</h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Choose the scholarship program that best fits your background and career goals
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
          <!-- Student Scholarship -->
          <div class="bg-white rounded-2xl shadow-xl p-8 border-l-4 border-blue-500 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
              <div class="bg-blue-100 rounded-full p-4 mr-4">
                <GraduationCap class="h-8 w-8 text-blue-600" />
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900">Student Scholarship</h3>
                <p class="text-blue-600 font-medium">For Previous Year Students</p>
              </div>
            </div>

            <div class="space-y-4 mb-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2">Eligibility Criteria</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                  <li>• Previous year students from recognized institutions</li>
                  <li>• Minimum grade requirement: C- or equivalent</li>
                  <li>• Demonstrated financial need</li>
                  <li>• Strong academic potential and commitment</li>
                </ul>
              </div>

              <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-semibold text-green-900 mb-2">Benefits</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                  <li>• Up to 25% reduction in course fees</li>
                  <li>• Priority enrollment in programs</li>
                  <li>• Access to mentorship programs</li>
                  <li>• Career guidance and support</li>
                </ul>
              </div>
            </div>

            <div class="space-y-3">
              <a href="/assets/docs/Scholarship Form-Students.pdf" 
                 download="Scholarship_Form_Students.pdf"
                 class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors w-full justify-center">
                <Download class="h-5 w-5 mr-2" />
                Download Student Application Form
              </a>
              <button @click="showStudentModal = true" 
                      class="inline-flex items-center bg-blue-100 hover:bg-blue-200 text-blue-800 px-6 py-3 rounded-lg font-medium transition-colors w-full justify-center">
                <Info class="h-5 w-5 mr-2" />
                View Application Process
              </button>
            </div>
          </div>

          <!-- Professional Scholarship -->
          <div class="bg-white rounded-2xl shadow-xl p-8 border-l-4 border-green-500 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center mb-6">
              <div class="bg-green-100 rounded-full p-4 mr-4">
                <Users class="h-8 w-8 text-green-600" />
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900">Professional Scholarship</h3>
                <p class="text-green-600 font-medium">For Working Professionals</p>
              </div>
            </div>

            <div class="space-y-4 mb-6">
              <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-semibold text-green-900 mb-2">Eligibility Criteria</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                  <li>• Currently employed professionals</li>
                  <li>• Minimum 2 years work experience</li>
                  <li>• Demonstrated career advancement goals</li>
                  <li>• Employer recommendation (optional)</li>
                </ul>
              </div>

              <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2">Benefits</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                  <li>• Up to 30% reduction in course fees</li>
                  <li>• Flexible learning schedules</li>
                  <li>• Industry networking opportunities</li>
                  <li>• Career transition support</li>
                </ul>
              </div>
            </div>

            <div class="space-y-3">
              <a href="/assets/docs/Scholarship Form-Professionals.pdf" 
                 download="Scholarship_Form_Professionals.pdf"
                 class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors w-full justify-center">
                <Download class="h-5 w-5 mr-2" />
                Download Professional Application Form
              </a>
              <button @click="showProfessionalModal = true" 
                      class="inline-flex items-center bg-green-100 hover:bg-green-200 text-green-800 px-6 py-3 rounded-lg font-medium transition-colors w-full justify-center">
                <Info class="h-5 w-5 mr-2" />
                View Application Process
              </button>
            </div>
          </div>
        </div>

        <!-- Application Process -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 text-white mb-16">
          <h3 class="text-3xl font-bold mb-6 text-center">Application Process</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
              <div class="bg-white/20 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <span class="text-2xl font-bold">1</span>
              </div>
              <h4 class="font-semibold mb-2">Download Form</h4>
              <p class="text-sm text-white/80">Download the appropriate application form</p>
            </div>
            <div class="text-center">
              <div class="bg-white/20 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <span class="text-2xl font-bold">2</span>
              </div>
              <h4 class="font-semibold mb-2">Fill & Prepare</h4>
              <p class="text-sm text-white/80">Complete the form with required documents</p>
            </div>
            <div class="text-center">
              <div class="bg-white/20 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <span class="text-2xl font-bold">3</span>
              </div>
              <h4 class="font-semibold mb-2">Submit Application</h4>
              <p class="text-sm text-white/80">Upload completed form via our portal</p>
            </div>
            <div class="text-center">
              <div class="bg-white/20 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <span class="text-2xl font-bold">4</span>
              </div>
              <h4 class="font-semibold mb-2">Review & Decision</h4>
              <p class="text-sm text-white/80">We'll review and notify you within 2 weeks</p>
            </div>
          </div>
        </div>

        <!-- Submit Application Section -->
        <div class="text-center">
          <div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto">
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Ready to Apply?</h3>
            <p class="text-xl text-gray-600 mb-8">
              Submit your completed scholarship application form through our secure portal
            </p>
            
            <div class="space-y-4">
              <div class="bg-blue-50 p-6 rounded-xl">
                <h4 class="font-semibold text-blue-900 mb-2">Required Documents</h4>
                <ul class="text-sm text-gray-700 space-y-1 text-left max-w-md mx-auto">
                  <li>• Completed scholarship application form</li>
                  <li>• Academic transcripts (for students)</li>
                  <li>• Employment verification (for professionals)</li>
                  <li>• Personal statement/essay</li>
                  <li>• Recommendation letter (optional)</li>
                </ul>
              </div>
              
              <button @click="showUploadModal = true" 
                      class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors transform hover:scale-105">
                <Upload class="h-6 w-6 mr-2" />
                Submit Scholarship Application
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Student Application Modal -->
    <div v-if="showStudentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold text-gray-900">Student Scholarship Application Process</h3>
          <button @click="showStudentModal = false" class="text-gray-400 hover:text-gray-600">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <div class="space-y-4 text-gray-700">
          <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-semibold text-blue-900 mb-2">Step 1: Download Form</h4>
            <p class="text-sm">Download the Student Scholarship Application Form from the link above.</p>
          </div>
          
          <div class="bg-green-50 p-4 rounded-lg">
            <h4 class="font-semibold text-green-900 mb-2">Step 2: Complete Form</h4>
            <p class="text-sm">Fill out all sections of the form including personal information, academic history, and financial need statement.</p>
          </div>
          
          <div class="bg-yellow-50 p-4 rounded-lg">
            <h4 class="font-semibold text-yellow-900 mb-2">Step 3: Gather Documents</h4>
            <p class="text-sm">Prepare academic transcripts, recommendation letters, and any other supporting documents.</p>
          </div>
          
          <div class="bg-purple-50 p-4 rounded-lg">
            <h4 class="font-semibold text-purple-900 mb-2">Step 4: Submit Application</h4>
            <p class="text-sm">Upload your completed form and documents through our scholarship portal.</p>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end">
          <button @click="showStudentModal = false" 
                  class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
            Got It
          </button>
        </div>
      </div>
    </div>

    <!-- Professional Application Modal -->
    <div v-if="showProfessionalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold text-gray-900">Professional Scholarship Application Process</h3>
          <button @click="showProfessionalModal = false" class="text-gray-400 hover:text-gray-600">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <div class="space-y-4 text-gray-700">
          <div class="bg-green-50 p-4 rounded-lg">
            <h4 class="font-semibold text-green-900 mb-2">Step 1: Download Form</h4>
            <p class="text-sm">Download the Professional Scholarship Application Form from the link above.</p>
          </div>
          
          <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-semibold text-blue-900 mb-2">Step 2: Complete Form</h4>
            <p class="text-sm">Fill out all sections including work experience, career goals, and professional achievements.</p>
          </div>
          
          <div class="bg-yellow-50 p-4 rounded-lg">
            <h4 class="font-semibold text-yellow-900 mb-2">Step 3: Gather Documents</h4>
            <p class="text-sm">Prepare employment verification, recommendation letters, and career development plan.</p>
          </div>
          
          <div class="bg-purple-50 p-4 rounded-lg">
            <h4 class="font-semibold text-purple-900 mb-2">Step 4: Submit Application</h4>
            <p class="text-sm">Upload your completed form and documents through our scholarship portal.</p>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end">
          <button @click="showProfessionalModal = false" 
                  class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
            Got It
          </button>
        </div>
      </div>
    </div>

    <!-- Upload Application Modal -->
    <div v-if="showUploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl p-8 max-w-2xl w-full">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold text-gray-900">Submit Scholarship Application</h3>
          <button @click="showUploadModal = false" class="text-gray-400 hover:text-gray-600">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form @submit.prevent="submitApplication" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Scholarship Type *</label>
            <select v-model="applicationData.type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': errors.type }">
              <option value="">Select scholarship type</option>
              <option value="student">Student Scholarship</option>
              <option value="professional">Professional Scholarship</option>
            </select>
            <p v-if="errors.type" class="text-red-500 text-sm mt-1">{{ errors.type }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
            <input v-model="applicationData.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': errors.name }" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
            <input v-model="applicationData.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': errors.email }" />
            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
            <input v-model="applicationData.phone" type="tel" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': errors.phone }" />
            <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Completed Application Form *</label>
            <input type="file" accept=".pdf,.doc,.docx" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': errors.applicationForm }" />
            <p v-if="errors.applicationForm" class="text-red-500 text-sm mt-1">{{ errors.applicationForm }}</p>
            <p class="text-xs text-gray-500 mt-1">Upload your completed scholarship application form (PDF, DOC, or DOCX)</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Supporting Documents</label>
            <input type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <p class="text-xs text-gray-500 mt-1">Upload additional supporting documents (transcripts, recommendations, etc.)</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
            <textarea v-model="applicationData.notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional information you'd like to share..."></textarea>
          </div>
          
          <div class="flex justify-end space-x-4">
            <button type="button" @click="showUploadModal = false" 
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button type="submit" :disabled="isSubmitting"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
              <span v-if="isSubmitting" class="mr-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ isSubmitting ? 'Submitting...' : 'Submit Application' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { 
  Award, 
  CheckCircle, 
  GraduationCap, 
  Users, 
  Download, 
  Info, 
  Upload, 
  X 
} from 'lucide-vue-next'

// Modal states
const showStudentModal = ref(false)
const showProfessionalModal = ref(false)
const showUploadModal = ref(false)
const isSubmitting = ref(false)

// Load saved data from localStorage
const savedApplicationData = localStorage.getItem('scholarshipApplicationData')
const initialApplicationData = savedApplicationData ? JSON.parse(savedApplicationData) : {
  type: '',
  name: '',
  email: '',
  phone: '',
  notes: ''
}

// Application form data
const applicationData = ref(initialApplicationData)

// Save form data to localStorage whenever it changes
watch(applicationData, (newData) => {
  localStorage.setItem('scholarshipApplicationData', JSON.stringify(newData))
}, { deep: true })

// Form validation errors
const errors = ref({})

// Validation function
const validateForm = () => {
  errors.value = {}
  let isValid = true

  if (!applicationData.value.type) {
    errors.value.type = 'Please select a scholarship type'
    isValid = false
  }

  if (!applicationData.value.name.trim()) {
    errors.value.name = 'Full name is required'
    isValid = false
  }

  if (!applicationData.value.email.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(applicationData.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!applicationData.value.phone.trim()) {
    errors.value.phone = 'Phone number is required'
    isValid = false
  }

  const applicationFormFile = document.querySelector('input[type="file"]:first-of-type').files[0]
  if (!applicationFormFile) {
    errors.value.applicationForm = 'Please upload your completed application form'
    isValid = false
  }

  return isValid
}

// Clear form data
const clearFormData = () => {
  applicationData.value = {
    type: '',
    name: '',
    email: '',
    phone: '',
    notes: ''
  }
  localStorage.removeItem('scholarshipApplicationData')
}

// Submit application
const submitApplication = async () => {
  if (!validateForm()) {
    return
  }

  isSubmitting.value = true
  errors.value = {}

  try {
    // Create FormData for file uploads
    const formData = new FormData();
    formData.append('type', applicationData.value.type);
    formData.append('name', applicationData.value.name);
    formData.append('email', applicationData.value.email);
    formData.append('phone', applicationData.value.phone);
    formData.append('notes', applicationData.value.notes);

    // Add files if selected
    const applicationFormFile = document.querySelector('input[type="file"]:first-of-type').files[0];
    if (applicationFormFile) {
      formData.append('application_form', applicationFormFile);
    }

    const supportingDocsFiles = document.querySelector('input[type="file"]:last-of-type').files;
    if (supportingDocsFiles.length > 0) {
      for (let i = 0; i < supportingDocsFiles.length; i++) {
        formData.append('supporting_documents[]', supportingDocsFiles[i]);
      }
    }

    // Get CSRF token with fallback
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value ||
                     '';

    // Submit to Laravel backend
    const response = await fetch('/scholarship-application', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    })

    const data = await response.json()

    if (data.success) {
      alert(data.success);
      // Clear form data and localStorage
      clearFormData();
      showUploadModal.value = false;
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

<style scoped>
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}
</style>
