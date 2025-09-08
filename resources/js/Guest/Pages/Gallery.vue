<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { Camera, Eye, Calendar as CalendarIcon, ArrowLeft, Search, Filter, Grid, List, Download, Share2, Heart, Play, ChevronLeft, ChevronRight } from 'lucide-vue-next'

// Get props from Inertia - following Home.vue pattern exactly
const { props } = usePage();

const albumCollections = computed(() => props.albumCollections ?? []);
const campusGallery = computed(() => props.campusGallery ?? []);
const videoGallery = computed(() => props.videoGallery ?? []);

// State management
const selectedImage = ref(null);
const selectedAlbum = ref(null);
const searchQuery = ref('');
const selectedCategory = ref('all');
const viewMode = ref('grid'); // 'grid' or 'list'
const isLoading = ref(false);
const currentSliderIndex = ref(0);
const isPlayingVideo = ref(false);
const selectedVideo = ref(null);

// Helper functions for image paths
const galleryImagePath = (cover_image) => `/storage/gallery/cover_images/${cover_image}`;
const videoThumbnailPath = (cover_image) => `thumbnails/${cover_image}`;
const videoFilePath = (video_path) => `/storage/videos/${video_path}`;

// Mapped data exactly like Home.vue
const mappedCampusGallery = computed(() => {
  try {
    if (!campusGallery?.value || !Array.isArray(campusGallery.value) || campusGallery.value.length === 0) {
      return [
        {
          id: 1,
          image: '/img/slider/slider-1.jpg',
          title: 'Innovation Hub',
          description: 'Discover cutting-edge technology and creative solutions in our state-of-the-art innovation hub.',
          category: 'Innovation'
        },
        {
          id: 2,
          image: '/img/slider/slider-2.jpg',
          title: 'Learning Excellence',
          description: 'Experience world-class education with hands-on training and industry-relevant curriculum.',
          category: 'Education'
        }
      ];
    }
    
    return campusGallery.value.map(item => ({
      id: item?.id || 0,
      image: item?.cover_image ? galleryImagePath(item.cover_image) : '/img/slider/slider-1.jpg',
      title: item?.title || 'Untitled',
      description: item?.description || 'No description available',
      category: item?.category ?? 'Gallery',
    }));
  } catch (error) {
    console.error('Error in mappedCampusGallery:', error);
    return [
      {
        id: 1,
        image: '/img/slider/slider-1.jpg',
        title: 'Innovation Hub',
        description: 'Discover cutting-edge technology and creative solutions in our state-of-the-art innovation hub.',
        category: 'Innovation'
      }
    ];
  }
});

const mappedVideoGallery = computed(() => {
  try {
    if (!videoGallery?.value || !Array.isArray(videoGallery.value)) return [];
    
    return videoGallery.value.map(item => ({
      id: item?.id || 0,
      title: item?.title || 'Untitled Video',
      description: item?.description || 'No description available',
      thumbnail: item?.cover_image ? videoThumbnailPath(item.cover_image) : '/img/slider/slider-1.jpg',
      video: item?.video_path ? videoFilePath(item.video_path) : null,
      duration: item?.duration || '0:00',
      category: item?.category || 'General',
      views: item?.views || 0,
    }));
  } catch (error) {
    console.error('Error in mappedVideoGallery:', error);
    return [];
  }
});

// Computed properties for filtered data
const filteredAlbumCollections = computed(() => {
  try {
    let filtered = albumCollections?.value || [];

    // Filter by search query
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase();
      filtered = filtered.filter(album => 
        album?.title?.toLowerCase().includes(query) ||
        album?.description?.toLowerCase().includes(query)
      );
    }

    // Filter by category
    if (selectedCategory.value !== 'all') {
      filtered = filtered.filter(album => album?.category === selectedCategory.value);
    }

    return filtered;
  } catch (error) {
    console.error('Error in filteredAlbumCollections:', error);
    return [];
  }
});

const filteredCampusGallery = computed(() => {
  try {
    let filtered = mappedCampusGallery?.value || [];

    // Filter by search query
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase();
      filtered = filtered.filter(gallery => 
        gallery?.title?.toLowerCase().includes(query) ||
        gallery?.description?.toLowerCase().includes(query)
      );
    }

    // Filter by category
    if (selectedCategory.value !== 'all') {
      filtered = filtered.filter(gallery => gallery?.category === selectedCategory.value);
    }

    return filtered;
  } catch (error) {
    console.error('Error in filteredCampusGallery:', error);
    return [];
  }
});

