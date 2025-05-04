import { initImageUploaders } from '../../form/image-uploader.js';

/**
 * Default handler for attack range pages
 * @param {string} action - Current CRUD action
 */
export default function attackRangeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup attack range form page
 */
function setupFormPage() {
  initImageUploaders();
}

// Named exports for direct router calls
export const create = setupFormPage;
export const edit = setupFormPage;