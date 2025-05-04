import { initImageUploaders } from '../../form/image-uploader';

/**
 * Default handler for superclass pages
 * @param {string} action - Current CRUD action
 */
export default function superclassHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup superclass form page
 */
function setupFormPage() {
  initImageUploaders();
}

// Named exports for direct router calls
export const create = setupFormPage;
export const edit = setupFormPage;