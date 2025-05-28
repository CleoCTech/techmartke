<template>
  <swiper
    :modules="modules"
    :slides-per-view="slidesPerView"
    :space-between="spaceBetween"
    navigation
    @swiper="onSwiper"
    @slideChange="onSlideChange"
    :breakpoints="slidesPerView != 1? breakpoints:breakpoints2"
    :loop="isLoop"
    :loopedSlides="1"
    :autoplay="{ delay: 5000 }"
  >
    <slot></slot>
  </swiper>
</template>
<script>
  import { Swiper } from 'swiper/vue';
  // import Swiper core and required modules
  import { Navigation, Pagination, Scrollbar, A11y, Autoplay} from 'swiper/modules';
  // Import Swiper styles
  import 'swiper/css';
  import 'swiper/css/navigation';
  import 'swiper/css/pagination';
  import 'swiper/css/scrollbar';

  export default {
    components: {
      Swiper,
    },
    props:{
        slidesPerView:{default:3},
        spaceBetween:{default:20},
        isLoop:{default:false},
    },
     setup() {
      const onSwiper = (swiper) => {
        //console.log(swiper);
      };
      const onSlideChange = () => {
        //console.log('slide change');
      };
      const navigation2 = {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      };
      const breakpoints = {
        // when window width is >= 320px
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 480px
        480: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 640px
        640: {
            slidesPerView: 2,
            spaceBetween: 30
        },
        // when window width is >= 640px
        800: {
            slidesPerView: 3,
            spaceBetween: 30
        }
    }
    const breakpoints2 = {
        // when window width is >= 320px
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },

    }
      return {
        onSwiper,
        onSlideChange,
        modules: [Navigation, Pagination, Scrollbar, A11y,Autoplay],
        breakpoints,breakpoints2,navigation2
      };
    },
  };
</script>
<style>
.swiper-container:hover .swiper-button-prev,
.swiper-container:hover .swiper-button-next {
  opacity: 1;
}
.swiper-button-prev,
.swiper-button-next {
  opacity: 0;
  transition: opacity 0.2s;
}

.swiper-button-prev:hover {
  opacity: 1;
}
.swiper-button-next:hover {
  opacity: 1;
}
</style>