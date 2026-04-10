import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import createServer from '@inertiajs/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

const appName = 'TechMart KE';

const resolvePage = (name) => {
    return resolvePageComponent(`./${name}.vue`, import.meta.glob('./**/*.vue'));
};

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,

        resolve: async (name) => {
            // SSR doesn't need layout assignments since Customer pages
            // already wrap themselves in CustomerLayout, and admin/system
            // pages are not SSR-critical (they're behind auth).
            return resolvePage(name);
        },

        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                })
                .use(createPinia())
                .component('Head', Head)
                .component('Link', Link);
        },
    }),
);