const categories = computed(() => {
  try {
    const albumCats = (albumCollections?.value || []).map(a => a?.category).filter(Boolean);
    const galleryCats = (mappedCampusGallery?.value || []).map(g => g?.category).filter(Boolean);
    const allCats = ['all', ...new Set([...albumCats, ...galleryCats])];
    return allCats;
  } catch (error) {
    console.error('Error in categories:', error);
    return ['all'];
  }
});

// For compatibility with existing template
const singlePictures = computed(() => filteredCampusGallery?.value || []);
const albums = computed(() => filteredAlbumCollections?.value || []);
const filteredGalleries = computed(() => [...(filteredCampusGallery?.value || []), ...(filteredAlbumCollections?.value || [])]);

// Methods
const openImageViewer = (imageData) => {
  if (typeof imageData === 'string') {
    selectedImage.value = {
      image: imageData,
      title: 'Gallery Image',
      description: 'A beautiful moment captured in our gallery',
      category: 'Gallery',
      date: '2024-01-01'
    };
  } else {
    selectedImage.value = imageData;
  }
};

const closeImageViewer = () => {
  selectedImage.value = null;
};

const openAlbumViewer = (album) => {
  selectedAlbum.value = album;
};

const closeAlbumViewer = () => {
  selectedAlbum.value = null;
};

const toggleViewMode = () => {
  viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = 'all';
};

// Slider Methods
const nextSlide = () => {
  currentSliderIndex.value = (currentSliderIndex.value + 1) % mappedCampusGallery.value.length;
};

const prevSlide = () => {
  currentSliderIndex.value = currentSliderIndex.value === 0 ? mappedCampusGallery.value.length - 1 : currentSliderIndex.value - 1;
};

const goToSlide = (index) => {
  currentSliderIndex.value = index;
};

// Video Methods
const playVideo = (video) => {
  selectedVideo.value = video;
  isPlayingVideo.value = true;
};

const closeVideoPlayer = () => {
  selectedVideo.value = null;
  isPlayingVideo.value = false;
};

onMounted(() => {
  // Auto-play slider
  setInterval(() => {
    nextSlide();
  }, 5000);
});
</script>

