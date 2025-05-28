<script setup>
import { ref, onMounted, reactive, watch, onUnmounted, computed } from 'vue'
import { useNotify } from "@/Composables/useNotify";
import Loader from '@/Guest/Partials/Preloader.vue';
import { SwiperSlide } from 'swiper/vue';
import xSwiper from '@/Components/Swiper.vue'
import WelcomeSection from '@/Guest/Partials/WelcomeSection.vue'
import WhatsHappeningSection from '@/Guest/Partials/WhatsHappeningSection.vue'
import CurrentSermonSeries from '@/Guest/Partials/CurrentSermonSeries.vue'
import ResourcesSection from '@/Guest/Partials/ResourcesSection.vue'
import LatestContentSection from '@/Guest/Partials/LatestContentSection.vue'
import HeroSection from '@/Guest/Partials/HeroSection.vue'

import { GraduationCap, Users, Award, BookOpen, Star, ArrowRight,  User, Lightbulb, ShieldCheck, Handshake, Globe, ArrowUpCircle, FileText, CreditCard, IdCard, Camera, Shield, Heart, MessageCircle  } from 'lucide-vue-next'

import { usePage, router } from '@inertiajs/vue3';

let { notification } = useNotify();

const isLoading = ref(true);
const { props } = usePage();
const staffs = props.staffs;
const news = props.news;
const events = props.events;
const carouselImages = props.carouselImages;
// const video = props.video;

const footerData = ref({});

const slides = ref([

]);

const expanded = ref({});
function toggleExpand(title) {
  expanded.value[title] = !expanded.value[title];
}

const expandedCore = ref({});
function toggleExpandCore(title) {
  expandedCore.value[title] = !expandedCore.value[title];
}

onMounted(async () => {
  // Delay added to ensure the DOM is rendered before initialization
  // initializeCarousel();
  // isLoading.value = true; // Set loading to true at the start
  isLoading.value = false;
  // try {
  //   //await getFooterData(); // Await getFooterData function
  // } finally {
  //   setTimeout(() => {
  //     isLoading.value = false; // Ensure loading is set to false no matter what
  //   }, 3000);
  // }

});

// Refactor fees based on categories



const randomIndex = computed(() => {
  if (footerData.value && footerData.value.galleries && footerData.value.galleries.length > 0) {
    return Math.floor(Math.random() * footerData.value.galleries.length);
  }
  return 0; // Default or safe fallback index
});

const getNavData = async () => {
  isLoading.value = true; // Set loading to true before starting the request
  try {
    const response = await axios.get('/topbar-data');
    navBarData.value = response.data;
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false; // Ensure loading is set to false no matter what
  }
};

const getFooterData = async () => {
  isLoading.value = true; // Set loading to true before starting the request
  try {
    const response = await axios.get('/footer-data');
    footerData.value = response.data;
  } catch (error) {
    console.error(error);
    notification(error, 'error');
  } finally {
    setTimeout(() => {
      isLoading.value = false; // Ensure loading is set to false no matter what
    }, 3000);
  }
};

// Data that would come from Laravel-Inertia
const heroContent = {
  title: 'Welcome to Novus Institute of Technology',
  subtitle: 'Shaping Tomorrow\'s Technology Leaders Through Innovation, Excellence, and Practical Learning'
}

