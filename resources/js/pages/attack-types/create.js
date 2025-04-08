// resources/js/pages/attack-types/create.js
import { initFormPage } from '../../common/page-initializers';

/**
 * Initialize attack type creation page
 */
export function initCreatePage() {
  // Initialize form with color input functionality
  initFormPage({
    hasColorInputs: true
  });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initCreatePage);