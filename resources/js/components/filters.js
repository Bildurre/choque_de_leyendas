import Choices from 'choices.js';

export default function initAdminFilters() {
  // Initialize Choices.js on filter selects
  const filterSelects = document.querySelectorAll('[data-choices]');
  
  filterSelects.forEach(select => {
    const choices = new Choices(select, {
      removeItemButton: true,
      // searchEnabled: true,
      // searchPlaceholderValue: 'Buscar...',
      itemSelectText: '',
      noResultsText: 'No se encontraron resultados',
      noChoicesText: 'No hay opciones para elegir',
      placeholderValue: select.getAttribute('placeholder') || 'Seleccione opciones...',
      // position: 'auto', // 'auto' intentará posicionar el dropdown según el espacio disponible
      renderChoiceLimit: -1, // Sin límite de opciones mostradas
    });
    
    // Añadir un evento para cerrar el menú después de seleccionar una opción
    select.addEventListener('choice', function() {
      // Cierra el menú después de un pequeño retraso para permitir que la selección se complete
      setTimeout(() => {
        choices.hideDropdown();
      }, 100);
    });
    
    // Corregir el posicionamiento del dropdown cuando se abre
    select.addEventListener('showDropdown', function() {
      // Obtener el contenedor del dropdown
      const dropdown = select.parentNode.querySelector('.choices__list--dropdown');
      if (dropdown) {
        // Asegurar que el dropdown no se recorte
        // Si es necesario, establecer posición absoluta relativa al documento
        const rect = dropdown.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        if (rect.bottom > windowHeight) {
          // Si el dropdown se sale de la ventana, posicionarlo arriba del select
          dropdown.style.bottom = '100%';
          dropdown.style.top = 'auto';
        }
      }
    });
  });  
}