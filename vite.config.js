import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // App entry points
                'resources/css/app.css',
                'resources/js/app.js',

                // VNKR Design System — built separately cho caching tối ưu
                // (tokens thay đổi ít, ui thay đổi trung bình, fe thay đổi nhiều)
                'public/assets/css/vnkr-tokens.css',
                'public/assets/css/vnkr-ui.css',
                'public/assets/css/vnkr-fe.css',
                'public/assets/css/vnkr-admin.css',
                'public/assets/js/vnkr-ui.js',
            ],
            // Hot reload khi edit Blade views hoặc CSS
            refresh: [
                'resources/views/**/*.blade.php',
                'public/assets/css/*.css',
                'public/assets/js/*.js',
            ],
        }),
    ],
    build: {
        // Tách chunks để tận dụng HTTP/2 multiplexing
        rollupOptions: {
            output: {
                // Giữ tên file gốc — không hash để SW cache stable
                entryFileNames: '[name].js',
                assetFileNames: '[name][extname]',
                manualChunks: undefined,
            },
        },
        // Sourcemap trong dev, tắt production
        sourcemap: process.env.NODE_ENV !== 'production',
    },
});
