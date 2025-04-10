import { initImageUploaders } from '../../components/image-uploader';

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

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}