import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Warna Primary - Biru Laut Gelap (Dark Ocean Blue)
                primary: {
                    50: '#e0f2ff',
                    100: '#b3daff',
                    200: '#80c1ff',
                    300: '#4da8ff',
                    400: '#1a75ff',
                    500: '#004080',  // Base - Biru Laut Gelap
                    600: '#003366',
                    700: '#002952',  // Darker
                    800: '#001f3d',
                    900: '#001529',  // Darkest
                },
                // Warna Secondary - Maroon
                secondary: {
                    50: '#fef2f2',
                    100: '#fde8e8',
                    200: '#fbd5d5',
                    300: '#f8b4b4',
                    400: '#f98080',
                    500: '#800020',  // Base - Maroon
                    600: '#6b001a',
                    700: '#590016',
                    800: '#470012',
                    900: '#35000d',
                },
            },
        },
    },

    plugins: [forms],
};
