// resources/js/admin/form-select.js

import Choices from 'choices.js';

/**
 * Initialize Choices.js on form selects
 * Looks for selects with data-choices attribute or multiple attribute
 */
export default function initFormSelects() {
  // Find all selects that should use Choices.js
  // Either with data-choices attribute OR multiple attribute
  const formSelects = document.querySelectorAll(
    '.form-select[data-choices], .form-select[multiple]'
  );
  
  if (formSelects.length === 0) {
    return;
  }

  formSelects.forEach(select => {
    // Skip if already initialized
    if (select.classList.contains('choices__input')) {
      return;
    }

    const isMultiple = select.hasAttribute('multiple');
    const placeholder = select.getAttribute('data-placeholder') 
      || select.getAttribute('placeholder')
      || (isMultiple 
          ? (window.translations?.forms?.choices?.placeholder || 'Select options...') 
          : '');

    // Initialize Choices.js
    const choices = new Choices(select, {
      removeItemButton: isMultiple, // Only show remove button for multiple
      itemSelectText: window.translations?.forms?.choices?.itemSelect || '',
      noResultsText: window.translations?.forms?.choices?.noResults || 'No results found',
      noChoicesText: window.translations?.forms?.choices?.noChoices || 'No choices to choose from',
      placeholderValue: placeholder,
      searchEnabled: true,
      searchPlaceholderValue: window.translations?.forms?.choices?.search || 'Type to search...',
      renderChoiceLimit: -1, // Show all choices
      shouldSort: false, // Keep original order
      allowHTML: true,
    });

    // For single selects: close dropdown after selection
    if (!isMultiple) {
      select.addEventListener('choice', function() {
        setTimeout(() => {
          choices.hideDropdown();
        }, 100);
      });
    }

    // Fix dropdown positioning if it goes off-screen
    select.addEventListener('showDropdown', function() {
      const dropdown = select.parentNode.querySelector('.choices__list--dropdown');
      if (dropdown) {
        const rect = dropdown.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        // If dropdown would go below viewport, show it above
        if (rect.bottom > windowHeight) {
          dropdown.style.bottom = '100%';
          dropdown.style.top = 'auto';
          dropdown.style.marginBottom = '4px';
        } else {
          dropdown.style.bottom = 'auto';
          dropdown.style.top = '100%';
          dropdown.style.marginTop = '4px';
        }
      }
    });

    // Store Choices instance on element for later access if needed
    select.choicesInstance = choices;
  });
}

/**
 * Destroy all Choices.js instances
 * Useful for cleanup or reinitialization
 */
export function destroyFormSelects() {
  const formSelects = document.querySelectorAll('.form-select[data-choices], .form-select[multiple]');
  
  formSelects.forEach(select => {
    if (select.choicesInstance) {
      select.choicesInstance.destroy();
      delete select.choicesInstance;
    }
  });
}

/**
 * Reinitialize form selects
 * Useful after dynamic content updates
 */
export function reinitFormSelects() {
  destroyFormSelects();
  initFormSelects();
}