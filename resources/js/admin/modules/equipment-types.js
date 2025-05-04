/**
 * Default handler for equipment types pages
 * @param {string} action - Current CRUD action
 */
export default function equipmentTypeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup equipment type form page
 */
function setupFormPage() {
  // Add any specific form functionality here if needed
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}