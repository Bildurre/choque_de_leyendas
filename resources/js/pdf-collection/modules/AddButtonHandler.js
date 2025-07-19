// resources/js/components/pdf-collection/modules/AddButtonHandler.js
export default class AddButtonHandler {
  constructor() {
    this.init();
  }
  
  init() {
    // Event delegation for add buttons
    document.addEventListener('click', (e) => {
      const button = e.target.closest('[data-collection-add]');
      if (!button) return;
      
      e.preventDefault();
      e.stopPropagation();
      
      this.handleAddClick(button);
    });
    
    // Listen for collection updates
    document.addEventListener('collection:updated', () => {
      this.checkAllButtonsStatus();
    });
    
    // Initial check
    setTimeout(() => this.checkAllButtonsStatus(), 100);
  }
  
  handleAddClick(button) {
    const entityType = button.dataset.entityType;
    const entityId = parseInt(button.dataset.entityId);
    
    document.dispatchEvent(new CustomEvent('collection:add', {
      detail: {
        type: entityType,
        entity_id: entityId,
        copies: entityType === 'card' ? 2 : 1
      },
      bubbles: true
    }));
  }
  
  async checkAllButtonsStatus() {
    const buttons = document.querySelectorAll('[data-collection-add]');
    if (buttons.length === 0) return;
    
    try {
      const response = await fetch('/pdf-collection/status', {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        }
      });
      const data = await response.json();
      
      if (data.hasItems) {
        this.checkItemsStatus(buttons);
      }
    } catch (error) {
      console.error('Error checking collection status:', error);
    }
  }
  
  async checkItemsStatus(buttons) {
    try {
      const response = await fetch('/pdf-collection/items', {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        }
      });
      const data = await response.json();
      
      buttons.forEach(button => {
        const entityType = button.dataset.entityType;
        const entityId = parseInt(button.dataset.entityId);
        
        const isInCollection = data.items.some(item => 
          item.type === entityType && 
          item.entity_id === entityId
        );
        
        this.updateButtonState(button, isInCollection);
      });
    } catch (error) {
      console.error('Error checking items status:', error);
    }
  }
  
  updateButtonState(button, isInCollection) {
    button.classList.toggle('add-button--active', isInCollection);
    button.title = isInCollection ? 
      'Remove from collection' : 
      'Add to collection';
  }
}