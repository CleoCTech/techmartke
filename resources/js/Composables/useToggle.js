import { ref } from 'vue';

const holder = ref(false);

export function useToggle() {
    function toggle(){
       return holder.value = !holder.value
    }

    return {toggle};
}