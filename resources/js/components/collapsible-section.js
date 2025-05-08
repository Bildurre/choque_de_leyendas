export default function initCollapsibleSections() {
  // Obtener todos los botones de toggle y secciones
  const collapsibleSections = document.querySelectorAll('.collapsible-section');
  
  if (!collapsibleSections.length) return;
  
  // Función para actualizar el icono según el estado
  function updateIcon(section) {
    const button = section.querySelector('.collapsible-section__toggle');
    const icon = button?.querySelector('.collapsible-section__icon');
    if (!icon) return;
    
    // Actualizar icon basado en el estado actual
    const isCollapsed = section.classList.contains('is-collapsed');
    
    icon.classList.remove('icon--chevron-up', 'icon--chevron-down');
    icon.classList.add(isCollapsed ? 'icon--chevron-down' : 'icon--chevron-up');
  }
  
  // Función para actualizar el estado del collapsible
  function updateCollapsibleState(section, collapsed = null) {
    // Si collapsed es null, alternamos el estado
    const newState = collapsed !== null ? collapsed : !section.classList.contains('is-collapsed');
    
    // Actualizar clase
    if (newState) {
      section.classList.add('is-collapsed');
    } else {
      section.classList.remove('is-collapsed');
    }
    
    // Actualizar icono
    updateIcon(section);
    
    return newState;
  }
  
  // Agregar event listeners a todos los botones
  collapsibleSections.forEach(section => {
    const button = section.querySelector('.collapsible-section__toggle');
    const sectionId = section.id;
    const accordion = section.closest('.accordion');
    
    if (!button || !sectionId) return;
    
    button.addEventListener('click', () => {
      const isSidebarAccordion = accordion?.getAttribute('data-is-sidebar') === 'true';
      
      // Actualizar estado de este collapsible
      const isNowCollapsed = updateCollapsibleState(section);
      
      // Si no es parte de un acordeón o no es el sidebar, guardar estado en localStorage
      if (!accordion || !isSidebarAccordion) {
        localStorage.setItem(`section-${sectionId}`, isNowCollapsed ? 'collapsed' : 'expanded');
      }
      
      // Si está en un acordeón y se expandió, disparar evento
      if (accordion && !isNowCollapsed) {
        // Crear y disparar evento personalizado
        const event = new CustomEvent('section:opened', {
          detail: { 
            sectionId: sectionId,
            section: section
          }
        });
        accordion.dispatchEvent(event);
      }
    });
  });
  
  // Inicializar el estado de las secciones que NO están en acordeones
  collapsibleSections.forEach(section => {
    const accordion = section.closest('.accordion');
    const sectionId = section.id;
    
    if (!sectionId || accordion) return; // Ignorar las que están en acordeones
    
    // Fuera de acordeón: usar localStorage o expandido por defecto
    const savedState = localStorage.getItem(`section-${sectionId}`);
    const shouldBeCollapsed = savedState === 'collapsed';
    
    // Aplicar estado inicial
    updateCollapsibleState(section, shouldBeCollapsed);
  });
  
  // Función para uso externo
  window.updateCollapsibleState = updateCollapsibleState;
}