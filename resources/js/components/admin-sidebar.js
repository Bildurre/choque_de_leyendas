export default function initSidebar() {
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const layout = document.querySelector('.admin-layout');
  
  if (!sidebarToggle || !layout) return;
  
  // Función para verificar si estamos en viewport de tablet o superior
  function isTabletOrLarger() {
    return window.matchMedia('(min-width: 48rem)').matches; // 768px/16 = 48rem
  }
  
  // Función para manejar el toggle del sidebar
  function toggleSidebar() {
    layout.classList.toggle('sidebar-visible');
    
    // Guardamos el estado en localStorage (solo para móvil)
    const isVisible = layout.classList.contains('sidebar-visible');
    if (!isTabletOrLarger()) {
      localStorage.setItem('sidebarVisible', isVisible ? 'true' : 'false');
    }
  }
  
  // Evento para el botón de hamburguesa
  sidebarToggle.addEventListener('click', toggleSidebar);
  
  // Ajuste cuando cambia el tamaño de la ventana
  function handleResize() {
    if (isTabletOrLarger()) {
      // En tablet o superior, aseguramos que sidebar-visible no esté presente
      layout.classList.remove('sidebar-visible');
    } else {
      // En móvil, SIEMPRE ocultamos el sidebar al cambiar el tamaño
      layout.classList.remove('sidebar-visible');
      localStorage.setItem('sidebarVisible', 'false');
    }
  }
  
  // Configuramos el manejo de resize con debounce
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleResize, 150);
  });
  
  // Configuración inicial
  handleResize();
  
  // Cerrar el sidebar al hacer clic fuera de él en modo móvil
  document.addEventListener('click', (event) => {
    if (!isTabletOrLarger() && 
        !event.target.closest('.admin-sidebar') && 
        !event.target.closest('#sidebar-toggle') && 
        layout.classList.contains('sidebar-visible')) {
      layout.classList.remove('sidebar-visible');
      localStorage.setItem('sidebarVisible', 'false');
    }
  });
}