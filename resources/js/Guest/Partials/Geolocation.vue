<script setup>
import { ref, onMounted } from 'vue';

const apiKey = 'AIzaSyCLNvruqejh6c-eU0TGpk67AdW-wfZfuaI'; // Replace with your actual Google Maps API key
const latitude = -1.13711; // Replace with the latitude of your shop
const longitude = 36.96981; // Replace with the longitude of your shop

const mapLoaded = ref(false);
const isLoading =  ref(true);

onMounted(() => {
  const script = document.createElement('script');
  script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}`;
  script.onload = () => {
    mapLoaded.value = true;
    initMap();
  };
  document.head.appendChild(script);
  isLoading.value = false;
});

function initMap() {
  const map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: latitude, lng: longitude },
    zoom: 14,
  });
  const marker = new google.maps.Marker({
    position: { lat: latitude, lng: longitude },
    map,
    title: 'Dentistry Now',
  });
}

</script>
<template>
    <div>
        <div v-if="isLoading" class="preloader">
            <div class="loader">
                <div class="sbl-half-circle-spin"></div>
            </div>
        </div>

        <div v-else class="hospital-about-area-without-color ptb-20">
            <div id="map" class="hospital-about-area-without-color ptb-100" ></div>
        </div>
        
    </div>
</template>
<style scoped>

</style>