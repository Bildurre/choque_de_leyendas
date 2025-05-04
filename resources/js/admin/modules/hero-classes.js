import { initWysiwygEditors } from '../../form/wysiwyg-editor';

/**
 * Default handler for hero class pages
 * @param {string} action - Current CRUD action
 */
export default function heroClassHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup hero class form page
 */
function setupFormPage() {
  initWysiwygEditors();
}

// Named exports for direct router calls
export const create = setupFormPage;
export const edit = setupFormPage;