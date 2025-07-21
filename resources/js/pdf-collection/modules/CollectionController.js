// resources/js/pdf-collection/modules/CollectionController.js
import CollectionRenderer from '../utils/CollectionRenderer';

export default class CollectionController {
  constructor(apiService, notificationService) {
    this.apiService = apiService;
    this.notificationService = notificationService;
    this.renderer = new CollectionRenderer();
    
    this.listElement = document.querySelector('[data-collection-content]');
    this.actionsElement = document.querySelector('[data-collection-actions]');
    
    if (!this.listElement) return;
    
    this.contentElement = this.initContentElement();
    this.init();
  }
  
  initContentElement() {
    let contentElement = this.listElement.querySelector('.entity-list__items');
    
    if (!contentElement) {
      const contentDiv = this.listElement.querySelector('.entity-list__content');
      if (contentDiv) {
        contentElement = document.createElement('div');
        contentElement.className = 'entity-list__items entity-list__items--wide';
        contentDiv.appendChild(contentElement);
      }
    }
    
    return contentElement;
  }
  
  init() {
    this.loadCollectionStatus();
    this.setupEventListeners();
    
    // External events
    document.addEventListener('collection:updated', () => {
      this.loadCollectionItems();
    });
    
    document.addEventListener('collection:cleared', () => {
      this.clearCollection();
    });
  }
  
  setupEventListeners() {
    if (!this.contentElement) return;
    
    // Remove button clicks
    this.contentElement.addEventListener('click', (e) => {
      const removeBtn = e.target.closest('[data-remove-item]');
      if (removeBtn) {
        const type = removeBtn.dataset.entityType;
        const entityId = removeBtn.dataset.entityId;
        this.removeFromCollection(type, entityId);
      }
    });
    
    // Copies change
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
      const data = await this.apiService.getCollectionStatus();
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
      const data = await this.apiService.getCollectionItems();
      const items = data.items || [];
      this.renderItems(items);
    } catch (error) {
      console.error('Error loading collection items:', error);
    }
  }
  
  async removeFromCollection(type, entityId) {
    try {
      const result = await this.apiService.removeFromCollection(type, entityId);
      
      if (result.success) {
        this.notificationService.success(result.message);
        this.updateUI(result);
        this.loadCollectionItems();
        
        // Update events
        document.dispatchEvent(new CustomEvent('collection:updated'));
        document.dispatchEvent(new CustomEvent('collection:countUpdated', {
          detail: { count: result.totalCards || result.count || 0 }
        }));
      } else {
        this.notificationService.error(result.message);
      }
    } catch (error) {
      console.error('Error removing from collection:', error);
      this.notificationService.error('Error removing from collection');
    }
  }
  
  async updateCopies(type, entityId, copies) {
    try {
      const result = await this.apiService.updateCopies(type, entityId, copies);
      
      if (result.success) {
        this.notificationService.success(result.message);
        document.dispatchEvent(new CustomEvent('collection:countUpdated', {
          detail: { count: result.totalCards || 0 }
        }));
      } else {
        this.notificationService.error(result.message);
      }
    } catch (error) {
      console.error('Error updating copies:', error);
      this.notificationService.error('Error updating copies');
    }
  }
  
  clearCollection() {
    this.renderItems([]);
    this.updateUI({ hasItems: false, count: 0, totalCards: 0 });
    
    document.dispatchEvent(new CustomEvent('collection:countUpdated', {
      detail: { count: 0 }
    }));
  }
  
  renderItems(items) {
    if (!this.contentElement) return;
    
    if (!items || !Array.isArray(items) || items.length === 0) {
      this.updateEmptyState(true);
      return;
    }
    
    this.updateEmptyState(false);
    this.contentElement.innerHTML = this.renderer.renderCollectionItems(items);
  }
  
  updateUI(data) {
    const hasItems = data.hasItems || (data.count && data.count > 0);
    
    if (hasItems) {
      this.updateEmptyState(false);
      if (this.actionsElement) {
        this.actionsElement.style.display = 'grid';
      }
    } else {
      this.updateEmptyState(true);
      if (this.actionsElement) {
        this.actionsElement.style.display = 'none';
      }
    }
  }
  
  updateEmptyState(isEmpty) {
    if (!this.listElement) return;
    
    const contentDiv = this.listElement.querySelector('.entity-list__content');
    if (!contentDiv) return;
    
    if (isEmpty) {
      if (this.contentElement) {
        this.contentElement.innerHTML = '';
        this.contentElement.style.display = 'none';
      }
      
      let emptyDiv = contentDiv.querySelector('.entity-list__empty');
      if (!emptyDiv) {
        emptyDiv = this.renderer.renderEmptyState();
        contentDiv.appendChild(emptyDiv);
      }
    } else {
      if (this.contentElement) {
        this.contentElement.style.display = '';
      }
      
      const emptyDiv = contentDiv.querySelector('.entity-list__empty');
      if (emptyDiv) {
        emptyDiv.remove();
      }
    }
  }
}