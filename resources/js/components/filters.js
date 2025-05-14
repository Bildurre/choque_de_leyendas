import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

export default function initAdminFilters() {
  // Initialize Choices.js on filter selects
  const filterSelects = document.querySelectorAll('[data-choices]');
  
  filterSelects.forEach(select => {
    const choices = new Choices(select, {
      removeItemButton: true,
      searchEnabled: true,
      searchPlaceholderValue: 'Buscar...',
      itemSelectText: '',
      noResultsText: 'No se encontraron resultados',
      noChoicesText: 'No hay opciones para elegir',
      placeholderValue: select.getAttribute('placeholder') || 'Seleccione opciones...',
    });
    
    // Añadir un evento para cerrar el menú después de seleccionar una opción
    select.addEventListener('choice', function() {
      // Cierra el menú después de un pequeño retraso para permitir que la selección se complete
      setTimeout(() => {
        choices.hideDropdown();
      }, 100);
    });
  });  
}