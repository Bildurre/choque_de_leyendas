/**
 * PDF Collection Controller
 * Manages the collection items on the PDF collection page
 */

export default function initPdfCollectionController() {
  const collectionElement = document.querySelector('[data-collection-content]');
  
  if (!collectionElement) return;
  
  class PdfCollectionManager {
    constructor() {
      this.listElement = document.querySelector('[data-collection-content]');
      this.actionsElement = document.querySelector('[data-collection-actions]');
      
      // Find the actual items container within entity-list
      if (this.listElement) {
        this.contentElement = this.listElement.querySelector('.entity-list__items');
        if (!this.contentElement) {
          // Create it if it doesn't exist
          const contentDiv = this.listElement.querySelector('.entity-list__content');
          if (contentDiv) {
            this.contentElement = document.createElement('div');
            this.contentElement.className = 'entity-list__items';
            contentDiv.appendChild(this.contentElement);
          }
        }
      }
      
      this.init();
    }
    
    init() {
      // Load collection status on page load
      this.loadCollectionStatus();
      
      // Listen for collection updates
      this.setupEventListeners();
      
      // Listen for external updates
      document.addEventListener('collection:updated', () => {
        this.loadCollectionItems();
      });
    }
    
    setupEventListeners() {
      // Listen for remove button clicks
      this.contentElement.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-item]');
        if (removeBtn) {
          const type = removeBtn.dataset.entityType;
          const entityId = removeBtn.dataset.entityId;
          this.removeFromCollection(type, entityId);
        }
      });
      
      // Listen for copies change
      this.contentElement.addEventListener('change', (e) => {
        if (e.target.matches('[data-copies-input]')) {
          const type = e.target.dataset.entityType;
          const entityId = e.target.dataset.entityId;
          const copies = parseInt(e.target.value);
          this.updateCopies(type, entityId, copies);
        }
      });
    }
    
    async loadCollectionStatus() {
      try {
        const response = await fetch('/pdf-collection/status', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          }
        });
        
        const data = await response.json();
        this.updateUI(data);
        
        if (data.hasItems) {
          this.loadCollectionItems();
        }
      } catch (error) {
        console.error('Error loading collection status:', error);
      }
    }
    
    async loadCollectionItems() {
      try {
        const response = await fetch('/pdf-collection/items', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          }
        });
        
        const data = await response.json();
        
        // Asegurarnos de que items sea un array
        const items = data.items || [];
        this.renderItems(items);
      } catch (error) {
        console.error('Error loading collection items:', error);
      }
    }
    
    async removeFromCollection(type, entityId) {
      try {
        const response = await fetch('/pdf-collection/remove', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            type: type,
            entity_id: parseInt(entityId)
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          this.showNotification(result.message, 'success');
          this.updateUI(result);
          this.loadCollectionItems();
          
          // Emit event for add buttons to update
          document.dispatchEvent(new CustomEvent('collection:updated'));
          
          // Update header counter
          document.dispatchEvent(new CustomEvent('collection:countUpdated', {
            detail: { count: result.totalCards || result.count || 0 }
          }));
        } else {
          this.showNotification(result.message, 'error');
        }
      } catch (error) {
        console.error('Error removing from collection:', error);
        this.showNotification('Error removing from collection', 'error');
      }
    }
    
    async updateCopies(type, entityId, copies) {
      try {
        const response = await fetch('/pdf-collection/update-copies', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            type: type,
            entity_id: parseInt(entityId),
            copies: copies
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          this.showNotification(result.message, 'success');
          
          // Update header counter only
          document.dispatchEvent(new CustomEvent('collection:countUpdated', {
            detail: { count: result.totalCards || 0 }
          }));
        } else {
          this.showNotification(result.message, 'error');
        }
      } catch (error) {
        console.error('Error updating copies:', error);
        this.showNotification('Error updating copies', 'error');
      }
    }
    
    renderItems(items) {
      if (!this.contentElement) return;
      
      // Verificar que items sea un array
      if (!items || !Array.isArray(items) || items.length === 0) {
        this.updateEmptyState(true);
        return;
      }
      
      this.updateEmptyState(false);
      
      const itemsHtml = items.map(item => `
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
              class="action-button action-button--delete action-button--sm entity-collection-card__remove"
              data-remove-item
              data-entity-type="${item.type}"
              data-entity-id="${item.entity_id}"
              title="${window.translations?.pdf?.collection?.remove_from_collection || 'Remove from collection'}"
            >
              <span class="icon icon--trash icon--sm action-button__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
              </span>
              <span class="action-button__text"></span>
            </button>
          </div>
          
          <div class="entity-collection-card__preview">
            ${item.image_url ? 
              `<img src="${item.image_url}" alt="${item.name}" class="preview-image">` : 
              `<div class="preview-placeholder">${item.name.charAt(0)}</div>`
            }
          </div>
        </div>
      `).join('');
      
      this.contentElement.innerHTML = itemsHtml;
    }
    
    updateUI(data) {
      // Show/hide elements based on collection state
      const hasItems = data.hasItems || (data.count && data.count > 0);
      
      if (hasItems) {
        this.updateEmptyState(false);
        this.actionsElement.style.display = 'flex';
      } else {
        this.updateEmptyState(true);
        this.actionsElement.style.display = 'none';
      }
    }
    
    updateEmptyState(isEmpty) {
      if (!this.listElement) return;
      
      const contentDiv = this.listElement.querySelector('.entity-list__content');
      if (!contentDiv) return;
      
      if (isEmpty) {
        // Clear items and show empty message
        if (this.contentElement) {
          this.contentElement.innerHTML = '';
          this.contentElement.style.display = 'none';
        }
        
        // Check if empty message already exists
        let emptyDiv = contentDiv.querySelector('.entity-list__empty');
        if (!emptyDiv) {
          emptyDiv = document.createElement('div');
          emptyDiv.className = 'entity-list__empty';
          emptyDiv.innerHTML = `
            <svg class="icon icon--lg"><use href="#icon-inbox"></use></svg>
            <p>${window.translations?.pdf?.collection?.empty_collection || 'Your collection is empty'}</p>
            <p class="temporary-collection__hint">${window.translations?.pdf?.collection?.add_hint || 'Add heroes and cards from faction pages'}</p>
          `;
          contentDiv.appendChild(emptyDiv);
        }
      } else {
        // Show items and hide empty message
        if (this.contentElement) {
          this.contentElement.style.display = '';
        }
        
        const emptyDiv = contentDiv.querySelector('.entity-list__empty');
        if (emptyDiv) {
          emptyDiv.remove();
        }
      }
    }
    
    showNotification(message, type = 'info') {
      // Usar el sistema de notificaciones global
      if (window.showNotification) {
        window.showNotification(message, type);
      }
    }
  }
  
  // Initialize
  new PdfCollectionManager();
}