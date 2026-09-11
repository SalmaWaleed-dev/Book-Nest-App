import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// NOTE: BookNest's actual CSS/JS (public/css/app.css, public/js/app.js) are
// loaded directly via asset() in resources/views/layouts/app.blade.php and
// do NOT depend on this build — `npm run build` is optional for this app to
// function. This file is provided so the standard Laravel asset pipeline is
// available if you want to migrate to Vite-managed assets later.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
