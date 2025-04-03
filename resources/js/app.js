// resources/js/app.js
import './bootstrap';
import '../scss/app.scss';
import './alpine-init';
import './components/sidebar';
import { setupAlertDismissal } from './common/alerts';

// Initialize common functionality
setupAlertDismissal();

// Función para inicializar páginas específicas
function initPages() {
  const path = window.location.pathname;
  
  // Cargar scripts específicos basados en la ruta
  if (path.includes('/admin/factions')) {
    if (path.includes('/edit')) {
      import('./pages/factions/edit');
    } else if (!path.includes('/create')) {
      import('./pages/factions/index');
    }
  }
  
  if (path.includes('/admin/hero-classes')) {
    if (path.includes('/create')) {
      import('./pages/hero-classes/create').then(module => {
        module.setupModifiersValidation();
      });
    } else if (path.includes('/edit')) {
      import('./pages/hero-classes/edit').then(module => {
        module.setupModifiersValidation();
      });
    } else if (!path.includes('/create')) {
      import('./pages/hero-classes/index');
    }
  }
  
  if (path.includes('/admin/superclasses') && !path.includes('/create') && !path.includes('/edit')) {
    import('./pages/superclasses/index');
  }
  
  // Initialize image uploader where needed
  if (document.querySelector('.image-uploader')) {
    import('./components/image-uploader').then(module => {
      module.default.init();
    });
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initPages);