// Handles UI updates and interactions for collection
export class CollectionUI {
  constructor(collectionManager, notificationManager) {
    this.collectionManager = collectionManager;
    this.notificationManager = notificationManager;
    this.debounceTimers = new Map();
  }
  
  init() {
    this.bindEvents();
    this.initializeCustomInputs();
    
    // Subscribe to collection changes
    this.collectionManager.subscribe((event, data) => {
      this.handleCollectionChange(event, data);
    });
  }
  
  bindEvents() {
    // Quantity controls
    document.addEventListener('click', (e) => {
      // Increment button
      if (e.target.closest('.quantity-control__increment')) {
        this.handleQuantityChange(e.target.closest('.quantity-control__increment'), 1);
      }
      
      // Decrement button
      if (e.target.closest('.quantity-control__decrement')) {
        this.handleQuantityChange(e.target.closest('.quantity-control__decrement'), -1);
      }
      
      // Remove item
      if (e.target.closest('.collection-item__remove')) {
        const btn = e.target.closest('.collection-item__remove');
        this.handleRemoveItem(btn.dataset.type, btn.dataset.id);
      }
      
      // Clear all
      if (e.target.closest('.collection-clear-all')) {
        this.handleClearCollection();
      }
    });
    
    // Quantity input changes
    document.addEventListener('input', (e) => {
      if (e.target.classList.contains('quantity-control__input')) {
        this.handleQuantityInput(e.target);
      }
    });
    
    // Prevent non-numeric input
    document.addEventListener('keypress', (e) => {
      if (e.target.classList.contains('quantity-control__input')) {
        if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
          e.preventDefault();
        }
      }
    });
  }
  
  initializeCustomInputs() {
    // Replace existing quantity inputs with custom controls
    document.querySelectorAll('.collection-item__quantity').forEach(input => {
      this.replaceWithCustomControl(input);
    });
  }
  
  replaceWithCustomControl(input) {
    const value = input.value;
    const type = input.closest('.collection-item').dataset.type;
    const id = input.closest('.collection-item').dataset.id;
    
    const customControl = document.createElement('div');
    customControl.className = 'quantity-control';
    customControl.innerHTML = `
      <button type="button" class="quantity-control__decrement" data-type="${type}" data-id="${id}">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
      <input type="number" 
             class="quantity-control__input" 
             value="${value}" 
             min="1" 
             max="99"
             data-type="${type}"
             data-id="${id}">
      <button type="button" class="quantity-control__increment" data-type="${type}" data-id="${id}">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
    `;
    
    // Replace the old input
    input.parentNode.replaceChild(customControl, input);
  }
  
  handleQuantityChange(button, delta) {
    const input = button.parentNode.querySelector('.quantity-control__input');
    const currentValue = parseInt(input.value) || 1;
    const newValue = Math.max(1, Math.min(99, currentValue + delta));
    
    if (newValue !== currentValue) {
      input.value = newValue;
      this.debouncedUpdate(button.dataset.type, button.dataset.id, newValue);
    }
  }
  
  handleQuantityInput(input) {
    let value = parseInt(input.value) || 1;
    value = Math.max(1, Math.min(99, value));
    input.value = value;
    
    this.debouncedUpdate(input.dataset.type, input.dataset.id, value);
  }
  
  debouncedUpdate(type, id, value) {
    const key = `${type}-${id}`;
    
    // Clear existing timer
    if (this.debounceTimers.has(key)) {
      clearTimeout(this.debounceTimers.get(key));
    }
    
    // Set new timer
    const timer = setTimeout(() => {
      this.collectionManager.updateQuantity(type, parseInt(id), value);
      this.debounceTimers.delete(key);
    }, 500);
    
    this.debounceTimers.set(key, timer);
  }
  
  handleRemoveItem(type, id) {
    this.collectionManager.removeItem(type, parseInt(id));
  }
  
  handleClearCollection() {
    this.collectionManager.clearCollection();
  }
  
  handleCollectionChange(event, data) {
    switch (event) {
      case 'item-added':
      case 'quantity-updated':
        this.updateCounter(data.count);
        this.updateStats(data.count, data.copies);
        break;
        
      case 'item-removed':
        this.removeItemFromUI(data);
        this.updateCounter(data.count);
        this.updateStats(data.count, data.copies);
        break;
        
      case 'collection-cleared':
        this.clearCollectionUI();
        this.updateCounter(0);
        this.updateStats(0, 0);
        break;
    }
  }
  
  removeItemFromUI(data) {
    // Find and remove the item element
    const item = document.querySelector(`.collection-item[data-type="${data.type}"][data-id="${data.id}"]`);
    if (item) {
      item.style.opacity = '0';
      setTimeout(() => {
        item.remove();
        this.checkEmptyState();
      }, 300);
    }
  }
  
  clearCollectionUI() {
    // Remove all items with animation
    document.querySelectorAll('.collection-item').forEach(item => {
      item.style.opacity = '0';
    });
    
    setTimeout(() => {
      document.querySelector('.collection-content')?.remove();
      document.querySelector('.collection-actions')?.remove();
      this.showEmptyState();
    }, 300);
  }
  
  updateCounter(count = null) {
    const counters = document.querySelectorAll('.collection-counter');
    
    if (count === null) {
      const counterElement = document.querySelector('.collection-counter');
      count = counterElement ? parseInt(counterElement.dataset.collectionCount || 0) : 0;
    }
    
    counters.forEach(counter => {
      counter.textContent = count;
      counter.style.display = count > 0 ? 'flex' : 'none';
      counter.dataset.collectionCount = count;
    });
  }
  
  updateStats(uniqueCount, totalCopies) {
    const uniqueElement = document.querySelector('.collection-stat:first-child .collection-stat__text');
    const copiesElement = document.querySelector('.collection-stat:last-child .collection-stat__text');
    
    if (uniqueElement) {
      uniqueElement.textContent = `${uniqueCount} unique items`;
    }
    
    if (copiesElement) {
      copiesElement.textContent = `${totalCopies} total copies`;
    }
  }
  
  checkEmptyState() {
    const items = document.querySelectorAll('.collection-item');
    if (items.length === 0) {
      this.showEmptyState();
    }
  }
  
  showEmptyState() {
    const section = document.querySelector('.collection-section');
    const emptyHtml = `
      <div class="collection-empty">
        <svg class="collection-empty__icon" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 7h-9l-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1Z"/>
        </svg>
        <p class="collection-empty__message">Your collection is empty</p>
        <p class="collection-empty__hint">Add heroes and cards from the library to create your custom collection</p>
      </div>
    `;
    
    // Remove existing content
    section.querySelectorAll('.collection-content, .collection-actions').forEach(el => el.remove());
    
    // Add empty state
    section.insertAdjacentHTML('beforeend', emptyHtml);
  }
}