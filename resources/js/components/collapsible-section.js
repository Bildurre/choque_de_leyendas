export default function initCollapsibleSections() {
  // Obtener todos los collapsibles
  const collapsibleSections = document.querySelectorAll('.collapsible-section');
  
  if (!collapsibleSections.length) return;
  
  // Función para actualizar el icono según el estado
  function updateIcon(section) {
    const icon = section.querySelector('.collapsible-section__icon');
    if (!icon) return;
    
    // Actualizar icon basado en el estado actual
    const isCollapsed = section.classList.contains('is-collapsed');
    
    icon.classList.remove('icon--chevron-up', 'icon--chevron-down');
    icon.classList.add(isCollapsed ? 'icon--chevron-down' : 'icon--chevron-up');
  }
  
  // Función para actualizar el estado del collapsible
  function updateCollapsibleState(section, collapsed = null, enableAnimation = false) {
    // Si queremos habilitar animaciones, quitamos la clase que las desactiva
    if (enableAnimation) {
      section.classList.remove('collapsible-section--no-animation');
    }
    
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
  
  // Configurar el event listener para cada sección
  function setupSectionListeners(section) {
    const header = section.querySelector('.collapsible-section__header');
    const sectionId = section.id;
    const accordion = section.closest('.accordion');
    
    if (!header || !sectionId) return;
    
    header.addEventListener('click', (event) => {
      // Asegurarnos de que el clic no fue en un enlace u otro elemento interactivo
      if (event.target.closest('a, button:not(.collapsible-section__toggle), input, textarea, select')) {
        return;
      }
      
      // Habilitar animaciones a partir del primer clic
      const enableAnimation = true;
      
      const isSidebarAccordion = accordion?.getAttribute('data-is-sidebar') === 'true';
      
      // Actualizar estado de este collapsible
      const isNowCollapsed = updateCollapsibleState(section, null, enableAnimation);
      
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
  }
  
  // Inicializar secciones independientes (fuera de acordeones)
  function initializeIndependentSections() {
    collapsibleSections.forEach(section => {
      const accordion = section.closest('.accordion');
      const sectionId = section.id;
      
      if (!sectionId || accordion) return; // Ignorar las que están en acordeones
      
      // Fuera de acordeón: usar localStorage o colapsado por defecto
      const savedState = localStorage.getItem(`section-${sectionId}`);
      
      // Si debe estar expandido según localStorage, expandirlo sin animación
      if (savedState === 'expanded') {
        updateCollapsibleState(section, false, false);
      }
      // Si no, ya está colapsado por defecto, no hacemos nada
    });
  }
  
  // Configurar todos los listeners de los collapsibles
  collapsibleSections.forEach(section => {
    setupSectionListeners(section);
    
    // Prevenir la propagación del evento cuando se hace clic en el botón de toggle
    const toggleButton = section.querySelector('.collapsible-section__toggle');
    if (toggleButton) {
      toggleButton.addEventListener('click', (e) => {
        // Prevenir que el clic llegue al header
        e.stopPropagation();
      });
    }
  });
  
  // Inicializar el estado de las secciones independientes
  initializeIndependentSections();
  
  // Exponer función para uso externo
  window.updateCollapsibleState = updateCollapsibleState;
}