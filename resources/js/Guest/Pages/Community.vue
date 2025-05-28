<template>
    <Head>
        <title>Community</title>
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
              <Users class="h-6 w-6 text-white" />
              <span class="text-white font-medium tracking-wide">NOVUS COMMUNITY</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Join Our
              <span class="block text-blue-200">Tech Community</span>
            </h1>
            
            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
              Connect, collaborate, and grow with fellow technology enthusiasts, alumni, and industry professionals.
            </p>
  
            <!-- Community Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
              <div
                v-for="(stat, index) in communityStats"
                :key="stat.label"
                class="bg-white/10 backdrop-blur-sm rounded-lg p-4 animate-scale-in"
                :style="{ animationDelay: `${index * 150}ms` }"
              >
                <div class="text-2xl font-bold text-white">{{ stat.value }}</div>
                <div class="text-white/80 text-sm">{{ stat.label }}</div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Interactive Community Features -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Community Tabs -->
          <div class="mb-12">
            <div class="flex flex-wrap justify-center space-x-1 bg-gray-100 rounded-lg p-1 max-w-2xl mx-auto">
              <button
                v-for="tab in communityTabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'px-6 py-3 rounded-md font-medium transition-all duration-200',
                  activeTab === tab.id
                    ? 'bg-blue-900 text-white shadow-md transform scale-105'
                    : 'text-gray-600 hover:text-blue-900 hover:bg-white'
                ]"
              >
                {{ tab.name }}
              </button>
            </div>
          </div>
  
          <!-- Tab Content -->
          <div class="transition-all duration-500">
            <!-- Events Tab -->
            <div v-if="activeTab === 'events'" class="animate-fade-in">
              <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
                <p class="text-xl text-gray-600">Join us for workshops, seminars, and networking events</p>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                  v-for="(event, index) in upcomingEvents"
                  :key="event.id"
                  class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in-up"
                  :style="{ animationDelay: `${index * 150}ms` }"
                >
                  <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute top-4 left-4 bg-white/90 rounded-lg px-3 py-1">
                      <div class="text-sm font-bold text-blue-900">{{ event.date }}</div>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white">
                      <h3 class="text-xl font-bold mb-1">{{ event.title }}</h3>
                      <p class="text-sm opacity-90">{{ event.type }}</p>
                    </div>
                  </div>
                  
                  <div class="p-6">
                    <p class="text-gray-600 mb-4">{{ event.description }}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center text-sm text-gray-500">
                        <Clock class="h-4 w-4 mr-1" />
                        {{ event.time }}
                      </div>
                      <div class="flex items-center text-sm text-gray-500">
                        <MapPin class="h-4 w-4 mr-1" />
                        {{ event.location }}
                      </div>
                    </div>
  
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-2">
                        <div class="flex -space-x-2">
                          <div
                            v-for="i in Math.min(event.attendees, 3)"
                            :key="i"
                            class="w-8 h-8 bg-blue-100 rounded-full border-2 border-white flex items-center justify-center"
                          >
                            <User class="h-4 w-4 text-blue-600" />
                          </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ event.attendees }} attending</span>
                      </div>
                      
                      <button
                        @click="joinEvent(event.id)"
                        :class="[
                          'px-4 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105',
                          event.joined
                            ? 'bg-green-100 text-green-800'
                            : 'bg-blue-900 text-white hover:bg-blue-800'
                        ]"
                      >
                        {{ event.joined ? 'Joined' : 'Join Event' }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Forum Tab -->
            <div v-if="activeTab === 'forum'" class="animate-fade-in">
              <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Community Forum</h2>
                <p class="text-xl text-gray-600">Share knowledge, ask questions, and connect with peers</p>
              </div>
  
              <!-- New Post Form -->
              <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Start a Discussion</h3>
                <div class="space-y-4">
                  <input
                    v-model="newPost.title"
                    type="text"
                    placeholder="What's your question or topic?"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <textarea
                    v-model="newPost.content"
                    rows="3"
                    placeholder="Share your thoughts, ask a question, or start a discussion..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                  ></textarea>
                  <div class="flex items-center justify-between">
                    <select
                      v-model="newPost.category"
                      class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                      <option value="">Select Category</option>
                      <option value="general">General Discussion</option>
                      <option value="technical">Technical Help</option>
                      <option value="career">Career Advice</option>
                      <option value="projects">Project Showcase</option>
                    </select>
                    <button
                      @click="createPost"
                      class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition-colors"
                    >
                      Post
                    </button>
                  </div>
                </div>
              </div>
  
              <!-- Forum Posts -->
              <div class="space-y-6">
                <div
                  v-for="(post, index) in forumPosts"
                  :key="post.id"
                  class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 animate-slide-in-up"
                  :style="{ animationDelay: `${index * 100}ms` }"
                >
                  <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                      <User class="h-6 w-6 text-blue-600" />
                    </div>
                    
                    <div class="flex-1">
                      <div class="flex items-center justify-between mb-2">
                        <div>
                          <h4 class="font-semibold text-gray-900">{{ post.author }}</h4>
                          <p class="text-sm text-gray-500">{{ post.timeAgo }}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                          {{ post.category }}
                        </span>
                      </div>
                      
                      <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ post.title }}</h3>
                      <p class="text-gray-600 mb-4">{{ post.content }}</p>
                      
                      <div class="flex items-center space-x-6">
                        <button
                          @click="likePost(post.id)"
                          :class="[
                            'flex items-center space-x-1 text-sm transition-colors',
                            post.liked ? 'text-red-500' : 'text-gray-500 hover:text-red-500'
                          ]"
                        >
                          <Heart :class="post.liked ? 'fill-current' : ''" class="h-4 w-4" />
                          <span>{{ post.likes }}</span>
                        </button>
                        
                        <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-blue-500 transition-colors">
                          <MessageSquare class="h-4 w-4" />
                          <span>{{ post.replies }}</span>
                        </button>
                        
                        <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-green-500 transition-colors">
                          <Share2 class="h-4 w-4" />
                          <span>Share</span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Alumni Tab -->
            <div v-if="activeTab === 'alumni'" class="animate-fade-in">
              <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Alumni Network</h2>
                <p class="text-xl text-gray-600">Connect with successful graduates making impact in the tech industry</p>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                  v-for="(alumni, index) in alumniProfiles"
                  :key="alumni.id"
                  class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in"
                  :style="{ animationDelay: `${index * 150}ms` }"
                >
                  <div class="h-32 bg-gradient-to-br from-blue-500 to-purple-600"></div>
                  
                  <div class="p-6 -mt-16 relative">
                    <div class="w-20 h-20 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center mx-auto mb-4">
                      <User class="h-10 w-10 text-gray-400" />
                    </div>
                    
                    <div class="text-center">
                      <h3 class="text-xl font-bold text-gray-900 mb-1">{{ alumni.name }}</h3>
                      <p class="text-blue-600 font-medium mb-2">{{ alumni.position }}</p>
                      <p class="text-gray-600 text-sm mb-4">{{ alumni.company }}</p>
                      <p class="text-gray-500 text-sm mb-4">Class of {{ alumni.graduationYear }}</p>
                      
                      <div class="flex justify-center space-x-2 mb-4">
                        <span
                          v-for="skill in alumni.skills.slice(0, 3)"
                          :key="skill"
                          class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs"
                        >
                          {{ skill }}
                        </span>
                      </div>
                      
                      <button class="w-full bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-800 transition-colors">
                        Connect
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Resources Tab -->
            <div v-if="activeTab === 'resources'" class="animate-fade-in">
              <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Community Resources</h2>
                <p class="text-xl text-gray-600">Access shared knowledge, tools, and learning materials</p>
              </div>
  
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                  v-for="(resource, index) in communityResources"
                  :key="resource.id"
                  class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-slide-in-up"
                  :style="{ animationDelay: `${index * 150}ms` }"
                >
                  <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-blue-100 rounded-lg p-3">
                      <component :is="resource.icon" class="h-6 w-6 text-blue-600" />
                    </div>
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ resource.title }}</h3>
                      <p class="text-sm text-gray-500">{{ resource.type }}</p>
                    </div>
                  </div>
                  
                  <p class="text-gray-600 mb-4">{{ resource.description }}</p>
                  
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                      <span class="flex items-center">
                        <Download class="h-4 w-4 mr-1" />
                        {{ resource.downloads }}
                      </span>
                      <span class="flex items-center">
                        <Star class="h-4 w-4 mr-1" />
                        {{ resource.rating }}
                      </span>
                    </div>
                    
                    <button class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors">
                      Access
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { 
    Users, 
    Clock, 
    MapPin, 
    User, 
    Heart, 
    MessageSquare, 
    Share2,
    Download,
    Star,
    BookOpen,
    Code,
    Video,
    FileText
  } from 'lucide-vue-next'
  
  const activeTab = ref('events')
  
  const communityStats = [
    { value: '100+', label: 'Active Members' },
    { value: '150+', label: 'Alumni Network' },
    { value: '2+', label: 'Monthly Events' },
    { value: '800+', label: 'Forum Posts' }
  ]
  
  const communityTabs = [
    { id: 'events', name: 'Events' },
    { id: 'forum', name: 'Forum' },
    { id: 'alumni', name: 'Alumni' },
    { id: 'resources', name: 'Resources' }
  ]
  
  const upcomingEvents = ref([
    {
      id: 1,
      title: 'AI Workshop',
      type: 'Workshop',
      date: 'Mar 15',
      time: '2:00 PM',
      location: 'Main Hall',
      description: 'Learn the fundamentals of artificial intelligence and machine learning.',
      attendees: 45,
      joined: false
    },
    {
      id: 2,
      title: 'Tech Career Fair',
      type: 'Networking',
      date: 'Mar 20',
      time: '10:00 AM',
      location: 'Campus Center',
      description: 'Connect with top tech companies and explore career opportunities.',
      attendees: 120,
      joined: true
    },
    {
      id: 3,
      title: 'Coding Bootcamp',
      type: 'Training',
      date: 'Mar 25',
      time: '9:00 AM',
      location: 'Lab 1',
      description: 'Intensive coding session covering modern web development frameworks.',
      attendees: 30,
      joined: false
    }
  ])
  
  const newPost = ref({
    title: '',
    content: '',
    category: ''
  })
  
  const forumPosts = ref([
    {
      id: 1,
      author: 'Sarah Johnson',
      title: 'Best practices for React development?',
      content: 'I\'m working on a large React project and looking for advice on state management and component organization.',
      category: 'technical',
      timeAgo: '2 hours ago',
      likes: 12,
      replies: 8,
      liked: false
    },
    {
      id: 2,
      author: 'Michael Chen',
      title: 'Career transition from finance to tech',
      content: 'Has anyone successfully transitioned from a finance background to software development? Looking for advice.',
      category: 'career',
      timeAgo: '5 hours ago',
      likes: 18,
      replies: 15,
      liked: true
    },
    {
      id: 3,
      author: 'Emily Rodriguez',
      title: 'My first mobile app project',
      content: 'Just launched my first React Native app! Here\'s what I learned during the development process.',
      category: 'projects',
      timeAgo: '1 day ago',
      likes: 25,
      replies: 12,
      liked: false
    }
  ])
  
  const alumniProfiles = [
    {
      id: 1,
      name: 'David Kimani',
      position: 'Senior Software Engineer',
      company: 'Google',
      graduationYear: '2019',
      skills: ['JavaScript', 'Python', 'Cloud Computing']
    },
    {
      id: 2,
      name: 'Grace Wanjiku',
      position: 'Data Scientist',
      company: 'Microsoft',
      graduationYear: '2020',
      skills: ['Machine Learning', 'Python', 'Statistics']
    },
    {
      id: 3,
      name: 'James Ochieng',
      position: 'Product Manager',
      company: 'Safaricom',
      graduationYear: '2018',
      skills: ['Product Strategy', 'Analytics', 'Leadership']
    }
  ]
  
  const communityResources = [
    {
      id: 1,
      title: 'JavaScript Fundamentals',
      type: 'Course Material',
      description: 'Comprehensive guide to JavaScript programming with practical examples.',
      downloads: 1250,
      rating: 4.8,
      icon: 'Code'
    },
    {
      id: 2,
      title: 'Career Development Guide',
      type: 'PDF Guide',
      description: 'Step-by-step guide to building a successful career in technology.',
      downloads: 890,
      rating: 4.6,
      icon: 'FileText'
    },
    {
      id: 3,
      title: 'Web Development Tutorials',
      type: 'Video Series',
      description: 'Complete video series covering modern web development technologies.',
      downloads: 2100,
      rating: 4.9,
      icon: 'Video'
    }
  ]
  
  const joinEvent = (eventId) => {
    const event = upcomingEvents.value.find(e => e.id === eventId)
    if (event) {
      event.joined = !event.joined
      if (event.joined) {
        event.attendees++
      } else {
        event.attendees--
      }
    }
  }
  
  const likePost = (postId) => {
    const post = forumPosts.value.find(p => p.id === postId)
    if (post) {
      post.liked = !post.liked
      if (post.liked) {
        post.likes++
      } else {
        post.likes--
      }
    }
  }
  
  const createPost = () => {
    if (newPost.value.title && newPost.value.content && newPost.value.category) {
      const post = {
        id: Date.now(),
        author: 'You',
        title: newPost.value.title,
        content: newPost.value.content,
        category: newPost.value.category,
        timeAgo: 'Just now',
        likes: 0,
        replies: 0,
        liked: false
      }
      
      forumPosts.value.unshift(post)
      
      // Reset form
      newPost.value = {
        title: '',
        content: '',
        category: ''
      }
    }
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
  
  .animate-slide-in-up {
    animation: slide-in-up 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .animate-scale-in {
    animation: scale-in 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .animate-fade-in {
    animation: fade-in 0.5s ease-out;
  }
  </style>