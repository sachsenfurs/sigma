import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

function manualChunks(id) {
    if (id.includes('node_modules/vue/') || id.includes('node_modules/laravel-vue-i18n/')) {
        return 'vue';
    }

    if (id.includes('node_modules/axios/')) {
        return 'axios';
    }

    if (id.includes('node_modules/jquery/')) {
        return 'jquery';
    }
}

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
                manualChunks,
            }
        }
    },
    server: {
        cors: {
            origin: "*",
        }
    }
});
