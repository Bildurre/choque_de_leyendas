// resources/js/pages/attack-types/edit.js
import { initFormPage } from '../../common/page-initializers';

/**
 * Initialize attack type edit page
 */
export function initEditPage() {
  // Initialize form with color input functionality
  initFormPage({
    hasColorInputs: true
  });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initEditPage);