<template>
  <Head>
    <title>Gallery - Novus Institute of Technology</title>
    <meta name="description" content="Explore our visual stories through photos and albums showcasing campus life, events, and achievements at Novus Institute of Technology.">
  </Head>

  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white py-16 animate-hero-glow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
          <div>
            <Link href="/" class="inline-flex items-center text-white/80 hover:text-white transition-colors mb-4">
              <ArrowLeft class="h-5 w-5 mr-2" />
              Back to Home
            </Link>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Gallery</h1>
            <p class="text-xl text-blue-200 max-w-2xl">
              Discover moments from our campus life, events, and achievements through our comprehensive photo collections.
            </p>
          </div>
          <div class="hidden md:block">
            <Camera class="h-24 w-24 text-blue-300 opacity-50" />
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Slider Section -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white py-16 animate-hero-glow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">Featured Highlights</h2>
          <p class="text-xl text-blue-200">Discover the best moments from our campus life and events</p>
        </div>

        <!-- Slider Container -->
        <div class="relative">
          <div class="overflow-hidden rounded-2xl">
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${currentSliderIndex * 100}%)` }">
              <div v-for="(slide, index) in mappedCampusGallery" :key="slide.id" class="w-full flex-shrink-0">
                <div class="relative h-96 md:h-[500px]">
                  <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                  <div class="absolute bottom-8 left-8 right-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                      <div class="inline-flex items-center bg-blue-500/90 text-white px-3 py-1 rounded-full text-sm font-medium mb-4">
                        {{ slide.category }}
                      </div>
                      <h3 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ slide.title }}</h3>
                      <p class="text-lg text-white/90">{{ slide.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Arrows -->
          <button @click="prevSlide" 
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300">
            <ChevronLeft class="h-6 w-6" />
          </button>
          <button @click="nextSlide" 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300">
            <ChevronRight class="h-6 w-6" />
          </button>

          <!-- Dots Indicator -->
          <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button v-for="(slide, index) in mappedCampusGallery" :key="slide.id" @click="goToSlide(index)"
              :class="[
                'w-3 h-3 rounded-full transition-all duration-300',
                currentSliderIndex === index ? 'bg-white w-8' : 'bg-white/40 hover:bg-white/60'
              ]"></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <!-- Search Bar -->
          <div class="relative flex-1 max-w-md">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search galleries..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Filters -->
          <div class="flex items-center gap-4">
            <!-- Category Filter -->
            <select
              v-model="selectedCategory"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="all">All Categories</option>
              <option v-for="category in categories.slice(1)" :key="category" :value="category">
                {{ category }}
              </option>
            </select>

            <!-- View Mode Toggle -->
            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
              <button
                @click="viewMode = 'grid'"
                :class="[
                  'px-3 py-2 transition-colors',
                  viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'
                ]"
              >
                <Grid class="h-5 w-5" />
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  'px-3 py-2 transition-colors',
                  viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'
                ]"
              >
                <List class="h-5 w-5" />
              </button>
            </div>

            <!-- Clear Filters -->
            <button
              @click="clearFilters"
              class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
            >
              Clear Filters
            </button>
          </div>
        </div>

        <!-- Results Summary -->
        <div class="mt-4 text-sm text-gray-600">
          Showing {{ filteredGalleries?.length || 0 }} of {{ (mappedCampusGallery?.value?.length || 0) + (albumCollections?.value?.length || 0) }} galleries
          <span v-if="searchQuery || selectedCategory !== 'all'" class="ml-2">
            ({{ singlePictures?.length || 0 }} photos, {{ albums?.length || 0 }} albums)
          </span>
        </div>
      </div>
    </div>

    <!-- Gallery Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Single Pictures Section -->
      <div v-if="singlePictures.length > 0" class="mb-16">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Featured Photos</h2>
          <span class="text-gray-600">{{ singlePictures.length }} photos</span>
        </div>

        <!-- Grid View -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div
            v-for="(photo, index) in singlePictures"
            :key="photo.id"
            class="group cursor-pointer animate-scale-in-delayed"
            :style="{ animationDelay: `${index * 100}ms` }"
            @click="openImageViewer(photo)"
          >
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
              <!-- Image -->
              <div class="relative h-64 overflow-hidden">
                <img
                  :src="photo.image"
                  :alt="photo.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                  <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                    <Eye class="h-8 w-8 text-white" />
                  </div>
                </div>

                <!-- Category Badge -->
                <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                  {{ photo.category }}
                </div>
              </div>

              <!-- Content -->
              <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                  {{ photo.title }}
                </h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-2">
                  {{ photo.description }}
                </p>
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span class="flex items-center">
                    <CalendarIcon class="h-3 w-3 mr-1" />
                    {{ photo.date }}
                  </span>
                  <span class="flex items-center hover:text-blue-600 transition-colors">
                    <Eye class="h-3 w-3 mr-1" />
                    View
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
          <div
            v-for="photo in singlePictures"
            :key="photo.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
            @click="openImageViewer(photo)"
          >
            <div class="flex">
              <div class="relative w-32 h-24 flex-shrink-0">
                <img
                  :src="photo.image"
                  :alt="photo.title"
                  class="w-full h-full object-cover"
                />
              </div>
              <div class="flex-1 p-4">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ photo.title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">{{ photo.description }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                      <span class="flex items-center">
                        <CalendarIcon class="h-3 w-3 mr-1" />
                        {{ photo.date }}
                      </span>
                      <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                        {{ photo.category }}
                      </span>
                    </div>
                  </div>
                  <Eye class="h-5 w-5 text-gray-400" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Albums Section -->
      <div v-if="albums.length > 0">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Photo Albums</h2>
          <span class="text-gray-600">{{ albums.length }} albums</span>
        </div>

        <!-- Grid View -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div
            v-for="(album, index) in albums"
            :key="album.id"
            class="group cursor-pointer animate-scale-in-delayed"
            :style="{ animationDelay: `${(index + singlePictures.length) * 100}ms` }"
            @click="openAlbumViewer(album)"
          >
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
              <!-- Album Cover -->
              <div class="relative h-64 overflow-hidden">
                <img
                  :src="album.cover_image_url || album.coverImage"
                  :alt="album.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                
              <!-- Image Count Overlay -->
              <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-medium">
                {{ album.image_count || album.imageCount }} photos
              </div>

                <!-- Category Badge -->
                <div class="absolute top-4 left-4 bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                  {{ album.category }}
                </div>

                <!-- Album Icon Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                  <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                    <Camera class="h-8 w-8 text-white" />
                  </div>
                </div>
              </div>

              <!-- Content -->
              <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                  {{ album.title }}
                </h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-2">
                  {{ album.description }}
                </p>
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span class="flex items-center">
                    <CalendarIcon class="h-3 w-3 mr-1" />
                    {{ album.created_at || album.date }}
                  </span>
                  <span class="flex items-center hover:text-purple-600 transition-colors">
                    <Camera class="h-3 w-3 mr-1" />
                    View Album
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
          <div
            v-for="album in albums"
            :key="album.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
            @click="openAlbumViewer(album)"
          >
            <div class="flex">
              <div class="relative w-32 h-24 flex-shrink-0">
                <img
                  :src="album.cover_image_url || album.coverImage"
                  :alt="album.title"
                  class="w-full h-full object-cover"
                />
                <div class="absolute bottom-1 right-1 bg-black/70 text-white px-2 py-1 rounded text-xs">
                  {{ album.image_count || album.imageCount }}
                </div>
              </div>
              <div class="flex-1 p-4">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ album.title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">{{ album.description }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                      <span class="flex items-center">
                        <CalendarIcon class="h-3 w-3 mr-1" />
                        {{ album.created_at || album.date }}
                      </span>
                      <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                        {{ album.category }}
                      </span>
                      <span class="flex items-center">
                        <Camera class="h-3 w-3 mr-1" />
                        {{ album.image_count || album.imageCount }} photos
                      </span>
                    </div>
                  </div>
                  <Camera class="h-5 w-5 text-gray-400" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredGalleries.length === 0" class="text-center py-16">
        <Camera class="h-24 w-24 text-gray-300 mx-auto mb-4" />
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No galleries found</h3>
        <p class="text-gray-600 mb-6">Try adjusting your search or filter criteria.</p>
        <button
          @click="clearFilters"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors"
        >
          Clear Filters
        </button>
      </div>
    </div>

    <!-- Video Gallery Section -->
    <div class="bg-gradient-to-br from-gray-900 to-black py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 border border-white/20 mb-6">
            <Play class="h-6 w-6 text-white" />
            <span class="text-white font-medium tracking-wide">VIDEO GALLERY</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            See Novus in
            <span class="block text-blue-400">Action</span>
          </h2>
          <p class="text-xl text-white/90 max-w-3xl mx-auto">
            Experience our campus, hear from students, and discover what makes Novus Institute special through our video collection.
          </p>
        </div>

        <!-- Video Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(video, index) in mappedVideoGallery" :key="video.id"
            class="group cursor-pointer animate-scale-in-delayed" :style="{ animationDelay: `${index * 150}ms` }"
            @click="playVideo(video)">
            <div class="relative overflow-hidden rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2">
              <!-- Video Thumbnail -->
              <div class="relative h-48 overflow-hidden">
                <img :src="video.thumbnail" :alt="video.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />

                <!-- Play Button Overlay -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300">
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
                  <span class="flex items-center">
                    <CalendarIcon class="h-3 w-3 mr-1" />
                    {{ video.category }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Viewer Modal -->
    <div
      v-if="selectedImage"
      class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      @click="closeImageViewer"
    >
      <div class="relative max-w-4xl w-full">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
          <div class="aspect-video bg-gray-900 flex items-center justify-center">
            <img
              :src="selectedImage.image"
              :alt="selectedImage.title"
              class="w-full h-full object-contain rounded-lg"
              @click.stop
            />
          </div>
          <div class="p-6 text-gray-900">
            <h3 class="text-2xl font-bold mb-2">{{ selectedImage.title }}</h3>
            <p class="text-gray-600 mb-4">{{ selectedImage.description }}</p>
            <div class="flex items-center justify-between text-sm text-gray-500">
              <span class="flex items-center">
                <CalendarIcon class="h-4 w-4 mr-1" />
                {{ selectedImage.date }}
              </span>
              <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                {{ selectedImage.category }}
              </span>
            </div>
          </div>
        </div>
        <button
          @click="closeImageViewer"
          class="absolute -top-4 -right-4 bg-white text-gray-900 rounded-full p-2 hover:bg-gray-100 transition-colors"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Album Viewer Modal -->
    <div
      v-if="selectedAlbum"
      class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      @click="closeAlbumViewer"
    >
      <div class="relative max-w-6xl w-full">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
          <!-- Album Header -->
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ selectedAlbum.title }}</h3>
            <p class="text-gray-600 mb-4">{{ selectedAlbum.description }}</p>
            <div class="flex items-center justify-between text-sm text-gray-500">
              <span class="flex items-center">
                <CalendarIcon class="h-4 w-4 mr-1" />
                {{ selectedAlbum.created_at || selectedAlbum.date }}
              </span>
              <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                {{ selectedAlbum.image_count || selectedAlbum.imageCount }} photos
              </span>
            </div>
          </div>

          <!-- Album Images Grid -->
          <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div
                v-for="(image, index) in selectedAlbum.images"
                :key="index"
                class="group cursor-pointer"
                @click="openImageViewer(image.image_url || image)"
              >
                <div class="relative aspect-square overflow-hidden rounded-lg">
                  <img
                    :src="image.image_url || image"
                    :alt="`${selectedAlbum.title} - Image ${index + 1}`"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                  />
                  <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <Eye class="h-6 w-6 text-white" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button
          @click="closeAlbumViewer"
          class="absolute -top-4 -right-4 bg-white text-gray-900 rounded-full p-2 hover:bg-gray-100 transition-colors"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Video Player Modal -->
    <div v-if="selectedVideo" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      @click="closeVideoPlayer">
      <div class="relative max-w-4xl w-full">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
          <div class="aspect-video bg-gray-900 flex items-center justify-center">
            <video v-if="selectedVideo" :src="selectedVideo.video || selectedVideo.videoUrl" controls autoplay
              class="w-full h-full rounded-lg" @click.stop>
              Your browser does not support the video tag.
            </video>
          </div>
          <div class="p-6 text-gray-900">
            <h3 class="text-2xl font-bold mb-2">{{ selectedVideo.title }}</h3>
            <p class="text-gray-600 mb-4">{{ selectedVideo.description }}</p>
            <div class="flex items-center justify-between text-sm text-gray-500">
              <span class="flex items-center">
                <CalendarIcon class="h-4 w-4 mr-1" />
                {{ selectedVideo.date }}
              </span>
              <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                {{ selectedVideo.category }}
              </span>
              <span class="flex items-center">
                <Play class="h-4 w-4 mr-1" />
                {{ selectedVideo.views }} views
              </span>
            </div>
          </div>
        </div>
        <button @click="closeVideoPlayer"
          class="absolute -top-4 -right-4 bg-white text-gray-900 rounded-full p-2 hover:bg-gray-100 transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.animate-scale-in-delayed {
  animation: scaleIn 0.6s ease-out forwards;
  opacity: 0;
  transform: scale(0.9);
}

@keyframes scaleIn {
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Hero Glow Animation - Same as HeroSection.vue */
@keyframes hero-glow {
  0% { 
    background-image: linear-gradient(to bottom right, 
      rgb(88 28 135), 
      rgb(30 64 175), 
      rgb(67 56 202));
    box-shadow: 0 0 40px rgba(139, 69, 255, 0.08), 0 0 20px rgba(59, 130, 246, 0.05);
  }
  5% {
    background-image: linear-gradient(to bottom right, 
      rgb(91 33 139), 
      rgb(32 69 182), 
      rgb(70 60 209));
    box-shadow: 0 0 42px rgba(139, 69, 255, 0.09), 0 0 22px rgba(59, 130, 246, 0.06);
  }
  10% {
    background-image: linear-gradient(to bottom right, 
      rgb(94 38 144), 
      rgb(34 74 189), 
      rgb(73 64 216));
    box-shadow: 0 0 44px rgba(139, 69, 255, 0.1), 0 0 24px rgba(59, 130, 246, 0.07);
  }
  15% {
    background-image: linear-gradient(to bottom right, 
      rgb(97 43 149), 
      rgb(36 79 196), 
      rgb(76 68 223));
    box-shadow: 0 0 46px rgba(139, 69, 255, 0.11), 0 0 26px rgba(59, 130, 246, 0.08);
  }
  20% {
    background-image: linear-gradient(to bottom right, 
      rgb(100 48 154), 
      rgb(38 84 203), 
      rgb(79 72 230));
    box-shadow: 0 0 48px rgba(139, 69, 255, 0.12), 0 0 28px rgba(59, 130, 246, 0.09);
  }
  25% {
    background-image: linear-gradient(to bottom right, 
      rgb(103 53 159), 
      rgb(40 89 210), 
      rgb(82 76 237));
    box-shadow: 0 0 50px rgba(139, 69, 255, 0.13), 0 0 30px rgba(59, 130, 246, 0.1);
  }
  30% {
    background-image: linear-gradient(to bottom right, 
      rgb(106 58 164), 
      rgb(42 94 217), 
      rgb(85 80 244));
    box-shadow: 0 0 52px rgba(139, 69, 255, 0.14), 0 0 32px rgba(59, 130, 246, 0.11);
  }
  35% {
    background-image: linear-gradient(to bottom right, 
      rgb(109 63 169), 
      rgb(44 99 224), 
      rgb(88 84 251));
    box-shadow: 0 0 54px rgba(139, 69, 255, 0.15), 0 0 34px rgba(59, 130, 246, 0.12);
  }
  40% {
    background-image: linear-gradient(to bottom right, 
      rgb(112 68 174), 
      rgb(46 104 231), 
      rgb(91 88 255));
    box-shadow: 0 0 56px rgba(139, 69, 255, 0.16), 0 0 36px rgba(59, 130, 246, 0.13);
  }
  45% {
    background-image: linear-gradient(to bottom right, 
      rgb(115 73 179), 
      rgb(48 109 238), 
      rgb(94 92 255));
    box-shadow: 0 0 58px rgba(139, 69, 255, 0.17), 0 0 38px rgba(59, 130, 246, 0.14);
  }
  50% {
    background-image: linear-gradient(to bottom right, 
      rgb(118 78 184), 
      rgb(50 114 245), 
      rgb(97 96 255));
    box-shadow: 0 0 60px rgba(139, 69, 255, 0.18), 0 0 40px rgba(59, 130, 246, 0.15);
  }
  55% {
    background-image: linear-gradient(to bottom right, 
      rgb(115 73 179), 
      rgb(48 109 238), 
      rgb(94 92 255));
    box-shadow: 0 0 58px rgba(139, 69, 255, 0.17), 0 0 38px rgba(59, 130, 246, 0.14);
  }
  60% {
    background-image: linear-gradient(to bottom right, 
      rgb(112 68 174), 
      rgb(46 104 231), 
      rgb(91 88 255));
    box-shadow: 0 0 56px rgba(139, 69, 255, 0.16), 0 0 36px rgba(59, 130, 246, 0.13);
  }
  65% {
    background-image: linear-gradient(to bottom right, 
      rgb(109 63 169), 
      rgb(44 99 224), 
      rgb(88 84 251));
    box-shadow: 0 0 54px rgba(139, 69, 255, 0.15), 0 0 34px rgba(59, 130, 246, 0.12);
  }
  70% {
    background-image: linear-gradient(to bottom right, 
      rgb(106 58 164), 
      rgb(42 94 217), 
      rgb(85 80 244));
    box-shadow: 0 0 52px rgba(139, 69, 255, 0.14), 0 0 32px rgba(59, 130, 246, 0.11);
  }
  75% {
    background-image: linear-gradient(to bottom right, 
      rgb(103 53 159), 
      rgb(40 89 210), 
      rgb(82 76 237));
    box-shadow: 0 0 50px rgba(139, 69, 255, 0.13), 0 0 30px rgba(59, 130, 246, 0.1);
  }
  80% {
    background-image: linear-gradient(to bottom right, 
      rgb(100 48 154), 
      rgb(38 84 203), 
      rgb(79 72 230));
    box-shadow: 0 0 48px rgba(139, 69, 255, 0.12), 0 0 28px rgba(59, 130, 246, 0.09);
  }
  85% {
    background-image: linear-gradient(to bottom right, 
      rgb(97 43 149), 
      rgb(36 79 196), 
      rgb(76 68 223));
    box-shadow: 0 0 46px rgba(139, 69, 255, 0.11), 0 0 26px rgba(59, 130, 246, 0.08);
  }
  90% {
    background-image: linear-gradient(to bottom right, 
      rgb(94 38 144), 
      rgb(34 74 189), 
      rgb(73 64 216));
    box-shadow: 0 0 44px rgba(139, 69, 255, 0.1), 0 0 24px rgba(59, 130, 246, 0.07);
  }
  95% {
    background-image: linear-gradient(to bottom right, 
      rgb(91 33 139), 
      rgb(32 69 182), 
      rgb(70 60 209));
    box-shadow: 0 0 42px rgba(139, 69, 255, 0.09), 0 0 22px rgba(59, 130, 246, 0.06);
  }
  100% { 
    background-image: linear-gradient(to bottom right, 
      rgb(88 28 135), 
      rgb(30 64 175), 
      rgb(67 56 202));
    box-shadow: 0 0 40px rgba(139, 69, 255, 0.08), 0 0 20px rgba(59, 130, 246, 0.05);
  }
}

.animate-hero-glow {
  animation: hero-glow 18s ease-in-out infinite;
}
</style>