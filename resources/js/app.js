import './bootstrap';
import '../scss/app.scss';
import Alpine from 'alpinejs';

// Core utilities
import { setupAlerts } from './core/alerts';
import { setupConfirmations } from './core/confirmations';

// Admin functionality
import { initSidebar } from './admin/sidebar';

// Form components
import { initFormComponents } from './form/form-components';

// Router
import { setupPageHandlers } from './router';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize main app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Alpine.js
  Alpine.start();
  
  // Setup global handlers
  setupAlerts();
  setupConfirmations();
  
  // Initialize sidebar if in admin section
  if (document.querySelector('.admin-sidebar')) {
    initSidebar();
  }
  
  // Initialize common form components
  initFormComponents();
  
  // Initialize page-specific functionality
  setupPageHandlers();
  
  // Trigger a custom event when everything is initialized
  document.dispatchEvent(new CustomEvent('appInitialized'));
});

// Re-initialize components for AJAX-loaded content
document.addEventListener('contentLoaded', function() {
  initFormComponents();
});