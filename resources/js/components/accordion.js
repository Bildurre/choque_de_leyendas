export default function initAccordions() {
  // Buscar todos los acordeones en la página
  const accordions = document.querySelectorAll('.accordion');
  
  if (!accordions.length) return;
  
  accordions.forEach(accordion => {
    // Obtener todas las secciones del acordeón
    const sections = accordion.querySelectorAll('.collapsible-section');
    
    // Si no hay secciones, no hacer nada
    if (!sections.length) return;
    
    // Agregar listener para el evento personalizado "section:opened"
    accordion.addEventListener('section:opened', event => {
      const openedSectionId = event.detail.sectionId;
      
      // Cerrar todas las otras secciones
      sections.forEach(section => {
        if (section.id !== openedSectionId && !section.classList.contains('is-collapsed')) {
          section.classList.add('is-collapsed');
          
          // Actualizar el icono también
          const toggleButton = section.querySelector('.collapsible-section__toggle');
          if (toggleButton) {
            const icon = toggleButton.querySelector('.collapsible-section__icon');
            if (icon) {
              icon.classList.remove('icon--chevron-up');
              icon.classList.add('icon--chevron-down');
            }
          }
          
          // Actualizar el estado en localStorage
          localStorage.setItem(`section-${section.id}`, 'collapsed');
        }
      });
    });
  });
}