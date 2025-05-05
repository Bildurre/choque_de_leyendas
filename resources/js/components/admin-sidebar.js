export default function initSidebar() {
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const layout = document.querySelector('.admin-layout');
  const sectionToggles = document.querySelectorAll('.admin-sidebar__section-toggle');
  
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
  
  // Manejar las secciones colapsables
  function handleSectionToggle() {
    const sectionId = this.getAttribute('data-section');
    const sectionElement = this.closest('.admin-sidebar__section');
    
    // Toggle expanded state
    sectionElement.classList.toggle('is-expanded');
    
    // Save expanded state to localStorage
    const expandedSections = JSON.parse(localStorage.getItem('expandedSections') || '{}');
    expandedSections[sectionId] = sectionElement.classList.contains('is-expanded');
    localStorage.setItem('expandedSections', JSON.stringify(expandedSections));
  }
  
  // Add click event to all section toggles
  sectionToggles.forEach(toggle => {
    toggle.addEventListener('click', handleSectionToggle);
  });
  
  // Initialize expanded sections from localStorage
  function initExpandedSections() {
    const expandedSections = JSON.parse(localStorage.getItem('expandedSections') || '{}');
    const currentRoute = window.location.pathname;
    
    sectionToggles.forEach(toggle => {
      const sectionId = toggle.getAttribute('data-section');
      const sectionElement = toggle.closest('.admin-sidebar__section');
      const submenu = document.getElementById(`section-${sectionId}`);
      
      // Check if section is expanded in localStorage
      if (expandedSections[sectionId]) {
        sectionElement.classList.add('is-expanded');
      } else {
        // Auto-expand section if current route matches any submenu link
        if (submenu) {
          const links = submenu.querySelectorAll('.admin-sidebar__link');
          
          for (const link of links) {
            if (link.classList.contains('admin-sidebar__link--active')) {
              sectionElement.classList.add('is-expanded');
              expandedSections[sectionId] = true;
              localStorage.setItem('expandedSections', JSON.stringify(expandedSections));
              break;
            }
          }
        }
      }
    });
  }
  
  // Initialize sections
  initExpandedSections();
}