<template>

    <Head>
        <title>FAQ</title>
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
              <HelpCircle class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">FREQUENTLY ASKED QUESTIONS</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Got
              <span class="block text-blue-200">Questions?</span>
            </h1>
            
            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
              Find answers to the most common questions about Novus Institute, our programs, and admissions process.
            </p>
  
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search for answers..."
                  class="w-full px-6 py-4 pl-12 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30"
                />
                <Search class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/60" />
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- FAQ Content -->
      <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- FAQ Categories -->
          <div class="mb-12">
            <div class="flex flex-wrap justify-center gap-4">
              <button
                v-for="category in faqCategories"
                :key="category.id"
                @click="activeCategory = category.id"
                :class="[
                  'px-6 py-3 rounded-full font-medium transition-all duration-200 transform hover:scale-105',
                  activeCategory === category.id
                    ? 'bg-blue-900 text-white shadow-lg'
                    : 'bg-white text-gray-600 hover:bg-blue-50 hover:text-blue-900 shadow-md'
                ]"
              >
                <component :is="category.icon" class="h-5 w-5 inline mr-2" />
                {{ category.name }}
              </button>
            </div>
          </div>
  
          <!-- FAQ Items -->
          <div class="space-y-4">
            <div
              v-for="(faq, index) in filteredFAQs"
              :key="faq.id"
              class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 animate-slide-in-up"
              :style="{ animationDelay: `${index * 100}ms` }"
            >
              <button
                @click="toggleFAQ(faq.id)"
                class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200"
              >
                <div class="flex items-start space-x-4">
                  <div class="bg-blue-100 rounded-lg p-2 flex-shrink-0">
                    <component :is="getCategoryIcon(faq.category)" class="h-5 w-5 text-blue-600" />
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ faq.question }}</h3>
                    <p class="text-sm text-gray-500">{{ faq.category }}</p>
                  </div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <span v-if="faq.popular" class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-medium">
                    Popular
                  </span>
                  <ChevronDown 
                    :class="[
                      'h-5 w-5 text-gray-400 transition-transform duration-200',
                      openFAQs.includes(faq.id) ? 'transform rotate-180' : ''
                    ]" 
                  />
                </div>
              </button>
              
              <div
                v-if="openFAQs.includes(faq.id)"
                class="px-6 pb-6 animate-fade-in"
              >
                <div class="pl-16">
                  <div class="prose prose-blue max-w-none">
                    <p class="text-gray-600 leading-relaxed">{{ faq.answer }}</p>
                    
                    <div v-if="faq.additionalInfo" class="mt-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                      <p class="text-sm text-blue-800">{{ faq.additionalInfo }}</p>
                    </div>
                    
                    <div v-if="faq.relatedLinks" class="mt-4">
                      <p class="text-sm font-medium text-gray-700 mb-2">Related Links:</p>
                      <div class="space-y-1">
                        <a
                          v-for="link in faq.relatedLinks"
                          :key="link.title"
                          href="#"
                          class="block text-sm text-blue-600 hover:text-blue-800 hover:underline"
                        >
                          → {{ link.title }}
                        </a>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Helpful Actions -->
                  <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm text-gray-500">Was this helpful?</span>
                      <div class="flex items-center space-x-2">
                        <button
                          @click="rateFAQ(faq.id, 'helpful')"
                          :class="[
                            'p-2 rounded-lg transition-colors duration-200',
                            faq.userRating === 'helpful'
                              ? 'bg-green-100 text-green-600'
                              : 'bg-gray-100 text-gray-400 hover:bg-green-100 hover:text-green-600'
                          ]"
                        >
                          <ThumbsUp class="h-4 w-4" />
                        </button>
                        <button
                          @click="rateFAQ(faq.id, 'not-helpful')"
                          :class="[
                            'p-2 rounded-lg transition-colors duration-200',
                            faq.userRating === 'not-helpful'
                              ? 'bg-red-100 text-red-600'
                              : 'bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-600'
                          ]"
                        >
                          <ThumbsDown class="h-4 w-4" />
                        </button>
                      </div>
                    </div>
                    
                    <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                      Contact Support
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- No Results -->
          <div v-if="filteredFAQs.length === 0" class="text-center py-12">
            <Search class="h-16 w-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your search or browse by category</p>
            <button
              @click="clearSearch"
              class="bg-blue-900 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors"
            >
              Clear Search
            </button>
          </div>
  
          <!-- Contact Support Section -->
          <div class="mt-16 bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Still have questions?</h3>
            <p class="text-xl mb-8 opacity-90">
              Our support team is here to help you with any additional questions
            </p>
            <div class="space-x-4">
              <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105">
                Contact Support
              </button>
              <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105">
                Schedule Call
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { 
    HelpCircle, 
    Search, 
    ChevronDown, 
    ThumbsUp, 
    ThumbsDown,
    GraduationCap,
    DollarSign,
    BookOpen,
    Users,
    Settings,
    FileText
  } from 'lucide-vue-next'
  
  const searchQuery = ref('')
  const activeCategory = ref('all')
  const openFAQs = ref([])
  
  const faqCategories = [
    { id: 'all', name: 'All Questions', icon: 'HelpCircle' },
    { id: 'admissions', name: 'Admissions', icon: 'GraduationCap' },
    { id: 'courses', name: 'Courses', icon: 'BookOpen' },
    { id: 'fees', name: 'Fees & Payment', icon: 'DollarSign' },
    { id: 'student-life', name: 'Student Life', icon: 'Users' },
    { id: 'technical', name: 'Technical', icon: 'Settings' }
  ]
  
  const faqs = ref([
    {
      id: 1,
      category: 'admissions',
      question: 'What are the admission requirements for Novus Institute?',
      answer: 'To be eligible for admission, you need a minimum of KCSE grade C+ or equivalent qualification. We also consider relevant work experience and demonstrated interest in technology. International students need to provide proof of English proficiency.',
      additionalInfo: 'We offer foundation courses for students who don\'t meet the minimum requirements but show potential.',
      relatedLinks: [
        { title: 'Application Form', url: '/application' },
        { title: 'Admission Guidelines', url: '/admissions' }
      ],
      popular: true,
      userRating: null
    },
    {
      id: 2,
      category: 'courses',
      question: 'How long do the courses take to complete?',
      answer: 'Course duration varies by program level. Refresher courses take 3 months, Beginner courses take 6 months, and Intermediate courses take 4 months. We also offer accelerated programs for experienced students.',
      additionalInfo: 'Part-time and evening classes are available for working professionals.',
      relatedLinks: [
        { title: 'Course Catalog', url: '/courses' },
        { title: 'Schedule Information', url: '/schedule' }
      ],
      popular: true,
      userRating: null
    },
    {
      id: 3,
      category: 'fees',
      question: 'What payment options are available?',
      answer: 'We offer flexible payment plans including full payment with 5% discount, monthly installments, and scholarship opportunities. The registration fee of Sh. 3,000 is required upfront.',
      additionalInfo: 'Financial aid and work-study programs are available for qualifying students.',
      relatedLinks: [
        { title: 'Fee Structure', url: '/fees' },
        { title: 'Scholarship Information', url: '/scholarships' }
      ],
      popular: false,
      userRating: null
    },
    {
      id: 4,
      category: 'courses',
      question: 'Are the courses industry-recognized?',
      answer: 'Yes, all our courses are accredited and recognized by industry bodies. We maintain partnerships with leading tech companies and our curriculum is regularly updated to meet industry standards.',
      additionalInfo: 'Graduates receive certificates that are recognized by employers across East Africa.',
      relatedLinks: [
        { title: 'Accreditation Details', url: '/accreditation' },
        { title: 'Industry Partners', url: '/partners' }
      ],
      popular: true,
      userRating: null
    },
    {
      id: 5,
      category: 'student-life',
      question: 'What support services are available for students?',
      answer: 'We provide comprehensive support including academic counseling, career guidance, technical support, library access, and student clubs. Our learning support program includes remedial classes for students who need extra help.',
      additionalInfo: 'We also have a peer mentoring program where senior students help newcomers.',
      relatedLinks: [
        { title: 'Student Services', url: '/student-services' },
        { title: 'Campus Life', url: '/campus-life' }
      ],
      popular: false,
      userRating: null
    },
    {
      id: 6,
      category: 'technical',
      question: 'What equipment and software do I need?',
      answer: 'All necessary software is provided by the institute. Students need a laptop with minimum 8GB RAM and 256GB storage. We provide access to specialized software and cloud platforms for coursework.',
      additionalInfo: 'Laptop financing options are available through our partner vendors.',
      relatedLinks: [
        { title: 'Technical Requirements', url: '/tech-requirements' },
        { title: 'Equipment Financing', url: '/equipment-financing' }
      ],
      popular: false,
      userRating: null
    },
    {
      id: 7,
      category: 'admissions',
      question: 'When do applications open for the next intake?',
      answer: 'We have three intakes per year: January, May, and September. Applications typically open 3 months before each intake. Early applications are encouraged as spaces are limited.',
      additionalInfo: 'Late applications may be considered on a case-by-case basis depending on availability.',
      relatedLinks: [
        { title: 'Application Deadlines', url: '/deadlines' },
        { title: 'Apply Now', url: '/application' }
      ],
      popular: true,
      userRating: null
    },
    {
      id: 8,
      category: 'fees',
      question: 'Are there any hidden costs or additional fees?',
      answer: 'No, our fee structure is transparent. The quoted fees include all learning materials, software licenses, and access to facilities. The only additional cost is the one-time registration fee of Sh. 3,000.',
      additionalInfo: 'Optional services like certification exams or additional workshops may have separate fees.',
      relatedLinks: [
        { title: 'Complete Fee Breakdown', url: '/fee-breakdown' },
        { title: 'Optional Services', url: '/optional-services' }
      ],
      popular: false,
      userRating: null
    }
  ])
  
  const filteredFAQs = computed(() => {
    let filtered = faqs.value
  
    // Filter by category
    if (activeCategory.value !== 'all') {
      filtered = filtered.filter(faq => faq.category === activeCategory.value)
    }
  
    // Filter by search query
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filtered = filtered.filter(faq => 
        faq.question.toLowerCase().includes(query) ||
        faq.answer.toLowerCase().includes(query)
      )
    }
  
    return filtered
  })
  
  const toggleFAQ = (faqId) => {
    const index = openFAQs.value.indexOf(faqId)
    if (index > -1) {
      openFAQs.value.splice(index, 1)
    } else {
      openFAQs.value.push(faqId)
    }
  }
  
  const rateFAQ = (faqId, rating) => {
    const faq = faqs.value.find(f => f.id === faqId)
    if (faq) {
      faq.userRating = faq.userRating === rating ? null : rating
    }
  }
  
  const getCategoryIcon = (category) => {
    const iconMap = {
      admissions: 'GraduationCap',
      courses: 'BookOpen',
      fees: 'DollarSign',
      'student-life': 'Users',
      technical: 'Settings'
    }
    return iconMap[category] || 'HelpCircle'
  }
  
  const clearSearch = () => {
    searchQuery.value = ''
    activeCategory.value = 'all'
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
  
  @keyframes slide-in-up {
    from {
      opacity: 0;
      transform: translateY(50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
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
  
  .animate-slide-in-up {
    animation: slide-in-up 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .animate-fade-in {
    animation: fade-in 0.5s ease-out;
  }
  
  .prose {
    max-width: none;
  }
  
  .prose p {
    margin-bottom: 1rem;
  }
  </style>