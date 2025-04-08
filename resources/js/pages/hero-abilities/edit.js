import { initFormPage } from '../../common/page-initializers';
import { setupPassiveToggle, setupTypeSubtypeRelationship } from './form-handlers';

/**
 * Initialize hero ability edit page
 */
export function initEditPage() {
  // Setup passive checkbox toggle
  setupPassiveToggle();
  
  // Setup type and subtype relationship with current selection
  const currentSubtypeId = document.getElementById('current-subtype-id');
  const subtypeId = currentSubtypeId ? currentSubtypeId.value : null;
  
  setupTypeSubtypeRelationship(subtypeId);
  
  // Initialize form with common features
  initFormPage({
    hasFileUploads: false
  });
}

// Initialize the page when DOM is loaded
document.addEventListener('DOMContentLoaded', initEditPage);