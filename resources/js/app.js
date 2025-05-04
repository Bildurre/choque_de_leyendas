import './bootstrap';
import '../scss/app.scss';
import Alpine from 'alpinejs';

// Core utilities
import { setupAlerts } from './core/alerts';
import { setupConfirmations } from './core/confirmations';

// Admin functionality
import { initSidebar } from './admin/sidebar';

// Router - loads appropriate module based on current route
import { setupPageHandlers } from './router';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize main app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Alpine.js
  Alpine.start();
  
  // Setup global alert handlers
  setupAlerts();
  
  // Setup confirmation dialogs
  setupConfirmations();
  
  // Initialize sidebar if in admin section
  if (document.querySelector('.admin-sidebar')) {
    initSidebar();
  }
  
  // Initialize page-specific functionality
  setupPageHandlers();

  // Initialize TinyMCE if we have wysiwyg-editor elements
  if (document.querySelector('.wysiwyg-editor')) {
    import('./form/wysiwyg-editor').then(module => {
      module.initWysiwygEditors();
    }).catch(err => {
      console.error('Error loading TinyMCE:', err);
    });
  }
});