<template>
    <div class="min-h-screen py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Courses</h1>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Comprehensive technology programs designed to prepare you for the future of work
          </p>
        </div>
  
        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
          <div
            v-for="course in courses"
            :key="course.id"
            class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow"
          >
            <div class="p-6">
              <div class="flex items-center space-x-3 mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                  <component :is="getIcon(course.icon)" class="h-6 w-6 text-blue-900" />
                </div>
                <div>
                  <h3 class="text-xl font-bold">{{ course.title }}</h3>
                  <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ course.level }}</span>
                </div>
              </div>
              <p class="text-gray-600 mb-4">{{ course.description }}</p>
              
              <div class="space-y-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                  <div class="flex items-center space-x-1">
                    <Clock class="h-4 w-4" />
                    <span>{{ course.duration }}</span>
                  </div>
                  <div class="flex items-center space-x-1">
                    <Users class="h-4 w-4" />
                    <span>{{ course.students }} enrolled</span>
                  </div>
                </div>
  
                <div>
                  <h4 class="font-semibold mb-2">Key Modules:</h4>
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="(module, index) in course.modules.slice(0, 3)"
                      :key="module"
                      class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs"
                    >
                      {{ module }}
                    </span>
                    <span
                      v-if="course.modules.length > 3"
                      class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs"
                    >
                      +{{ course.modules.length - 3 }} more
                    </span>
                  </div>
                </div>
  
                <div>
                  <h4 class="font-semibold mb-2">Skills You'll Learn:</h4>
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="(skill, index) in course.skills.slice(0, 4)"
                      :key="skill"
                      class="border border-gray-300 text-gray-700 px-2 py-1 rounded text-xs"
                    >
                      {{ skill }}
                    </span>
                    <span
                      v-if="course.skills.length > 4"
                      class="border border-gray-300 text-gray-700 px-2 py-1 rounded text-xs"
                    >
                      +{{ course.skills.length - 4 }} more
                    </span>
                  </div>
                </div>
  
                <a
                  href="/application"
                  class="block w-full bg-blue-900 text-white text-center py-2 rounded-lg hover:bg-blue-800 transition-colors"
                >
                  Enroll Now
                </a>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Course Features -->
        <section class="bg-gray-50 p-8 rounded-lg mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">What Makes Our Courses Special</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
              v-for="feature in courseFeatures"
              :key="feature.title"
              class="text-center"
            >
              <component :is="getIcon(feature.icon)" class="h-12 w-12 text-blue-900 mx-auto mb-4" />
              <h3 class="font-semibold mb-2">{{ feature.title }}</h3>
              <p class="text-gray-600 text-sm">{{ feature.description }}</p>
            </div>
          </div>
        </section>
  
        <!-- CTA Section -->
        <section class="text-center bg-blue-900 text-white p-8 rounded-lg">
          <h2 class="text-3xl font-bold mb-4">Ready to Start Learning?</h2>
          <p class="text-xl mb-6">Join thousands of students who have transformed their careers</p>
          <div class="space-x-4">
            <a
              href="/application"
              class="inline-block bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
            >
              Apply Now
            </a>
            <a
              href="/contact"
              class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors"
            >
              Contact Admissions
            </a>
          </div>
        </section>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Clock, Users, Award, BookOpen, Shield, Code, Database, Smartphone, Brain, Palette } from 'lucide-vue-next'
  
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
      Award
    }
    return iconMap[iconName] || Code
  }
  </script>