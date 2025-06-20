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
        // Asegurar nombres consistentes para los archivos
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith('.css')) {
            // Si es el CSS principal de app.scss
            if (assetInfo.name === 'app.css') {
              return 'assets/app-[hash].css';
            }
            // Para otros CSS (como choices.css)
            return 'assets/[name]-[hash][extname]';
          }
          return 'assets/[name]-[hash][extname]';
        },
        manualChunks: (id) => {
          // Para módulos específicos
          if (id.includes('chart.js')) {
            return 'vendors-chart';
          }
          
          // Para los módulos de pages
          if (id.includes('resources/js/pages/modules/')) {
            return 'page-modules';
          }
          
          // NO crear chunks separados para CSS importado desde JS
          // Esto ayuda a evitar que choices.css se extraiga como archivo separado
          if (id.includes('choices.js') && id.includes('.css')) {
            return undefined; // Incluir en el chunk principal
          }
        }
      }
    },
    // Desactivar la división de código CSS
    cssCodeSplit: false
  },
  optimizeDeps: {
    include: ['chart.js']
  }
});