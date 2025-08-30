export default function initConfirmActions() {
  // Manejar clicks en elementos con confirmación
  document.addEventListener('click', function(event) {
    // Buscar si el elemento clickeado o sus padres tienen data-confirm o data-confirm-message
    const element = event.target.closest('[data-confirm-message], [data-confirm]');
    
    if (element) {
      // Obtener el mensaje de confirmación
      const confirmMessage = element.getAttribute('data-confirm-message') || element.getAttribute('data-confirm');
      
      if (confirmMessage) {
        // Mostrar diálogo de confirmación
        if (!window.confirm(confirmMessage)) {
          // Si no se confirma, cancelar la acción
          event.preventDefault();
          event.stopPropagation();
          return false;
        }
        // Si se confirma, dejar que el evento continúe normalmente
      }
    }
  });
}