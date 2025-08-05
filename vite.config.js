import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: ({ name }) => {
                    // Force the name to be agnstk for all entries
                    return 'assets/first-case.js';
                },
                chunkFileNames: 'assets/agnstk-[name].js',
                assetFileNames: ({ name }) => {
                    if (name && name.endsWith('.css')) {
                        return 'assets/public-css.css';
                    }
                    if (name && name.endsWith('.js')) {
                        return 'assets/named-js.js';
                    }
                    return 'assets/fallback-[name][extname]';
                }
            }
        }
    }
});
