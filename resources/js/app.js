import './bootstrap';
import '../scss/app.scss';
import Alpine from 'alpinejs';

// Core components and utilities
import { setupComponents } from './components';
import { setupPageHandlers } from './pages';
import { setupCommonHandlers } from './common';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize main app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Alpine.js
  Alpine.start();
  
  // Setup global components that might be present on any page
  setupComponents();
  
  // Setup common handlers (alerts, confirmations, etc.)
  setupCommonHandlers();
  
  // Initialize page-specific functionality
  setupPageHandlers();
});