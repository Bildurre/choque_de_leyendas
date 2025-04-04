// resources/js/app.js
import './bootstrap';
import '../scss/app.scss';
import './alpine-init';
import './components/sidebar';
import { setupAlertDismissal } from './common/alerts';

// Initialize common functionality
setupAlertDismissal();

/**
 * Inicializa los componentes específicos para la página actual
 */
function initCurrentPage() {
  const path = window.location.pathname;
  
  // Inicializar componentes de la entidad específica
  if (path.startsWith('/admin/factions')) {
    initFactionPages(path);
  } 
  else if (path.startsWith('/admin/hero-classes')) {
    initHeroClassPages(path);
  } 
  else if (path.startsWith('/admin/superclasses')) {
    initSuperclassPages(path);
  }
  
  // Inicializar componentes comunes basados en elementos en la página
  initCommonComponents();
}

/**
 * Inicializa componentes comunes basados en elementos en la página
 */
function initCommonComponents() {
  // Inicializar cargador de imágenes si está presente
  if (document.querySelector('.image-uploader')) {
    import('./components/image-uploader')
      .then(module => {
        if (module.default && typeof module.default.init === 'function') {
          module.default.init();
        }
      })
      .catch(error => {
        console.warn('No se pudo cargar el módulo de carga de imágenes:', error);
      });
  }
}

/**
 * Inicializa páginas relacionadas con facciones
 */
function initFactionPages(path) {
  if (path.includes('/create')) {
    // Módulos para la página de creación de facciones
    // Actualmente no hay un archivo específico para esta ruta
  } 
  else if (path.includes('/edit')) {
    // Módulos para la página de edición de facciones
    import('./pages/factions/edit')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de edición de facciones:', error);
      });
  } 
  else {
    // Módulos para la página de listado de facciones
    import('./pages/factions/index')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de índice de facciones:', error);
      });
  }
}

/**
 * Inicializa páginas relacionadas con clases de héroe
 */
function initHeroClassPages(path) {
  if (path.includes('/create')) {
    // Módulos para la página de creación de clases de héroe
    import('./pages/hero-classes/create')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de creación de clases de héroe:', error);
      });
  } 
  else if (path.includes('/edit')) {
    // Módulos para la página de edición de clases de héroe
    import('./pages/hero-classes/edit')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de edición de clases de héroe:', error);
      });
  } 
  else {
    // Módulos para la página de listado de clases de héroe
    import('./pages/hero-classes/index')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de índice de clases de héroe:', error);
      });
  }
}

/**
 * Inicializa páginas relacionadas con superclases
 */
function initSuperclassPages(path) {
  if (path.includes('/create')) {
    // Módulos para la página de creación de superclases
    // Actualmente no hay un archivo específico para esta ruta
  } 
  else if (path.includes('/edit')) {
    // Módulos para la página de edición de superclases
    // Actualmente no hay un archivo específico para esta ruta
  } 
  else {
    // Módulos para la página de listado de superclases
    import('./pages/superclasses/index')
      .catch(error => {
        console.warn('No se pudo cargar el módulo de índice de superclases:', error);
      });
  }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initCurrentPage);