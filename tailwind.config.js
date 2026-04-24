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
                // Couleurs du logo AT Logement
                'at-orange': {
                    DEFAULT: '#f3a43e',
                    '50': '#fef5e6',
                    '100': '#fde9cc',
                    '200': '#fbd399',
                    '300': '#f9bd66',
                    '400': '#f7a733',
                    '500': '#f3a43e', // Orange principal
                    '600': '#d18a2e',
                    '700': '#af701f',
                    '800': '#8d5610',
                    '900': '#6b3c01',
                },
                'at-orange-alt': {
                    DEFAULT: '#f97316', // Orange alternatif
                    '50': '#fff7ed',
                    '100': '#ffedd5',
                    '200': '#fed7aa',
                    '300': '#fdba74',
                    '400': '#fb923c',
                    '500': '#f97316',
                    '600': '#ea580c',
                    '700': '#c2410c',
                    '800': '#9a3412',
                    '900': '#7c2d12',
                },
                'at-green': {
                    DEFAULT: '#86c14f',
                    '50': '#f0f9e8',
                    '100': '#e1f3d1',
                    '200': '#c3e7a3',
                    '300': '#a5db75',
                    '400': '#87cf47',
                    '500': '#86c14f', // Vert principal
                    '600': '#6ba13f',
                    '700': '#50812f',
                    '800': '#35611f',
                    '900': '#1a410f',
                },
                'at-green-alt': {
                    DEFAULT: '#87c04f', // Vert alternatif
                },
                'at-gray': {
                    DEFAULT: '#352f30',
                    '50': '#f5f4f4',
                    '100': '#ebe9e9',
                    '200': '#d7d3d3',
                    '300': '#c3bdbd',
                    '400': '#afa7a7',
                    '500': '#9b9191',
                    '600': '#726961', // Gris moyen
                    '700': '#4a4142',
                    '800': '#352f30', // Gris foncé principal
                    '900': '#1f1b1c',
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out',
                'fade-in-up-delay': 'fadeInUp 0.8s ease-out 0.3s backwards',
                'fade-in-up-delay-2': 'fadeInUp 0.8s ease-out 0.6s backwards',
                'fade-in-scale': 'fadeInScale 0.8s ease-out',
                'slide-in-left': 'slideInLeft 0.8s ease-out',
                'slide-in-right': 'slideInRight 0.8s ease-out',
                'zoom-banner': 'zoomBanner 20s ease-in-out infinite alternate',
                'bounce-slow': 'bounce 2s infinite',
                'pulse-slow': 'pulse 3s infinite',
                'spin-slow': 'spin 3s linear infinite',
            },
            keyframes: {
                fadeInUp: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(50px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                fadeInScale: {
                    '0%': {
                        opacity: '0',
                        transform: 'scale(0.9) translateY(30px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'scale(1) translateY(0)',
                    },
                },
                slideInLeft: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(-50px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                slideInRight: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(50px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                zoomBanner: {
                    '0%': {
                        transform: 'scale(1)',
                    },
                    '100%': {
                        transform: 'scale(1.1)',
                    },
                },
            },
            transitionDuration: {
                '400': '400ms',
                '600': '600ms',
                '800': '800ms',
                '1200': '1200ms',
            },
            boxShadow: {
                '3xl': '0 35px 60px -12px rgba(0, 0, 0, 0.25)',
                '4xl': '0 45px 80px -15px rgba(0, 0, 0, 0.3)',
                'at-orange': '0 10px 30px -5px rgba(243, 164, 62, 0.3)',
                'at-green': '0 10px 30px -5px rgba(134, 193, 79, 0.3)',
            },
        },
    },

    plugins: [forms],
};
