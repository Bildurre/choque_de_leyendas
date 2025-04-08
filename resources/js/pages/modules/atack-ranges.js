import { initImageUploaders } from '../../components/image-uploader';

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

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}