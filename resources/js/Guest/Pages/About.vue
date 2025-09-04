<template>
  <Head>
    <title>About Us - Novus Institute</title>
  </Head>
  
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
    <!-- Hero Section -->
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
                    "Coding the Future Today, Byte by Byte"
                  </p>
                </div>
                
                <!-- Contact Info -->
                <div class="mt-6 space-y-2 text-white/80 text-sm">
                  <div class="flex items-center justify-center space-x-2">
                    <Phone class="h-4 w-4" />
                    <span>0724 301 007 / 0734 722 381</span>
                  </div>
                  <div class="flex items-center justify-center space-x-2">
                    <Mail class="h-4 w-4" />
                    <span>novustechhub@gmail.com</span>
                  </div>
                  <div class="flex items-center justify-center space-x-2">
                    <Globe class="h-4 w-4" />
                    <span>www.novustechhub.com</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Section -->
          <div class="order-1 lg:order-2 text-white animate-slide-in-right">
            <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
              <Info class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">ABOUT NOVUS</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight animate-slide-in-up">
              Shaping the
              <span class="block text-blue-200 animate-slide-in-up-delayed">Future of Tech</span>
            </h1>
            
            <p class="text-xl text-white/90 mb-8 leading-relaxed animate-fade-in-up-slow">
              At Novus Institute of Technology, we're not just teaching technology – we're cultivating the next generation of innovators, problem-solvers, and digital leaders who will shape tomorrow's world.
            </p>
            
            <!-- Download Link -->
            <div class="animate-scale-in-delayed">
              <a 
                href="/assets/docs/ABOUT NOVUS.pdf" 
                download="ABOUT_NOVUS_Institute.pdf"
                target="_blank"
                class="inline-flex items-center space-x-3 bg-white text-blue-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg"
              >
                <Download class="h-5 w-5" />
                <span>Download About Novus PDF</span>
              </a>
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
              <p class="text-gray-600 leading-relaxed text-sm">
                Our mission is to provide a transformative educational experience that nurtures creativity, fosters innovation, and promotes ethical practices in the field of computer science. We strive to cultivate a diverse and inclusive learning environment, equipping students with the skills and knowledge necessary to excel in the ever-evolving tech landscape. Through a commitment to excellence in teaching, research, and community engagement, we aim to inspire our students to make meaningful contributions to the world and to drive positive change in the digital age.
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
              <p class="text-gray-600 leading-relaxed text-sm">
                Our vision at NOVUS COMPUTER TECHNOLOGY TRAINING INSTITUTE is to be a leading institution in computer science and technology education, renowned for our commitment to excellence, innovation, and inclusivity. We aspire to create a dynamic learning environment that fosters creativity, encourages collaboration, and prepares students to thrive in a rapidly evolving digital world. Through cutting-edge research, industry partnerships, and community engagement, we aim to shape the future of technology, inspire the next generation of tech leaders, and make a positive impact on society.
              </p>
            </div>
          </div>

          <!-- Values -->
          <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed-3 group" style="animation-delay: 400ms;">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Heart class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Core Values</h3>
              <div class="text-gray-600 leading-relaxed space-y-3 text-sm">
                <div class="flex items-start space-x-3 text-left">
                  <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <Star class="h-3 w-3 text-yellow-600" />
                  </div>
                  <div>
                    <span class="font-semibold text-gray-800">Humility:</span>
                    <span class="text-gray-600"> Embracing continuous learning and growth, acknowledging others' contributions.</span>
                  </div>
                </div>
                <div class="flex items-start space-x-3 text-left">
                  <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <Lightbulb class="h-3 w-3 text-blue-600" />
                  </div>
                  <div>
                    <span class="font-semibold text-gray-800">Innovation:</span>
                    <span class="text-gray-600"> Embracing creativity and technological advancements.</span>
                  </div>
                </div>
                <div class="flex items-start space-x-3 text-left">
                  <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <Shield class="h-3 w-3 text-green-600" />
                  </div>
                  <div>
                    <span class="font-semibold text-gray-800">Integrity:</span>
                    <span class="text-gray-600"> Upholding ethical principles and honesty.</span>
                  </div>
                </div>
                <div class="flex items-start space-x-3 text-left">
                  <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <Users class="h-3 w-3 text-purple-600" />
                  </div>
                  <div>
                    <span class="font-semibold text-gray-800">Collaboration:</span>
                    <span class="text-gray-600"> Promoting teamwork and partnership.</span>
                  </div>
                </div>
                <div class="flex items-start space-x-3 text-left">
                  <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <TrendingUp class="h-3 w-3 text-red-600" />
                  </div>
                  <div>
                    <span class="font-semibold text-gray-800">Lifelong Learning:</span>
                    <span class="text-gray-600"> Encouraging continuous growth.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Core Functions Section -->
    <section class="py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-32 left-20 w-32 h-32 border border-white rounded-lg transform rotate-45 animate-float"></div>
        <div class="absolute bottom-20 right-32 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Settings class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">CORE FUNCTIONS</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            What We
            <span class="block text-blue-200 animate-slide-in-up-delayed">Do</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up-slow">
            Our three core functions that drive our institution forward
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Teaching and Learning -->
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed group">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <GraduationCap class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Teaching & Learning</h3>
              <p class="text-white/90 leading-relaxed">
                The institution offers innovative, relevant and market-driven academic programs, at all levels. We provide cutting-edge education that prepares students for real-world challenges.
              </p>
            </div>
          </div>

          <!-- Research -->
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed-2 group" style="animation-delay: 200ms;">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Search class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Research</h3>
              <p class="text-white/90 leading-relaxed">
                The institution provides a conducive environment for quality research that contributes to the development of society through the generation, preservation, dissemination and application of knowledge.
              </p>
            </div>
          </div>

          <!-- Community Service -->
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed-3 group" style="animation-delay: 400ms;">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <Heart class="h-10 w-10 text-white" />
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Community Service</h3>
              <p class="text-white/90 leading-relaxed">
                The institution engages in community programs and activities as part of its corporate social responsibility, making a positive impact on society.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Additional Values Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <Award class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">ADDITIONAL VALUES</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            More Core
            <span class="text-blue-600">Values</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            Additional principles that guide our institution
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="(value, index) in additionalValues" 
            :key="value.id"
            class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed group"
            :style="{ animationDelay: `${index * 100}ms` }"
          >
            <div class="text-center">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <component :is="value.icon" class="h-8 w-8 text-white" />
              </div>
              <h3 class="text-lg font-bold text-gray-900 mb-2">{{ value.title }}</h3>
              <p class="text-gray-600 text-sm">{{ value.description }}</p>
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
            <a 
              href="/application" 
              class="inline-block bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105"
            >
              Apply Now
            </a>
            <a 
              href="/assets/docs/ABOUT NOVUS.pdf" 
              download="ABOUT_NOVUS_Institute.pdf"
              target="_blank"
              class="inline-flex items-center space-x-2 border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105"
            >
              <Download class="h-5 w-5" />
              <span>Download About PDF</span>
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  GraduationCap, Calendar, Info, Target, Compass, Eye, Heart, Star, 
  Lightbulb, Shield, Users, TrendingUp, BookOpen, Award, Trophy,
  Linkedin, Mail, Phone, Globe, Download, Settings, Search
} from 'lucide-vue-next'

// Additional Values Data
const additionalValues = [
  {
    id: 1,
    icon: 'Users',
    title: 'Inclusivity & Freedom of Thoughts',
    description: 'Fostering a diverse and welcoming environment where everyone is respected and valued.'
  },
  {
    id: 2,
    icon: 'TrendingUp',
    title: 'Lifelong Learning',
    description: 'Encouraging continuous personal and professional growth through education and development.'
  },
  {
    id: 3,
    icon: 'Shield',
    title: 'Responsibility',
    description: 'Committing to the responsible use of technology for the betterment of society and the environment.'
  },
  {
    id: 4,
    icon: 'Award',
    title: 'Empowerment',
    description: 'Equipping students with the knowledge, skills, and confidence to succeed and lead in the tech industry.'
  }
]
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
  