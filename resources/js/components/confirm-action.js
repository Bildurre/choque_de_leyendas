export default function initConfirmActions() {
  // Obtener todos los botones que necesitan confirmación
  const confirmButtons = document.querySelectorAll('[data-confirm-message]');
  
  // Agregar event listener a cada botón
  confirmButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      // Prevenir la acción por defecto
      event.preventDefault();
      
      // Obtener el mensaje de confirmación
      const confirmMessage = this.getAttribute('data-confirm-message');
      
      // Mostrar diálogo de confirmación
      if (window.confirm(confirmMessage)) {
        // Si se confirma, enviar el formulario
        const form = this.closest('form');
        if (form) {
          form.submit();
        }
      }
    });
  });
}