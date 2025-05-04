import './bootstrap';
import '../scss/app.scss';
import Alpine from 'alpinejs';

// Core utilities
import { setupAlerts } from './core/alerts';
import { setupConfirmations } from './core/confirmations';

// Admin functionality
import { initSidebar } from './admin/sidebar';

// Form components
import { initImageUploaders } from './form/image-uploader';
import { initWysiwygEditors } from './form/wysiwyg-editor';
import { initCostInputs } from './form/cost-input';
import { setupTranslateFields } from './form/translate-fields';

// Router - loads appropriate module based on current route
import { setupPageHandlers } from './router';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize main app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded - initializing application');
  
  // Initialize Alpine.js
  Alpine.start();
  console.log('Alpine.js initialized');
  
  // Setup global alert handlers
  setupAlerts();
  
  // Setup confirmation dialogs
  setupConfirmations();
  
  // Initialize sidebar if in admin section
  if (document.querySelector('.admin-sidebar')) {
    initSidebar();
  }
  
  // Initialize common form components
  initImageUploaders();
  setupTranslateFields();
  initCostInputs();
  
  // Initialize page-specific functionality
  setupPageHandlers();

  // Initialize TinyMCE if we have wysiwyg-editor elements
  if (document.querySelector('.wysiwyg-editor')) {
    initWysiwygEditors();
  }
  
  // Trigger a custom event when everything is initialized
  document.dispatchEvent(new CustomEvent('appInitialized'));
  
  console.log('Application initialization completed');
});

// Also set up event listeners for AJAX-loaded content
document.addEventListener('contentLoaded', function() {
  console.log('New content loaded - initializing components');
  
  // Re-initialize form components on new content
  initImageUploaders();
  setupTranslateFields();
  initCostInputs();
  
  // Initialize TinyMCE for any new editors
  if (document.querySelector('.wysiwyg-editor:not([data-initialized])')) {
    initWysiwygEditors();
  }
});