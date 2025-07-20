// resources/js/pdf-collection/modules/PdfListManager.js
import SectionCreator from '../utils/SectionCreator';
import ScrollManager from '../utils/ScrollManager';

export default class PdfListManager {
  constructor() {
    this.sectionCreator = new SectionCreator();
    this.scrollManager = new ScrollManager();
  }
  
  addPdfToList(pdfItemHtml) {
    let temporarySection = this.findOrCreateSection();
    
    if (!temporarySection) return;
    
    // Handle collapsible section
    if (temporarySection.classList.contains('collapsible-section')) {
      // Esperar un momento para asegurar que los eventos están configurados
      setTimeout(() => {
        this.handleCollapsibleSection(temporarySection);
      }, 50);
    }
    
    // Add PDF to list
    const listContainer = this.findListContainer(temporarySection);
    if (listContainer) {
      listContainer.insertAdjacentHTML('afterbegin', pdfItemHtml);
    }
    
    // Remove empty state
    this.removeEmptyState(temporarySection);
  }
  
  findOrCreateSection() {
    // Look for collapsible section first
    let section = document.querySelector('#downloads-temporary');
    
    if (!section) {
      // Look for old structure
      section = this.findLegacySection();
      
      if (!section) {
        // Create new section
        section = this.createNewSection();
      }
    }
    
    return section;
  }
  
  findLegacySection() {
    const sections = document.querySelectorAll('.pdf-collection__section');
    
    for (const section of sections) {
      const title = section.querySelector('.pdf-collection__section-title');
      if (title && (title.textContent.includes('Tus PDFs Generados') || 
                    title.textContent.includes('Your Generated PDFs'))) {
        return section;
      }
    }
    
    return null;
  }
  
  createNewSection() {
    const section = this.sectionCreator.createTemporaryPdfsSection();
    
    // Si la sección ya fue añadida al acordeón por el SectionCreator, no hacer nada más
    if (section.parentElement) {
      return section;
    }
    
    // Si no, intentar insertarla en el lugar correcto
    const accordion = document.getElementById('pdf-downloads-accordion');
    if (accordion) {
      accordion.appendChild(section);
    } else {
      // Fallback al comportamiento anterior
      const collectionSection = document.querySelector('.pdf-collection__section--temporary');
      if (collectionSection && collectionSection.parentElement) {
        collectionSection.parentElement.insertBefore(section, collectionSection);
      }
    }
    
    return section;
  }
  
  handleCollapsibleSection(section) {
    const accordion = section.closest('.accordion');
    
    if (section.classList.contains('is-collapsed')) {
      if (accordion) {
        // Primero expandir la sección
        if (window.updateCollapsibleState) {
          window.updateCollapsibleState(section, false);
        }
        
        // Luego disparar el evento para cerrar otras secciones
        // Dar un pequeño delay para asegurar que la animación comience
        setTimeout(() => {
          const event = new CustomEvent('section:opened', {
            detail: { 
              sectionId: section.id,
              section: section
            },
            bubbles: true
          });
          accordion.dispatchEvent(event);
        }, 10);
        
        // Scroll con delay para después de las animaciones
        setTimeout(() => {
          this.scrollManager.scrollToSection(section);
        }, 350); // Usar el tiempo de transición
      } else {
        // No accordion, just expand
        if (window.updateCollapsibleState) {
          window.updateCollapsibleState(section, false);
        }
      }
    } else {
      // Already open, just scroll
      this.scrollManager.scrollToSection(section);
    }
  }
  
  findListContainer(section) {
    if (section.classList.contains('collapsible-section')) {
      const contentWrapper = section.querySelector('.collapsible-section__content');
      if (contentWrapper) {
        return this.ensureListContainer(contentWrapper);
      }
    } else {
      return section.querySelector('.pdf-list__items');
    }
    
    return null;
  }
  
  ensureListContainer(contentWrapper) {
    let listContainer = contentWrapper.querySelector('.pdf-list__items');
    
    if (!listContainer) {
      const pdfList = contentWrapper.querySelector('.pdf-list');
      if (pdfList) {
        listContainer = pdfList.querySelector('.pdf-list__items');
      } else {
        // Create structure
        const newPdfList = document.createElement('div');
        newPdfList.className = 'pdf-list';
        newPdfList.innerHTML = '<div class="pdf-list__items"></div>';
        contentWrapper.appendChild(newPdfList);
        listContainer = newPdfList.querySelector('.pdf-list__items');
      }
    }
    
    return listContainer;
  }
  
  removeEmptyState(section) {
    const emptyState = section?.querySelector('.pdf-collection__empty');
    if (emptyState) {
      emptyState.remove();
    }
  }
}