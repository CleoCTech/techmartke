<template>
    <Head>
        <title>About Us</title>
    </Head>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
    <!-- Hero Section with Logo -->
    <section class="relative py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 overflow-hidden">
      <!-- Animated Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-32 h-32 border border-white rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute top-40 right-32 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-32 left-1/4 w-40 h-40 border border-white rounded-lg transform -rotate-6 animate-float-delayed"></div>
        <div class="absolute bottom-20 right-20 w-28 h-28 border border-white rounded-full animate-bounce-slow"></div>
      </div>

      <!-- Floating Particles -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/2 w-2 h-2 bg-white rounded-full animate-float-particle"></div>
        <div class="absolute top-3/4 left-1/4 w-1 h-1 bg-white rounded-full animate-float-particle-delayed"></div>
        <div class="absolute top-1/2 right-1/4 w-3 h-3 bg-white rounded-full animate-float-particle-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Logo Section -->
          <div class="order-2 lg:order-1 animate-slide-in-left">
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:scale-105">
              <!-- Logo Container -->
              <div class="text-center mb-6">
                <div class="w-32 h-32 mx-auto bg-white rounded-full flex items-center justify-center shadow-2xl animate-bounce-in">
                  <GraduationCap class="h-16 w-16 text-blue-600 animate-spin-slow" />
                </div>
              </div>
              
              <!-- Institution Name -->
              <div class="text-center">
                <h1 class="text-3xl font-bold text-white mb-4 animate-fade-in-up">
                  NOVUS COMPUTER TRAINING INSTITUTE
                </h1>
                <h2 class="text-xl text-blue-200 mb-6 animate-fade-in-up-delayed">
                  OF TECHNOLOGY
                </h2>
                
                <!-- Motto -->
                <div class="bg-white/10 rounded-lg p-4 animate-scale-in-delayed">
                  <p class="text-white/90 italic text-lg">
                    "{{ companyInfo.motto }}"
                  </p>
                </div>
                
                <!-- Established Badge -->
                <div class="mt-6 inline-flex items-center space-x-2 bg-blue-500 rounded-full px-4 py-2 animate-slide-in-up">
                  <Calendar class="h-4 w-4 text-white" />
                  <span class="text-white font-medium">Established {{ companyInfo.establishment_date }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Section -->
          <div class="order-1 lg:order-2 text-white animate-slide-in-right">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
              <Info class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">ABOUT US</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight animate-slide-in-up">
              Shaping the
              <span class="block text-blue-200 animate-slide-in-up-delayed">Future of Tech</span>
            </h1>
            
            <p class="text-xl text-white/90 mb-8 leading-relaxed animate-fade-in-up-slow">
              At Novus Institute of Technology, we're not just teaching technology – we're cultivating the next generation of innovators, problem-solvers, and digital leaders who will shape tomorrow's world.
            </p>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-6 animate-scale-in-delayed">
              <div class="text-center">
                <div class="text-3xl font-bold text-white mb-2">
                  <span ref="studentsCounter" class="counter">{{ companyInfo.total_members }}</span>+
                </div>
                <div class="text-white/80 text-sm uppercase tracking-wide">Students</div>
              </div>
              <div class="text-center">
                <div class="text-3xl font-bold text-white mb-2">
                  <span ref="programsCounter" class="counter">{{ companyInfo.programs }}</span>
                </div>
                <div class="text-white/80 text-sm uppercase tracking-wide">Programs</div>
              </div>
              <div class="text-center">
                <div class="text-3xl font-bold text-white mb-2">
                  <span ref="yearsCounter" class="counter">{{ companyInfo.years }}</span>
                </div>
                <div class="text-white/80 text-sm uppercase tracking-wide">Years</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Mission, Vision, Values Section -->
    <section class="py-20 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 right-20 w-40 h-40 border border-blue-300 rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute bottom-32 left-32 w-32 h-32 border border-blue-300 rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <Target class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">OUR FOUNDATION</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Mission, Vision &
            <span class="text-blue-600">Values</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            The principles that guide everything we do at Novus Institute
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Mission -->
          <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed group">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Compass class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
              <p class="text-gray-600 leading-relaxed">
                {{ companyInfo.mission }}
              </p>
            </div>
          </div>

          <!-- Vision -->
          <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed-2 group" style="animation-delay: 200ms;">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Eye class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
              <p class="text-gray-600 leading-relaxed">
                {{ companyInfo.vision }}
              </p>
            </div>
          </div>

          <!-- Values -->
          <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed-3 group" style="animation-delay: 400ms;">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Heart class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Values</h3>
              <div  class="text-gray-600 leading-relaxed space-y-2">
                <div class="flex items-center justify-center space-x-2">
                  <Star class="h-4 w-4 text-yellow-500" />
                  <span>Excellence</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                  <Lightbulb class="h-4 w-4 text-yellow-500" />
                  <span>Innovation</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                  <Shield class="h-4 w-4 text-yellow-500" />
                  <span>Integrity</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                  <Users class="h-4 w-4 text-yellow-500" />
                  <span>Collaboration</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                  <TrendingUp class="h-4 w-4 text-yellow-500" />
                  <span>Growth</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-32 left-20 w-32 h-32 border border-white rounded-lg transform rotate-45 animate-float"></div>
        <div class="absolute bottom-20 right-32 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Content -->
          <div class="text-white animate-slide-in-left">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
              <BookOpen class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">OUR STORY</span>
            </div>
            
            <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-slide-in-up">
              A Journey of
              <span class="block text-blue-200 animate-slide-in-up-delayed">Innovation</span>
            </h2>
            
            <div class="space-y-6 text-lg text-white/90 leading-relaxed">
              <p v-html="companyInfo.about" class="animate-fade-in-up" style="animation-delay: 200ms;">
               
              </p>
              
             
            </div>

            <!-- Timeline Highlights -->
            <div class="mt-8 space-y-4 animate-slide-in-up" style="animation-delay: 800ms;">
              <!-- <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                <span class="text-white/80">2020 - Institute Founded</span>
              </div>
              <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                <span class="text-white/80">2021 - First Graduation Class</span>
              </div>
              <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                <span class="text-white/80">2023 - Industry Partnership Program</span>
              </div>
              <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                <span class="text-white/80">2024 - Innovation Hub Launch</span>
              </div> -->
            </div>
          </div>

          <!-- Image/Visual -->
          <div class="animate-slide-in-right">
            <div class="relative">
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <img 
                  src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80"
                  alt="Novus Institute Journey"
                  class="w-full h-80 object-cover rounded-xl"
                />
                <div class="absolute -bottom-4 -right-4 bg-white rounded-full p-4 shadow-lg animate-bounce-gentle">
                  <Award class="h-8 w-8 text-blue-600" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Leadership Team Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <Users class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">LEADERSHIP</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Meet Our
            <span class="text-blue-600">Leadership Team</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            {{ companyInfo.vision }}
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- <div
            v-for="(leader, index) in leadershipTeam"
            :key="leader.id"
            class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed group"
            :style="{ animationDelay: `${index * 150}ms` }"
          >
            <div class="relative h-64 overflow-hidden">
              <img 
                :src="leader.image" 
                :alt="leader.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
              
              <div class="absolute bottom-4 right-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                  <Linkedin class="h-4 w-4 text-white" />
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                  <Mail class="h-4 w-4 text-white" />
                </div>
              </div>
            </div>

            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-2">{{ leader.name }}</h3>
              <p class="text-blue-600 font-medium mb-3">{{ leader.position }}</p>
              <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ leader.bio }}</p>
              
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="skill in leader.expertise.slice(0, 2)"
                  :key="skill"
                  class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium"
                >
                  {{ skill }}
                </span>
                <span
                  v-if="leader.expertise.length > 2"
                  class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs"
                >
                  +{{ leader.expertise.length - 2 }} more
                </span>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </section>

    <!-- Achievements Section -->
    <section class="py-20 bg-gradient-to-br from-blue-900 to-purple-900 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 border border-white rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute bottom-32 right-32 w-32 h-32 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Trophy class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">ACHIEVEMENTS</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            Our
            <span class="block text-purple-200">Milestones</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up-slow">
            Recognition and achievements that reflect our commitment to excellence
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="(achievement, index) in achievements"
            :key="achievement.id"
            class="text-center animate-scale-in-delayed"
            :style="{ animationDelay: `${index * 200}ms` }"
          >
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2">
              <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <component :is="achievement.icon" class="h-8 w-8 text-white" />
              </div>
              <h3 class="text-3xl font-bold text-white mb-2">{{ achievement.value }}</h3>
              <p class="text-white/80 font-medium">{{ achievement.label }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-br from-white to-blue-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-12 text-white animate-scale-in-delayed">
          <h2 class="text-3xl md:text-4xl font-bold mb-6 animate-slide-in-up">
            Ready to Join Our Community?
          </h2>
          <p class="text-xl mb-8 opacity-90 animate-fade-in-up-slow">
            Discover how Novus Institute can help you build the future you envision
          </p>
          <div class="space-x-4 animate-slide-in-up" style="animation-delay: 400ms;">
            <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105">
              Apply Now
            </button>
            <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105">
              Learn More
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { Quote,  GraduationCap, Calendar, Info, Target, Compass, Eye, Heart, Star, 
  Lightbulb, Shield, Users, TrendingUp, BookOpen, Award, Trophy,
  Linkedin, Mail } from 'lucide-vue-next'
  import axios from 'axios'
  
  const studentsCounter = ref(null)
  const programsCounter = ref(null)
  const yearsCounter = ref(null)

  const companyInfo = ref({ total_members: 0, programs: 0, years: 0, motto: '', establishment_date: '', mission: '', vision: '', about: '', history: '', core_values: '' })
  const isLoading = ref(true)

  const testimonials = [
    {
      id: 1,
      content: 'Novus Institute transformed my understanding of technology. The hands-on projects and mentorship prepared me for real-world challenges.',
      name: 'Sarah Johnson',
      position: 'Software Engineer, TechCorp',
      rating: 5
    },
    {
      id: 2,
      content: 'The faculty\'s industry experience and the collaborative environment made learning both challenging and enjoyable.',
      name: 'Michael Chen',
      position: 'Data Scientist, InnovateLab',
      rating: 5
    },
    {
      id: 3,
      content: 'The emphasis on ethics and responsible technology development sets Novus apart from other institutions.',
      name: 'Emily Rodriguez',
      position: 'UX Designer, DesignStudio',
      rating: 5
    }
  ]
  
  const partners = [
    { name: 'Loading...' },
    { name: 'Loading...' },
    { name: 'Loading...' },
    { name: 'Loading...' },
    { name: 'Loading...' },
    { name: 'Loading...' },
  ]
  
  const awards = [
    {
      id: 1,
      title: 'Title',
      organization: 'Organization',
      year: 'Year'
    },
    {
      id: 2,
      title: 'Title',
      organization: 'Organization',
      year: 'Year'
    },
    {
      id: 3,
      title: 'Title',
      organization: 'Organization',
      year: 'Year'
    }
  ]

  // Leadership Team Data
  const leadershipTeam = [
    {
      id: 1,
      name: 'Dr. Margaret Wanjiku',
      position: 'Founder & CEO',
      image: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
      bio: 'Visionary leader with 20+ years in technology education and industry partnerships.',
      expertise: ['Educational Leadership', 'Strategic Planning', 'Innovation Management']
    },
    {
      id: 2,
      name: 'Prof. David Kimani',
      position: 'Academic Director',
      image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
      bio: 'Former MIT professor specializing in computer science and artificial intelligence research.',
      expertise: ['Computer Science', 'AI Research', 'Curriculum Development']
    },
    {
      id: 3,
      name: 'Sarah Ochieng',
      position: 'Director of Student Affairs',
      image: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
      bio: 'Passionate advocate for student success with expertise in career development and mentorship.',
      expertise: ['Student Development', 'Career Counseling', 'Community Building']
    }
  ]

  // Achievements Data
  const achievements = [
    {
      id: 1,
      icon: 'GraduationCap',
      value: '2,500+',
      label: 'Graduates'
    },
    {
      id: 2,
      icon: 'Award',
      value: '95%',
      label: 'Job Placement Rate'
    },
    {
      id: 3,
      icon: 'Users',
      value: '50+',
      label: 'Industry Partners'
    },
    {
      id: 4,
      icon: 'Trophy',
      value: '15+',
      label: 'Awards Won'
    }
  ]

  // Currency formatter for KSH
  const formatKsh = (value) => {
    if (isNaN(value)) return '';
    return 'KSH ' + Number(value).toLocaleString('en-KE', { minimumFractionDigits: 0 });
  };

  // Counter animation function
  const animateCounter = (element, target, duration = 2000) => {
    let start = 0
    const increment = target / (duration / 16)
    const timer = setInterval(() => {
      start += increment
      if (start >= target) {
        element.textContent = target
        clearInterval(timer)
      } else {
        element.textContent = Math.floor(start)
      }
    }, 16)
  }

  onMounted(async () => {
    isLoading.value = true;
    try {
      const response = await axios.get('/footer-data');
      if (response.data && response.data.companyInfo) {
        const establishmentDate = response.data.companyInfo.establishment_date;
        let years = 0;
        if (establishmentDate) {
          // Try to parse as year or date string
          const estYear = (typeof establishmentDate === 'string' && establishmentDate.length > 4)
            ? new Date(establishmentDate).getFullYear()
            : Number(establishmentDate);
          const currentYear = new Date().getFullYear();
          if (!isNaN(estYear) && estYear > 1900 && estYear <= currentYear) {
            years = currentYear - estYear;
          }
        }
        companyInfo.value = {
          total_members: Number(response.data.companyInfo.total_members) || 0,
          programs: Number(response.data.companyInfo.programs_available) || 0,
          years,
          motto: response.data.companyInfo.motto || '',
          establishment_date: establishmentDate || '',
          mission: response.data.companyInfo.mission || '',
          vision: response.data.companyInfo.vision || '',
          about: response.data.companyInfo.about || '',
          history: response.data.companyInfo.history || '',
          core_values: response.data.companyInfo.core_values || ''
        };
      }
    } catch (error) {
      companyInfo.value = { total_members: 0, programs: 0, years: 0, motto: '', establishment_date: '', mission: '', vision: '', about: '', history: '', core_values: '' };
    } finally {
      setTimeout(() => {
        if (studentsCounter.value) animateCounter(studentsCounter.value, companyInfo.value.total_members, 2000)
        if (programsCounter.value) animateCounter(programsCounter.value, companyInfo.value.programs, 1500)
        if (yearsCounter.value) animateCounter(yearsCounter.value, companyInfo.value.years, 1200)
        isLoading.value = false;
      }, 1500);
    }
  })
  </script>
  <style scoped>
  /* Enhanced Animations */
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(12deg); }
    50% { transform: translateY(-20px) rotate(12deg); }
  }
  
  @keyframes float-delayed {
    0%, 100% { transform: translateY(0px) rotate(-6deg); }
    50% { transform: translateY(-15px) rotate(-6deg); }
  }
  
  @keyframes float-particle {
    0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
    25% { transform: translateY(-10px) translateX(5px); opacity: 1; }
    50% { transform: translateY(-20px) translateX(-5px); opacity: 0.7; }
    75% { transform: translateY(-10px) translateX(10px); opacity: 1; }
  }
  
  @keyframes float-particle-delayed {
    0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.5; }
    33% { transform: translateY(-15px) translateX(-8px); opacity: 1; }
    66% { transform: translateY(-25px) translateX(8px); opacity: 0.8; }
  }
  
  @keyframes float-particle-slow {
    0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.4; }
    50% { transform: translateY(-30px) translateX(15px); opacity: 1; }
  }
  
  @keyframes pulse-slow {
    0%, 100% { opacity: 0.1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(1.05); }
  }
  
  @keyframes bounce-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }
  
  @keyframes slide-in-left {
    0% { transform: translateX(-100px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  
  @keyframes slide-in-right {
    0% { transform: translateX(100px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
  }
  
  @keyframes bounce-in {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); opacity: 0.8; }
    70% { transform: scale(0.9); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
  }
  
  @keyframes slide-in-up {
    0% { transform: translateY(100px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }
  
  @keyframes slide-in-up-delayed {
    0% { transform: translateY(100px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }
  
  @keyframes fade-in-up {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }
  
  @keyframes fade-in-up-delayed {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }
  
  @keyframes fade-in-up-slow {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }
  
  @keyframes scale-in-delayed {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  
  @keyframes scale-in-delayed-2 {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  
  @keyframes scale-in-delayed-3 {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  
  @keyframes bounce-gentle {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
  }
  
  @keyframes spin-slow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Animation Classes */
  .animate-float {
    animation: float 6s ease-in-out infinite;
  }
  
  .animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
    animation-delay: 2s;
  }
  
  .animate-float-particle {
    animation: float-particle 10s ease-in-out infinite;
  }
  
  .animate-float-particle-delayed {
    animation: float-particle-delayed 12s ease-in-out infinite;
    animation-delay: 3s;
  }
  
  .animate-float-particle-slow {
    animation: float-particle-slow 15s ease-in-out infinite;
    animation-delay: 5s;
  }
  
  .animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
  }
  
  .animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
    animation-delay: 1s;
  }
  
  .animate-slide-in-left {
    animation: slide-in-left 1s ease-out;
  }
  
  .animate-slide-in-right {
    animation: slide-in-right 1s ease-out;
  }
  
  .animate-bounce-in {
    animation: bounce-in 1.2s ease-out;
  }
  
  .animate-slide-in-up {
    animation: slide-in-up 1s ease-out;
  }
  
  .animate-slide-in-up-delayed {
    animation: slide-in-up-delayed 1s ease-out 0.3s both;
  }
  
  .animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
  }
  
  .animate-fade-in-up-delayed {
    animation: fade-in-up-delayed 1s ease-out 0.3s both;
  }
  
  .animate-fade-in-up-slow {
    animation: fade-in-up-slow 1.5s ease-out 0.6s both;
  }
  
  .animate-scale-in-delayed {
    animation: scale-in-delayed 0.8s ease-out 1s both;
  }
  
  .animate-scale-in-delayed-2 {
    animation: scale-in-delayed-2 0.8s ease-out 1.2s both;
  }
  
  .animate-scale-in-delayed-3 {
    animation: scale-in-delayed-3 0.8s ease-out 1.4s both;
  }
  
  .animate-bounce-gentle {
    animation: bounce-gentle 3s ease-in-out infinite;
  }
  
  .animate-spin-slow {
    animation: spin-slow 8s linear infinite;
  }
  </style>
  