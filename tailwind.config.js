import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Lexend', ...defaultTheme.fontFamily.sans],           // Body / specs / descriptions
                heading: ['Manrope', ...defaultTheme.fontFamily.sans],        // h1-h6 headings
                price: ['Inter', ...defaultTheme.fontFamily.sans],            // Prices & numbers
                cta: ['Urbanist', ...defaultTheme.fontFamily.sans],           // Buttons & CTAs
            },
            colors: {
                ablue: '#0855A8',
                amaroon: '#EA231D',
            }
        },
    },

    plugins: [forms, typography],
};
