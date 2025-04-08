import { setupDeleteConfirmation } from '../../common/confirmations';

/**
 * Initialize hero ability show page
 */
export function initShowPage() {
  // Setup delete confirmation
  setupDeleteConfirmation('.delete-form', 'data-ability-name', 'habilidad');
}

document.addEventListener('DOMContentLoaded', initShowPage);