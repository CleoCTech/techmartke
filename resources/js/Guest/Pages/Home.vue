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

import { GraduationCap, Users, Award, BookOpen, Star, ArrowRight, User, Lightbulb, ShieldCheck, Handshake, Globe, ArrowUpCircle, FileText, CreditCard, IdCard, Camera, Shield, Heart, MessageCircle } from 'lucide-vue-next'
import { Play, Pause, ChevronLeft, ChevronRight, ExternalLink, MapPin, Calendar as CalendarIcon, Clock as ClockIcon } from 'lucide-vue-next'

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

const downloadAttachment = (uuid) => {
  //system/attachment/show/${attachment.uuid}
  window.open(`/system/attachment/show/${uuid}`, '_blank');
};

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
  // Start auto-play for sliders
  setTimeout(() => {
    startAutoPlay('campus', 6000)
    startAutoPlay('success', 8000)
    startAutoPlay('course', 7000)
    startAutoPlay('faculty', 9000)
    startAutoPlay('event', 10000)
  }, 3000)

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

const testimonials = computed(() => props.testimonials ?? []);
const campusGallery = computed(() => props.campusGallery ?? []);
const successStories = computed(() => props.successStories ?? []);
const coursePreview = computed(() => props.coursePreview ?? []);
const facultySpotlight = computed(() => props.facultySpotlight ?? []);
const upcomingEventsSlider = computed(() => props.upcomingEventsSlider ?? []);
const videoGallery = computed(() => props.videoGallery ?? []);
const firstAttachment = computed(() => props.firstAttachment ?? []);
// Counter refs
const studentsCounter = ref(null)
const programsCounter = ref(null)
const placementCounter = ref(null)

// Slider states
const currentCampusSlide = ref(0)
const currentSuccessSlide = ref(0)
const currentCourseSlide = ref(0)
const currentFacultySlide = ref(0)
const currentEventSlide = ref(0)
const currentVideoSlide = ref(0)

// Auto-play intervals
const campusInterval = ref(null)
const successInterval = ref(null)
const courseInterval = ref(null)
const facultyInterval = ref(null)
const eventInterval = ref(null)

// Video states
const playingVideo = ref(null)
// Slider Functions
const nextSlide = (sliderType) => {
  switch (sliderType) {
    case 'campus':
      currentCampusSlide.value = (currentCampusSlide.value + 1) % campusGallery.length
      break
    case 'success':
      currentSuccessSlide.value = (currentSuccessSlide.value + 1) % successStories.length
      break
    case 'course':
      currentCourseSlide.value = (currentCourseSlide.value + 1) % coursePreview.length
      break
    case 'faculty':
      currentFacultySlide.value = (currentFacultySlide.value + 1) % facultySpotlight.length
      break
    case 'event':
      currentEventSlide.value = (currentEventSlide.value + 1) % upcomingEventsSlider.length
      break
    case 'video':
      currentVideoSlide.value = (currentVideoSlide.value + 1) % videoGallery.length
      break
  }
}

const prevSlide = (sliderType) => {
  switch (sliderType) {
    case 'campus':
      currentCampusSlide.value = currentCampusSlide.value === 0 ? campusGallery.length - 1 : currentCampusSlide.value - 1
      break
    case 'success':
      currentSuccessSlide.value = currentSuccessSlide.value === 0 ? successStories.length - 1 : currentSuccessSlide.value - 1
      break
    case 'course':
      currentCourseSlide.value = currentCourseSlide.value === 0 ? coursePreview.length - 1 : currentCourseSlide.value - 1
      break
    case 'faculty':
      currentFacultySlide.value = currentFacultySlide.value === 0 ? facultySpotlight.length - 1 : currentFacultySlide.value - 1
      break
    case 'event':
      currentEventSlide.value = currentEventSlide.value === 0 ? upcomingEventsSlider.length - 1 : currentEventSlide.value - 1
      break
    case 'video':
      currentVideoSlide.value = currentVideoSlide.value === 0 ? videoGallery.length - 1 : currentVideoSlide.value - 1
      break
  }
}

