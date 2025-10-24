// resources/js/components/entity-selector.js
export default function initEntitySelector() {
  const selectors = document.querySelectorAll('.entity-selector');
  if (!selectors.length) return;
  
  selectors.forEach(selector => {
    const entityType = selector.dataset.entityType || 'entity';
    const fieldName = selector.dataset.fieldName || entityType + 's';
    const checkboxes = selector.querySelectorAll('.entity-selector__checkbox');
    const selectedList = selector.querySelector('.entity-selector__selected-list');
    const placeholder = selector.querySelector('.entity-selector__placeholder');
    const searchInput = selector.querySelector('.entity-selector__search-input');
    const clearSearchBtn = selector.querySelector('.entity-selector__search-clear');
    const showCopies = selector.querySelector('.entity-selector__copies') !== null;
    const formInputsContainer = selector.querySelector('.entity-selector__form-inputs');
    
    // Initialize
    updateSelectedList();
    updateFormInputs();
    
    // Handle entity selection
    selector.addEventListener('click', (e) => {
      const entityItem = e.target.closest('.entity-selector__item');
      if (!entityItem || e.target.closest('.entity-selector__copies-controls')) {
        return;
      }
      
      const checkbox = entityItem.querySelector('.entity-selector__checkbox');
      if (!checkbox) return;
      
      checkbox.checked = !checkbox.checked;
      
      if (checkbox.checked) {
        entityItem.classList.add('is-selected');
        
        // Enable copies input if available
        if (showCopies) {
          const copiesInput = entityItem.querySelector('.entity-selector__copies-input');
          if (copiesInput) copiesInput.disabled = false;
        }
      } else {
        entityItem.classList.remove('is-selected');
        
        // Disable copies input if available
        if (showCopies) {
          const copiesInput = entityItem.querySelector('.entity-selector__copies-input');
          if (copiesInput) {
            copiesInput.disabled = true;
            copiesInput.value = 1; // Reset to 1
          }
        }
      }
      
      // Clear search when adding/removing entities
      clearSearch();
      
      updateSelectedList();
      updateFormInputs();
      dispatchChangeEvent();
    });
    
    // Setup copy buttons if needed
    if (showCopies) {
      setupCopyButtons();
    }
    
    // Search filtering
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        filterEntities(searchTerm);
        
        // Show/hide clear button based on input value
        if (clearSearchBtn) {
          clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
        }
      });
      
      // Initially hide clear button
      if (clearSearchBtn) {
        clearSearchBtn.style.display = 'none';
      }
    }
    
    // Clear search button
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        clearSearch();
        searchInput.focus();
      });
    }
    
    // Function to setup copy buttons
    function setupCopyButtons() {
      selector.addEventListener('click', (e) => {
        const decreaseBtn = e.target.closest('.entity-selector__copies-btn--decrease');
        const increaseBtn = e.target.closest('.entity-selector__copies-btn--increase');
        
        if (decreaseBtn) {
          handleDecreaseCopies(decreaseBtn);
        } else if (increaseBtn) {
          handleIncreaseCopies(increaseBtn);
        }
      });
      
      selector.addEventListener('change', (e) => {
        if (e.target.classList.contains('entity-selector__copies-input')) {
          handleCopiesInputChange(e.target);
        }
      });
    }
    
    function handleDecreaseCopies(button) {
      const controls = button.closest('.entity-selector__copies-controls');
      const input = controls.querySelector('.entity-selector__copies-input');
      const entityItem = button.closest('.entity-selector__item');
      const maxCopies = parseInt(button.dataset.maxCopies || '3', 10);
      
      const currentValue = parseInt(input.value, 10);
      if (currentValue > 1) {
        input.value = currentValue - 1;
        updateCopyDisplay(entityItem, currentValue - 1);
        updateFormInputs();
        dispatchChangeEvent();
      }
    }
    
    function handleIncreaseCopies(button) {
      const controls = button.closest('.entity-selector__copies-controls');
      const input = controls.querySelector('.entity-selector__copies-input');
      const entityItem = button.closest('.entity-selector__item');
      const maxCopies = parseInt(button.dataset.maxCopies || '3', 10);
      
      const currentValue = parseInt(input.value, 10);
      if (currentValue < maxCopies) {
        input.value = currentValue + 1;
        updateCopyDisplay(entityItem, currentValue + 1);
        updateFormInputs();
        dispatchChangeEvent();
      }
    }
    
    function handleCopiesInputChange(input) {
      const entityItem = input.closest('.entity-selector__item');
      const maxCopies = parseInt(input.dataset.maxCopies || '3', 10);
      let value = parseInt(input.value, 10);
      
      if (isNaN(value) || value < 1) {
        value = 1;
      } else if (value > maxCopies) {
        value = maxCopies;
      }
      
      input.value = value;
      updateCopyDisplay(entityItem, value);
      updateFormInputs();
      dispatchChangeEvent();
    }
    
    function updateCopyDisplay(item, value) {
      // Update copy display in both available and selected lists
      const entityId = item.getAttribute('data-entity-id');
      const allItems = selector.querySelectorAll(`[data-entity-id="${entityId}"]`);
      
      allItems.forEach(itemToUpdate => {
        const copiesInput = itemToUpdate.querySelector('.entity-selector__copies-input');
        if (copiesInput && copiesInput.value !== value.toString()) {
          copiesInput.value = value;
        }
      });
    }
    
    // Function to update selected list
    function updateSelectedList() {
      if (!selectedList) return;
      
      // Clear current selected list (except placeholder)
      const existingItems = selectedList.querySelectorAll('.entity-selector__item');
      existingItems.forEach(item => item.remove());
      
      // Get selected entities
      const selectedEntities = getSelectedEntities();
      
      if (selectedEntities.length > 0) {
        // Hide placeholder
        if (placeholder) placeholder.style.display = 'none';
        
        // Add selected items to the list
        selectedEntities.forEach(entity => {
          // Find the original item
          const originalItem = selector.querySelector(`.entity-selector__list [data-entity-id="${entity.id}"]`);
          if (!originalItem) return;
          
          // Clone the item
          const clonedItem = originalItem.cloneNode(true);
          clonedItem.classList.add('in-selected-list');
          
          // Remove display style to ensure visibility
          clonedItem.style.display = '';
          
          // Create remove button
          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'entity-selector__remove';
          removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
          removeBtn.setAttribute('aria-label', 'Remove');
          
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Uncheck the original checkbox
            const originalCheckbox = originalItem.querySelector('.entity-selector__checkbox');
            if (originalCheckbox) {
              originalCheckbox.checked = false;
              originalItem.classList.remove('is-selected');
            }
            
            // Reset copies if applicable
            if (showCopies) {
              const copiesInput = originalItem.querySelector('.entity-selector__copies-input');
              if (copiesInput) {
                copiesInput.disabled = true;
                copiesInput.value = 1;
              }
            }
            
            // Clear search when removing entities
            clearSearch();
            
            // Update the lists
            updateSelectedList();
            updateFormInputs();
            dispatchChangeEvent();
          });
          
          clonedItem.appendChild(removeBtn);
          selectedList.appendChild(clonedItem);
        });
        
      } else {
        // Show placeholder when no items are selected
        if (placeholder) placeholder.style.display = 'block';
      }
    }
    
    // Function to update form inputs
    function updateFormInputs() {
      if (!formInputsContainer) return;
      
      // Clear container
      formInputsContainer.innerHTML = '';
      
      // Get selected entities
      const selectedEntities = getSelectedEntities();
      
      // Create inputs for each selected entity
      selectedEntities.forEach((entity, index) => {
        // Input for ID
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `${fieldName}[${index}][id]`;
        idInput.value = entity.id;
        formInputsContainer.appendChild(idInput);
        
        // Input for copies if needed
        if (showCopies) {
          const copiesInput = document.createElement('input');
          copiesInput.type = 'hidden';
          copiesInput.name = `${fieldName}[${index}][copies]`;
          copiesInput.value = entity.copies;
          formInputsContainer.appendChild(copiesInput);
        }
      });
    }
    
    // Function to filter entities
    function filterEntities(searchTerm) {
      // Filter items in available list
      const availableItems = selector.querySelectorAll('.entity-selector__list .entity-selector__item');
      availableItems.forEach(item => {
        const name = (item.getAttribute('data-entity-name') || '').toLowerCase();
        const type = (item.getAttribute('data-entity-type') || '').toLowerCase();
        const detailsText = Array.from(item.querySelectorAll('.entity-selector__details'))
          .map(el => el.textContent.toLowerCase())
          .join(' ');
        
        if (searchTerm === '' || name.includes(searchTerm) || type.includes(searchTerm) || detailsText.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      
      // Filter items in selected list
      const selectedItems = selector.querySelectorAll('.entity-selector__selected-list .entity-selector__item');
      selectedItems.forEach(item => {
        const name = (item.getAttribute('data-entity-name') || '').toLowerCase();
        const type = (item.getAttribute('data-entity-type') || '').toLowerCase();
        const detailsText = Array.from(item.querySelectorAll('.entity-selector__details'))
          .map(el => el.textContent.toLowerCase())
          .join(' ');
        
        if (searchTerm === '' || name.includes(searchTerm) || type.includes(searchTerm) || detailsText.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      
      // Update empty messages if they exist
      updateEmptyMessages();
    }
    
    // Function to clear search
    function clearSearch() {
      if (searchInput) {
        searchInput.value = '';
        filterEntities('');
        
        // Hide clear button
        if (clearSearchBtn) {
          clearSearchBtn.style.display = 'none';
        }
      }
    }
    
    // Function to update empty messages
    function updateEmptyMessages() {
      const availableList = selector.querySelector('.entity-selector__list');
      const selectedListContainer = selector.querySelector('.entity-selector__selected');
      
      // Check available list
      if (availableList) {
        const visibleItems = availableList.querySelectorAll('.entity-selector__item:not([style*="display: none"])');
        let emptyMessage = availableList.querySelector('.entity-selector__empty-search');
        
        if (visibleItems.length === 0 && searchInput && searchInput.value.trim() !== '') {
          if (!emptyMessage) {
            emptyMessage = document.createElement('div');
            emptyMessage.className = 'entity-selector__empty-search';
            emptyMessage.textContent = 'No results found';
            availableList.appendChild(emptyMessage);
          }
          emptyMessage.style.display = 'block';
        } else if (emptyMessage) {
          emptyMessage.style.display = 'none';
        }
      }
      
      // Check selected list
      if (selectedListContainer && selectedList) {
        const visibleItems = selectedList.querySelectorAll('.entity-selector__item:not([style*="display: none"])');
        const hasSelectedItems = selectedList.querySelectorAll('.entity-selector__item').length > 0;
        
        if (hasSelectedItems && visibleItems.length === 0 && searchInput && searchInput.value.trim() !== '') {
          // All selected items are hidden by filter
          if (placeholder) {
            placeholder.textContent = 'No selected items match the search';
            placeholder.style.display = 'block';
          }
        } else if (!hasSelectedItems) {
          // No items selected at all
          if (placeholder) {
            placeholder.textContent = placeholder.getAttribute('data-original-text') || 'No items selected';
            placeholder.style.display = 'block';
          }
        } else {
          // Has visible selected items
          if (placeholder) {
            placeholder.style.display = 'none';
          }
        }
      }
    }
    
    // Function to get selected count
    function getSelectedCount() {
      return Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
    }
    
    // Function to get selected entities
    function getSelectedEntities() {
      return Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => {
          const item = checkbox.closest('.entity-selector__item');
          const result = {
            id: checkbox.value,
            name: item.getAttribute('data-entity-name')
          };
          
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            if (copiesInput) {
              result.copies = parseInt(copiesInput.value, 10);
            } else {
              result.copies = 1;
            }
          }
          
          return result;
        });
    }
    
    // Function to get total count
    function getTotalCount() {
      return Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .reduce((total, checkbox) => {
          if (showCopies) {
            const item = checkbox.closest('.entity-selector__item');
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            if (copiesInput) {
              return total + parseInt(copiesInput.value, 10);
            }
          }
          return total + 1;
        }, 0);
    }
    
    // Function to dispatch change event
    function dispatchChangeEvent() {
      const event = new CustomEvent('entity-selection-changed', {
        detail: {
          entityType: entityType,
          selectedCount: getSelectedCount(),
          selectedEntities: getSelectedEntities(),
          totalCount: getTotalCount()
        }
      });
      selector.dispatchEvent(event);
    }
    
    // Store original placeholder text
    if (placeholder) {
      placeholder.setAttribute('data-original-text', placeholder.textContent);
    }
  });
}