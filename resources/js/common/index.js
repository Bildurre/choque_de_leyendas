import { setupAlertDismissal } from './alerts';
import { setupDeleteConfirmations } from './confirmations';
import { setupFormComponents } from './forms';

/**
 * Set up common handlers used across multiple pages
 */
export function setupCommonHandlers() {
  // Initialize alert dismissal
  setupAlertDismissal();
  
  // Setup delete confirmations for any delete buttons
  setupDeleteConfirmations();
  
  // Setup common form components (color inputs, file validation, etc.)
  setupFormComponents();
}

// Export individual handlers for direct use
export {
  setupAlertDismissal,
  setupDeleteConfirmations,
  setupFormComponents
};