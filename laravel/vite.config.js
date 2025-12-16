import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ command, mode, isSsrBuild, isPreview }) => ({
    assetsInclude: command == 'serve' ? ['resources/healthcheck.txt'] : [], // to expose healthcheck.txt raw
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            //usePolling: true, // uncomment if HMR isn't working
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/main.tsx'],
            refresh: true,
        }),
        tailwindcss(),
        react(),
    ],
}));