const goToSlide = (sliderType, index) => {
  switch (sliderType) {
    case 'campus':
      currentCampusSlide.value = index
      break
    case 'success':
      currentSuccessSlide.value = index
      break
    case 'course':
      currentCourseSlide.value = index
      break
    case 'faculty':
      currentFacultySlide.value = index
      break
    case 'event':
      currentEventSlide.value = index
      break
    case 'video':
      currentVideoSlide.value = index
      break
  }
}

const startAutoPlay = (sliderType, interval = 5000) => {
  const timer = setInterval(() => nextSlide(sliderType), interval)

  switch (sliderType) {
    case 'campus':
      campusInterval.value = timer
      break
    case 'success':
      successInterval.value = timer
      break
    case 'course':
      courseInterval.value = timer
      break
    case 'faculty':
      facultyInterval.value = timer
      break
    case 'event':
      eventInterval.value = timer
      break
  }
}

const stopAutoPlay = (sliderType) => {
  switch (sliderType) {
    case 'campus':
      if (campusInterval.value) clearInterval(campusInterval.value)
      break
    case 'success':
      if (successInterval.value) clearInterval(successInterval.value)
      break
    case 'course':
      if (courseInterval.value) clearInterval(courseInterval.value)
      break
    case 'faculty':
      if (facultyInterval.value) clearInterval(facultyInterval.value)
      break
    case 'event':
      if (eventInterval.value) clearInterval(eventInterval.value)
      break
  }
}

const playVideo = (videoId) => {
  playingVideo.value = videoId
}

const pauseVideo = () => {
  playingVideo.value = null
}

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
    Award,
    FileText,
    CreditCard,
    IdCard,
    Camera
  };
  return iconMap[iconName] || GraduationCap;
};

const galleryImagePath = (cover_image) => `/storage/gallery/cover_images/${cover_image}`;

const mappedCampusGallery = computed(() =>
  campusGallery.value.map(item => ({
    id: item.id,
    image: galleryImagePath(item.cover_image),
    title: item.title,
    description: item.description,
    category: item.category ?? '',
    // add other fields if needed
  }))
);

const successStoryImagePath = (cover_image) => `/storage/success_stories/cover_images/${cover_image}`;

const mappedSuccessStories = computed(() =>
  successStories.value.map(item => ({
    id: item.id,
    name: item.student_name,
    position: item.course, // or use another field if needed
    image: successStoryImagePath(item.cover_image),
    story: item.description,
    achievement: item.achievement,
    year: item.graduation_year,
    company: item.course, // or use another field if needed
    // add other fields if needed
  }))
);

const courseImagePath = (image) => `/storage/courses/cover_images/${image}`;

const mappedCoursePreview = computed(() =>
  coursePreview.value.map(item => ({
    id: item.id,
    title: item.title,
    image: courseImagePath(item.image),
    duration: item.duration,
    level: item.level,
    description: item.description,
    projects: item.projects,
    students: item.students,
    technologies: Array.isArray(item.technologies) ? item.technologies : (typeof item.technologies === 'string' ? JSON.parse(item.technologies) : []),
    // add other fields if needed
  }))
);

const facultyImagePath = (cover_image) => `/storage/staff/cover_images/${cover_image}`;

const mappedFacultySpotlight = computed(() =>
  facultySpotlight.value.map(item => ({
    id: item.id,
    name: item.name,
    position: item.title,
    image: facultyImagePath(item.cover_image),
    expertise: Array.isArray(item.expertise) ? item.expertise : (typeof item.expertise === 'string' ? JSON.parse(item.expertise) : []),
    experience: item.experience,
    education: item.education,
    achievements: item.achievements,
    quote: item.quote,
    // add other fields if needed
  }))
);

const eventImagePath = (cover_image) => `/storage/event/cover_images/${cover_image}`;

const mappedUpcomingEventsSlider = computed(() =>
  upcomingEventsSlider.value.map(item => ({
    id: item.id,
    title: item.title,
    date: item.start_date, // or combine with end_date if needed
    time: item.start_time && item.end_time ? `${item.start_time} - ${item.end_time}` : item.start_time || '',
    location: item.location,
    image: eventImagePath(item.cover_image),
    type: item.type,
    speakers: Array.isArray(item.speakers) ? item.speakers : (typeof item.speakers === 'string' ? JSON.parse(item.speakers) : []),
    attendees: item.attendees,
    price: item.price,
    description: item.description,
    // add other fields if needed
  }))
);

