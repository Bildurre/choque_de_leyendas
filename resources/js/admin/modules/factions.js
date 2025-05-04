import { setupColorInputs } from '../../form/color-input';
import { initImageUploaders } from '../../form/image-uploader';

/**
 * Default handler for faction pages
 * @param {string} action - Current CRUD action
 */
export default function factionHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Common setup for faction form pages
 */
function setupFormPage() {
  setupColorInputs();
  initImageUploaders();
}

// Named exports for direct router calls
export const create = setupFormPage;
export const edit = setupFormPage;