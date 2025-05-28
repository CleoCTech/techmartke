<template>
      <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700">
      <!-- Animated Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-32 h-32 border border-white rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute top-40 right-32 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-32 left-1/4 w-40 h-40 border border-white rounded-lg transform -rotate-6 animate-float-delayed"></div>
        <div class="absolute bottom-20 right-20 w-28 h-28 border border-white rounded-full animate-bounce-slow"></div>
        
        <!-- Floating Particles -->
        <div class="absolute top-1/4 left-1/2 w-2 h-2 bg-white rounded-full animate-float-particle"></div>
        <div class="absolute top-3/4 left-1/4 w-1 h-1 bg-white rounded-full animate-float-particle-delayed"></div>
        <div class="absolute top-1/2 right-1/4 w-3 h-3 bg-white rounded-full animate-float-particle-slow"></div>
        
        <!-- Code-like elements with typing animation -->
        <div class="absolute top-1/3 left-1/3 text-white font-mono text-sm animate-fade-in-delayed">
          <div class="animate-typing-1">&lt;innovation&gt;</div>
          <div class="ml-4 animate-typing-2">&lt;education/&gt;</div>
          <div class="animate-typing-3">&lt;/innovation&gt;</div>
        </div>
      </div>

      <!-- Animated Grid Background -->
      <div class="absolute inset-0 opacity-5">
        <div class="grid-background animate-grid-move"></div>
      </div>

      <!-- Vertical Navigation Elements with enhanced animations -->
      <div class="absolute left-6 top-1/2 transform -translate-y-1/2 z-20 hidden lg:block">
        <div class="flex flex-col space-y-8">
          <div class="transform -rotate-90 origin-center animate-slide-in-left-delayed">
            <button class="text-white/80 hover:text-white transition-all duration-500 text-sm font-medium tracking-wider hover:scale-110 hover:tracking-widest">
              APPLY NOW
            </button>
          </div>
          <div class="transform -rotate-90 origin-center animate-slide-in-left-delayed-2">
            <button class="text-white/80 hover:text-white transition-all duration-500 text-sm font-medium tracking-wider hover:scale-110 hover:tracking-widest">
              GET INVOLVED
            </button>
          </div>
        </div>
      </div>

      <!-- Right Side Vertical Text with animations -->
      <div class="absolute right-6 top-1/2 transform -translate-y-1/2 z-20 hidden lg:block">
        <div class="flex flex-col items-center space-y-4 animate-slide-in-right-delayed">
          <div class="transform rotate-90 origin-center">
            <div class="text-white/60 text-xs font-light tracking-widest animate-fade-in-up-delayed">
              NOVUS INSTITUTE
            </div>
          </div>
          <div class="w-px h-20 bg-white/30 animate-grow-height"></div>
          <div class="transform rotate-90 origin-center">
            <div class="text-white/60 text-xs font-light tracking-widest animate-fade-in-up-delayed-2">
              HOME
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content with staggered animations -->
      <div class="relative z-20 text-center px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mb-20">
        <!-- Logo/Badge with bounce-in animation -->
        <div class="mb-4 mt-4 animate-bounce-in">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 hover:bg-white/20 transition-all duration-500 hover:scale-105">
            <GraduationCap class="h-6 w-6 text-white animate-spin-slow" />
            <span class="text-white font-medium tracking-wide">NOVUS INSTITUTE OF TECHNOLOGY</span>
          </div>
        </div>

        <!-- Main Heading with typewriter effect -->
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold text-white mb-6 leading-tight">
          <span class="block animate-slide-in-up">Technology</span>
          <span class="block text-blue-200 animate-slide-in-up-delayed">Education</span>
        </h1>

        <!-- Subtitle with fade-in -->
        <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-4xl mx-auto leading-relaxed animate-fade-in-up-slow">
          Shaping Tomorrow's Technology Leaders Through Innovation, Excellence, and Practical Learning
        </p>

        <!-- Stats with counter animation -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 max-w-4xl mx-auto">
          <div class="text-center animate-scale-in-delayed">
            <div class="text-3xl font-bold text-white mb-2">
              <span ref="studentsCounter" class="counter">0</span>+
            </div>
            <div class="text-white/80 text-sm uppercase tracking-wide">Students Enrolled</div>
          </div>
          <div class="text-center animate-scale-in-delayed-2">
            <div class="text-3xl font-bold text-white mb-2">
              <span ref="programsCounter" class="counter">0</span>
            </div>
            <div class="text-white/80 text-sm uppercase tracking-wide">Programs Available</div>
          </div>
          <div class="text-center animate-scale-in-delayed-3">
            <div class="text-3xl font-bold text-white mb-2">
              <span ref="placementCounter" class="counter">0</span>%
            </div>
            <div class="text-white/80 text-sm uppercase tracking-wide">Job Placement Rate</div>
          </div>
        </div>
      </div>

      <!-- Bottom Action Cards with slide-up animation -->
      <div class="absolute bottom-0 left-0 right-0 z-20 animate-slide-in-up-bottom">
        <div class="grid grid-cols-1 md:grid-cols-2">
          <!-- Left Action Card -->
          <div class="bg-black/40 backdrop-blur-sm border-t border-white/20 p-8 hover:bg-black/60 transition-all duration-500 cursor-pointer group transform hover:scale-105">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-white/80 text-sm uppercase tracking-wide mb-2 group-hover:text-blue-200 transition-colors">EXPLORE OUR</div>
                <div class="text-white text-2xl font-bold group-hover:text-blue-200 transition-colors">
                  Course Catalog
                </div>
              </div>
              <ArrowRight class="h-6 w-6 text-white/60 group-hover:text-white group-hover:translate-x-2 transition-all duration-300" />
            </div>
          </div>

          <!-- Right Action Card -->
          <div class="bg-black/40 backdrop-blur-sm border-t border-l border-white/20 p-8 hover:bg-black/60 transition-all duration-500 cursor-pointer group transform hover:scale-105">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-white/80 text-sm uppercase tracking-wide mb-2 group-hover:text-blue-200 transition-colors">CHECK OUT</div>
                <div class="text-white text-2xl font-bold group-hover:text-blue-200 transition-colors">
                  What's Happening
                </div>
              </div>
              <Calendar class="h-6 w-6 text-white/60 group-hover:text-white group-hover:translate-x-2 transition-all duration-300" />
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 animate-bounce-gentle">
        <div class="flex flex-col items-center space-y-2 cursor-pointer hover:scale-110 transition-transform duration-300">
          <div class="text-white/60 text-xs uppercase tracking-wide animate-pulse">Scroll</div>
          <div class="relative">
            <ChevronDown class="h-4 w-4 text-white/60 animate-bounce-arrow" />
            <ChevronDown class="h-4 w-4 text-white/60 absolute top-2 animate-bounce-arrow-delayed" />
          </div>
        </div>
      </div>
    </section>
