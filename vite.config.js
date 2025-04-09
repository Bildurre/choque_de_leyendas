import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
  plugins: [
      laravel({
          input: ['resources/scss/app.scss', 'resources/js/app.js'],
          refresh: true,
      }),
      viteStaticCopy({
        targets: [
          {
            src: 'node_modules/tinymce/**/*',
            dest: 'tinymce'
          },
        ]
      }),
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('resources/js/pages/modules/')) {
            return 'page-modules';
          }
        }
      }
    }
  }
});
