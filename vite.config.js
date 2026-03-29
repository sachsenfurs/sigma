import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/site.js',
                'resources/js/schedule-list.js',
                'resources/js/schedule-calendar.js',
                'resources/sass/app.scss',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
        vue({
            // template: {
            //     transformAssetUrls: {
            //         base: null,
            //         includeAbsolute: false,
            //     },
            // },
        }),
    ],
    server: {
        cors: {
            origin: "*",
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['color-functions'],
            },
        },
    },
});
