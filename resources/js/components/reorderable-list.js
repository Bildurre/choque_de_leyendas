import Sortable from 'sortablejs';

export default function initReorderableLists() {
  const container = document.getElementById('reorderable-items');
  if (!container) return;
  
  const reorderUrl = container.dataset.reorderUrl;
  const idField = container.dataset.idField || 'id';
  let orderChanged = false;
  let reorderButtons = document.getElementById('reorder-buttons');
  let saveOrderButton = document.getElementById('save-reorder-button');
  let cancelOrderButton = document.getElementById('cancel-reorder-button');
  let initialOrder = [];
  let initialPositions = [];
  
  // Get all reorderable items
  const reorderableItems = container.children;
  
  // Save initial order and positions
  initialOrder = Array.from(reorderableItems)
    .map(item => item.dataset[toCamelCase(idField)]);
  
  // Save initial positions to restore if needed
  initialPositions = saveElementPositions(reorderableItems);
  
  // Add arrow buttons functionality
  addArrowButtonsListeners();
  
  const sortable = Sortable.create(container, {
    animation: 150,
    ghostClass: 'entity-list-card--ghost',
    chosenClass: 'entity-list-card--chosen',
    dragClass: 'entity-list-card--drag',
    handle: '.entity-list-card__handle', // Changed to use specific handle
    filter: '.action-button, button, a, input, textarea',
    preventOnFilter: true,
    onEnd: function() {
      checkOrderChanged();
    }
  });
  
  // Initially hide buttons
  if (reorderButtons) {
    toggleReorderButtons(false);
    
    // Add event to save button
    if (saveOrderButton) {
      saveOrderButton.addEventListener('click', function() {
        updateItemsOrder();
      });
    }
    
    // Add event to cancel button
    if (cancelOrderButton) {
      cancelOrderButton.addEventListener('click', function() {
        cancelReorder();
      });
    }
  }
  
  function addArrowButtonsListeners() {
    container.addEventListener('click', function(e) {
      const upButton = e.target.closest('.entity-list-card__move-up');
      const downButton = e.target.closest('.entity-list-card__move-down');
      
      if (upButton) {
        e.preventDefault();
        moveItem(upButton.closest('.entity-list-card'), 'up');
      } else if (downButton) {
        e.preventDefault();
        moveItem(downButton.closest('.entity-list-card'), 'down');
      }
    });
  }
  
  function moveItem(item, direction) {
    const sibling = direction === 'up' 
      ? item.previousElementSibling 
      : item.nextElementSibling;
    
    if (!sibling) return;
    
    if (direction === 'up') {
      container.insertBefore(item, sibling);
    } else {
      container.insertBefore(sibling, item);
    }
    
    checkOrderChanged();
    updateArrowButtons();
  }
  
  function updateArrowButtons() {
    const items = Array.from(container.children);
    
    items.forEach((item, index) => {
      const upButton = item.querySelector('.entity-list-card__move-up');
      const downButton = item.querySelector('.entity-list-card__move-down');
      
      if (upButton) {
        upButton.disabled = index === 0;
      }
      
      if (downButton) {
        downButton.disabled = index === items.length - 1;
      }
    });
  }
  
  function checkOrderChanged() {
    const currentOrder = Array.from(reorderableItems)
      .map(item => item.dataset[toCamelCase(idField)]);
      
    orderChanged = !arraysEqual(initialOrder, currentOrder);
    toggleReorderButtons(orderChanged);
  }
  
  function toggleReorderButtons(show) {
    if (!reorderButtons) return;
    
    if (show) {
      reorderButtons.style.display = 'flex';
    } else {
      reorderButtons.style.display = 'none';
    }
  }
  
  function updateItemsOrder() {
    const itemIds = Array.from(reorderableItems)
      .map(item => item.dataset[toCamelCase(idField)]);
    
    const input = document.getElementById('item-ids-input');
    input.value = JSON.stringify(itemIds);
    
    const form = document.getElementById('reorder-form');
    form.action = reorderUrl;
    form.submit();
  }
  
  function cancelReorder() {
    // Restore original order
    restoreOriginalOrder(reorderableItems, initialPositions);
    
    // Update arrow buttons state
    updateArrowButtons();
    
    // Hide buttons
    toggleReorderButtons(false);
  }
  
  function saveElementPositions(elements) {
    return Array.from(elements).map((element, index) => {
      const id = element.dataset[toCamelCase(idField)];
      return { id, index };
    });
  }
  
  function restoreOriginalOrder(elements, originalPositions) {
    // Create a sorted copy according to original positions
    const orderedElements = Array.from(elements);
    
    // Sort by original positions
    orderedElements.sort((a, b) => {
      const aId = a.dataset[toCamelCase(idField)];
      const bId = b.dataset[toCamelCase(idField)];
      
      const aPos = originalPositions.find(pos => pos.id === aId)?.index || 0;
      const bPos = originalPositions.find(pos => pos.id === bId)?.index || 0;
      
      return aPos - bPos;
    });
    
    // Reinsert in correct order
    const parent = elements[0].parentNode;
    orderedElements.forEach(element => {
      parent.appendChild(element);
    });
  }
  
  function arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    for (let i = 0; i < a.length; i++) {
      if (a[i] !== b[i]) return false;
    }
    return true;
  }
  
  // Helper function to convert kebab-case or snake_case to camelCase
  function toCamelCase(str) {
    return str.replace(/[-_]([a-z])/g, (g) => g[1].toUpperCase());
  }
  
  // Initialize arrow buttons state
  updateArrowButtons();
}