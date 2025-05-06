export default function initNotifications() {
  // Seleccionar todos los botones de cierre de notificaciones
  const closeButtons = document.querySelectorAll('.notification__close');
  
  // Agregar event listener a cada botón
  closeButtons.forEach(button => {
    button.addEventListener('click', function() {
      const notification = this.closest('.notification');
      
      // Agregar clase para la animación de salida
      notification.classList.add('is-closing');
      
      // Eliminar la notificación después de la animación
      setTimeout(() => {
        notification.remove();
      }, 300); // Debe coincidir con la duración de la animación
    });
  });
  
  // Auto-cerrar las notificaciones de éxito después de 5 segundos
  const successNotifications = document.querySelectorAll('.notification--success');
  successNotifications.forEach(notification => {
    setTimeout(() => {
      // Solo cerrar si la notificación aún existe en el DOM
      if (document.body.contains(notification)) {
        notification.classList.add('is-closing');
        
        setTimeout(() => {
          notification.remove();
        }, 300);
      }
    }, 5000);
  });
}