// vite.config.js (updated)
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/theme-detector.js',
        'resources/scss/app.scss', 
        'resources/js/app.js'
      ],
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
          // Create a separate chunk for the theme detector
          if (id.includes('theme-detector.js')) {
            return 'theme-detector';
          }
          
          // For specific modules
          if (id.includes('chart.js')) {
            return 'vendors-chart';
          }
          
          // For the modules of pages
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