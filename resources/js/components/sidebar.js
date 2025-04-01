/**
 * Sidebar functionality handler
 * Manages sidebar state changes based on window size and user interactions
 */
document.addEventListener('DOMContentLoaded', () => {
  // Función para sincronizar el estado del sidebar con el tamaño de la ventana
  const syncSidebarState = () => {
    const bodyElement = document.querySelector('body');
    
    if (window.innerWidth > 768) {
      // En desktop, asegurarse de que el sidebar esté abierto
      if (!bodyElement.classList.contains('sidebar-open')) {
        bodyElement.classList.add('sidebar-open');
        // Actualizar el estado en Alpine.js si está disponible
        if (window.Alpine && bodyElement._x_dataStack && bodyElement._x_dataStack[0]) {
          bodyElement._x_dataStack[0].sidebarOpen = true;
        }
      }
    } else {
      // En móvil/tablet, asegurarse de que el sidebar esté cerrado
      if (bodyElement.classList.contains('sidebar-open')) {
        bodyElement.classList.remove('sidebar-open');
        // Actualizar el estado en Alpine.js si está disponible
        if (window.Alpine && bodyElement._x_dataStack && bodyElement._x_dataStack[0]) {
          bodyElement._x_dataStack[0].sidebarOpen = false;
        }
      }
    }
  };

  // Escuchar eventos de cambio de tamaño
  let resizeTimer;
  window.addEventListener('resize', () => {
    // Usar debounce para no ejecutar demasiadas veces
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncSidebarState, 100);
  });
});