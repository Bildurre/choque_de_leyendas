export default function initCollapsibleSections() {
  // Obtener todos los botones de toggle
  const toggleButtons = document.querySelectorAll('.collapsible-section__toggle');
  
  // Función para actualizar el icono según el estado
  function updateIcon(button, isCollapsed) {
    const icon = button.querySelector('.collapsible-section__icon');
    if (!icon) return;
    
    // Limpiar clases existentes
    icon.classList.remove('icon--chevron-up', 'icon--chevron-down');
    
    // Agregar la clase correcta según el estado
    icon.classList.add(isCollapsed ? 'icon--chevron-down' : 'icon--chevron-up');
  }
  
  // Agregar evento de clic a cada botón
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const section = document.getElementById(targetId);
      
      if (!section) return;
      
      // Toggle la clase collapsed
      const wasCollapsed = section.classList.contains('is-collapsed');
      section.classList.toggle('is-collapsed');
      
      // Actualizar el icono
      updateIcon(this, !wasCollapsed);
      
      // Si la sección se acaba de expandir y está dentro de un acordeón,
      // disparar evento para cerrar otras secciones
      if (wasCollapsed) {
        const accordion = section.closest('.accordion');
        if (accordion) {
          const event = new CustomEvent('section:opened', {
            detail: { sectionId: section.id }
          });
          accordion.dispatchEvent(event);
        }
      }
      
      // Guardar estado en localStorage
      localStorage.setItem(`section-${targetId}`, section.classList.contains('is-collapsed') ? 'collapsed' : 'expanded');
    });
  });
  
  // Inicializar estado de las secciones
  document.querySelectorAll('.collapsible-section').forEach(section => {
    const id = section.id;
    const savedState = localStorage.getItem(`section-${id}`);
    const toggleButton = section.querySelector('.collapsible-section__toggle');
    
    // Verificar si la sección contiene un enlace activo
    const hasActiveLink = section.querySelector('.admin-sidebar__link--active');
    
    let shouldBeCollapsed = false;
    
    // Determinar si la sección debe estar colapsada
    if (savedState === 'collapsed' && !hasActiveLink) {
      shouldBeCollapsed = true;
    } else if (savedState === 'expanded' || hasActiveLink) {
      shouldBeCollapsed = false;
    } else {
      // Si no hay estado guardado, usar el valor predeterminado del HTML
      shouldBeCollapsed = section.classList.contains('is-collapsed');
    }
    
    // Establecer el estado de colapso
    if (shouldBeCollapsed) {
      section.classList.add('is-collapsed');
    } else {
      section.classList.remove('is-collapsed');
    }
    
    // Actualizar el icono según el estado
    if (toggleButton) {
      updateIcon(toggleButton, shouldBeCollapsed);
    }
  });
}