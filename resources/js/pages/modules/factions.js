import { setupColorInputs } from '../../common/forms';
import { initImageUploaders } from '../../components/image-uploader';

/**
 * Default handler for faction pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
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
 * Setup faction form page
 */
export function create() {
  setupFormPage();
}

/**
 * Setup faction edit page
 */
export function edit() {
  setupFormPage();
}

/**
 * Common setup for faction form pages
 */
function setupFormPage() {
  setupColorInputs();
  initImageUploaders();
}