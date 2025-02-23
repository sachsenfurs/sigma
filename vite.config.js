import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss', 'resources/css/filament/admin/theme.css'],
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
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vue: ['vue', 'laravel-vue-i18n'],
                    axios: ['axios'],
                    jquery: ['jquery'],
                }
            }
        }
    },
    server: {
        cors: {
            origin: "*",
        }
    }
});
