<template>
  <section class="relative bg-white w-full overflow-hidden">
    <!-- Mobile Layout -->
    <div class="block lg:hidden">
      <div class="relative">
        <!-- Single Background Image for Mobile -->
        <div class="relative aspect-[4/3]">
          <img 
            src="https://images.unsplash.com/photo-1511649475669-e288648b2339?auto=format&fit=crop&w=800&q=80" 
            alt="Worship service"
            class="w-full h-full object-cover grayscale"
          />
          <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Blue Circle - Mobile -->
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 sm:w-48 sm:h-48">
          <div class="w-full h-full bg-[#00A5E7] rounded-full flex items-center justify-center">
            <div class="relative w-20 h-20 sm:w-24 sm:h-24">
              <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-white text-2xl sm:text-3xl font-bold tracking-wider">ABLE</span>
              </div>
              <div class="absolute inset-0 animate-spin-slow">
                <svg viewBox="0 0 100 100" class="w-full h-full">
                  <defs>
                    <path
                      id="circlePathMobile"
                      d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0"
                      fill="none"
                    />
                  </defs>
                  <text class="text-[6px] sm:text-[7px] fill-white uppercase">
                    <textPath href="#circlePathMobile" startOffset="0%">
                      A Campaign to Accelerate the Vision of the Austin Stone •
                    </textPath>
                  </text>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Content -->
      <div class="px-6 py-8 space-y-4">
        <div class="space-y-3">
          <h3 class="text-xs sm:text-sm font-medium tracking-wider text-gray-600">
            CURRENT SERMON SERIES
          </h3>
          <h2 class="text-2xl sm:text-3xl font-medium text-gray-900">[Able]</h2>
          <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
            If God is able, how can we be ready? The ABLE sermon series explores how our church can pursue a call to make disciples for years to come in a rapidly changing city.
          </p>
        </div>
        <button class="w-full bg-[#00A5E7] text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-[#0094d1] transition-colors">
          ABOUT THE SERIES
        </button>
      </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
      <div class="grid grid-cols-12">
        <!-- Image Section - 8 columns -->
        <div class="col-span-8 relative">
          <div class="relative aspect-[16/9]">
            <!-- Image Grid -->
            <div class="absolute inset-0 grid grid-cols-3 gap-0">
              <div class="relative overflow-hidden">
                <img 
                  src="https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=800&q=80" 
                  alt="Church gathering"
                  class="w-full h-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/20"></div>
              </div>
              
              <div class="relative overflow-hidden">
                <img 
                  src="https://images.unsplash.com/photo-1511649475669-e288648b2339?auto=format&fit=crop&w=800&q=80" 
                  alt="Worship service"
                  class="w-full h-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/20"></div>
              </div>
              
              <div class="relative overflow-hidden">
                <img 
                  src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80" 
                  alt="Community event"
                  class="w-full h-full object-cover grayscale"
                />
                <div class="absolute inset-0 bg-black/20"></div>
              </div>
            </div>
            
            <!-- Blue Circle - Desktop -->
            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-72 h-72">
              <div class="w-full h-full bg-[#00A5E7] rounded-full flex items-center justify-center">
                <div class="relative w-32 h-32">
                  <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-white text-4xl font-bold tracking-wider">ABLE</span>
                  </div>
                  <div class="absolute inset-0 animate-spin-slow">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                      <defs>
                        <path
                          id="circlePathDesktop"
                          d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0"
                          fill="none"
                        />
                      </defs>
                      <text class="text-[8px] fill-white uppercase">
                        <textPath href="#circlePathDesktop" startOffset="0%">
                          A Campaign to Accelerate the Vision of the Austin Stone •
                        </textPath>
                      </text>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Content Section - 4 columns -->
        <div class="col-span-4 flex items-center">
          <div class="px-12 space-y-6">
            <div class="space-y-4">
              <h3 class="text-sm font-medium tracking-wider text-gray-600">
                CURRENT SERMON SERIES
              </h3>
              <h2 class="text-4xl font-medium text-gray-900">[Able]</h2>
              <p class="text-gray-600 leading-relaxed">
                If God is able, how can we be ready? The ABLE sermon series explores how our church can pursue a call to make disciples for years to come in a rapidly changing city.
              </p>
            </div>
            <button class="bg-[#00A5E7] text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-[#0094d1] transition-colors">
              ABOUT THE SERIES
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// No additional script needed
</script>

<style scoped>
.animate-spin-slow {
  animation: spin 20s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Prevent image drag */
img {
  user-drag: none;
  -webkit-user-drag: none;
  user-select: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}
</style>

