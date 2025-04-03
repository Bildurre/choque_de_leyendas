// resources/js/app.js

import './bootstrap';
import '../scss/app.scss';
import './alpine-init';
import './components/sidebar';

// Función para inicializar páginas específicas
function initPages() {
  // Detectar la ruta actual para cargar solo los scripts necesarios
  const path = window.location.pathname;
  
  // Alternativa: detectar un atributo data en el body
  // const pageType = document.body.dataset.page;
  
  // Importar los scripts específicos de cada página según la ruta
  if (path.includes('/admin/factions') && !path.includes('/create') && !path.includes('/edit')) {
    import('./factions/index').then(module => {
      // Opcional: se puede llamar a alguna función de inicialización específica
      // module.default.init();
    });
  }
  
  // Aquí puedes añadir más condiciones para otras páginas
  // if (path.includes('/admin/heroes')) {
  //   import('./pages/heroes/index');
  // }
}

// Inicializar las páginas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initPages);