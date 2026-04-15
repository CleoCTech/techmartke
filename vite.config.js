import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
import { resolve } from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/ck-editor.css',
                'resources/css/app.css'
            ],
            ssr: 'resources/js/ssr.js',
            refresh: true,
            detectTls: false,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            // Inject the manifest into Laravel's blade via a static /manifest.webmanifest file
            registerType: 'prompt',
            // Build output: the SW + manifest go into public/ so Laravel serves them at root
            outDir: resolve('public'),
            base: '/',
            // Disable in dev to avoid interfering with Vite HMR
            devOptions: { enabled: false },
            // Keep the manifest and SW at predictable URLs so <link rel="manifest"> finds them
            filename: 'sw.js',
            manifestFilename: 'manifest.webmanifest',
            manifest: {
                id: '/',
                name: 'TechMart KE — Phones, Laptops & Accessories',
                short_name: 'TechMart KE',
                description: 'Kenya\'s trusted phone & computer marketplace. AI-powered smart shopping.',
                theme_color: '#000000',
                background_color: '#FFFFFF',
                display: 'standalone',
                orientation: 'portrait-primary',
                scope: '/',
                start_url: '/',
                lang: 'en',
                categories: ['shopping', 'business'],
                icons: [
                    { src: '/pwa/icon-192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
                    { src: '/pwa/icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
                    { src: '/pwa/icon-maskable-512.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
                ],
                shortcuts: [
                    { name: 'Browse Products', short_name: 'Shop', url: '/products', icons: [{ src: '/pwa/icon-192.png', sizes: '192x192' }] },
                    { name: 'Trade-In', short_name: 'Trade-In', url: '/trade-in', icons: [{ src: '/pwa/icon-192.png', sizes: '192x192' }] },
                    { name: 'VIP Program', short_name: 'VIP', url: '/vip', icons: [{ src: '/pwa/icon-192.png', sizes: '192x192' }] },
                ],
            },
            workbox: {
                // Precache only from the Vite build output directory
                globDirectory: resolve('public/build'),
                globPatterns: ['**/*.{js,css,woff,woff2}'],
                globIgnores: ['**/Admin*', '**/System*'],
                // The SW lives at /sw.js but assets are served from /build/...
                // Rewrite precache URLs so they resolve correctly from the SW scope
                modifyURLPrefix: {
                    '': '/build/',
                },
                // Prevent precaching files larger than 3 MB (product images, etc.)
                maximumFileSizeToCacheInBytes: 3 * 1024 * 1024,
                // Offline fallback for navigation requests
                navigateFallback: '/offline.html',
                navigateFallbackDenylist: [/^\/admin/, /^\/system/, /^\/api/, /^\/storage/, /^\/build/],
                // Runtime caching strategies
                runtimeCaching: [
                    // Google Fonts / Bunny Fonts — cache first, long TTL
                    {
                        urlPattern: /^https:\/\/fonts\.(bunny\.net|googleapis\.com|gstatic\.com)\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'fonts-cache',
                            expiration: { maxEntries: 30, maxAgeSeconds: 60 * 60 * 24 * 365 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                    // Product images served from /storage — stale-while-revalidate
                    {
                        urlPattern: /^https?:\/\/[^/]+\/storage\/.*\.(png|jpg|jpeg|webp|avif|gif|svg)$/i,
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'product-images',
                            expiration: { maxEntries: 200, maxAgeSeconds: 60 * 60 * 24 * 30 },
                        },
                    },
                    // Laravel/Inertia page GET requests — network first, fall back to cache
                    {
                        urlPattern: ({ request, url }) =>
                            request.method === 'GET' &&
                            !url.pathname.startsWith('/admin') &&
                            !url.pathname.startsWith('/api') &&
                            !url.pathname.startsWith('/system'),
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages-cache',
                            networkTimeoutSeconds: 5,
                            expiration: { maxEntries: 50, maxAgeSeconds: 60 * 60 * 24 },
                        },
                    },
                ],
                // Skip the SW entirely for admin/API routes
                skipWaiting: false,
                clientsClaim: false,
            },
            // Include the offline fallback + our static icons in the precache list
            includeAssets: [
                'favicon.ico',
                'offline.html',
                'pwa/icon-192.png',
                'pwa/icon-512.png',
                'pwa/icon-maskable-512.png',
                'pwa/apple-touch-icon.png',
            ],
            // Don't generate its own HTML — Laravel serves our app.blade.php
            injectRegister: null,
        }),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        },
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        cors: true,
        hmr: {
            host: 'techmart.test',
            port: 5173,
        },
    },
    resolve: {
        alias: {
          '@': resolve('./resources/js'),
        },
    },
});
