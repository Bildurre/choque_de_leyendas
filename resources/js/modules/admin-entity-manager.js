// Manages entity cards and filtering
export class EntityManager {
  constructor(pdfGenerator) {
    this.pdfGenerator = pdfGenerator;
  }
  
  init() {
    this.bindEvents();
    this.initializeSearch();
  }
  
  bindEvents() {
    // Search functionality
    const searchInputs = document.querySelectorAll('.pdf-entities__search');
    searchInputs.forEach(input => {
      input.addEventListener('input', (e) => {
        this.filterEntities(e.target);
      });
    });
  }
  
  initializeSearch() {
    // Add search inputs to entity sections if they don't exist
    const sections = document.querySelectorAll('.pdf-entities');
    sections.forEach(section => {
      if (!section.querySelector('.pdf-entities__search')) {
        const searchHtml = `
          <div class="pdf-entities__search-wrapper">
            <input type="text" 
                   class="pdf-entities__search" 
                   placeholder="Search...">
            <svg class="pdf-entities__search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/>
              <path d="m21 21-4.35-4.35"/>
            </svg>
          </div>
        `;
        
        const actionsDiv = section.querySelector('.pdf-entities__actions');
        if (actionsDiv) {
          actionsDiv.insertAdjacentHTML('beforebegin', searchHtml);
        }
      }
    });
  }
  
  filterEntities(searchInput) {
    const searchTerm = searchInput.value.toLowerCase();
    const section = searchInput.closest('.pdf-entities');
    const cards = section.querySelectorAll('.pdf-entity-card');
    
    let visibleCount = 0;
    
    cards.forEach(card => {
      const name = card.querySelector('.pdf-entity-card__name').textContent.toLowerCase();
      const subtitle = card.querySelector('.pdf-entity-card__subtitle')?.textContent.toLowerCase() || '';
      
      if (name.includes(searchTerm) || subtitle.includes(searchTerm)) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });
    
    // Show/hide empty message
    this.updateEmptyState(section, visibleCount);
  }
  
  updateEmptyState(section, visibleCount) {
    let emptyMessage = section.querySelector('.pdf-entities__empty');
    
    if (visibleCount === 0) {
      if (!emptyMessage) {
        emptyMessage = document.createElement('div');
        emptyMessage.className = 'pdf-entities__empty';
        emptyMessage.innerHTML = `
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
          <p>No results found</p>
        `;
        section.querySelector('.pdf-entities__grid').appendChild(emptyMessage);
      }
      emptyMessage.style.display = 'flex';
    } else if (emptyMessage) {
      emptyMessage.style.display = 'none';
    }
  }
}