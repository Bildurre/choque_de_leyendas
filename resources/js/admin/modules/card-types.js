/**
 * Default handler for card type pages
 * @param {string} action - Current CRUD action
 */
export default function cardTypeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup card type form page
 */
function setupFormPage() {
  // Add specific form functionality if needed
}

// Named exports for direct router calls
export const create = setupFormPage;
export const edit = setupFormPage;