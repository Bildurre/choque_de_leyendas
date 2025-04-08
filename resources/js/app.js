// resources/js/app.js
import './bootstrap';
import '../scss/app.scss';
import './alpine-init';
import './components/sidebar';
import { setupAlertDismissal } from './common/alerts';
import EntityCardToggle from './components/entity-card-toggle';
import { initWysiwygEditors } from './components/wysiwyg-editor';
import CostInput from './components/cost-input';

document.addEventListener('DOMContentLoaded', function() {
  EntityCardToggle.init();

  // Initialize WYSIWYG editors if present
  if (document.querySelector('.wysiwyg-editor')) {
    initWysiwygEditors();
  }
  
  // Initialize cost inputs if present
  if (document.querySelector('.cost-input')) {
    CostInput.init();
  }
});

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
  if (path.startsWith('/admin/attack-types')) {
    initAttackTypesPages(path);
  } 
  else if (path.startsWith('/admin/attack-subtypes')) {
    initAttackSubtypesPages(path);
  } 
  else if (path.startsWith('/admin/attack-ranges')) {
    initAttackRangesPages(path);
  } 
  else if (path.startsWith('/admin/hero-abilities')) {
    initHeroAbilitiesPages(path);
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
    import('./pages/factions/create')
  } 
  else if (path.includes('/edit')) {
    import('./pages/factions/edit')
  } 
  else {
    import('./pages/factions/index')
  }
}

/**
 * Inicializa páginas relacionadas con clases de héroe
 */
function initHeroClassPages(path) {
  if (path.includes('/create')) {
    import('./pages/hero-classes/create')
  } 
  else if (path.includes('/edit')) {
    import('./pages/hero-classes/edit')
  } 
  else {
    import('./pages/hero-classes/index')
  }
}

/**
 * Inicializa páginas relacionadas con superclases
 */
function initSuperclassPages(path) {
  if (path.includes('/create')) {
  } 
  else if (path.includes('/edit')) {
  } 
  else {
    import('./pages/superclasses/index')
  }
}

/**
 * Inicializa páginas relacionadas con habilidades de héroe
 */
function initHeroAbilitiesPages(path) {
  if (path.includes('/create')) {
    import('./pages/hero-abilities/create');
  } 
  else if (path.includes('/edit')) {
    import('./pages/hero-abilities/edit');
  }
  else if (path.includes('/show')) {
    import('./pages/hero-abilities/show');
  }
  else {
    import('./pages/hero-abilities/index');
  }
}

/**
 * Inicializa páginas relacionadas con tipos de ataque
 */
function initAttackTypesPages(path) {
  if (path.includes('/index') || !path.includes('/')) {
    import('./pages/attack-types/index');
  }
}

/**
 * Inicializa páginas relacionadas con subtipos de ataque
 */
function initAttackSubtypesPages(path) {
  if (path.includes('/index') || !path.includes('/')) {
    import('./pages/attack-subtypes/index');
  }
}

/**
 * Inicializa páginas relacionadas con rangos de ataque
 */
function initAttackRangesPages(path) {
  if (path.includes('/index') || !path.includes('/')) {
    import('./pages/attack-ranges/index');
  }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initCurrentPage);