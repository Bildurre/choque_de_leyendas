export default function initPrintCollection() {
  // Add to collection buttons
  document.addEventListener('click', function(e) {
    // Handle add to collection buttons
    const addBtn = e.target.closest('.entity-public-card__action--add');
    if (addBtn && addBtn.dataset.entityType && addBtn.dataset.entityId) {
      e.preventDefault();
      addToCollection(addBtn);
    }
    
    // Handle regular entity card actions (heroes/cards)
    const regularBtn = e.target.closest('.entity-public-card__action:not(.entity-public-card__action--add):not(.entity-public-card__action--download)');
    if (regularBtn && regularBtn.dataset.entityType && regularBtn.dataset.entityId) {
      e.preventDefault();
      addToCollection(regularBtn);
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
    
    // Handle generate PDF button
    const generateBtn = e.target.closest('.collection-generate-pdf');
    if (generateBtn) {
      e.preventDefault();
      generatePDF();
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
  
  // Generate PDF function
  function generatePDF() {
    const reduceHeroesCheckbox = document.getElementById('reduce-heroes');
    const withGapCheckbox = document.getElementById('with-gap');
    
    const reduceHeroes = reduceHeroesCheckbox ? reduceHeroesCheckbox.checked : false;
    const withGap = withGapCheckbox ? withGapCheckbox.checked : true;
    
    // Get the base URL from the button's data attribute or construct it
    const generateBtn = document.querySelector('.collection-generate-pdf');
    let baseUrl = generateBtn.dataset.url || '/print-collection/generate-pdf';
    
    // Build query parameters
    const params = new URLSearchParams();
    if (reduceHeroes) params.append('reduce_heroes', '1');
    if (withGap) params.append('with_gap', '1');
    
    // Add parameters to URL
    const url = baseUrl + (params.toString() ? '?' + params.toString() : '');
    
    // Open in new tab
    window.open(url, '_blank');
  }
  
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
        window.showNotification(data.message);
      }
    } catch (error) {
      console.error('Error adding to collection:', error);
      window.showNotification(__('public.error_adding_to_collection'), 'error');
    }
  }
  
  // Update quantity function
  async function updateQuantity(button) {
    const type = button.dataset.entityType;
    const id = button.dataset.entityId;
    const input = button.previousElementSibling;
    const copies = parseInt(input.value);
    
    if (isNaN(copies) || copies < 1 || copies > 99) {
      window.showNotification(__('public.invalid_quantity'), 'error');
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
        window.showNotification(__('public.quantity_updated'));
      }
    } catch (error) {
      console.error('Error updating quantity:', error);
      window.showNotification(__('public.error_updating_quantity'), 'error');
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
        
        window.showNotification(__('public.item_removed'));
        
        // Check if collection is empty
        checkEmptyState();
      }
    } catch (error) {
      console.error('Error removing from collection:', error);
      window.showNotification(__('public.error_removing_item'), 'error');
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
        
        window.showNotification(data.message);
        
        // Show empty state
        checkEmptyState();
      }
    } catch (error) {
      console.error('Error clearing collection:', error);
      window.showNotification(__('public.error_clearing_collection'), 'error');
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
  
  // Translation helper (would need to be implemented)
  function __(key) {
    const translations = window.translations || {};
    return translations[key] || key;
  }
}