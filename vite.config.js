import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/custom.css',
                'resources/js/custom.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    custom: ['resources/js/custom.js'],
                },
                entryFileNames: (chunkInfo) => {
                    // untuk entry point JS
                    if (chunkInfo.name === 'custom') {
                        return 'assets/custom.min.js'; // tanpa hash
                    }
                    return 'assets/[name]-[hash].min.js'; // default
                },
                // Konfigurasi nama file untuk asset (CSS, gambar, dll)
                assetFileNames: (assetInfo) => {
                    // Cek jika nama asset adalah custom.css yang dihasilkan
                    const fileName = assetInfo.names?.[0];
                    if (fileName.includes('custom.css')) {
                        return 'assets/custom.min.css';
                    }
                    // Asset lainnya menggunakan pola default
                    return 'assets/[name]-[hash].min.[ext]';
                },
                chunkFileNames: 'assets/[name]-[hash].js' // Pola untuk chunks
            }
        }
    }
});
