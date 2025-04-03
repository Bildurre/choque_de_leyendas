// resources/js/pages/hero-classes/index.js
import { setupDeleteConfirmations } from '../../common/confirmations';
import { setupAlertDismissal } from '../../common/alerts';

document.addEventListener('DOMContentLoaded', function() {
  // Set up delete confirmations
  setupDeleteConfirmations('.delete-btn', 'data-hero-class-name', 'clase');
  
  // Set up alerts auto-dismissal
  setupAlertDismissal();
});