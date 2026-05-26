import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // 💡 Sailでの通信トラブルを防ぐための設定を追記します
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/customer-edit.css',
                'resources/css/customer-edit-history.css',
                'resources/css/customer-index.css',
                'resources/css/customer-show.css',
                'resources/js/app.js',
                'resources/css/reservations.css',
                'resources/js/reservations.js',
            ],
            refresh: true,
        }),
    ],
});
