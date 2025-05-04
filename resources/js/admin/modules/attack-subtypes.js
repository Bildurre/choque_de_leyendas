/**
 * Default handler for attack subtype pages
 * @param {string} action - Current CRUD action
 */
export default function attackSubtypeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup attack subtype form page
 */
function setupFormPage() {
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}