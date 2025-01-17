import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
// import fs from 'fs';
import copyImagesPostBuildPlugin from './copyImagesPostBuildPlugin.js';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        copyImagesPostBuildPlugin(),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'),
            '@fortawesome': path.resolve(__dirname, 'node_modules/@fortawesome'),
            '~admin-lte': path.resolve(__dirname, 'node_modules/admin-lte'),
            '~sanitize.css': path.resolve(__dirname, 'node_modules/sanitize.css'),
            '~sweetalert2': path.resolve(__dirname, 'node_modules/sweetalert2'),
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
