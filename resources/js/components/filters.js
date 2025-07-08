import Choices from 'choices.js';

export default function initAdminFilters() {
  
  // Initialize standard Choices.js on other filter selects
  const filterSelects = document.querySelectorAll('[data-choices]:not([data-cost-filter])');
  
  filterSelects.forEach(select => {
    const choices = new Choices(select, {
      removeItemButton: true,
      itemSelectText: '',
      noResultsText: 'No se encontraron resultados',
      noChoicesText: 'No hay opciones para elegir',
      placeholderValue: select.getAttribute('placeholder') || 'Seleccione opciones...',
      renderChoiceLimit: -1,
      shouldSort: false, // Mantener el orden original
    });
    
    // Close dropdown after selection
    select.addEventListener('choice', function() {
      setTimeout(() => {
        choices.hideDropdown();
      }, 100);
    });
    
    // Fix dropdown positioning
    select.addEventListener('showDropdown', function() {
      const dropdown = select.parentNode.querySelector('.choices__list--dropdown');
      if (dropdown) {
        const rect = dropdown.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        if (rect.bottom > windowHeight) {
          dropdown.style.bottom = '100%';
          dropdown.style.top = 'auto';
        }
      }
    });
  });  
}