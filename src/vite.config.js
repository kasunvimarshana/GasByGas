import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
// import fs from 'fs';
import copyImagesPostBuildPlugin from './copyImagesPostBuildPlugin.js';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Main CSS entry point
                'resources/js/app.js',  // Main JavaScript entry point
            ],
            refresh: true, // Enable Hot Module Replacement for development
        }),
        copyImagesPostBuildPlugin(),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'), // Bootstrap alias
            '~bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'), // Bootstrap Icons alias
            '@fortawesome': path.resolve(__dirname, 'node_modules/@fortawesome'), // FontAwesome alias
            '~admin-lte': path.resolve(__dirname, 'node_modules/admin-lte'), // AdminLTE alias
            '~sanitize.css': path.resolve(__dirname, 'node_modules/sanitize.css'), // Sanitize.css alias
            '~sweetalert2': path.resolve(__dirname, 'node_modules/sweetalert2'), // SweetAlert2 alias
        }
    },
    build: {
        // manifest: true,
        rollupOptions: {
            input: {
                // Define entry points for JavaScript and CSS
                mainJs: path.resolve(__dirname, 'resources/js/app.js'), // JavaScript entry
                mainCss: path.resolve(__dirname, 'resources/css/app.css'), // CSS entry
            },
            output: {
                // Define output file structure for processed assets
                // automatically processes assets (like images, fonts) and copies them to the output folder ('public/assets').
                assetFileNames: (assetInfo) => {
                    /*
                    // Extract file names or provide a fallback
                    const fileNames = assetInfo.names || []; // Array of names
                    const fileNameString = fileNames.join(''); // Combine names into a single string
                    const [baseName, extension] = fileNameString.split('.'); // Extract base name and extension
                    // Handle CSS files specifically
                    // if (/\.(css)$/.test(assetInfo.name)) {
                    //     return 'assets/css/[name].[hash][extname]';
                    // }
                    if (extension === 'css') {
                        return `assets/css/${baseName}.[hash].${extension}`; // CSS goes into 'assets/css'
                    }
                    // Default output for other assets
                    return `assets/${baseName}.[hash].${extension}`;
                    */

                    return 'assets/[name].[hash][extname]'; // Default output for other assets
                }
            },
        },
    },
});