</template>

<script setup>
import { GraduationCap, Users, Award, BookOpen, Star, ArrowRight, Calendar, ChevronDown } from 'lucide-vue-next'
// No major logic needed for this hero section
import { onMounted, ref } from 'vue'
// Counter refs
const studentsCounter = ref(null)
const programsCounter = ref(null)
const placementCounter = ref(null)

// Data
const institutionValues = [
  {
    title: 'MOTTO',
    description: '"Innovation Through Education"'
  },
  {
    title: 'MISSION',
    description: 'To provide world-class technology education that empowers students to become innovative leaders in the digital age.'
  },
  {
    title: 'VISION',
    description: 'To be the premier technology institute that bridges the gap between academic excellence and industry innovation.'
  },
  {
    title: 'CORE VALUES',
    description: 'Excellence • Innovation • Integrity • Collaboration • Continuous Learning'
  }
]

const features = [
  {
    title: 'Expert Faculty',
    description: 'Learn from industry professionals and academic experts',
    icon: 'GraduationCap'
  },
  {
    title: 'Modern Curriculum',
    description: 'Stay ahead with cutting-edge technology courses',
    icon: 'BookOpen'
  },
  {
    title: 'Strong Community',
    description: 'Join a network of passionate technology enthusiasts',
    icon: 'Users'
  },
  {
    title: 'Industry Recognition',
    description: 'Accredited programs with industry partnerships',
    icon: 'Award'
  }
]

const testimonials = [
  {
    id: 1,
    content: 'The hands-on approach at Novus Institute prepared me perfectly for my career in software development.',
    name: 'Sarah Johnson',
    position: 'Software Engineer, TechCorp'
  },
  {
    id: 2,
    content: 'The faculty\'s industry experience and mentorship made all the difference in my learning journey.',
    name: 'Michael Chen',
    position: 'Data Scientist, InnovateLab'
  },
  {
    id: 3,
    content: 'The practical projects and real-world applications helped me build a strong portfolio.',
    name: 'Emily Rodriguez',
    position: 'UX Designer, DesignStudio'
  }
]

// Icon mapping function
const getIcon = (iconName) => {
  const iconMap = {
    GraduationCap,
    BookOpen,
    Users,
    Award
  }
  return iconMap[iconName] || GraduationCap
}

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

