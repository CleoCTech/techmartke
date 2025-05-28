import { useNotification } from "@kyvg/vue3-notification";

const { notify }  = useNotification()

export function useNotify() {
    function notification(text, type = 'success') {

        return notify({
            text: text,
            type:type,
        });
    }

    //return whatever you want to expose to outside world
    return { notification };
}