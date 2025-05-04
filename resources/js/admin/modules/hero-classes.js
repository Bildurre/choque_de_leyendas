import { initWysiwygEditors } from '../../form/wysiwyg-editor';

/**
 * Default handler for hero class pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
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

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}