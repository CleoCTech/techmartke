<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted  } from 'vue';

import {useNotify} from "@/Composables/useNotify";

const {notification} = useNotify();

const navBarData = ref({});

const footerData = ref({});
const isLoading = ref(true);
const showBookAppointment  = ref(false)
const showMenu  = ref(false)

let clickHandler = (event) => {
  if (!event.target.closest('.meanmenu-reveal')) {
    showMenu.value = false;
  }
}
let clickHandler2 = (event) => {
  if (!event.target.closest('.dot-menu')) {
    showBookAppointment.value = false;
  }
}
const getNavData = () => {
  axios.get('/topbar-data').then(response => {
    navBarData.value = response.data;
    isLoading.value = false;
  })
  .catch((error) => {
    isLoading.value = false;
  });
};

onMounted(() => {
    // getFooterData();
    // getNavData();
    // document.addEventListener('click', clickHandler);
    // document.addEventListener('click', clickHandler2);
});

function toggleBookMenu(){
  showBookAppointment.value = !showBookAppointment.value;
}
function toggleMenu(){
  showMenu.value = !showMenu.value;
}
function getFooterData(){
  axios.get('/footer-data')
    .then(response => {
      // console.log(response.data)
      footerData.value = response.data
      isLoading.value = false;
    })
    .catch(error => {
      console.error(error)
      notification(error, 'error');
      isLoading.value = false;
    })
}

onUnmounted(() => {
  
  // Clean up resources, remove event listeners, etc.
  // document.removeEventListener('click', clickHandler);
  // document.removeEventListener('click', clickHandler2);
  // document.removeEventListener('click', clickHandlerMobile);
});
</script>
<template>
<div>
    <div v-if="isLoading" class="preloader">
      <div class="loader">
        <div class="sbl-half-circle-spin"></div>
      </div>
    </div>

    <header v-else >
        <Link href="/" class="brand">
          Twiggy Travels
        </Link>
        <div class="menu-btn"></div>
        <div class="navigation">
            <div class="navigation-items">
                <Link :href="route('home')">Home</Link>
                <Link href="/">About</Link>
                <Link href="/">Explore</Link>
                <Link href="/">Gallery</Link>
                <a href="/login">Login</a>
            </div>
        </div>
    </header>

</div>
</template>
