import { initFormPage } from '../../common/page-initializers';
import { setupPassiveToggle } from './form-handlers';
import { setupTypeSubtypeRelationship } from './form-handlers';

/**
 * Initialize hero ability creation page
 */
export function initCreatePage() {
  // Setup passive checkbox toggle
  setupPassiveToggle();
  
  // Setup type and subtype relationship
  setupTypeSubtypeRelationship();
  
  // Initialize form with common features
  initFormPage({
    hasFileUploads: false
  });
}

// Initialize the page when DOM is loaded
document.addEventListener('DOMContentLoaded', initCreatePage);