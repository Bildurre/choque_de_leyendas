// Component initializers

// Import component initializers
import { initEntityCards } from './entity-card';
import { initWysiwygEditors } from './wysiwyg-editor';
import { initCostInputs } from './cost-input';
import { initImageUploaders } from './image-uploader';
import { initSidebar } from './sidebar';

/**
 * Initialize all components based on their presence in the DOM
 */
export function setupComponents() {
  // Initialize sidebar functionality
  initSidebar();
  
  // Check for presence of components and initialize them
  if (document.querySelector('.entity-card')) {
    initEntityCards();
  }
  
  if (document.querySelector('.wysiwyg-editor')) {
    initWysiwygEditors();
  }
  
  if (document.querySelector('.cost-input')) {
    initCostInputs();
  }
  
  if (document.querySelector('.image-uploader')) {
    initImageUploaders();
  }
}

// Export individual component initializers for direct use
export {
  initEntityCards,
  initWysiwygEditors,
  initCostInputs,
  initImageUploaders,
  initSidebar
};