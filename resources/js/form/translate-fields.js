/**
 * Manage translate fields functionality
 * Handles tabbed language inputs for multilingual content
 */

/**
 * Initialize translate fields
 * @param {string} selector - CSS selector for translate field containers
 */
export function initTranslateFields(selector = '.translate-field') {
  const translateFields = document.querySelectorAll(selector);
  
  translateFields.forEach(field => {
    if (field.dataset.initialized === 'true') return;
    
    const tabs = field.querySelectorAll('.translate-field__tab');
    const wrappers = field.querySelectorAll('.translate-field__input-wrapper');
    
    // Setup tab functionality
    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        // Get locale from tab
        const locale = this.textContent.trim().toLowerCase();
        
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('translate-field__tab--active'));
        
        // Add active class to clicked tab
        this.classList.add('translate-field__tab--active');
        
        // Hide all wrappers
        wrappers.forEach(w => {
          w.style.display = 'none';
          w.classList.remove('translate-field__input-wrapper--active');
        });
        
        // Show wrapper for selected locale
        wrappers.forEach(w => {
          const input = w.querySelector('input, textarea');
          if (input && input.name.includes(`[${locale}]`)) {
            w.style.display = 'block';
            w.classList.add('translate-field__input-wrapper--active');
          }
        });
      });
    });
    
    // Set as initialized
    field.dataset.initialized = 'true';
  });
}

/**
 * Initialize translate fields on page load
 */
export function setupTranslateFields() {
  document.addEventListener('DOMContentLoaded', function() {
    initTranslateFields();
  });
  
  // Re-initialize on dynamic content load
  document.addEventListener('contentLoaded', function() {
    initTranslateFields();
  });
}

// Export both functions
export default {
  initTranslateFields,
  setupTranslateFields
};