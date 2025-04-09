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
        manualChunks: {
          'page-modules': [
            'resources/js/pages/modules/*.js',
          ],
          'vendors-chart': [
            'chart.js'
          ]
        }
      }
    }
  },
  optimizeDeps: {
    include: ['chart.js']
  }
});