// Start animations when component mounts
onMounted(() => {
  // Delay counter animations to start after other animations
  setTimeout(() => {
    if (studentsCounter.value) animateCounter(studentsCounter.value, 50, 2000)
    if (programsCounter.value) animateCounter(programsCounter.value, 8, 1500)
    if (placementCounter.value) animateCounter(placementCounter.value, 95, 1800)
  }, 1500)
})
</script>

<style scoped>
.drop-shadow-lg {
  filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
}

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

@keyframes typing-1 {
  0% { width: 0; opacity: 0; }
  20% { width: 0; opacity: 1; }
  50% { width: 100%; opacity: 1; }
  100% { width: 100%; opacity: 1; }
}

@keyframes typing-2 {
  0% { width: 0; opacity: 0; }
  40% { width: 0; opacity: 0; }
  60% { width: 0; opacity: 1; }
  80% { width: 100%; opacity: 1; }
  100% { width: 100%; opacity: 1; }
}

@keyframes typing-3 {
  0% { width: 0; opacity: 0; }
  60% { width: 0; opacity: 0; }
  70% { width: 0; opacity: 1; }
  90% { width: 100%; opacity: 1; }
  100% { width: 100%; opacity: 1; }
}

@keyframes grid-move {
  0% { transform: translateX(0) translateY(0); }
  100% { transform: translateX(20px) translateY(20px); }
}

@keyframes slide-in-left-delayed {
  0% { transform: translateX(-100px) rotate(-90deg); opacity: 0; }
  100% { transform: translateX(0) rotate(-90deg); opacity: 1; }
}

@keyframes slide-in-left-delayed-2 {
  0% { transform: translateX(-100px) rotate(-90deg); opacity: 0; }
  100% { transform: translateX(0) rotate(-90deg); opacity: 1; }
}

@keyframes slide-in-right-delayed {
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

@keyframes slide-in-up-bottom {
  0% { transform: translateY(100%); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}

@keyframes bounce-gentle {
  0%, 100% { transform: translateY(0px) translateX(-50%); }
  50% { transform: translateY(-5px) translateX(-50%); }
}

@keyframes bounce-arrow {
  0%, 100% { transform: translateY(0px); opacity: 0.6; }
  50% { transform: translateY(3px); opacity: 1; }
}

@keyframes bounce-arrow-delayed {
  0%, 100% { transform: translateY(0px); opacity: 0.3; }
  50% { transform: translateY(3px); opacity: 0.6; }
}

@keyframes grow-height {
  0% { height: 0; }
  100% { height: 5rem; }
}

@keyframes fade-in-delayed {
  0% { opacity: 0; }
  100% { opacity: 1; }
}

@keyframes fade-in-up-delayed {
  0% { transform: translateY(20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}

@keyframes fade-in-up-delayed-2 {
  0% { transform: translateY(20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
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

.animate-typing-1 {
  animation: typing-1 4s ease-in-out;
  overflow: hidden;
  white-space: nowrap;
}

.animate-typing-2 {
  animation: typing-2 4s ease-in-out;
  overflow: hidden;
  white-space: nowrap;
}

.animate-typing-3 {
  animation: typing-3 4s ease-in-out;
  overflow: hidden;
  white-space: nowrap;
}

.animate-grid-move {
  animation: grid-move 20s linear infinite;
}

.animate-slide-in-left-delayed {
  animation: slide-in-left-delayed 1s ease-out 0.5s both;
}

.animate-slide-in-left-delayed-2 {
  animation: slide-in-left-delayed-2 1s ease-out 0.8s both;
}

.animate-slide-in-right-delayed {
  animation: slide-in-right-delayed 1s ease-out 0.6s both;
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

.animate-slide-in-up-bottom {
  animation: slide-in-up-bottom 1.5s ease-out 1.8s both;
}

.animate-bounce-gentle {
  animation: bounce-gentle 3s ease-in-out infinite;
}

.animate-bounce-arrow {
  animation: bounce-arrow 2s ease-in-out infinite;
}

.animate-bounce-arrow-delayed {
  animation: bounce-arrow-delayed 2s ease-in-out infinite;
  animation-delay: 0.3s;
}

.animate-grow-height {
  animation: grow-height 1s ease-out 0.8s both;
}

.animate-fade-in-delayed {
  animation: fade-in-delayed 2s ease-out 2s both;
}

.animate-fade-in-up-delayed {
  animation: fade-in-up-delayed 1s ease-out 1s both;
}

.animate-fade-in-up-delayed-2 {
  animation: fade-in-up-delayed-2 1s ease-out 1.3s both;
}

.animate-spin-slow {
  animation: spin-slow 8s linear infinite;
}

/* Grid Background */
.grid-background {
  background-image: 
    linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  background-size: 50px 50px;
  width: 100%;
  height: 100%;
}
</style>

