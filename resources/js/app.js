import './bootstrap';
// import '../css/app.css';

import '../css/ck-editor.css'; 

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// CUSTOM
import { useNotification } from "@kyvg/vue3-notification";
import { createPinia } from 'pinia';
import VueSocialSharing from 'vue-social-sharing'

import Notifications from '@kyvg/vue3-notification'
// import { notify } from "@kyvg/vue3-notification";
import VueLoading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import 'gitart-vue-dialog/dist/style.css'
import { GDialog } from 'gitart-vue-dialog'
import { plugin as dialogPlugin } from 'gitart-vue-dialog'
import { Icon } from '@iconify/vue';

// import vSelect from 'vue-select-3';
// // import 'vue-select/dist/vue-select.css';
// import "vue-select-3/dist/vue-select.css";

// import $ from 'jquery';
// window.$ = window.jQuery = $;

// import 'jquery.appear';
// import 'jquery.easing';
// import 'counter-up';

import PrimeVue from 'primevue/config';
import TawkMessengerVue from '@tawk.to/tawk-messenger-vue-3';

import GuestBlankLayout from '@/Guest/Layouts/Blank.vue'
import GuestLayout from '@/Guest/Layouts/Guest.vue'
import SystemLayout from '@/System/Layouts/AppLayout.vue'


// const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const resolvePage = (name) => {
    return resolvePageComponent(`./${name}.vue`, import.meta.glob("./**/*.vue"));
};
  
createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: async (name) => {
        if (name.startsWith('Guest/')) {
            // console.log('Guest Layout is available');
            const page = await resolvePage(name);
            if (!page) {
                console.error(`Page component '${name}' not found.`);
                return;
            }
    
            const module = await page;
    
            if (!module || !module.default) {
                console.error(`Module or module.default is undefined for page '${name}'.`);
                return;
            }
    
            if (name.startsWith('Guest/Auth/')) {
                if (name === 'Guest/Auth/Login') {
                    module.default.layout = GuestBlankLayout;
                } else if (name === 'Guest/Auth/ResetPassword') {
                    module.default.layout = GuestBlankLayout;
                } else if (name === 'Guest/Auth/VerifyEmail') {
                    module.default.layout = GuestBlankLayout;
                }else if (name === 'Guest/Auth/ForgotPassword') {
                    module.default.layout = GuestBlankLayout;
                } else if (name === 'Guest/Auth/ConfirmPassword') {
                    module.default.layout = GuestBlankLayout;
                }
                else {
                    module.default.layout = GuestBlankLayout;
                }
            } else {
               module.default.layout = GuestLayout;
            }
  
        } else if (name.startsWith('Admin/') || name.startsWith('System/') || name.startsWith('Auth/')) {
            const page = await resolvePage(name);
    
            if (!page) {
                console.error(`Page component '${name}' not found.`);
                return;
            }
    
            const module = await page;
    
            if (!module || !module.default) {
                console.error(`Module or module.default is undefined for page '${name}'.`);
                return;
            }
    
            module.default.layout = SystemLayout;
        } else if(name.startsWith('Pages/Auth/')) {
            console.log('Pages/Auth/');
            const page = await resolvePage(name);
    
            if (!page) {
                console.error(`Page component '${name}' not found.`);
                return;
            }
    
            const module = await page;
    
            if (!module || !module.default) {
                console.error(`Module or module.default is undefined for page '${name}'.`);
                return;
            }
            module.default.layout = GuestBlankLayout;
        } else if(name.startsWith('Auth/')) {
            console.log('Auth/');
            const page = await resolvePage(name);
    
            if (!page) {
                console.error(`Page component '${name}' not found.`);
                return;
            }
    
            const module = await page;
    
            if (!module || !module.default) {
                console.error(`Module or module.default is undefined for page '${name}'.`);
                return;
            }
            module.default.layout = GuestBlankLayout;
        } else {
            console.log('else');
        }
    
        // console.log('name: ' + name);
        return resolvePage(name);
    },

    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

            .use(Notifications)
            .use(VueLoading)
            .use(PrimeVue)
            // .use(PrimeVue, { unstyled: true })
            .component('loading',VueLoading)
            .component('Head',Head)
            .component('Link',Link)
            .component('GDialog', GDialog)
            .component('Icon', Icon)
            .use(createPinia())
           
            .use(VueSocialSharing, {
                //add custom network
                networks: {
                        linkedin: 'https://linkedin.com/share?url=@url&title=@title'
                }
            })
            .mount(el);
    },
    progress: {
        color: '#0855A8',
        showSpinner: true,
    },
});
