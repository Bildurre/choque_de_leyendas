export default function initAccordions() {
  // Buscar todos los acordeones en la página
  const accordions = document.querySelectorAll('.accordion');
  
  if (!accordions.length) return;
  
  // Esperar a que la función updateCollapsibleState esté disponible
  function waitForUpdateFunction() {
    if (typeof window.updateCollapsibleState === 'undefined') {
      setTimeout(waitForUpdateFunction, 50);
      return;
    }
    
    // Una vez disponible la función, inicializar acordeones
    initializeAccordions();
  }
  
  // Función para inicializar los acordeones
  function initializeAccordions() {
    accordions.forEach(accordion => {
      const isSidebarAccordion = accordion.getAttribute('data-is-sidebar') === 'true';
      const accordionId = accordion.id;
      
      // Obtener todas las secciones del acordeón
      const sections = accordion.querySelectorAll('.collapsible-section');
      
      // Si no hay secciones, no hacer nada
      if (!sections.length) return;
      
      // Inicializar estado de las secciones en este acordeón
      initializeAccordionSections(accordion, sections, isSidebarAccordion);
      
      // Configurar el manejo de exclusividad (sólo una sección abierta a la vez)
      setupExclusiveHandling(accordion, sections, isSidebarAccordion, accordionId);
    });
  }
  
  // Configurar el manejo exclusivo de secciones
  function setupExclusiveHandling(accordion, sections, isSidebarAccordion, accordionId) {
    // Agregar listener para el evento personalizado "section:opened"
    accordion.addEventListener('section:opened', event => {
      const openedSectionId = event.detail.sectionId;
      
      // Si no es el sidebar, recordar cuál fue el último abierto
      if (!isSidebarAccordion) {
        localStorage.setItem(`accordion-${accordionId}-last-opened`, openedSectionId);
      }
      
      // Cerrar todas las otras secciones con animación
      sections.forEach(section => {
        if (section.id !== openedSectionId) {
          window.updateCollapsibleState(section, true, true);
        }
      });
    });
  }
  
  // Función para inicializar el estado de las secciones en un acordeón
  function initializeAccordionSections(accordion, sections, isSidebarAccordion) {
    const accordionId = accordion.id;
    
    if (isSidebarAccordion) {
      // Para acordeón de sidebar: expandir sección con enlace activo sin animación
      handleSidebarAccordion(sections);
    } else {
      // Para acordeones normales
      handleRegularAccordion(sections, accordionId);
    }
  }
  
  // Manejar la inicialización de un acordeón de sidebar
  function handleSidebarAccordion(sections) {
    let activeFound = false;
    
    sections.forEach(section => {
      const hasActiveLink = section.querySelector('.admin-sidebar__link--active');
      
      if (hasActiveLink) {
        window.updateCollapsibleState(section, false, false); // Expandir sin animación
        activeFound = true;
      }
      // El resto ya está colapsado por defecto
    });
  }
  
  // Manejar la inicialización de un acordeón regular
  function handleRegularAccordion(sections, accordionId) {
    let sectionToExpand = null;
    
    // Buscar la última sección abierta
    const lastOpenedId = localStorage.getItem(`accordion-${accordionId}-last-opened`);
    if (lastOpenedId) {
      sectionToExpand = document.getElementById(lastOpenedId);
    }
    
    // Si no hay última abierta, buscar en localStorage individual
    if (!sectionToExpand) {
      for (const section of sections) {
        const savedState = localStorage.getItem(`section-${section.id}`);
        if (savedState === 'expanded') {
          sectionToExpand = section;
          // Marcar esta como última abierta
          localStorage.setItem(`accordion-${accordionId}-last-opened`, section.id);
          break;
        }
      }
    }
    
    // Si encontramos una sección para expandir, hacerlo sin animación
    if (sectionToExpand) {
      window.updateCollapsibleState(sectionToExpand, false, false);
    }
    // El resto ya está colapsado por defecto
  }
  
  // Iniciar la inicialización
  waitForUpdateFunction();
}