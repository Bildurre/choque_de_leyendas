import { initWysiwygEditors } from '../../form/wysiwyg-editor';
import { initCostInputs } from '../../form/cost-input';

/**
 * Default handler for hero abilities pages
 * @param {string} action - Current CRUD action
 */
export default function heroAbilityHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
    case 'show':
      // Any show-specific functionality would go here
      break;
  }
}

/**
 * Common setup for hero ability form pages
 */
function setupFormPage() {
  initWysiwygEditors();
  initCostInputs();
}

// Named exports for direct router calls
export { setupFormPage as create };
export { setupFormPage as edit };