export default function initPrintCollection() {
  // Update counter on page load
  updateCounter();
  
  // Add to collection buttons
  document.addEventListener('click', function(e) {
    // Handle add to collection buttons
    const addBtn = e.target.closest('.entity-public-card__action');
    if (addBtn && addBtn.dataset.entityType && addBtn.dataset.entityId) {
      e.preventDefault();
      addToCollection(addBtn);
    }
    
    // Handle single page add buttons
    const singleAddBtn = e.target.closest('.print-collection-add');
    if (singleAddBtn) {
      e.preventDefault();
      addToCollection(singleAddBtn);
    }
    
    // Handle update quantity
    const updateBtn = e.target.closest('.collection-item__update');
    if (updateBtn) {
      updateQuantity(updateBtn);
    }
    
    // Handle remove item
    const removeBtn = e.target.closest('.collection-item__remove');
    if (removeBtn) {
      e.preventDefault();
      removeFromCollection(removeBtn);
    }
    
    // Handle clear all
    const clearBtn = e.target.closest('.collection-clear-all');
    if (clearBtn) {
      e.preventDefault();
      clearCollection();
    }
  });
  
  // Handle quantity input changes
  document.addEventListener('change', function(e) {
    if (e.target.classList.contains('collection-item__quantity')) {
      const updateBtn = e.target.nextElementSibling;
      if (updateBtn) {
        updateQuantity(updateBtn);
      }
    }
  });
  
  // Add to collection function
  async function addToCollection(button) {
    const type = button.dataset.entityType;
    const id = button.dataset.entityId;
    
    try {
      const response = await fetch('/print-collection/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Update button state
        button.classList.add('added');
        const icon = button.querySelector('.icon');
        if (icon) {
          icon.innerHTML = '<svg>...</svg>'; // Check icon SVG
        }
        
        // Reset button after animation
        setTimeout(() => {
          button.classList.remove('added');
          if (icon) {
            icon.innerHTML = '<svg>...</svg>'; // Original icon SVG
          }
        }, 2000);
        
        // Update counter
        updateCounter(data.count);
        
        // Show toast notification
        showToast(data.message);
      }
    } catch (error) {
      console.error('Error adding to collection:', error);
      showToast(__('public.error_adding_to_collection'), 'error');
    }
  }
  
  // Update quantity function
  async function updateQuantity(button) {
    const container = button.closest('.collection-item');
    const input = container.querySelector('.collection-item__quantity');
    const type = button.dataset.entityType;
    const id = button.dataset.entityId;
    const copies = parseInt(input.value);
    
    if (copies < 1 || copies > 99) {
      showToast(__('public.invalid_quantity'), 'error');
      return;
    }
    
    try {
      const response = await fetch('/print-collection/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id, copies })
      });
      
      const data = await response.json();
      
      if (data.success) {
        updateCounter(data.count);
        showToast(__('public.quantity_updated'));
      }
    } catch (error) {
      console.error('Error updating quantity:', error);
      showToast(__('public.error_updating_quantity'), 'error');
    }
  }
  
  // Remove from collection function
  async function removeFromCollection(button) {
    const type = button.dataset.entityType;
    const id = button.dataset.entityId;
    
    try {
      const response = await fetch('/print-collection/remove', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, id })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Remove item from DOM
        const item = button.closest('.collection-item');
        item.style.opacity = '0';
        setTimeout(() => item.remove(), 300);
        
        updateCounter(data.count);
        showToast(__('public.item_removed'));
        
        // Check if collection is empty
        checkEmptyState();
      }
    } catch (error) {
      console.error('Error removing from collection:', error);
      showToast(__('public.error_removing_item'), 'error');
    }
  }
  
  // Clear collection function
  async function clearCollection() {
    if (!confirm(__('public.confirm_clear_collection'))) {
      return;
    }
    
    try {
      const response = await fetch('/print-collection/clear', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Remove all items from DOM
        document.querySelectorAll('.collection-item').forEach(item => {
          item.style.opacity = '0';
          setTimeout(() => item.remove(), 300);
        });
        
        updateCounter(0);
        showToast(data.message);
        
        // Show empty state
        checkEmptyState();
      }
    } catch (error) {
      console.error('Error clearing collection:', error);
      showToast(__('public.error_clearing_collection'), 'error');
    }
  }
  
  // Update counter in header
  function updateCounter(count = null) {
    const counter = document.querySelector('.print-collection-counter');
    if (!counter) return;
    
    if (count === null) {
      // Get count from server or localStorage
      count = parseInt(localStorage.getItem('print_collection_count') || 0);
    } else {
      // Save to localStorage
      localStorage.setItem('print_collection_count', count);
    }
    
    counter.textContent = count;
    counter.style.display = count > 0 ? 'flex' : 'none';
    
    // Update header icon visibility
    const headerIcon = document.querySelector('.print-collection-icon');
    if (headerIcon) {
      headerIcon.style.display = count > 0 ? 'flex' : 'none';
    }
  }
  
  // Check empty state
  function checkEmptyState() {
    const items = document.querySelectorAll('.collection-item');
    const emptyState = document.querySelector('.collection-empty');
    const collectionContent = document.querySelector('.collection-content');
    
    if (items.length === 0 && emptyState && collectionContent) {
      emptyState.style.display = 'block';
      collectionContent.style.display = 'none';
    }
  }
  
  // Show toast notification
  function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    toast.innerHTML = `
      <div class="toast__message">${message}</div>
      ${type === 'success' ? '<a href="/print-collection" class="toast__action">' + __('public.view_collection') + '</a>' : ''}
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('toast--show'), 10);
    
    // Remove after delay
    setTimeout(() => {
      toast.classList.remove('toast--show');
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }
  
  // Translation helper (would need to be implemented)
  function __(key) {
    const translations = window.translations || {};
    return translations[key] || key;
  }
}