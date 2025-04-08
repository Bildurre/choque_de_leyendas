/**
 * Sidebar functionality handler
 * Manages sidebar state changes based on window size and user interactions
 */
document.addEventListener('DOMContentLoaded', () => {
  // Crear el overlay para cerrar el sidebar
  const createOverlay = () => {
    // Verificar si el overlay ya existe
    if (!document.querySelector('.sidebar-overlay')) {
      const overlay = document.createElement('div');
      overlay.className = 'sidebar-overlay';
      document.querySelector('.admin-main-container').appendChild(overlay);
      
      // Añadir evento para cerrar sidebar al hacer clic
      overlay.addEventListener('click', () => {
        const bodyElement = document.querySelector('body');
        bodyElement.classList.remove('sidebar-open');
        
        // Actualizar el estado en Alpine.js si está disponible
        if (window.Alpine && bodyElement._x_dataStack && bodyElement._x_dataStack[0]) {
          bodyElement._x_dataStack[0].sidebarOpen = false;
        }
      });
    }
  };

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

  // Crear overlay al cargar la página
  createOverlay();

  // Escuchar eventos de cambio de tamaño
  let resizeTimer;
  window.addEventListener('resize', () => {
    // Usar debounce para no ejecutar demasiadas veces
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncSidebarState, 100);
  });
  
  // Estado inicial del sidebar basado en el tamaño de ventana
  syncSidebarState();
});