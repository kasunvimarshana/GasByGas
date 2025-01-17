import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
// import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@fortawesome': path.resolve(__dirname, 'node_modules/@fortawesome'),
        }
    },
    build: {
        rollupOptions: {
            input: {
                app: path.resolve(__dirname, 'resources/js/app.js'),
            },
            output: {
                // automatically processes assets (like images, fonts) and copies them to the output folder ('public/assets').
                assetFileNames: (assetInfo) => {
                    return 'assets/[name].[hash][extname]'; // Default output for other assets
                }
            },
        },
    },
});
