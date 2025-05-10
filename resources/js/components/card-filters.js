export default function initCardFilters() {
  const filterForms = document.querySelectorAll('.filters-form');
  
  if (!filterForms.length) return;
  
  filterForms.forEach(form => {
    // Referencia a los elementos del formulario
    const filterFields = form.querySelectorAll('select, input:not([type="checkbox"])');
    const clearButton = form.querySelector('a[href*="clear"]');
    
    // Función para activar/desactivar el botón de limpiar
    function updateClearButtonVisibility() {
      let hasActiveFilters = false;
      
      // Verificar si hay algún filtro activo
      filterFields.forEach(field => {
        if (field.value !== '') {
          hasActiveFilters = true;
        }
      });
      
      // También verificar checkboxes
      const checkboxes = form.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          hasActiveFilters = true;
        }
      });
      
      // Actualizar visibilidad o estado del botón
      if (clearButton) {
        clearButton.style.opacity = hasActiveFilters ? '1' : '0.5';
      }
    }
    
    // Escuchar cambios en los campos del formulario
    filterFields.forEach(field => {
      field.addEventListener('change', updateClearButtonVisibility);
    });
    
    // Escuchar cambios en los checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', updateClearButtonVisibility);
    });
    
    // Inicializar estado
    updateClearButtonVisibility();
  });
}