// resources/js/admin/deck-card-selector.js

export default function initDeckCardSelector() {
  const selectors = document.querySelectorAll('[data-component="deck-card-selector"]');
  
  if (!selectors.length) return;
  
  selectors.forEach(selectorElement => {
    const maxCopies = parseInt(selectorElement.dataset.maxCopies) || 3;
    const minCards = parseInt(selectorElement.dataset.minCards) || 0;
    const maxCards = parseInt(selectorElement.dataset.maxCards) || 999;
    const searchInput = selectorElement.querySelector('[data-search-input]');
    const searchClear = selectorElement.querySelector('[data-search-clear]');
    const availableList = selectorElement.querySelector('[data-available-list]');
    const selectedList = selectorElement.querySelector('[data-selected-list]');
    const selectedCount = selectorElement.querySelector('[data-selected-count]');
    const totalCardsDisplay = selectorElement.querySelector('[data-total-cards]');
    const emptyMessage = selectorElement.querySelector('[data-empty-message]');
    const hiddenInputsContainer = selectorElement.querySelector('[data-hidden-inputs]');
    
    if (!availableList || !selectedList || !hiddenInputsContainer) return;
    
    const selectedCards = new Map();
    const MERCENARIES_ID = '1';
    
    // Get faction selector (now supports multiple)
    const factionSelect = document.querySelector('select[name="faction_ids[]"]');
    
    // Get selected faction IDs
    function getSelectedFactionIds() {
      if (!factionSelect) return [];
      
      // If Choices.js is initialized
      if (factionSelect.choicesInstance) {
        const values = factionSelect.choicesInstance.getValue(true);
        return Array.isArray(values) ? values.map(String) : [String(values)];
      }
      
      // Fallback to native select
      const options = Array.from(factionSelect.selectedOptions);
      return options.map(option => String(option.value)).filter(val => val);
    }
    
    // Initialize selected cards from server-rendered content
    function initializeSelectedCards() {
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      selectedItems.forEach(item => {
        const cardId = item.dataset.cardId;
        const isUnique = item.dataset.cardUnique === 'true';
        
        // Try to get copies from span (Blade-rendered) or input (JS-created)
        const copiesSpan = item.querySelector('[data-copies-count]');
        const copiesInput = item.querySelector('[data-copies-input]');
        
        let copies = 1;
        if (copiesSpan) {
          copies = parseInt(copiesSpan.textContent) || 1;
        } else if (copiesInput) {
          copies = parseInt(copiesInput.value) || 1;
        }
        
        selectedCards.set(cardId, copies);
        
        // Convert Blade-rendered controls to interactive ones
        if (copiesSpan) {
          convertCopiesControlToInteractive(item, copies, isUnique);
        }
      });
    }
    
    // Convert Blade-rendered copies control to interactive version
    function convertCopiesControlToInteractive(selectedItem, currentCopies, isUnique) {
      const copiesControl = selectedItem.querySelector('[data-copies-control]');
      if (!copiesControl) return;
      
      const copiesSpan = copiesControl.querySelector('[data-copies-count]');
      if (!copiesSpan) return;
      
      // Replace span with input
      const copiesInput = document.createElement('input');
      copiesInput.type = 'number';
      copiesInput.className = 'card-item__copies-input';
      copiesInput.dataset.copiesInput = '';
      copiesInput.value = currentCopies;
      copiesInput.min = '1';
      copiesInput.max = isUnique ? '1' : maxCopies;
      copiesInput.readOnly = true;
      
      copiesSpan.replaceWith(copiesInput);
      
      // Disable increase button if unique
      const increaseBtn = copiesControl.querySelector('[data-increase-copies]');
      if (increaseBtn && isUnique) {
        increaseBtn.disabled = true;
      }
    }
    
    // Check if card belongs to valid factions
    function cardBelongsToValidFaction(cardFactionId, selectedFactionIds) {
      // If no factions selected, show all
      if (!selectedFactionIds || selectedFactionIds.length === 0) {
        return true;
      }
      
      // Mercenaries cards are always valid if there's at least one faction selected
      if (cardFactionId === MERCENARIES_ID) {
        return true;
      }
      
      // Card must belong to one of the selected non-mercenary factions
      return selectedFactionIds.includes(cardFactionId);
    }
    
    // Filter cards by faction(s)
    function filterByFactions(selectedFactionIds) {
      const availableItems = availableList.querySelectorAll('[data-card-item]');
      
      availableItems.forEach(item => {
        const cardFactionId = item.dataset.cardFaction;
        const cardId = item.dataset.cardId;
        const isSelected = selectedCards.has(cardId);
        
        // Hide if already selected
        if (isSelected) {
          item.style.display = 'none';
          return;
        }
        
        // Check if card belongs to valid factions
        if (cardBelongsToValidFaction(cardFactionId, selectedFactionIds)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      
      // Re-apply search filter if there's a search term
      if (searchInput && searchInput.value.trim()) {
        handleSearch({ target: searchInput });
      }
    }
    
    // Handle search input
    function handleSearch(event) {
      const searchTerm = event.target.value.toLowerCase().trim();
      
      // Show/hide clear button
      if (searchClear) {
        searchClear.style.display = searchTerm ? 'flex' : 'none';
      }

      const availableItems = availableList.querySelectorAll('[data-card-item]');
      const selectedFactionIds = getSelectedFactionIds();
      
      availableItems.forEach(item => {
        const cardName = item.dataset.cardName;
        const cardFactionId = item.dataset.cardFaction;
        const cardId = item.dataset.cardId;
        const isSelected = selectedCards.has(cardId);
        
        if (isSelected) {
          item.style.display = 'none';
          return;
        }
        
        // Check faction filter
        const passesFactionFilter = cardBelongsToValidFaction(cardFactionId, selectedFactionIds);
        
        // Check search filter
        const passesSearchFilter = !searchTerm || cardName.includes(searchTerm);
        
        if (passesFactionFilter && passesSearchFilter) {
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
    
    // Add card to selected list
    function addCard(cardItem) {
      const cardId = cardItem.dataset.cardId;
      const isUnique = cardItem.dataset.cardUnique === 'true';
      
      if (selectedCards.has(cardId)) {
        return;
      }

      // Check max cards limit
      const currentTotal = getTotalCards();
      if (currentTotal >= maxCards) {
        alert(`No puedes añadir más cartas. Máximo: ${maxCards}`);
        return;
      }

      // Hide from available list
      cardItem.style.display = 'none';
      
      // Clone and modify for selected list
      const selectedItem = createSelectedItem(cardItem, isUnique);
      
      // Hide empty message if visible
      if (emptyMessage) {
        emptyMessage.style.display = 'none';
      }
      
      // Add to selected list
      selectedList.appendChild(selectedItem);
      
      // Update state
      selectedCards.set(cardId, 1);
      updateSelectedCount();
      updateTotalCardsDisplay();
      updateHiddenInputs();
      clearSearch();
    }
    
    // Create selected item from available item
    function createSelectedItem(cardItem, isUnique) {
      const selectedItem = document.createElement('div');
      selectedItem.className = 'deck-card-selector__item deck-card-selector__item--selected';
      selectedItem.dataset.selectedItem = '';
      selectedItem.dataset.cardId = cardItem.dataset.cardId;
      selectedItem.dataset.cardUnique = isUnique ? 'true' : 'false';
      
      // Clone the card item
      const cardItemInner = cardItem.querySelector('[data-card-item-inner]');
      const clonedItem = cardItemInner.cloneNode(true);
      
      // Add copies controls
      const copiesControl = document.createElement('div');
      copiesControl.className = 'card-item__copies';
      copiesControl.dataset.copiesControl = '';
      copiesControl.innerHTML = `
        <button type="button" class="card-item__copies-btn" data-decrease-copies aria-label="Decrease copies">
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        <input type="number" class="card-item__copies-input" data-copies-input value="1" min="1" max="${isUnique ? 1 : maxCopies}" readonly>
        <button type="button" class="card-item__copies-btn" data-increase-copies aria-label="Increase copies" ${isUnique ? 'disabled' : ''}>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 2V10M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      `;
      
      // Add remove button
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'card-item__remove';
      removeButton.dataset.removeCard = '';
      removeButton.setAttribute('aria-label', 'Remove card');
      removeButton.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      `;
      
      clonedItem.appendChild(copiesControl);
      clonedItem.appendChild(removeButton);
      selectedItem.appendChild(clonedItem);
      
      return selectedItem;
    }
    
    // Remove card from selected list
    function removeCard(selectedItem) {
      const cardId = selectedItem.dataset.cardId;
      
      // Remove from selected list
      selectedItem.remove();
      
      // Show in available list
      const availableItem = availableList.querySelector(`[data-card-id="${cardId}"]`);
      if (availableItem) {
        const selectedFactionIds = getSelectedFactionIds();
        const cardFactionId = availableItem.dataset.cardFaction;
        
        // Only show if matches faction filter
        if (cardBelongsToValidFaction(cardFactionId, selectedFactionIds)) {
          availableItem.style.display = '';
        }
      }
      
      // Update state
      selectedCards.delete(cardId);
      updateSelectedCount();
      updateTotalCardsDisplay();
      updateHiddenInputs();
      
      // Show empty message if no items selected
      if (selectedCards.size === 0 && emptyMessage) {
        emptyMessage.style.display = '';
      }
    }
    
    // Update copies for a card
    function updateCopies(selectedItem, newCopies) {
      const cardId = selectedItem.dataset.cardId;
      const isUnique = selectedItem.dataset.cardUnique === 'true';
      const copiesInput = selectedItem.querySelector('[data-copies-input]');
      
      // Validate copies
      const validCopies = Math.max(1, Math.min(newCopies, isUnique ? 1 : maxCopies));
      
      // Check if total would exceed max
      const currentTotal = getTotalCards();
      const currentCopies = selectedCards.get(cardId) || 1;
      const difference = validCopies - currentCopies;
      
      if (currentTotal + difference > maxCards) {
        alert(`No puedes añadir más cartas. Máximo: ${maxCards}`);
        return;
      }
      
      // Update UI
      if (copiesInput) {
        copiesInput.value = validCopies;
      }
      
      // Update state
      selectedCards.set(cardId, validCopies);
      updateTotalCardsDisplay();
      updateHiddenInputs();
    }
    
    // Get total number of cards (sum of all copies)
    function getTotalCards() {
      let total = 0;
      selectedCards.forEach(copies => {
        total += copies;
      });
      return total;
    }
    
    // Update selected count badge
    function updateSelectedCount() {
      if (selectedCount) {
        selectedCount.textContent = selectedCards.size;
      }
    }
    
    // Update total cards display
    function updateTotalCardsDisplay() {
      if (totalCardsDisplay) {
        const total = getTotalCards();
        totalCardsDisplay.textContent = total;
        
        // Add visual feedback if constraints not met
        const display = totalCardsDisplay.parentElement;
        if (display) {
          display.classList.remove('deck-card-selector__total-cards--warning', 'deck-card-selector__total-cards--error');
          
          if (total < minCards) {
            display.classList.add('deck-card-selector__total-cards--warning');
          } else if (total > maxCards) {
            display.classList.add('deck-card-selector__total-cards--error');
          }
        }
      }
    }
    
    // Update hidden form inputs
    function updateHiddenInputs() {
      hiddenInputsContainer.innerHTML = '';
      
      let index = 0;
      selectedCards.forEach((copies, cardId) => {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `cards[${index}][id]`;
        idInput.value = cardId;
        
        const copiesInput = document.createElement('input');
        copiesInput.type = 'hidden';
        copiesInput.name = `cards[${index}][copies]`;
        copiesInput.value = copies;
        
        hiddenInputsContainer.appendChild(idInput);
        hiddenInputsContainer.appendChild(copiesInput);
        index++;
      });
    }
    
    // Attach event listeners
    if (searchInput) {
      searchInput.addEventListener('input', handleSearch);
    }

    if (searchClear) {
      searchClear.addEventListener('click', clearSearch);
    }

    // Faction filter change
    if (factionSelect) {
      // Listen for changes (both native and Choices.js)
      factionSelect.addEventListener('change', () => {
        const selectedFactionIds = getSelectedFactionIds();
        filterByFactions(selectedFactionIds);
      });
      
      // Also listen to Choices.js specific events if available
      if (factionSelect.choicesInstance) {
        factionSelect.addEventListener('addItem', () => {
          const selectedFactionIds = getSelectedFactionIds();
          filterByFactions(selectedFactionIds);
        });
        
        factionSelect.addEventListener('removeItem', () => {
          const selectedFactionIds = getSelectedFactionIds();
          filterByFactions(selectedFactionIds);
        });
      }
    }

    // Add card on click
    availableList.addEventListener('click', (e) => {
      const cardItem = e.target.closest('[data-card-item]');
      if (cardItem) {
        addCard(cardItem);
      }
    });

    // Selected list event delegation
    selectedList.addEventListener('click', (e) => {
      const removeButton = e.target.closest('[data-remove-card]');
      const increaseButton = e.target.closest('[data-increase-copies]');
      const decreaseButton = e.target.closest('[data-decrease-copies]');
      
      if (removeButton) {
        const selectedItem = removeButton.closest('[data-selected-item]');
        if (selectedItem) {
          removeCard(selectedItem);
        }
      } else if (increaseButton && !increaseButton.disabled) {
        const selectedItem = increaseButton.closest('[data-selected-item]');
        const copiesInput = selectedItem.querySelector('[data-copies-input]');
        const currentCopies = parseInt(copiesInput.value);
        updateCopies(selectedItem, currentCopies + 1);
      } else if (decreaseButton) {
        const selectedItem = decreaseButton.closest('[data-selected-item]');
        const copiesInput = selectedItem.querySelector('[data-copies-input]');
        const currentCopies = parseInt(copiesInput.value);
        if (currentCopies > 1) {
          updateCopies(selectedItem, currentCopies - 1);
        }
      }
    });
    
    // Initialize
    initializeSelectedCards();
    updateSelectedCount();
    updateTotalCardsDisplay();
    updateHiddenInputs();
    
    // Apply initial faction filter if factions are already selected
    const initialFactionIds = getSelectedFactionIds();
    if (initialFactionIds.length > 0) {
      filterByFactions(initialFactionIds);
    }
  });
}