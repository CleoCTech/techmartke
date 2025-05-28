<script setup>
import {ref, onMounted, watch} from 'vue'

const isLoading =  ref(true);
const props = defineProps({
    faqs:Object,
});

const open = ref(false);

onMounted(() => {
    if (props.faqs) {
        props.faqs.forEach(element => {
            element.open= false;
        });
    }
    // console.log(props.faqs);
    isLoading.value = false;
})

function toggleOpen(index) 
{
    props.faqs = props.faqs.map((faq, i) => {
        if (index === faq.uuid) {
            faq.open = !faq.open;

        } else {
            faq.open = false;
        }
    })
}
      
</script>
<template>
    <div>
        <div v-if="isLoading" class="preloader">
            <div class="loader">
                <div class="sbl-half-circle-spin"></div>
            </div>
        </div>
        <div v-for="(faq, index) in faqs" :key="index" :class="faq.open ? 'faq open' : 'faq'" 
        v-on:click="toggleOpen(faq.uuid)">
            <div class="question">{{ faq.question }}</div>
            <div class="answer" v-html="faq.answer"></div>
        </div>
    </div>
</template>
<style scoped>
.faq {
  display: block;
  width: 100%;
  max-width: 768px;
  margin: 15px auto;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
  background-color: #FFF;
}
.faq .question {
  position: relative;
  color: #3c3c3c;
  font-size: 20px;
  transition: all 0.4s linear;
}
.faq .question::after {
  content: '';
  position: absolute;
  top: 50%;
  right: 0px;
  transform: translateY(-50%) rotate(0deg);
  width: 30px;
  height: 30px;
  background-image: url('./arrow-down-mint.svg');
  background-position: center;
  background-size: contain;
  background-repeat: no-repeat;
  
  transition: all 0.2s linear;
}
.faq.open .question {
  margin-bottom: 15px;
}
.faq.open .question::after {
  transform: translateY(-50%) rotate(90deg);
}
.faq .answer {
  color: #3c3c3c;
  font-size: 18px;
  opacity: 0;
  max-height: 0px;
  overflow-y: hidden;
  transition: all 0.4s ease-out;
}
.faq.open .answer {
  opacity: 1;
  max-height: 1000px;
}
</style>