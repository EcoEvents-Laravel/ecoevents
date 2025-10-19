import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import postcss from './postcss.config.js'; // <-- ON IMPORTE LA CONFIGURATION

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // --- ON AJOUTE CETTE SECTION POUR FORCER L'UTILISATION DE POSTCSS ---
    css: {
        postcss,
    },
    // --------------------------------------------------------------------
});