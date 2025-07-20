// resources/js/pdf-collection/utils/CollectionRenderer.js
export default class CollectionRenderer {
  renderCollectionItems(items) {
    return items.map(item => this.renderCollectionItem(item)).join('');
  }
  
  renderCollectionItem(item) {
    return `
      <div class="entity-collection-card" data-type="${item.type}" data-entity-id="${item.entity_id}">
        <div class="entity-collection-card__controls">
          <div class="form-field">
            <input 
              type="number" 
              name="copies_${item.type}_${item.entity_id}"
              id="copies_${item.type}_${item.entity_id}"
              min="1" 
              max="10" 
              value="${item.copies}"
              data-copies-input
              data-entity-type="${item.type}"
              data-entity-id="${item.entity_id}"
              class="form-input entity-collection-card__copies-input"
              placeholder="${window.translations?.pdf?.collection?.copies || 'Copies'}"
            >
          </div>
          
          <button 
            type="button"
            class="pdf-action-button"
            data-remove-item
            data-entity-type="${item.type}"
            data-entity-id="${item.entity_id}"
            title="${window.translations?.pdf?.collection?.remove_from_collection || 'Remove from collection'}"
          >
            <span class="icon icon--trash icon--md">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
              </svg>
            </span>
          </button>
        </div>
        
        <div class="entity-collection-card__preview">
          ${item.image_url ? 
            `<img src="${item.image_url}" alt="${item.name}" class="preview-image">` : 
            `<div class="preview-placeholder">${item.name.charAt(0)}</div>`
          }
        </div>
      </div>
    `;
  }
  
  renderEmptyState() {
    const emptyDiv = document.createElement('div');
    emptyDiv.className = 'entity-list__empty';
    emptyDiv.innerHTML = `
      <p>${window.translations?.pdf?.collection?.empty_collection || 'Your collection is empty'}</p>
    `;
    return emptyDiv;
  }
}