<template>
    <Head>
        <title>About Us</title>
    </Head>
    <div class="min-h-screen py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">About Novus Institute of Technology</h1>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Discover our story, values, and the inspiration behind our commitment to excellence in technology education.
          </p>
        </div>
  
        <!-- Our Story -->
        <section class="mb-16">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story & Inspiration</h2>
              <div class="space-y-4 text-gray-600">
                <p>
                  Founded in 2017, Novus Institute of Technology emerged from a vision to bridge the gap between
                  traditional education and the rapidly evolving technology landscape. Our founders, a group of industry
                  veterans and academic pioneers, recognized the need for an institution that could prepare students for
                  the challenges of tomorrow.
                </p>
                <p>
                  The name "Novus" means "new" in Latin, reflecting our commitment to innovation and fresh approaches to
                  learning. We believe that education should not just transfer knowledge, but inspire creativity, foster
                  critical thinking, and nurture the entrepreneurial spirit.
                </p>
                <p>
                  Our inspiration comes from the belief that technology, when guided by ethical principles and human
                  values, can solve the world's greatest challenges. We strive to create not just skilled professionals,
                  but responsible leaders who will shape a better future.
                </p>
              </div>
            </div>
            <div class="bg-blue-50 p-8 rounded-lg">
              <Quote class="h-12 w-12 text-blue-900 mb-4" />
              <blockquote class="text-lg italic text-gray-700 mb-4">
                "Education is the most powerful weapon which you can use to change the world. At Novus, we arm our
                students with knowledge, skills, and values to create positive change through technology."
              </blockquote>
              <cite class="text-blue-900 font-semibold">- Dr. Paul Kihara, Founder & President</cite>
            </div>
          </div>
        </section>
  
        <!-- Mission, Vision, Values -->
        <section class="mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Foundation</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white border border-blue-200 rounded-lg p-6 text-center">
              <Target class="h-12 w-12 text-blue-900 mx-auto mb-4" />
              <h3 class="text-blue-900 font-bold text-xl mb-4">MISSION</h3>
              <p>
                Our mission is to provide a transformative educational experience that nurtures creativity, fosters innovation, and promotes ethical practices in the field of computer science.
              </p>
            </div>
            <div class="bg-white border border-blue-200 rounded-lg p-6 text-center">
              <BookOpen class="h-12 w-12 text-blue-900 mx-auto mb-4" />
              <h3 class="text-blue-900 font-bold text-xl mb-4">VISION</h3>
              <p>
                Our vision is to be a leading institution in computer science and technology education, renowned for our commitment to excellence, innovation, and inclusivity. 
              </p>
            </div>
            <div class="bg-white border border-blue-200 rounded-lg p-6 text-center">
              <Award class="h-12 w-12 text-blue-900 mx-auto mb-4" />
              <h3 class="text-blue-900 font-bold text-xl mb-4">CORE VALUES</h3>
              <ul class="space-y-2 text-sm">
                <li class="flex items-center justify-center">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mr-2">Humility</span>
                  Embracing a mindset of continuous learning and growth...
                </li>
                <li class="flex items-center justify-center">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mr-2">Innovation</span>
                  Embracing creativity and technological advancements 
                </li>
                <li class="flex items-center justify-center">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mr-2">Integrity</span>
                  Upholding ethical principles and honesty in all academic
                </li>
                <li class="flex items-center justify-center">
                  <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs mr-2">Excellence</span>
                  Maintaining the highest standards in education
                </li>
              </ul>
            </div>
          </div>
        </section>
  
        <!-- Testimonials -->
        <section class="mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Student Testimonials</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="testimonial in testimonials"
              :key="testimonial.id"
              class="bg-white rounded-lg shadow-md p-6"
            >
              <div class="flex items-center mb-4">
                <Star
                  v-for="star in testimonial.rating"
                  :key="star"
                  class="h-4 w-4 fill-yellow-400 text-yellow-400"
                />
              </div>
              <p class="text-gray-600 mb-4">"{{ testimonial.content }}"</p>
              <div class="font-semibold">{{ testimonial.name }}</div>
              <div class="text-sm text-gray-500">{{ testimonial.position }}</div>
            </div>
          </div>
        </section>
  
        <!-- Partners -->
        <section class="mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Partners</h2>
          <div class="bg-gray-50 p-8 rounded-lg">
            <p class="text-center text-gray-600 mb-8">
              We collaborate with leading technology companies and organizations to provide our students with the best
              opportunities and real-world experience.
            </p>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center">
              <div
                v-for="partner in partners"
                :key="partner.name"
                class="text-center"
              >
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                  <div class="h-12 flex items-center justify-center">
                    <span class="font-semibold text-gray-700">{{ partner.name }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
  
        <!-- Awards -->
        <section>
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Awards & Recognition</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="award in awards"
              :key="award.id"
              class="bg-white rounded-lg shadow-md p-6 text-center"
            >
              <Award class="h-12 w-12 text-yellow-500 mx-auto mb-4" />
              <h3 class="font-bold text-lg mb-2">{{ award.title }}</h3>
              <p class="text-gray-600">{{ award.organization }} {{ award.year }}</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Target, BookOpen, Award, Quote, Star } from 'lucide-vue-next'
  
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
  </script>