const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/*.js',
        './resources/ts/*.ts',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                noto: "'Noto Sans TC', sans-serif"
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: [
                        {
                            a: {
                                color: theme('colors.blue.400'),
                                'text-decoration': 'none',
                            },
                            'a:hover': {
                                color: theme('colors.blue.500'),
                                'text-decoration': 'underline',
                            },
                            'ol > li::marker, ul > li::marker': {
                                color: theme('colors.gray.900'),
                            },
                            'pre, code': {
                                'white-space': 'pre-wrap !important',
                            },
                            'code::before, code::after': {
                                content: '',
                            },
                        },
                    ],
                },
                dark: {
                    css: [
                        {
                            color: theme('colors.gray.50'),
                            a: {
                                color: theme('colors.blue.400'),
                                'text-decoration': 'none',
                            },
                            'a:hover': {
                                color: theme('colors.blue.300'),
                                'text-decoration': 'underline',
                            },
                            strong: {
                                color: theme('colors.gray.50'),
                            },
                            'mark strong': {
                                color: theme('colors.gray.900'),
                            },
                            'ol > li::marker, ul > li::marker': {
                                color: theme('colors.gray.50'),
                            },
                            hr: {
                                borderColor: theme('colors.gray.200'),
                            },
                            blockquote: {
                                color: theme('colors.gray.50'),
                                borderLeftColor: theme('colors.gray.400'),
                            },
                            'h1, h2, h3, h4': {
                                color: theme('colors.gray.50'),
                            },
                            'figure figcaption': {
                                'color': '#f9fafb !important',
                                'backgroundColor': '#6b7280 !important'
                            },
                            code: {
                                color: theme('colors.gray.50'),
                            },
                            pre: {
                                color: theme('colors.gray.200'),
                                backgroundColor: theme('colors.gray.800'),
                            },
                            'thead th': {
                                color: theme('colors.gray.50'),
                            },
                        },
                    ],
                },
            }),
        },
    },
    plugins: [
        require("@tailwindcss/forms")({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
    ],
};
