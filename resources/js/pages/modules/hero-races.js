/**
 * Default handler for hero race pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
 */
export default function heroRaceHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup hero race form page
 */
function setupFormPage() {
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}