const institutionValues = [
  {
    title: 'MOTTO',
    description: '"Building the Future, Byte by Byte "'
  },
  {
    title: 'MISSION',
    description: 'Our mission is to provide a transformative educational experience that nurtures creativity, fosters innovation, and promotes ethical practices in the field of computer science. We strive to cultivate a diverse and inclusive learning environment, equipping students with the skills and knowledge necessary to excel in the ever-evolving tech landscape. Through a commitment to excellence in teaching, research, and community engagement, we aim to inspire our students to make meaningful contributions to the world and to drive positive change in the digital age. '
  },
  {
    title: 'VISION',
    description: 'Our vision at Nariphon Academy is to be a leading institution in computer science and technology education, renowned for our commitment to excellence, innovation, and inclusivity. We aspire to create a dynamic learning environment that fosters creativity, encourages collaboration, and prepares students to thrive in a rapidly evolving digital world. Through cutting-edge research, industry partnerships, and community engagement, we aim to shape the future of technology, inspire the next generation of tech leaders, and make a positive impact on society.'
  },
  {
    title: 'CORE VALUES',
    description: '•	Humility • Innovation •	Excellence • Integrity • Inclusivity • Collaboration • Lifelong Learning •Responsibility • Empowerment'
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

const ctaContent = {
  title: 'Ready to Start Your Journey?',
  subtitle: 'Join thousands of students who have transformed their careers with us'
}

const coreValues = [
  {
    title: 'Humility',
    icon: User,
    description: 'Embracing a mindset of continuous learning and growth, acknowledging the contributions of others, and maintaining a genuine respect for diverse perspectives and experiences. We recognize that true progress comes from collaboration, openness, and the willingness to learn from mistakes and successes alike. By cultivating humility, we foster an environment where everyone feels valued and empowered to contribute to the collective advancement of knowledge and innovation. Humility is our powerful core value that promotes a culture of mutual respect and continuous improvement.'
  },
  {
    title: 'Innovation',
    icon: Lightbulb,
    description: 'Embracing creativity and technological advancements to drive progress and solve complex problems.'
  },
  {
    title: 'Excellence',
    icon: Star,
    description: 'Maintaining the highest standards in education, research, and professional practice.'
  },
  {
    title: 'Integrity',
    icon: ShieldCheck,
    description: 'Upholding ethical principles and honesty in all academic, research, and professional endeavors.'
  },
  {
    title: 'Inclusivity',
    icon: Users,
    description: 'Fostering a diverse and welcoming environment where everyone is respected and valued.'
  },
  {
    title: 'Collaboration',
    icon: Handshake,
    description: 'Promoting teamwork and partnership among students, faculty, industry, and the community.'
  },
  {
    title: 'Lifelong Learning',
    icon: BookOpen,
    description: 'Encouraging continuous personal and professional growth through education and development.'
  },
  {
    title: 'Responsibility',
    icon: Globe,
    description: 'Committing to the responsible use of technology for the betterment of society and the environment.'
  },
  {
    title: 'Empowerment',
    icon: ArrowUpCircle,
    description: 'Equipping students with the knowledge, skills, and confidence to succeed and lead in the tech industry.'
  }
];


// Registration Requirements Data
const registrationRequirements = [
  {
    id: 1,
    title: 'Application Form',
    description: 'Complete the admission form with accurate and verifiable student information through our online portal or physical form.',
    icon: 'FileText',
    note: 'Ensure all information is accurate and verifiable'
  },
  {
    id: 2,
    title: 'Payment Terms',
    description: 'Pay 70% of fees upon course opening, with the remaining balance due after the first month of study.',
    icon: 'CreditCard',
    note: 'Flexible payment options available'
  },
  {
    id: 3,
    title: 'Identification',
    description: 'Provide a copy of your National ID or Passport. International students can upload documents via our online application.',
    icon: 'IdCard',
    note: 'Digital uploads accepted for online applications'
  },
  {
    id: 4,
    title: 'Passport Photo',
    description: 'Submit a recent passport-size photograph for your student identification and official records.',
    icon: 'Camera',
    note: 'Professional photo recommended'
  }
]

// Computer Ethics Data
const computerEthics = [
  {
    id: 1,
    number: 'I',
    text: 'Thou shalt not use a computer to harm other people.',
    explanation: 'Technology should be used to benefit and protect others, never to cause harm or distress.'
  },
  {
    id: 2,
    number: 'II',
    text: 'Thou shalt not interfere with other people\'s computer work.',
    explanation: 'Respect others\' digital workspace and avoid disrupting their legitimate computer activities.'
  },
  {
    id: 3,
    number: 'III',
    text: 'Thou shalt not snoop around in other people\'s computer files.',
    explanation: 'Privacy is fundamental. Access only files and data that you have explicit permission to view.'
  },
  {
    id: 4,
    number: 'IV',
    text: 'Thou shalt not use a computer to steal.',
    explanation: 'Digital theft is still theft. Respect digital property and intellectual assets of others.'
  },
  {
    id: 5,
    number: 'V',
    text: 'Thou shalt not use a computer to bear false witness.',
    explanation: 'Maintain honesty and integrity in all digital communications and representations.'
  },
  {
    id: 6,
    number: 'VI',
    text: 'Thou shalt not copy or use proprietary software without permission.',
    explanation: 'Respect software licenses and intellectual property rights of developers and companies.'
  },
  {
    id: 7,
    number: 'VII',
    text: 'Thou shalt not use other people\'s computer resources without authorization.',
    explanation: 'Always obtain proper permission before using someone else\'s computing resources or network.'
  },
  {
    id: 8,
    number: 'VIII',
    text: 'Thou shalt not appropriate other people\'s intellectual output.',
    explanation: 'Give credit where due and respect the creative and intellectual work of others.'
  },
  {
    id: 9,
    number: 'IX',
    text: 'Thou shalt think about the social consequences of your programs.',
    explanation: 'Consider the broader impact of your code and systems on society and communities.'
  },
  {
    id: 10,
    number: 'X',
    text: 'Thou shalt always use computers with consideration and respect for others.',
    explanation: 'Technology should enhance human dignity and promote respectful interactions.'
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


</script>

<template>

  <Head>
    <title>Home</title>
  </Head>

  <div v-if="isLoading">
    <Loader />
  </div>

  <div v-else>

    <!-- Hero Section -->
    <!-- <div class="relative h-screen"> -->

      <HeroSection />

      <!-- Registration Requirements Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-white relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 right-10 w-32 h-32 border border-blue-300 rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 border border-blue-300 rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <FileText class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">ADMISSION PROCESS</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Registration Requirements
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            Follow these simple steps to begin your journey with Novus Institute of Technology
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="(requirement, index) in registrationRequirements"
            :key="requirement.id"
            class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed"
            :style="{ animationDelay: `${index * 200}ms` }"
          >
            <div class="text-center">
              <!-- Step Number -->
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <span class="text-white font-bold text-xl">{{ index + 1 }}</span>
              </div>
              
              <!-- Icon -->
              <div class="bg-blue-100 rounded-full p-4 w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                <component :is="requirement.icon" class="h-10 w-10 text-blue-600" />
              </div>
              
              <!-- Content -->
              <h3 class="text-xl font-bold text-gray-900 mb-4">{{ requirement.title }}</h3>
              <p class="text-gray-600 leading-relaxed">{{ requirement.description }}</p>
              
              <!-- Additional Info -->
              <div v-if="requirement.note" class="mt-4 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                <p class="text-sm text-blue-800 font-medium">{{ requirement.note }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-16">
          <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 text-white animate-slide-in-up-bottom">
            <h3 class="text-2xl font-bold mb-4">Ready to Apply?</h3>
            <p class="text-xl mb-8 opacity-90">
              Start your application process today and join our community of innovators
            </p>
            <div class="space-x-4">
             <Link href="/application">
              <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105">
                Apply Online
              </button>
             </Link> 
              <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105">
                Download Form
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Computer Ethics Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 relative overflow-hidden">
      <!-- Animated Background -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 border border-white rounded-lg transform rotate-12 animate-float"></div>
        <div class="absolute bottom-32 right-32 w-32 h-32 border border-white rounded-full animate-pulse-slow"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
          <div class="text-white font-mono text-6xl opacity-20 animate-pulse">{ }</div>
        </div>
      </div>

      <!-- Floating Code Elements -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 text-white font-mono text-sm animate-typing-1">
          <div>if (ethical) {</div>
          <div class="ml-4">code();</div>
          <div>}</div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Shield class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">CODE OF CONDUCT</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            The Ten Commandments of
            <span class="block text-blue-200">Computer Ethics</span>
          </h2>
          <p class="text-xl text-white/90 max-w-4xl mx-auto leading-relaxed animate-fade-in-up-slow">
            At Novus Institute, we believe in responsible technology use. These principles guide our students and faculty in ethical computing practices.
          </p>
        </div>

        <!-- Commandments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div
            v-for="(commandment, index) in computerEthics"
            :key="commandment.id"
            class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-1 animate-slide-in-up"
            :style="{ animationDelay: `${index * 150}ms` }"
          >
            <div class="flex items-start space-x-4">
              <!-- Roman Numeral -->
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
                  <span class="text-white font-bold text-sm">{{ commandment.number }}</span>
                </div>
              </div>
              
              <!-- Content -->
              <div class="flex-1">
                <h3 class="text-lg font-bold text-white mb-3 leading-relaxed">
                  {{ commandment.text }}
                </h3>
                <p class="text-white/80 text-sm leading-relaxed">
                  {{ commandment.explanation }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Ethics Pledge -->
        <div class="mt-16 text-center">
          <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm rounded-2xl p-8 border border-white/20 animate-scale-in-delayed">
            <h3 class="text-2xl font-bold text-white mb-4">Our Commitment</h3>
            <p class="text-white/90 text-lg leading-relaxed max-w-4xl mx-auto mb-6">
              Every student at Novus Institute pledges to uphold these ethical standards, ensuring that technology serves humanity with integrity, respect, and responsibility.
            </p>
            <div class="inline-flex items-center space-x-2 bg-white/10 rounded-full px-6 py-3 border border-white/20">
              <Heart class="h-5 w-5 text-red-400" />
              <span class="text-white font-medium">Building Ethical Tech Leaders</span>
            </div>
          </div>
        </div>
      </div>
    </section>

   

      <!-- Institution Values -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Foundation</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="value in institutionValues" :key="value.title" class="bg-white rounded-lg shadow-md p-6 text-center">
              <h3 class="text-blue-900 font-bold text-lg mb-4">{{ value.title }}</h3>
              <p class="text-gray-600">
                <span v-if="!expanded[value.title] && value.description.length > 120">
                  {{ value.description.slice(0, 120) }}...
                  <button class="text-blue-700 underline ml-1" @click="toggleExpand(value.title)">Read More</button>
                </span>
                <span v-else>
                  {{ value.description }}
                  <button v-if="value.description.length > 120" class="text-blue-700 underline ml-1" @click="toggleExpand(value.title)">Show Less</button>
                </span>
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Features -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Novus Institute?</h2>
            <p class="text-xl text-gray-600">Experience excellence in technology education</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="feature in features" :key="feature.title" class="text-center">
              <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <component :is="getIcon(feature.icon)" class="h-8 w-8 text-blue-900" />
              </div>
              <h3 class="text-xl font-semibold mb-2">{{ feature.title }}</h3>
              <p class="text-gray-600">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Preview -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Students Say</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="testimonial in testimonials" :key="testimonial.id" class="bg-white rounded-lg shadow-md p-6">
              <div class="flex items-center mb-4">
                <Star v-for="star in 5" :key="star" class="h-4 w-4 fill-yellow-400 text-yellow-400" />
              </div>
              <p class="text-gray-600 mb-4">"{{ testimonial.content }}"</p>
              <div class="font-semibold">{{ testimonial.name }}</div>
              <div class="text-sm text-gray-500">{{ testimonial.position }}</div>
            </div>
          </div>
          <div class="text-center mt-8">
            <a href="/about"
              class="inline-flex items-center border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors">
              Read More Testimonials
              <ArrowRight class="ml-2 h-4 w-4" />
            </a>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-16 bg-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 class="text-3xl font-bold mb-4">{{ ctaContent.title }}</h2>
          <p class="text-xl mb-8">{{ ctaContent.subtitle }}</p>
          <div class="space-x-4">
            <Link href="/application"
              class="inline-block bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
              Apply Now
            </Link>
            <Link href="/contact-us"
              class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors">
              Contact Us
            </Link>
          </div>
        </div>
      </section>

      <!-- Core Values -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Core Values</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="value in coreValues" :key="value.title" class="bg-white rounded-lg shadow p-6 flex flex-col">
              <div class="flex items-center mb-2">
                <component :is="value.icon" class="h-6 w-6 text-blue-700 mr-2" />
                <h4 class="font-bold text-blue-900 text-lg">{{ value.title }}</h4>
              </div>
              <p class="text-gray-700">
                <span v-if="!expandedCore[value.title] && value.description.length > 120">
                  {{ value.description.slice(0, 120) }}...
                  <button class="text-blue-700 underline ml-1" @click="toggleExpandCore(value.title)">Read More</button>
                </span>
                <span v-else>
                  {{ value.description }}
                  <button v-if="value.description.length > 120" class="text-blue-700 underline ml-1" @click="toggleExpandCore(value.title)">Show Less</button>
                </span>
              </p>
            </div>
          </div>
        </div>
      </section>
    <!-- </div> -->



  </div>


</template>

<style scoped>
.clip-hexagon {
  clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover animations */
.group:hover .transform {
  transform: translateX(0.5rem);
}

/* Side navigation animations */
.group:hover .bg-white\/90 {
  background-color: rgba(255, 255, 255, 1);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Arrow button animations */
button:hover {
  transform: translateY(-50%) scale(1.05);
}

/* Vertical line animations */
.group:hover .bg-white\/30 {
  height: 24px;
  background-color: rgba(255, 255, 255, 0.5);
}

/* Text rotation */
.text-orientation\:mixed {
  text-orientation: mixed;
}

/* Additional hover effects */
a,
button {
  transition: all 0.2s ease-in-out;
}
</style>