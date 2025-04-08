// resources/js/pages/modules/attack-types.js
import { setupColorInputs } from '../../common/forms';

/**
 * Default handler for attack type pages
 * @param {string} action - Current CRUD action
 */
export default function attackTypeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup attack type form page
 */
function setupFormPage() {
  setupColorInputs();
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}