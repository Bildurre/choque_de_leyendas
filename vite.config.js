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
        manualChunks: (id) => {
          // Para módulos específicos
          if (id.includes('chart.js')) {
            return 'vendors-chart';
          }
          
          // Para los módulos de pages
          if (id.includes('resources/js/pages/modules/')) {
            return 'page-modules';
          }
        }
      }
    }
  },
  optimizeDeps: {
    include: ['chart.js']
  }
});