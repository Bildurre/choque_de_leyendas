import Sortable from 'sortablejs';

export default function initHeroAbilitySelector() {
  const selectors = document.querySelectorAll('[data-component="hero-ability-selector"]');
  
  if (!selectors.length) return;
  
  selectors.forEach(selectorElement => {
    const searchInput = selectorElement.querySelector('[data-search-input]');
    const searchClear = selectorElement.querySelector('[data-search-clear]');
    const availableList = selectorElement.querySelector('[data-available-list]');
    const selectedList = selectorElement.querySelector('[data-selected-list]');
    const selectedCount = selectorElement.querySelector('[data-selected-count]');
    const emptyMessage = selectorElement.querySelector('[data-empty-message]');
    const hiddenInputsContainer = selectorElement.querySelector('[data-hidden-inputs]');
    
    if (!availableList || !selectedList || !hiddenInputsContainer) return;
    
    const selectedAbilities = new Set();
    
    // Initialize selected abilities from server-rendered content
    function initializeSelectedAbilities() {
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      selectedItems.forEach(item => {
        const abilityId = item.dataset.abilityId;
        selectedAbilities.add(abilityId);
      });
    }
    
    // Initialize Sortable.js for drag and drop
    function initializeSortable() {
      new Sortable(selectedList, {
        animation: 150,
        handle: '[data-drag-handle]',
        ghostClass: 'hero-ability-selector__item--ghost',
        dragClass: 'hero-ability-selector__item--drag',
        chosenClass: 'hero-ability-selector__item--chosen',
        forceFallback: true,
        onEnd: () => {
          updateHiddenInputs();
        }
      });
    }
    
    // Handle search input
    function handleSearch(event) {
      const searchTerm = event.target.value.toLowerCase().trim();
      
      // Show/hide clear button
      if (searchClear) {
        searchClear.style.display = searchTerm ? 'flex' : 'none';
      }

      // Filter available abilities
      const availableItems = availableList.querySelectorAll('[data-ability-item]');
      
      availableItems.forEach(item => {
        const abilityName = item.dataset.abilityName;
        const isSelected = selectedAbilities.has(item.dataset.abilityId);
        
        if (isSelected) {
          item.style.display = 'none';
          return;
        }

        if (!searchTerm || abilityName.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }
    
    // Clear search
    function clearSearch() {
      if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
      }
    }
    
    // Add ability to selected list
    function addAbility(abilityItem) {
      const abilityId = abilityItem.dataset.abilityId;
      
      if (selectedAbilities.has(abilityId)) {
        return;
      }

      // Hide from available list
      abilityItem.style.display = 'none';
      
      // Clone and modify for selected list
      const selectedItem = createSelectedItem(abilityItem);
      
      // Hide empty message if visible
      if (emptyMessage) {
        emptyMessage.style.display = 'none';
      }
      
      // Add to selected list
      selectedList.appendChild(selectedItem);
      
      // Update state
      selectedAbilities.add(abilityId);
      updateSelectedCount();
      updateHiddenInputs();
      clearSearch();
    }
    
    // Create selected item from available item
    function createSelectedItem(abilityItem) {
      const selectedItem = document.createElement('div');
      selectedItem.className = 'hero-ability-selector__item hero-ability-selector__item--selected';
      selectedItem.dataset.selectedItem = '';
      selectedItem.dataset.abilityId = abilityItem.dataset.abilityId;
      
      // Clone the ability card
      const abilityCard = abilityItem.querySelector('[data-ability-card]');
      const clonedCard = abilityCard.cloneNode(true);
      
      // Add remove button
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'ability-card__remove';
      removeButton.dataset.removeAbility = '';
      removeButton.setAttribute('aria-label', 'Remove ability');
      removeButton.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      `;
      
      // Add drag handle
      const dragHandle = document.createElement('div');
      dragHandle.className = 'ability-card__drag-handle';
      dragHandle.dataset.dragHandle = '';
      dragHandle.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 3H6.01M10 3H10.01M6 8H6.01M10 8H10.01M6 13H6.01M10 13H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      `;
      
      clonedCard.appendChild(removeButton);
      clonedCard.appendChild(dragHandle);
      selectedItem.appendChild(clonedCard);
      
      return selectedItem;
    }
    
    // Remove ability from selected list
    function removeAbility(selectedItem) {
      const abilityId = selectedItem.dataset.abilityId;
      
      // Remove from selected list
      selectedItem.remove();
      
      // Show in available list
      const availableItem = availableList.querySelector(`[data-ability-id="${abilityId}"]`);
      if (availableItem) {
        availableItem.style.display = '';
      }
      
      // Update state
      selectedAbilities.delete(abilityId);
      updateSelectedCount();
      updateHiddenInputs();
      
      // Show empty message if no items selected
      if (selectedAbilities.size === 0 && emptyMessage) {
        emptyMessage.style.display = '';
      }
    }
    
    // Update selected count badge
    function updateSelectedCount() {
      if (selectedCount) {
        selectedCount.textContent = selectedAbilities.size;
      }
    }
    
    // Update hidden form inputs
    function updateHiddenInputs() {
      // Clear existing hidden inputs
      hiddenInputsContainer.innerHTML = '';
      
      // Get all selected items in order
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      
      selectedItems.forEach((item, index) => {
        const abilityId = item.dataset.abilityId;
        
        // Create hidden input for the ability ID
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `hero_abilities[${index}][id]`;
        hiddenInput.value = abilityId;
        
        hiddenInputsContainer.appendChild(hiddenInput);
      });
    }
    
    // Attach event listeners
    if (searchInput) {
      searchInput.addEventListener('input', handleSearch);
    }

    if (searchClear) {
      searchClear.addEventListener('click', clearSearch);
    }

    // Add ability on click
    availableList.addEventListener('click', (e) => {
      const abilityItem = e.target.closest('[data-ability-item]');
      if (abilityItem) {
        addAbility(abilityItem);
      }
    });

    // Remove ability on button click
    selectedList.addEventListener('click', (e) => {
      const removeButton = e.target.closest('[data-remove-ability]');
      if (removeButton) {
        const selectedItem = removeButton.closest('[data-selected-item]');
        if (selectedItem) {
          removeAbility(selectedItem);
        }
      }
    });
    
    // Initialize
    initializeSelectedAbilities();
    initializeSortable();
    updateSelectedCount();
    updateHiddenInputs();
  });
}