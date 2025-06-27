// Manages collection data and API calls
export class CollectionManager {
  constructor() {
    this.listeners = [];
  }
  
  init() {
    // Any initialization logic
  }
  
  // Subscribe to collection changes
  subscribe(callback) {
    this.listeners.push(callback);
  }
  
  // Notify all listeners of changes
  notify(event, data) {
    this.listeners.forEach(callback => callback(event, data));
  }
  
  // Add item to collection
  async addItem(type, id) {
    try {
      const response = await fetch('/collection/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id })
      });
      
      const data = await response.json();
      
      if (data.success) {
        this.notify('item-added', data);
        window.showNotification(data.message, 'success');
        
        // Animate button if exists
        const button = document.querySelector(`[data-entity-type="${type}"][data-entity-id="${id}"]`);
        if (button) {
          button.classList.add('added');
          setTimeout(() => button.classList.remove('added'), 2000);
        }
      } else {
        window.showNotification(data.message || 'Error', 'error');
      }
      
      return data;
    } catch (error) {
      console.error('Error adding to collection:', error);
      window.showNotification('Error adding to collection', 'error');
      throw error;
    }
  }
  
  // Update item quantity
  async updateQuantity(type, id, copies) {
    if (copies < 1 || copies > 99) {
      window.showNotification('Quantity must be between 1 and 99', 'error');
      return;
    }
    
    try {
      const response = await fetch('/collection/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id, copies })
      });
      
      const data = await response.json();
      
      if (data.success) {
        this.notify('quantity-updated', data);
      }
      
      return data;
    } catch (error) {
      console.error('Error updating quantity:', error);
      window.showNotification('Error updating quantity', 'error');
      throw error;
    }
  }
  
  // Remove item from collection
  async removeItem(type, id) {
    if (!confirm('Remove this item from collection?')) {
      return;
    }
    
    try {
      const response = await fetch('/collection/remove', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id })
      });
      
      const data = await response.json();
      
      if (data.success) {
        this.notify('item-removed', data);
        window.showNotification(data.message, 'success');
      }
      
      return data;
    } catch (error) {
      console.error('Error removing from collection:', error);
      window.showNotification('Error removing item', 'error');
      throw error;
    }
  }
  
  // Clear entire collection
  async clearCollection() {
    if (!confirm('Clear entire collection? This cannot be undone.')) {
      return;
    }
    
    try {
      const response = await fetch('/collection/clear', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        this.notify('collection-cleared', data);
        window.showNotification(data.message, 'success');
      }
      
      return data;
    } catch (error) {
      console.error('Error clearing collection:', error);
      window.showNotification('Error clearing collection', 'error');
      throw error;
    }
  }
}