<template>
    <!-- Main Background with Subtle Gradient -->
    <div class="min-h-screen relative overflow-hidden">
      <!-- Animated Gradient Background -->
      <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50"></div>
      <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-blue-100/20 to-purple-100/30"></div>
      
      <!-- Subtle Glowing Orbs -->
      <div class="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-blue-200/30 to-indigo-200/30 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-l from-purple-200/25 to-pink-200/25 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-indigo-200/20 to-blue-200/20 rounded-full blur-2xl animate-pulse" style="animation-delay: 4s;"></div>
      
      <div class="relative z-10 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header with Glass Effect -->
          <div class="text-center mb-12">
            <div class="relative inline-block">
              <h1 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent mb-4">
                Our Courses
              </h1>
              <div class="absolute -inset-1 bg-gradient-to-r from-blue-200/20 to-purple-200/20 rounded-lg blur opacity-30"></div>
            </div>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
              Comprehensive technology programs designed to prepare you for the future of work
            </p>
          </div>
  
          <!-- Course Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div
              v-for="(course, index) in courses"
              :key="course.id"
              class="group relative bg-white/70 backdrop-blur-sm rounded-2xl border border-white/20 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden animate-gentle-glow"
              :style="{ animationDelay: `${index * 0.8}s` }"
            >
              <!-- Animated Glow Ring -->
              <div class="absolute -inset-1 bg-gradient-to-r from-blue-300/40 via-indigo-300/40 to-purple-300/40 rounded-2xl opacity-0 blur-sm animate-color-pulse" :style="{ animationDelay: `${index * 1.2}s` }"></div>
              
              <!-- Hover Glow Effect -->
              <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-200/50 via-indigo-200/50 to-purple-200/50 rounded-2xl opacity-0 group-hover:opacity-100 blur-sm transition-all duration-500"></div>
              
              <!-- Inner Glass Card -->
              <div class="relative bg-white/80 backdrop-blur-md rounded-2xl border border-white/40 p-6">
                <div class="flex items-center space-x-3 mb-4">
                  <div class="relative">
                    <div class="bg-gradient-to-r from-blue-100 to-indigo-100 p-4 rounded-xl shadow-sm">
                      <component :is="getIcon(course.icon)" class="h-7 w-7 text-blue-800" />
                    </div>
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-300/30 to-indigo-300/30 rounded-xl blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-800 transition-colors">{{ course.title }}</h3>
                    <span class="inline-block bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium shadow-sm">{{ course.level }}</span>
                  </div>
                </div>
                <p class="text-gray-600 mb-4 leading-relaxed">{{ course.description }}</p>
              
                <div class="space-y-4">
                  <div class="flex items-center justify-between text-sm text-gray-600 bg-gray-50/80 backdrop-blur-sm rounded-lg p-3">
                    <div class="flex items-center space-x-2">
                      <Clock class="h-4 w-4 text-blue-600" />
                      <span class="font-medium">{{ course.duration }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                      <Users class="h-4 w-4 text-green-600" />
                      <span class="font-medium">{{ course.students }} enrolled</span>
                    </div>
                  </div>

                  <div>
                    <h4 class="font-semibold mb-3 text-gray-800">Key Modules:</h4>
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="(module, index) in course.modules.slice(0, 3)"
                        :key="module"
                        class="bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm hover:shadow-md transition-shadow"
                      >
                        {{ module }}
                      </span>
                      <span
                        v-if="course.modules.length > 3"
                        class="bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm"
                      >
                        +{{ course.modules.length - 3 }} more
                      </span>
                    </div>
                  </div>

                  <div>
                    <h4 class="font-semibold mb-3 text-gray-800">Skills You'll Learn:</h4>
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="(skill, index) in course.skills.slice(0, 4)"
                        :key="skill"
                        class="bg-white/60 backdrop-blur-sm border border-gray-200/60 text-gray-700 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm hover:shadow-md hover:border-blue-300/60 transition-all"
                      >
                        {{ skill }}
                      </span>
                      <span
                        v-if="course.skills.length > 4"
                        class="bg-white/60 backdrop-blur-sm border border-gray-200/60 text-gray-700 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm"
                      >
                        +{{ course.skills.length - 4 }} more
                      </span>
                    </div>
                  </div>

                  <div class="relative pt-2">
                    <a
                      href="/application"
                      class="block w-full relative group overflow-hidden bg-gradient-to-r from-blue-900 to-indigo-900 text-white text-center py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                    >
                      <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-purple-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <span class="relative">Enroll Now</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Brochure Download Section -->
          <section class="relative mb-16">
            <div class="relative bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 p-8 rounded-3xl shadow-2xl overflow-hidden animate-subtle-pulse">
              <!-- Background Pattern -->
              <div class="absolute inset-0 bg-black/10"></div>
              <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
              
              <!-- Floating Elements -->
              <div class="absolute top-4 right-4 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
              <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/10 rounded-full blur-lg"></div>
              
              <div class="relative z-10 text-center text-white">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl mb-6">
                  <BookOpen class="h-8 w-8 text-white" />
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                  Get Our Complete Course Brochure
                </h2>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                  Download our comprehensive guide with detailed course information, curriculum, and career opportunities.
                </p>
                <a
                  href="/assets/docs/Bronchure.jpeg"
                  download="Novus_Course_Brochure.jpeg"
                  class="inline-flex items-center space-x-3 bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 group"
                >
                  <BookOpen class="h-6 w-6 group-hover:rotate-12 transition-transform duration-300" />
                  <span>Download Free Brochure</span>
                  <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </a>
              </div>
            </div>
          </section>

          <!-- Course Features -->
          <section class="relative mb-16">
            <div class="relative bg-white/60 backdrop-blur-sm p-8 rounded-3xl border border-white/20 shadow-xl">
              <!-- Subtle background gradient -->
              <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-indigo-50/50 to-purple-50/50 rounded-3xl"></div>
              
              <div class="relative z-10">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent text-center mb-12">
                  What Makes Our Courses Special
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                  <div
                    v-for="(feature, index) in courseFeatures"
                    :key="feature.title"
                    class="text-center group animate-feature-float"
                    :style="{ animationDelay: `${index * 0.5}s` }"
                  >
                    <div class="relative inline-block mb-6">
                      <div class="bg-gradient-to-br from-blue-100 to-indigo-100 p-4 rounded-2xl shadow-lg group-hover:shadow-xl transition-all duration-300">
                        <component :is="getIcon(feature.icon)" class="h-8 w-8 text-blue-800 mx-auto" />
                      </div>
                      <div class="absolute -inset-1 bg-gradient-to-r from-blue-300/30 to-indigo-300/30 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-3 group-hover:text-blue-800 transition-colors">{{ feature.title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ feature.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- CTA Section -->
          <section class="relative">
            <div class="relative bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 p-12 rounded-3xl shadow-2xl overflow-hidden animate-cta-glow">
              <!-- Background Effects -->
              <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-transparent to-purple-600/20"></div>
              <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-l from-blue-400/10 to-transparent rounded-full blur-3xl"></div>
              <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-r from-indigo-400/10 to-transparent rounded-full blur-2xl"></div>
              
              <div class="relative z-10 text-center text-white">
                <h2 class="text-4xl md:text-5xl font-bold mb-6 bg-gradient-to-r from-white via-blue-100 to-purple-100 bg-clip-text text-transparent">
                  Ready to Start Learning?
                </h2>
                <p class="text-xl md:text-2xl text-blue-100 mb-10 max-w-3xl mx-auto leading-relaxed">
                  Join thousands of students who have transformed their careers with our cutting-edge programs
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                  <a
                    href="/application"
                    class="group relative overflow-hidden bg-white text-blue-900 px-10 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
                  >
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <span class="relative">Apply Now</span>
                  </a>
                  <a
                    href="/contact"
                    class="group relative overflow-hidden border-2 border-white/80 text-white px-10 py-4 rounded-2xl font-bold text-lg backdrop-blur-sm hover:bg-white hover:text-blue-900 transition-all duration-300 hover:scale-105"
                  >
                    <span class="relative">Contact Admissions</span>
                  </a>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Clock, Users, Award, BookOpen, Shield, Code, Database, Smartphone, Brain, Palette, Download } from 'lucide-vue-next'
  
  const courses = [
    {
      id: 1,
      title: 'Software Development',
      description: 'Master full-stack development with modern frameworks and best practices',
      icon: 'Code',
      duration: '12 months',
      students: '150+',
      level: 'Beginner to Advanced',
      modules: ['Web Development', 'Mobile Apps', 'Database Design', 'DevOps', 'Testing'],
      skills: ['JavaScript', 'React', 'Node.js', 'Python', 'SQL', 'Git']
    },
    {
      id: 2,
      title: 'Data Science & Analytics',
      description: 'Learn to extract insights from data using machine learning and statistical analysis',
      icon: 'Database',
      duration: '10 months',
      students: '120+',
      level: 'Intermediate',
      modules: ['Statistics', 'Machine Learning', 'Data Visualization', 'Big Data', 'AI Ethics'],
      skills: ['Python', 'R', 'SQL', 'Tableau', 'TensorFlow', 'Pandas']
    },
    {
      id: 3,
      title: 'Cybersecurity',
      description: 'Protect digital assets and learn ethical hacking and security protocols',
      icon: 'Shield',
      duration: '8 months',
      students: '80+',
      level: 'Intermediate to Advanced',
      modules: ['Network Security', 'Ethical Hacking', 'Incident Response', 'Compliance', 'Forensics'],
      skills: ['Penetration Testing', 'Firewalls', 'Encryption', 'Risk Assessment', 'Security Auditing']
    },
    {
      id: 4,
      title: 'Mobile App Development',
      description: 'Build native and cross-platform mobile applications for iOS and Android',
      icon: 'Smartphone',
      duration: '9 months',
      students: '100+',
      level: 'Beginner to Intermediate',
      modules: ['iOS Development', 'Android Development', 'Cross-platform', 'UI/UX', 'App Store'],
      skills: ['Swift', 'Kotlin', 'React Native', 'Flutter', 'Firebase', 'API Integration']
    },
    {
      id: 5,
      title: 'Artificial Intelligence',
      description: 'Explore AI technologies including machine learning, deep learning, and neural networks',
      icon: 'Brain',
      duration: '14 months',
      students: '90+',
      level: 'Advanced',
      modules: ['Machine Learning', 'Deep Learning', 'NLP', 'Computer Vision', 'AI Ethics'],
      skills: ['Python', 'TensorFlow', 'PyTorch', 'OpenCV', 'NLTK', 'Scikit-learn']
    },
    {
      id: 6,
      title: 'UI/UX Design',
      description: 'Create intuitive and beautiful user experiences for digital products',
      icon: 'Palette',
      duration: '6 months',
      students: '70+',
      level: 'Beginner to Intermediate',
      modules: ['Design Thinking', 'User Research', 'Prototyping', 'Visual Design', 'Usability Testing'],
      skills: ['Figma', 'Adobe XD', 'Sketch', 'InVision', 'User Research', 'Wireframing']
    }
  ]
  
  const courseFeatures = [
    {
      title: 'Hands-on Learning',
      description: 'Real projects and practical exercises',
      icon: 'BookOpen'
    },
    {
      title: 'Expert Instructors',
      description: 'Industry professionals and academics',
      icon: 'Users'
    },
    {
      title: 'Industry Certification',
      description: 'Recognized credentials and certificates',
      icon: 'Award'
    },
    {
      title: 'Career Support',
      description: 'Job placement and career guidance',
      icon: 'Shield'
    }
  ]
  
  // Icon mapping function
  const getIcon = (iconName) => {
    const iconMap = {
      Code,
      Database,
      Shield,
      Smartphone,
      Brain,
      Palette,
      BookOpen,
      Users,
      Award,
      Download
    }
    return iconMap[iconName] || Code
  }
  </script>

  <style scoped>
  /* Custom animations for enhanced visual effects */
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }

  @keyframes glow {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
  }

  /* Gentle card glow animation */
  @keyframes gentle-glow {
    0%, 100% { 
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    50% { 
      box-shadow: 0 10px 25px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -2px rgba(59, 130, 246, 0.05);
    }
  }

  /* Color pulse animation for glow rings */
  @keyframes color-pulse {
    0%, 100% { 
      opacity: 0;
      transform: scale(0.95);
    }
    25% {
      opacity: 0.3;
      transform: scale(1);
    }
    50% { 
      opacity: 0.5;
      transform: scale(1.02);
    }
    75% {
      opacity: 0.2;
      transform: scale(1);
    }
  }

  /* Soft breathing animation */
  @keyframes soft-breathe {
    0%, 100% { 
      transform: scale(1);
      filter: brightness(1);
    }
    50% { 
      transform: scale(1.005);
      filter: brightness(1.02);
    }
  }

  .animate-float {
    animation: float 6s ease-in-out infinite;
  }

  .animate-glow {
    animation: glow 4s ease-in-out infinite;
  }

  .animate-gentle-glow {
    animation: gentle-glow 4s ease-in-out infinite, soft-breathe 6s ease-in-out infinite;
  }

  .animate-color-pulse {
    animation: color-pulse 5s ease-in-out infinite;
  }

  /* Subtle pulse animation for sections */
  @keyframes subtle-pulse {
    0%, 100% { 
      transform: scale(1);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    50% { 
      transform: scale(1.002);
      box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.15), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
    }
  }

  .animate-subtle-pulse {
    animation: subtle-pulse 8s ease-in-out infinite;
  }

  /* Feature float animation */
  @keyframes feature-float {
    0%, 100% { 
      transform: translateY(0px);
      opacity: 0.9;
    }
    50% { 
      transform: translateY(-4px);
      opacity: 1;
    }
  }

  .animate-feature-float {
    animation: feature-float 4s ease-in-out infinite;
  }

  /* CTA glow animation */
  @keyframes cta-glow {
    0%, 100% { 
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 0 40px rgba(99, 102, 241, 0.1);
    }
    25% {
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 0 50px rgba(59, 130, 246, 0.15);
    }
    50% { 
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 0 60px rgba(139, 92, 246, 0.2);
    }
    75% {
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 0 50px rgba(99, 102, 241, 0.15);
    }
  }

  .animate-cta-glow {
    animation: cta-glow 6s ease-in-out infinite;
  }

  /* Enhance backdrop blur support */
  .backdrop-blur-3xl {
    backdrop-filter: blur(64px);
  }

  /* Custom gradient text animation */
  @keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }

  .animate-gradient {
    background-size: 200% 200%;
    animation: gradient-shift 8s ease infinite;
  }

  /* Enhanced glass morphism */
  .glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  /* Custom hover effects */
  .hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .hover-lift:hover {
    transform: translateY(-8px) scale(1.02);
  }
  </style>