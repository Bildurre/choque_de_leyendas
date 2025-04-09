import { setupTypeSubtypeRelationship } from './hero-ability-helpers';
import { initWysiwygEditors } from '../../components/wysiwyg-editor';
import { initCostInputs } from '../../components/cost-input';

/**
 * Default handler for hero abilities pages
 * @param {string} action - Current CRUD action
 */
export default function heroAbilityHandler(action) {
  switch (action) {
    case 'create':
      create();
      break;
    case 'edit':
      edit();
      break;
    case 'show':
      show();
      break;
  }
}

/**
 * Initialize hero ability creation page
 */
export function create() {
  setupFormPage();
}

/**
 * Initialize hero ability edit page
 */
export function edit() {
  // Get current subtype ID for edit page
  const currentSubtypeEl = document.getElementById('current-subtype-id');
  const currentSubtypeId = currentSubtypeEl ? currentSubtypeEl.value : null;
  
  setupFormPage(currentSubtypeId);
}

/**
 * Initialize hero ability show page
 */
export function show() {
  // Any show page specific functionality
}

/**
 * Common setup for hero ability form pages
 * @param {string|null} currentSubtypeId - Current subtype ID for edit pages
 */
function setupFormPage(currentSubtypeId = null) {
  // Setup type and subtype relationship
  setupTypeSubtypeRelationship(currentSubtypeId);
  
  // Initialize WYSIWYG editor and cost inputs
  initWysiwygEditors();
  initCostInputs();
}