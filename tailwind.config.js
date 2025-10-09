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
                // Warna Primary - Biru Laut
                primary: {
                    50: '#e6f0ff',
                    100: '#b3d1ff',
                    200: '#80b3ff',
                    300: '#4d94ff',
                    400: '#1a75ff',
                    500: '#0056b3',  // Base - Biru Laut
                    600: '#004080',
                    700: '#003366',  // Darker
                    800: '#002952',
                    900: '#001f3f',  // Darkest
                },
                // Warna Secondary - Merah Maroon
                secondary: {
                    50: '#ffe6e6',
                    100: '#ffb3b3',
                    200: '#ff8080',
                    300: '#ff4d4d',
                    400: '#cc0000',
                    500: '#990000',  // Base - Merah Maroon
                    600: '#800000',  // Maroon
                    700: '#660000',
                    800: '#4d0000',
                    900: '#330000',
                },
            },
        },
    },

    plugins: [forms],
};
