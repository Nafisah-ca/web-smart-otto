/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50:  '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#f43f5e',
                    600: '#e11d48',
                    700: '#be123c',
                    800: '#9f1239',
                    900: '#881337',
                },
                navy: {
                    50:  '#f0f3f9',
                    100: '#dce3f0',
                    200: '#b9c8e2',
                    300: '#8da6cf',
                    400: '#6183b8',
                    500: '#3f63a0',
                    600: '#2d4d87',
                    700: '#1e3a6e',
                    800: '#152c57',
                    900: '#0d1f40',
                    950: '#080f20',
                },
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
