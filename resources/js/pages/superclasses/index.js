// resources/js/pages/superclasses/index.js
import { setupDeleteConfirmations } from '../../common/confirmations';
import { setupAlertDismissal } from '../../common/alerts';

document.addEventListener('DOMContentLoaded', function() {
  // Set up delete confirmations
  setupDeleteConfirmations('.delete-btn', 'data-superclass-name', 'superclase');
  
  // Set up alerts auto-dismissal
  setupAlertDismissal();
});