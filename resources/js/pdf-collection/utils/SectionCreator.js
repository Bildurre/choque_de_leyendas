// resources/js/components/pdf-collection/utils/SectionCreator.js
export default class SectionCreator {
  createTemporaryPdfsSection() {
    // Buscar el acordeón por ID específico
    const accordion = document.getElementById('pdf-downloads-accordion');
    
    if (accordion) {
      return this.createCollapsibleSection(accordion);
    } else {
      // Fallback: buscar cualquier acordeón
      const anyAccordion = document.querySelector('.accordion');
      if (anyAccordion) {
        return this.createCollapsibleSection(anyAccordion);
      }
      // Si no hay acordeón, crear sección legacy
      return this.createLegacySection();
    }
  }
  
  createCollapsibleSection(accordion) {
    const section = document.createElement('div');
    section.className = 'collapsible-section is-collapsed'; // Añadir is-collapsed desde el principio
    section.id = 'downloads-temporary';
    section.setAttribute('data-in-accordion', 'true');
    section.setAttribute('data-force-collapse', 'false');
    
    section.innerHTML = `
      <div class="collapsible-section__header">
        <h2 class="collapsible-section__title">${this.getTitle()}</h2>
        <div class="collapsible-section__icon-container">
          <span class="icon icon--chevron-down icon--md collapsible-section__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </span>
        </div>
      </div>
      <div class="collapsible-section__content-wrapper">
        <div class="collapsible-section__content">
          <p class="pdf-collection__section-description">
            ${this.getDescription()}
          </p>
          <div class="pdf-list pdf-list--temporary">
            <div class="pdf-list__items"></div>
          </div>
        </div>
      </div>
    `;
    
    // Add to accordion
    accordion.appendChild(section);
    
    // Primero registrar la sección en el acordeón
    this.registerSectionInAccordion(section, accordion);
    
    // Luego inicializar eventos (después de que el acordeón la conozca)
    setTimeout(() => {
      this.initializeCollapsibleEvents(section, accordion);
    }, 0);
    
    return section;
  }
  
  createLegacySection() {
    const section = document.createElement('div');
    section.className = 'pdf-collection__section';
    
    section.innerHTML = `
      <h2 class="pdf-collection__section-title">${this.getTitle()}</h2>
      <p class="pdf-collection__section-description">
        ${this.getDescription()}
      </p>
      <div class="pdf-list pdf-list--temporary">
        <div class="pdf-list__items"></div>
      </div>
    `;
    
    return section;
  }
  
  getTitle() {
    return window.translations?.pdf?.collection?.your_pdfs || 'Tus PDFs Generados';
  }
  
  getDescription() {
    return window.translations?.pdf?.collection?.temporary_description || 
           'Estos PDFs se eliminarán automáticamente después de 24 horas';
  }
  
  initializeCollapsibleEvents(section, accordion) {
    if (!window.updateCollapsibleState) return;
    
    const header = section.querySelector('.collapsible-section__header');
    if (!header) return;
    
    header.addEventListener('click', (event) => {
      if (event.target.closest('a, input, textarea, select')) return;
      
      const isNowCollapsed = window.updateCollapsibleState(section);
      
      // Trigger accordion event if expanded
      if (!isNowCollapsed) {
        const customEvent = new CustomEvent('section:opened', {
          detail: { 
            sectionId: section.id,
            section: section
          },
          bubbles: true // Importante para que burbujee hasta el acordeón
        });
        accordion.dispatchEvent(customEvent);
      }
    });
  }
  
  registerSectionInAccordion(section, accordion) {
    // Disparar un evento personalizado para notificar al acordeón
    // que hay una nueva sección
    const registerEvent = new CustomEvent('section:added', {
      detail: { 
        section: section,
        accordion: accordion
      },
      bubbles: true
    });
    
    // Disparar el evento en el documento para que el acordeón lo capture
    document.dispatchEvent(registerEvent);
  }
}