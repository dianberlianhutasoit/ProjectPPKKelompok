import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
<<<<<<< HEAD
import { bunny } from 'laravel-vite-plugin/fonts';
=======
>>>>>>> feature/person-1-backend
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
<<<<<<< HEAD
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
=======
>>>>>>> feature/person-1-backend
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
