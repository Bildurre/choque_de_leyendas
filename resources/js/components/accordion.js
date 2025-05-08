export default function initAccordions() {
  // Buscar todos los acordeones en la página
  const accordions = document.querySelectorAll('.accordion');
  
  if (!accordions.length) return;
  
  // Esperar a que la función updateCollapsibleState esté disponible (desde collapsible-section.js)
  const waitForUpdateFunction = () => {
    if (typeof window.updateCollapsibleState === 'undefined') {
      setTimeout(waitForUpdateFunction, 50);
      return;
    }
    
    // Una vez disponible la función, inicializar acordeones
    initializeAccordions();
  };
  
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
      
      // Agregar listener para el evento personalizado "section:opened"
      accordion.addEventListener('section:opened', event => {
        const openedSectionId = event.detail.sectionId;
        
        // Si no es el sidebar, recordar cuál fue el último abierto
        if (!isSidebarAccordion) {
          localStorage.setItem(`accordion-${accordionId}-last-opened`, openedSectionId);
        }
        
        // Cerrar todas las otras secciones
        sections.forEach(section => {
          if (section.id !== openedSectionId) {
            window.updateCollapsibleState(section, true);
          }
        });
      });
    });
  }
  
  // Función para inicializar el estado de las secciones en un acordeón
  function initializeAccordionSections(accordion, sections, isSidebarAccordion) {
    const accordionId = accordion.id;
    
    if (isSidebarAccordion) {
      // Para acordeón de sidebar: expandir sección con enlace activo
      let activeFound = false;
      
      sections.forEach(section => {
        const hasActiveLink = section.querySelector('.admin-sidebar__link--active');
        
        if (hasActiveLink) {
          window.updateCollapsibleState(section, false); // Expandir
          activeFound = true;
        } else {
          window.updateCollapsibleState(section, true); // Colapsar
        }
      });
      
      // Si no se encontró sección activa, colapsar todas
      if (!activeFound) {
        sections.forEach(section => {
          window.updateCollapsibleState(section, true);
        });
      }
    } else {
      // Para acordeones normales: 
      // 1. Usar la última sección abierta guardada
      // 2. Si no hay, usar localStorage individual 
      // 3. Por defecto, todas colapsadas
      const lastOpenedId = localStorage.getItem(`accordion-${accordionId}-last-opened`);
      let anyExpanded = false;
      
      if (lastOpenedId) {
        // Buscar y expandir la última sección abierta
        sections.forEach(section => {
          if (section.id === lastOpenedId) {
            window.updateCollapsibleState(section, false);
            anyExpanded = true;
          } else {
            window.updateCollapsibleState(section, true);
          }
        });
      }
      
      // Si no hay última abierta, revisar localStorage individual
      if (!anyExpanded) {
        sections.forEach(section => {
          const savedState = localStorage.getItem(`section-${section.id}`);
          if (savedState === 'expanded') {
            window.updateCollapsibleState(section, false);
            // Marcar esta como última abierta
            localStorage.setItem(`accordion-${accordionId}-last-opened`, section.id);
            anyExpanded = true;
            
            // Solo permitir una expandida
            return; 
          } else {
            window.updateCollapsibleState(section, true);
          }
        });
      }
      
      // Si ninguna está expandida, colapsar todas por defecto
      if (!anyExpanded) {
        sections.forEach(section => {
          window.updateCollapsibleState(section, true);
        });
      }
    }
  }
  
  // Iniciar la inicialización
  waitForUpdateFunction();
}