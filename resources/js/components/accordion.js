export default function initAccordions() {
  // Buscar todos los acordeones en la página
  const accordions = document.querySelectorAll('.accordion');
  
  if (!accordions.length) return;
  
  // Almacenar referencias a los acordeones y sus secciones
  const accordionMap = new Map();
  
  // Esperar a que la función updateCollapsibleState esté disponible
  function waitForUpdateFunction() {
    if (typeof window.updateCollapsibleState === 'undefined') {
      setTimeout(waitForUpdateFunction, 50);
      return;
    }
    
    // Una vez disponible la función, inicializar acordeones
    initializeAccordions();
    setupDynamicSectionListener();
  }
  
  // Escuchar cuando se añaden nuevas secciones dinámicamente
  function setupDynamicSectionListener() {
    document.addEventListener('section:added', (event) => {
      const { section, accordion } = event.detail;
      
      if (accordionMap.has(accordion)) {
        const accordionData = accordionMap.get(accordion);
        // Añadir la nueva sección a la lista
        accordionData.sections.push(section);
        
        // Re-configurar el manejo exclusivo con todas las secciones
        setupExclusiveHandling(
          accordion, 
          Array.from(accordion.querySelectorAll('.collapsible-section')),
          accordionData.isSidebar,
          accordion.id
        );
      }
    });
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
      
      // Guardar referencia del acordeón
      accordionMap.set(accordion, {
        isSidebar: isSidebarAccordion,
        sections: Array.from(sections)
      });
      
      // Marcar las secciones como parte de un acordeón
      sections.forEach(section => {
        section.setAttribute('data-in-accordion', 'true');
      });
      
      // Inicializar estado de las secciones en este acordeón
      initializeAccordionSections(accordion, sections, isSidebarAccordion);
      
      // Configurar el manejo de exclusividad (sólo una sección abierta a la vez)
      setupExclusiveHandling(accordion, sections, isSidebarAccordion, accordionId);
    });
  }
  
  // Configurar el manejo exclusivo de secciones
  function setupExclusiveHandling(accordion, sections, isSidebarAccordion, accordionId) {
    // Remover listeners anteriores para evitar duplicados
    const oldHandler = accordion._sectionOpenedHandler;
    if (oldHandler) {
      accordion.removeEventListener('section:opened', oldHandler);
    }
    
    // Crear nuevo handler
    const handler = (event) => {
      const openedSectionId = event.detail.sectionId;
      
      // Guardar cuál fue el último abierto
      localStorage.setItem(`accordion-${accordionId}-last-opened`, openedSectionId);
      
      // Cerrar todas las otras secciones con animación
      sections.forEach(section => {
        if (section.id !== openedSectionId) {
          window.updateCollapsibleState(section, true);
        }
      });
    };
    
    // Guardar referencia al handler
    accordion._sectionOpenedHandler = handler;
    
    // Agregar listener para el evento personalizado "section:opened"
    accordion.addEventListener('section:opened', handler);
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
        // Asegurarse de que esté colapsado primero
        section.classList.add('is-collapsed');
        // Luego expandir sin animación
        setTimeout(() => {
          window.updateCollapsibleState(section, false);
        }, 0);
        activeFound = true;
      }
      // El resto ya está colapsado por defecto
    });
  }
  
  // Manejar la inicialización de un acordeón regular
  function handleRegularAccordion(sections, accordionId) {
    // Primero, asegurarse de que todas las secciones estén colapsadas
    sections.forEach(section => {
      section.classList.add('is-collapsed');
    });
    
    // Buscar la última sección abierta
    const lastOpenedId = localStorage.getItem(`accordion-${accordionId}-last-opened`);
    let sectionToExpand = null;
    
    if (lastOpenedId) {
      sectionToExpand = document.getElementById(lastOpenedId);
      // Verificar que la sección encontrada pertenezca a este acordeón
      const sectionsArray = Array.from(sections);
      if (sectionToExpand && !sectionsArray.includes(sectionToExpand)) {
        sectionToExpand = null;
      }
    }
    
    // Si encontramos una sección para expandir, hacerlo después de un pequeño delay
    if (sectionToExpand) {
      setTimeout(() => {
        window.updateCollapsibleState(sectionToExpand, false);
        // Disparar el evento para cerrar otras secciones
        const event = new CustomEvent('section:opened', {
          detail: { 
            sectionId: sectionToExpand.id,
            section: sectionToExpand
          }
        });
        sectionToExpand.closest('.accordion').dispatchEvent(event);
      }, 100);
    }
  }
  
  // Iniciar la inicialización
  waitForUpdateFunction();
}