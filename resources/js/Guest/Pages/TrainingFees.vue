<template>
    <head>
        <title>Training Fees</title>
    </head>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
      <!-- Header Section -->
      <section class="relative py-20 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-10 left-10 w-20 h-20 border border-white rounded-full animate-pulse"></div>
          <div class="absolute top-32 right-20 w-16 h-16 border border-white rounded-lg transform rotate-45 animate-bounce"></div>
          <div class="absolute bottom-20 left-1/4 w-24 h-24 border border-white rounded-full animate-ping"></div>
          <div class="absolute bottom-10 right-10 w-18 h-18 border border-white rounded-lg animate-pulse"></div>
        </div>
  
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div class="animate-fade-in-up">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6">
              <DollarSign class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">TRAINING FEES STRUCTURE 2025</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Affordable Excellence in
              <span class="block text-blue-200">Technology Education</span>
            </h1>
            
            <p class="text-xl text-white/90 max-w-4xl mx-auto leading-relaxed mb-8">
              Novus Institute provides a world-class learning experience with equal opportunities and the best quality skills for everyone interested in computing at affordable prices with flexible payment terms.
            </p>
  
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
              <div class="flex items-center space-x-2 text-white/80">
                <CheckCircle class="h-5 w-5 text-green-400" />
                <span>Flexible Payment Terms</span>
              </div>
              <div class="flex items-center space-x-2 text-white/80">
                <CheckCircle class="h-5 w-5 text-green-400" />
                <span>World-Class Learning Experience</span>
              </div>
              <div class="flex items-center space-x-2 text-white/80">
                <CheckCircle class="h-5 w-5 text-green-400" />
                <span>Affordable Pricing</span>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Fee Structure Content -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Registration Fee Highlight -->
          <div class="text-center mb-16">
            <div class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300 animate-bounce-in">
              <div class="flex items-center space-x-3">
                <CreditCard class="h-6 w-6" />
                <div>
                  <div class="text-sm font-medium opacity-90">One-Time Registration Fee</div>
                  <div class="text-2xl font-bold">Sh. 3,000</div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Module Cards -->
          <div class="space-y-12">
            <div
              v-for="(module, index) in modules"
              :key="module.id"
              class="animate-slide-in-left"
              :style="{ animationDelay: `${index * 200}ms` }"
            >
              <!-- Module Header -->
              <div class="text-center mb-8">
                <div class="inline-flex items-center space-x-3 bg-blue-900 text-white px-6 py-3 rounded-full shadow-lg">
                  <BookOpen class="h-5 w-5" />
                  <span class="font-bold text-lg">{{ module.title }}</span>
                </div>
              </div>
  
              <!-- Fee Table -->
              <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                  <h3 class="text-white font-bold text-xl text-center">Course Fees</h3>
                </div>
                
                <div class="overflow-x-auto">
                  <table class="w-full">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">
                          Course Type
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900 uppercase tracking-wider">
                          Total Fees
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 uppercase tracking-wider">
                          Duration
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                      <tr
                        v-for="(course, courseIndex) in module.courses"
                        :key="course.type"
                        class="hover:bg-blue-50 transition-colors duration-200 animate-fade-in"
                        :style="{ animationDelay: `${(index * 200) + (courseIndex * 100)}ms` }"
                      >
                        <td class="px-6 py-4">
                          <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                              <div class="w-3 h-3 rounded-full" :class="getCourseColor(course.type)"></div>
                            </div>
                            <div class="font-medium text-gray-900">{{ course.type }}</div>
                          </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                          <div class="text-2xl font-bold text-blue-900">
                            Sh. {{ course.fee.toLocaleString() }}
                          </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <Clock class="h-4 w-4 mr-1" />
                            {{ course.duration }}
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Additional Information -->
          <div class="mt-16 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
            <div class="text-center mb-6">
              <Info class="h-8 w-8 text-blue-600 mx-auto mb-3" />
              <h3 class="text-2xl font-bold text-gray-900 mb-2">Important Information</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-start space-x-3">
                  <GraduationCap class="h-6 w-6 text-blue-600 mt-1" />
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Learning Support Program</h4>
                    <p class="text-gray-600 text-sm">
                      Training fees include comprehensive learning support programs with remedial classes for primary and high school students.
                    </p>
                  </div>
                </div>
              </div>
              
              <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-start space-x-3">
                  <HelpCircle class="h-6 w-6 text-blue-600 mt-1" />
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Questions & Support</h4>
                    <p class="text-gray-600 text-sm">
                      We are always available if you have any questions or wish to discuss any aspect of the finances.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Payment Options -->
          <div class="mt-16">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Flexible Payment Options</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div
                v-for="(option, index) in paymentOptions"
                :key="option.title"
                class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in"
                :style="{ animationDelay: `${index * 150}ms` }"
              >
                <div class="text-center">
                  <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <component :is="option.icon" class="h-8 w-8 text-blue-600" />
                  </div>
                  <h4 class="text-xl font-bold text-gray-900 mb-3">{{ option.title }}</h4>
                  <p class="text-gray-600 mb-4">{{ option.description }}</p>
                  <div class="text-2xl font-bold text-blue-900">{{ option.benefit }}</div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- CTA Section -->
          <div class="mt-16 text-center">
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 text-white">
              <h3 class="text-3xl font-bold mb-4">Ready to Begin Your Journey?</h3>
              <p class="text-xl mb-8 opacity-90">
                Contact our admissions team to discuss payment plans and enrollment options
              </p>
              <div class="space-x-4">
                <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105">
                  Apply Now
                </button>
                <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105">
                  Contact Admissions
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { 
    DollarSign, 
    CheckCircle, 
    CreditCard, 
    BookOpen, 
    Clock, 
    Info, 
    GraduationCap, 
    HelpCircle,
    Calendar,
    Users,
    Zap
  } from 'lucide-vue-next'
  
  const modules = [
    {
      id: 1,
      title: 'MODULE I',
      courses: [
        { type: 'Refresher Course', fee: 12775, duration: '3 months' },
        { type: 'Beginner Course', fee: 17845, duration: '6 months' },
        { type: 'Intermediate Course', fee: 9850, duration: '4 months' }
      ]
    },
    {
      id: 2,
      title: 'MODULE II',
      courses: [
        { type: 'Refresher Course', fee: 17645, duration: '3 months' },
        { type: 'Beginner Course', fee: 22945, duration: '6 months' },
        { type: 'Intermediate Course', fee: 13845, duration: '4 months' }
      ]
    },
    {
      id: 3,
      title: 'MODULE III',
      courses: [
        { type: 'Refresher Course', fee: 27775, duration: '3 months' },
        { type: 'Beginner Course', fee: 36845, duration: '6 months' },
        { type: 'Intermediate Course', fee: 23850, duration: '4 months' }
      ]
    }
  ]
  
  const paymentOptions = [
    {
      title: 'Full Payment',
      description: 'Pay the complete fee upfront and save on processing',
      benefit: '5% Discount',
      icon: 'DollarSign'
    },
    {
      title: 'Installments',
      description: 'Split your payment into manageable monthly installments',
      benefit: 'Flexible Terms',
      icon: 'Calendar'
    },
    {
      title: 'Scholarship',
      description: 'Apply for merit-based scholarships and financial aid',
      benefit: 'Up to 30% Off',
      icon: 'Users'
    }
  ]
  
  const getCourseColor = (courseType) => {
    const colors = {
      'Refresher Course': 'bg-green-400',
      'Beginner Course': 'bg-blue-400',
      'Intermediate Course': 'bg-purple-400'
    }
    return colors[courseType] || 'bg-gray-400'
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
  
  @keyframes bounce-in {
    0% {
      opacity: 0;
      transform: scale(0.3);
    }
    50% {
      transform: scale(1.05);
    }
    70% {
      transform: scale(0.9);
    }
    100% {
      opacity: 1;
      transform: scale(1);
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
    animation-fill-mode: both;
  }
  
  .animate-bounce-in {
    animation: bounce-in 0.8s ease-out;
  }
  
  .animate-scale-in {
    animation: scale-in 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .animate-fade-in {
    animation: fade-in 0.5s ease-out;
    animation-fill-mode: both;
  }
  </style>