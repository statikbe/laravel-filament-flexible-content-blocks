import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        outDir: path.resolve(__dirname, 'resources/dist'),
        emptyOutDir: true,
        lib: {
            entry: {
                'background-video': path.resolve(__dirname, 'resources/js/components/background-video/index.ts'),
            },
            formats: ['cjs'],
            fileName: (format, entryName) => `js/${entryName}.js`
        },
        rollupOptions: {
            external: ['alpinejs'], /* assume Alpine is loaded by host app */
            output: {
                globals: {
                    alpinejs: 'Alpine'
                }
            }
        },
    },
});