const videoThumbnailPath = (cover_image) => `thumbnails/${cover_image}`;
const videoFilePath = (video_path) => `/storage/videos/${video_path}`;

const mappedVideoGallery = computed(() =>
  videoGallery.value.map(item => ({
    id: item.id,
    title: item.title,
    description: item.description,
    thumbnail: videoThumbnailPath(item.cover_image),
    video: videoFilePath(item.video_path),
    duration: item.duration,
    category: item.category,
    views: item.views,
    // add other fields if needed
  }))
);

const mappedTestimonials = computed(() =>
  testimonials.value.map(item => ({
    id: item.id,
    name: item.name,
    content: item.feedback,
    rating: item.rating,
    // add other fields if needed
  }))
);

// Currency formatter for KSH
const formatKsh = (value) => {
  if (isNaN(value)) return '';
  return 'KSH ' + Number(value).toLocaleString('en-KE', { minimumFractionDigits: 0 });
};

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

    <!-- Campus Gallery Section -->
    <section class="py-20 bg-gradient-to-br from-white to-blue-50 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-5">
        <div
          class="absolute top-20 right-20 w-40 h-40 border border-blue-300 rounded-lg transform rotate-12 animate-float">
        </div>
        <div class="absolute bottom-32 left-32 w-32 h-32 border border-blue-300 rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <Camera class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">CAMPUS LIFE</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Experience Our
            <span class="text-blue-600">Modern Campus</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            Discover state-of-the-art facilities, collaborative spaces, and an inspiring environment designed for
            innovation
          </p>
        </div>

        <!-- Campus Slider -->
        <div class="relative">
          <div class="overflow-hidden rounded-2xl shadow-2xl" @mouseenter="stopAutoPlay('campus')"
            @mouseleave="startAutoPlay('campus', 6000)">
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${currentCampusSlide * 100}%)` }">
              <div v-for="slide in mappedCampusGallery" :key="slide.id" class="w-full flex-shrink-0 relative">
                <div class="relative h-96 md:h-[500px]">
                  <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                  <!-- Content Overlay -->
                  <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <div class="max-w-4xl mx-auto">
                      <div class="flex items-center space-x-3 mb-4">
                        <span class="bg-blue-500 px-3 py-1 rounded-full text-sm font-medium">
                          {{ slide.category }}
                        </span>
                      </div>
                      <h3 class="text-3xl md:text-4xl font-bold mb-4">{{ slide.title }}</h3>
                      <p class="text-xl text-white/90 max-w-2xl">{{ slide.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Arrows -->
          <button @click="prevSlide('campus')"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 hover:scale-110">
            <ChevronLeft class="h-6 w-6" />
          </button>
          <button @click="nextSlide('campus')"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 hover:scale-110">
            <ChevronRight class="h-6 w-6" />
          </button>

          <!-- Dots Indicator -->
          <div class="flex justify-center space-x-2 mt-8">
            <button v-for="(slide, index) in mappedCampusGallery" :key="slide.id" @click="goToSlide('campus', index)"
              :class="[
                'w-3 h-3 rounded-full transition-all duration-300',
                currentCampusSlide === index
                  ? 'bg-blue-600 w-8'
                  : 'bg-gray-300 hover:bg-gray-400'
              ]"></button>
          </div>
        </div>
      </div>
    </section>

    <!-- Success Stories Section -->
    <section class="py-20 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-32 left-20 w-32 h-32 border border-white rounded-lg transform rotate-45 animate-float">
        </div>
        <div class="absolute bottom-20 right-32 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div
            class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Star class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">SUCCESS STORIES</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            Our Alumni
            <span class="block text-blue-200">Making Impact</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up-slow">
            Discover how our graduates are leading innovation and driving change in the tech industry
          </p>
        </div>

        <!-- Success Stories Slider -->
        <div class="relative">
          <div class="overflow-hidden" @mouseenter="stopAutoPlay('success')"
            @mouseleave="startAutoPlay('success', 8000)">
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${currentSuccessSlide * 100}%)` }">
              <div v-for="story in mappedSuccessStories" :key="story.id" class="w-full flex-shrink-0">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Image -->
                    <div class="relative">
                      <div class="aspect-square rounded-2xl overflow-hidden">
                        <img :src="story.image" :alt="story.name" class="w-full h-full object-cover" />
                      </div>
                      <div class="absolute -bottom-4 -right-4 bg-white rounded-full p-4 shadow-lg">
                        <!-- <img 
                          :src="`/placeholder.svg?height=60&width=120&text=${story.company}`" 
                          :alt="story.company"
                          class="h-8 w-16 object-contain"
                        /> -->
                      </div>
                    </div>

                    <!-- Content -->
                    <div class="text-white">
                      <div class="mb-4">
                        <span class="bg-blue-500 px-3 py-1 rounded-full text-sm font-medium">
                          {{ story.year }}
                        </span>
                      </div>
                      <h3 class="text-3xl font-bold mb-2">{{ story.name }}</h3>
                      <p class="text-blue-200 text-xl mb-6">{{ story.position }}</p>
                      <blockquote class="text-lg text-white/90 mb-6 italic">
                        "{{ story.story }}"
                      </blockquote>
                      <div class="bg-white/10 rounded-lg p-4">
                        <p class="font-semibold text-blue-200">Key Achievement:</p>
                        <p class="text-white/90">{{ story.achievement }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-center items-center space-x-4 mt-8">
            <button @click="prevSlide('success')"
              class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
              <ChevronLeft class="h-5 w-5" />
            </button>

            <div class="flex space-x-2">
              <button v-for="(story, index) in mappedSuccessStories" :key="story.id"
                @click="goToSlide('success', index)" :class="[
                  'w-3 h-3 rounded-full transition-all duration-300',
                  currentSuccessSlide === index
                    ? 'bg-white w-8'
                    : 'bg-white/40 hover:bg-white/60'
                ]"></button>
            </div>

            <button @click="nextSlide('success')"
              class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
              <ChevronRight class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Course Preview Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <BookOpen class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">COURSE PREVIEW</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Explore Our
            <span class="text-blue-600">Programs</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            Comprehensive courses designed to prepare you for the future of technology
          </p>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
          <div v-for="(course, index) in mappedCoursePreview" :key="course.id"
            class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed"
            :style="{ animationDelay: `${index * 150}ms` }">
            <!-- Course Image -->
            <div class="relative h-48 overflow-hidden">
              <img :src="course.image" :alt="course.title"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" />
              <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                {{ course.level }}
              </div>
              <div
                class="absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm">
                {{ course.duration }}
              </div>
            </div>

            <!-- Course Content -->
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-3">{{ course.title }}</h3>
              <p v-html="course.description" class="text-gray-600 mb-4 text-sm leading-relaxed"></p>

              <!-- Technologies -->
              <div class="flex flex-wrap gap-2 mb-4">
                <span v-for="tech in course.technologies.slice(0, 3)" :key="tech"
                  class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                  {{ tech }}
                </span>
                <span v-if="course.technologies.length > 3" class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                  +{{ course.technologies.length - 3 }} more
                </span>
              </div>

              <!-- Stats -->
              <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                <span class="flex items-center">
                  <BookOpen class="h-4 w-4 mr-1" />
                  {{ course.projects }} Projects
                </span>
                <span class="flex items-center">
                  <Users class="h-4 w-4 mr-1" />
                  {{ course.students }} Students
                </span>
              </div>

              <!-- CTA Button -->
              <button
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Learn More
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Faculty Spotlight Section -->
    <section class="py-20 bg-gradient-to-br from-blue-900 to-purple-900 relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 border border-white rounded-lg transform rotate-12 animate-float">
        </div>
        <div class="absolute bottom-32 right-32 w-32 h-32 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div
            class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <GraduationCap class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">FACULTY SPOTLIGHT</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            Learn from
            <span class="block text-purple-200">Industry Experts</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up-slow">
            Our world-class faculty brings decades of industry experience and academic excellence
          </p>
        </div>

        <!-- Faculty Slider -->
        <div class="relative">
          <div class="overflow-hidden" @mouseenter="stopAutoPlay('faculty')"
            @mouseleave="startAutoPlay('faculty', 9000)">
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${currentFacultySlide * 100}%)` }">
              <div v-for="faculty in mappedFacultySpotlight" :key="faculty.id" class="w-full flex-shrink-0">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- Faculty Image -->
                    <div class="lg:col-span-1">
                      <div class="relative">
                        <div class="aspect-square rounded-2xl overflow-hidden">
                          <img :src="faculty.image" :alt="faculty.name" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 -right-4 bg-white rounded-full p-3 shadow-lg">
                          <GraduationCap class="h-6 w-6 text-blue-600" />
                        </div>
                      </div>
                    </div>

                    <!-- Faculty Info -->
                    <div class="lg:col-span-2 text-white">
                      <h3 class="text-3xl font-bold mb-2">{{ faculty.name }}</h3>
                      <p class="text-purple-200 text-xl mb-4">{{ faculty.position }}</p>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                          <h4 class="font-semibold text-purple-200 mb-2">Expertise</h4>
                          <div class="flex flex-wrap gap-2">
                            <span v-for="skill in faculty.expertise" :key="skill"
                              class="bg-purple-500/30 text-white px-3 py-1 rounded-full text-sm">
                              {{ skill }}
                            </span>
                          </div>
                        </div>
                        <div>
                          <h4 class="font-semibold text-purple-200 mb-2">Experience</h4>
                          <p class="text-white/90">{{ faculty.experience }}</p>
                          <h4 class="font-semibold text-purple-200 mb-2 mt-3">Education</h4>
                          <p class="text-white/90 text-sm">{{ faculty.education }}</p>
                        </div>
                      </div>

                      <div class="bg-white/10 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-purple-200 mb-2">Achievements</h4>
                        <p class="text-white/90 text-sm">{{ faculty.achievements }}</p>
                      </div>

                      <blockquote class="text-lg text-white/90 italic border-l-4 border-purple-400 pl-4">
                        "{{ faculty.quote }}"
                      </blockquote>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-center items-center space-x-4 mt-8">
            <button @click="prevSlide('faculty')"
              class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
              <ChevronLeft class="h-5 w-5" />
            </button>

            <div class="flex space-x-2">
              <button v-for="(faculty, index) in mappedFacultySpotlight" :key="faculty.id"
                @click="goToSlide('faculty', index)" :class="[
                  'w-3 h-3 rounded-full transition-all duration-300',
                  currentFacultySlide === index
                    ? 'bg-white w-8'
                    : 'bg-white/40 hover:bg-white/60'
                ]"></button>
            </div>

            <button @click="nextSlide('faculty')"
              class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
              <ChevronRight class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Gallery Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 to-black relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-32 left-32 w-32 h-32 border border-white rounded-lg transform rotate-45 animate-float">
        </div>
        <div class="absolute bottom-20 right-20 w-24 h-24 border border-white rounded-full animate-pulse-slow"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div
            class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Play class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">VIDEO GALLERY</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            See Novus in
            <span class="block text-blue-400">Action</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto animate-fade-in-up-slow">
            Experience our campus, hear from students, and discover what makes Novus Institute special
          </p>
        </div>

        <!-- Video Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(video, index) in mappedVideoGallery" :key="video.id"
            class="group cursor-pointer animate-scale-in-delayed" :style="{ animationDelay: `${index * 150}ms` }"
            @click="playVideo(video.id)">
            <div
              class="relative overflow-hidden rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2">
              <!-- Video Thumbnail -->
              <div class="relative h-48 overflow-hidden">
                <img :src="video.thumbnail" :alt="video.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />

                <!-- Play Button Overlay -->
                <div
                  class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div
                    class="bg-white/20 backdrop-blur-sm rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                    <Play class="h-8 w-8 text-white fill-current" />
                  </div>
                </div>

                <!-- Duration Badge -->
                <div class="absolute bottom-3 right-3 bg-black/70 text-white px-2 py-1 rounded text-sm font-medium">
                  {{ video.duration }}
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 left-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                  {{ video.category }}
                </div>
              </div>

              <!-- Video Info -->
              <div class="p-6 text-white">
                <h3 class="text-lg font-bold mb-2 group-hover:text-blue-400 transition-colors">
                  {{ video.title }}
                </h3>
                <p class="text-white/80 text-sm mb-3 leading-relaxed">
                  {{ video.description }}
                </p>
                <div class="flex items-center justify-between text-xs text-white/60">
                  <span class="flex items-center">
                    <Play class="h-3 w-3 mr-1" />
                    {{ video.views }} views
                  </span>
                  <span class="flex items-center hover:text-blue-400 transition-colors">
                    <ExternalLink class="h-3 w-3 mr-1" />
                    Watch
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Featured Video Player (Modal-like) -->
        <div v-if="playingVideo"
          class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4"
          @click="pauseVideo">
          <div class="relative max-w-4xl w-full">
            <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
              <div class="aspect-video bg-gray-900 flex items-center justify-center">
                <video v-if="mappedVideoGallery.find(v => v.id === playingVideo)"
                  :src="mappedVideoGallery.find(v => v.id === playingVideo).video" controls autoplay
                  class="w-full h-full rounded-lg" @click.stop>
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="text-center text-gray-900 mt-2 p-2">
                <p class="text-xl font-bold">{{mappedVideoGallery.find(v => v.id === playingVideo)?.title}}</p>
              </div>
            </div>
            <button @click="pauseVideo"
              class="absolute -top-4 -right-4 bg-white text-gray-900 rounded-full p-2 hover:bg-gray-100 transition-colors">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Events Timeline Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-white relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-blue-100 rounded-full px-6 py-3 mb-6 animate-bounce-in">
            <CalendarIcon class="h-6 w-6 text-blue-600" />
            <span class="text-blue-900 font-medium tracking-wide">UPCOMING EVENTS</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 animate-slide-in-up">
            Join Our
            <span class="text-blue-600">Community Events</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up-slow">
            Connect, learn, and grow with fellow technology enthusiasts at our exciting events
          </p>
        </div>

        <!-- Events Slider -->
        <div class="relative">
          <div class="overflow-hidden" @mouseenter="stopAutoPlay('event')" @mouseleave="startAutoPlay('event', 10000)">
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${currentEventSlide * 100}%)` }">
              <div v-for="event in mappedUpcomingEventsSlider" :key="event.id" class="w-full flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Event Image -->
                    <div class="relative h-64 lg:h-auto">
                      <img :src="event.image" :alt="event.title" class="w-full h-full object-cover" />
                      <div class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <div class="text-sm font-medium">{{ event.type }}</div>
                      </div>
                      <div
                        class="absolute bottom-4 right-4 bg-black/70 backdrop-blur-sm text-white px-3 py-2 rounded-lg">
                        <div class="text-sm">{{ event.attendees }} attending</div>
                      </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-8">
                      <div class="mb-4">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                          {{ formatKsh(event.price) }}
                        </span>
                      </div>

                      <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ event.title }}</h3>
                      <p v-html="event.description" class="text-gray-600 mb-6 leading-relaxed"></p>

                      <!-- Event Meta -->
                      <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-600">
                          <CalendarIcon class="h-5 w-5 mr-3 text-blue-600" />
                          <span>{{ event.date }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                          <ClockIcon class="h-5 w-5 mr-3 text-blue-600" />
                          <span>{{ event.time }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                          <MapPin class="h-5 w-5 mr-3 text-blue-600" />
                          <span>{{ event.location }}</span>
                        </div>
                      </div>

                      <!-- Speakers -->
                      <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Featured Speakers</h4>
                        <div class="flex flex-wrap gap-2">
                          <span v-for="speaker in event.speakers.slice(0, 2)" :key="speaker"
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            {{ speaker }}
                          </span>
                          <span v-if="event.speakers.length > 2"
                            class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                            +{{ event.speakers.length - 2 }} more
                          </span>
                        </div>
                      </div>

                      <!-- CTA Buttons -->
                      <div class="flex space-x-4">
                        <button
                          class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                          Register Now
                        </button>
                        <button
                          class="border-2 border-blue-600 text-blue-600 py-3 px-6 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                          Learn More
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-center items-center space-x-4 mt-8">
            <button @click="prevSlide('event')"
              class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-full transition-all duration-300">
              <ChevronLeft class="h-5 w-5" />
            </button>

            <div class="flex space-x-2">
              <button v-for="(event, index) in mappedUpcomingEventsSlider" :key="event.id"
                @click="goToSlide('event', index)" :class="[
                  'w-3 h-3 rounded-full transition-all duration-300',
                  currentEventSlide === index
                    ? 'bg-blue-600 w-8'
                    : 'bg-gray-300 hover:bg-gray-400'
                ]"></button>
            </div>

            <button @click="nextSlide('event')"
              class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-full transition-all duration-300">
              <ChevronRight class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Registration Requirements Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-white relative overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 opacity-5">
        <div
          class="absolute top-10 right-10 w-32 h-32 border border-blue-300 rounded-lg transform rotate-12 animate-float">
        </div>
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
          <div v-for="(requirement, index) in registrationRequirements" :key="requirement.id"
            class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in-delayed"
            :style="{ animationDelay: `${index * 200}ms` }">
            <div class="text-center">
              <!-- Step Number -->
              <div
                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <span class="text-white font-bold text-xl">{{ index + 1 }}</span>
              </div>

              <!-- Icon -->
              <div class="bg-blue-100 rounded-full p-4 w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                <component :is="getIcon(requirement.icon)" class="h-10 w-10 text-blue-600" />
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
              <button
                class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors transform hover:scale-105">
                Apply Online
              </button>
              </Link>
              <button @click="downloadAttachment(firstAttachment.uuid)"
                class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition-colors transform hover:scale-105">
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
        <div class="absolute top-20 left-20 w-40 h-40 border border-white rounded-lg transform rotate-12 animate-float">
        </div>
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
          <div
            class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6 animate-bounce-in">
            <Shield class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">CODE OF CONDUCT</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-in-up">
            The Ten Commandments of
            <span class="block text-blue-200">Computer Ethics</span>
          </h2>
          <p class="text-xl text-white/90 max-w-4xl mx-auto leading-relaxed animate-fade-in-up-slow">
            At Novus Institute, we believe in responsible technology use. These principles guide our students and
            faculty in ethical computing practices.
          </p>
        </div>

        <!-- Commandments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div v-for="(commandment, index) in computerEthics" :key="commandment.id"
            class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-1 animate-slide-in-up"
            :style="{ animationDelay: `${index * 150}ms` }">
            <div class="flex items-start space-x-4">
              <!-- Roman Numeral -->
              <div class="flex-shrink-0">
                <div
                  class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
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
          <div
            class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm rounded-2xl p-8 border border-white/20 animate-scale-in-delayed">
            <h3 class="text-2xl font-bold text-white mb-4">Our Commitment</h3>
            <p class="text-white/90 text-lg leading-relaxed max-w-4xl mx-auto mb-6">
              Every student at Novus Institute pledges to uphold these ethical standards, ensuring that technology
              serves humanity with integrity, respect, and responsibility.
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
          <div v-for="value in institutionValues" :key="value.title"
            class="bg-white rounded-lg shadow-md p-6 text-center">
            <h3 class="text-blue-900 font-bold text-lg mb-4">{{ value.title }}</h3>
            <p class="text-gray-600">
              <span v-if="!expanded[value.title] && value.description.length > 120">
                {{ value.description.slice(0, 120) }}...
                <button class="text-blue-700 underline ml-1" @click="toggleExpand(value.title)">Read More</button>
              </span>
              <span v-else>
                {{ value.description }}
                <button v-if="value.description.length > 120" class="text-blue-700 underline ml-1"
                  @click="toggleExpand(value.title)">Show Less</button>
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
          <div v-for="testimonial in mappedTestimonials" :key="testimonial.id"
            class="bg-white rounded-lg shadow-md p-6">
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
                <button v-if="value.description.length > 120" class="text-blue-700 underline ml-1"
                  @click="toggleExpandCore(value.title)">Show Less</button>
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