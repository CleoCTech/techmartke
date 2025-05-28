<template>
    <Head>
        <title>Contact Us</title>
    </Head>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
      <!-- Hero Section -->
      <section class="relative py-20 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-10 left-10 w-20 h-20 border border-white rounded-full animate-pulse"></div>
          <div class="absolute top-32 right-20 w-16 h-16 border border-white rounded-lg transform rotate-45 animate-bounce"></div>
          <div class="absolute bottom-20 left-1/4 w-24 h-24 border border-white rounded-full animate-ping"></div>
        </div>
  
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div class="animate-fade-in-up">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6">
              <MessageCircle class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">GET IN TOUCH</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Contact
              <span class="block text-blue-200">Novus Institute of Technology</span>
            </h1>
            
            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
              Ready to start your technology journey? We're here to help you every step of the way.
            </p>
          </div>
        </div>
      </section>
  
      <!-- Contact Content -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="animate-slide-in-left">
              <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                
                <form @submit.prevent="submitForm" class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                      <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                      <input
                        v-model="form.firstName"
                        type="text"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your first name"
                      />
                    </div>
                    <div class="form-group">
                      <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                      <input
                        v-model="form.lastName"
                        type="text"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your last name"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                      placeholder="Enter your email address"
                    />
                  </div>
  
                  <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input
                      v-model="form.phone"
                      type="tel"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                      placeholder="Enter your phone number"
                    />
                  </div>
  
                  <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select
                      v-model="form.subject"
                      required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                      <option value="">Select a subject</option>
                      <option value="admissions">Admissions Inquiry</option>
                      <option value="courses">Course Information</option>
                      <option value="fees">Fee Structure</option>
                      <option value="technical">Technical Support</option>
                      <option value="general">General Question</option>
                    </select>
                  </div>
  
                  <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea
                      v-model="form.message"
                      required
                      rows="5"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                      placeholder="Tell us how we can help you..."
                    ></textarea>
                  </div>
  
                  <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="w-full bg-blue-900 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="!isSubmitting" class="flex items-center justify-center">
                      <Send class="h-5 w-5 mr-2" />
                      Send Message
                    </span>
                    <span v-else class="flex items-center justify-center">
                      <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                      Sending...
                    </span>
                  </button>
                </form>
  
                <!-- Success Message -->
                <div v-if="showSuccess" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in">
                  <div class="flex items-center">
                    <CheckCircle class="h-5 w-5 text-green-500 mr-2" />
                    <span class="text-green-800 font-medium">Message sent successfully! We'll get back to you soon.</span>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Contact Information -->
            <div class="animate-slide-in-right">
              <div class="space-y-8">
                <!-- Contact Cards -->
                <div class="grid grid-cols-1 gap-6">
                  <div
                    v-for="(contact, index) in contactInfo"
                    :key="contact.title"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-scale-in"
                    :style="{ animationDelay: `${index * 150}ms` }"
                  >
                    <div class="flex items-start space-x-4">
                      <div class="bg-blue-100 rounded-lg p-3 flex-shrink-0">
                        <component :is="contact.icon" class="h-6 w-6 text-blue-600" />
                      </div>
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ contact.title }}</h3>
                        <div class="space-y-1">
                          <p v-for="detail in contact.details" :key="detail" class="text-gray-600">
                            {{ detail }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Office Hours -->
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-xl p-6 text-white">
                  <h3 class="text-xl font-bold mb-4 flex items-center">
                    <Clock class="h-6 w-6 mr-2" />
                    Office Hours
                  </h3>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span>Monday - Friday</span>
                      <span class="font-medium">8:00 AM - 6:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                      <span>Saturday</span>
                      <span class="font-medium">9:00 AM - 12:30 PM</span>
                    </div>
                    <div class="flex justify-between">
                      <span>Sunday</span>
                      <span class="font-medium">9:00 AM - 12:30 PM</span>
                    </div>
                  </div>
                </div>
  
                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                  <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                  <div class="space-y-3">
                    <button
                      v-for="action in quickActions"
                      :key="action.title"
                      class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                    >
                      <span class="font-medium">{{ action.title }}</span>
                      <component :is="action.icon" class="h-5 w-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Map Section -->
          <div class="mt-16">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
              <div class="p-6 bg-gray-50 border-b">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                  <MapPin class="h-6 w-6 mr-2 text-blue-600" />
                  Find Us
                </h3>
              </div>
              <div class="h-96 bg-gray-200 flex items-center justify-center">
                <div class="text-center">
                  <MapPin class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                  <p class="text-gray-600">Interactive map would be integrated here</p>
                  <p class="text-sm text-gray-500 mt-2">123 Technology Street, Innovation District</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { 
    MessageCircle, 
    Send, 
    CheckCircle, 
    Phone, 
    Mail, 
    MapPin, 
    Clock,
    Calendar,
    FileText,
    Users
  } from 'lucide-vue-next'
  
  const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
  })
  
  const isSubmitting = ref(false)
  const showSuccess = ref(false)
  
  const contactInfo = [
    {
      title: 'Phone Numbers',
      icon: 'Phone',
      details: [
        '+254 724 301 007',
        '+254 791 675 898',
      ]
    },
    {
      title: 'Email Addresses',
      icon: 'Mail',
      details: [
        'admissions@novustechhub.com',
        'info@novustechhub.com',
        'support@novustechhub.com'
      ]
    },
    {
      title: 'Physical Address',
      icon: 'MapPin',
      details: [
        '123 Technology Street',
        'Innovation District',
        'Nakuru, Kenya'
      ]
    }
  ]
  
  const quickActions = [
    { title: 'Schedule Campus Visit', icon: 'Calendar' },
    { title: 'Download Brochure', icon: 'FileText' },
    { title: 'Join Virtual Tour', icon: 'Users' }
  ]
  
  const submitForm = async () => {
    isSubmitting.value = true
    
    // Simulate form submission
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    isSubmitting.value = false
    showSuccess.value = true
    
    // Reset form
    form.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      subject: '',
      message: ''
    }
    
    // Hide success message after 5 seconds
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)
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
  
  @keyframes slide-in-left {
    from {
      opacity: 0;
      transform: translateX(-50px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes slide-in-right {
    from {
      opacity: 0;
      transform: translateX(50px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes scale-in {
    from {
      opacity: 0;
      transform: scale(0.8);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }
  
  @keyframes fade-in {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
  
  .animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
  }
  
  .animate-slide-in-left {
    animation: slide-in-left 0.6s ease-out;
  }
  
  .animate-slide-in-right {
    animation: slide-in-right 0.6s ease-out;
  }
  
  .animate-scale-in {
    animation: scale-in 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .animate-fade-in {
    animation: fade-in 0.5s ease-out;
  }
  
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }
  </style>