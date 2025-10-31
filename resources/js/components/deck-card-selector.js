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
    
    const selectedCards = new Map(); // Map<cardId, copies>
    let totalCards = 0; // Sum of all copies
    
    // Get faction selector
    const factionSelect = document.querySelector('select[name="faction_id"]');
    
    // Initialize selected cards from server-rendered content
    function initializeSelectedCards() {
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      selectedItems.forEach(item => {
        const cardId = item.dataset.cardId;
        const copiesElement = item.querySelector('[data-copies-count]');
        const copies = copiesElement ? parseInt(copiesElement.textContent) : 1;
        selectedCards.set(cardId, copies);
        totalCards += copies;
      });
    }
    
    // Filter cards by faction
    function filterByFaction(selectedFactionId) {
      const availableItems = availableList.querySelectorAll('[data-card-item]');
      
      availableItems.forEach(item => {
        const cardFactionId = item.dataset.cardFaction;
        const isSelected = selectedCards.has(item.dataset.cardId);
        
        // Hide if selected
        if (isSelected) {
          item.style.display = 'none';
          return;
        }
        
        // If no faction selected, show all
        if (!selectedFactionId) {
          item.style.display = '';
          return;
        }
        
        // Filter by faction
        if (cardFactionId === selectedFactionId) {
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
      const selectedFactionId = factionSelect ? factionSelect.value : null;
      
      availableItems.forEach(item => {
        const cardName = item.dataset.cardName;
        const cardFactionId = item.dataset.cardFaction;
        const isSelected = selectedCards.has(item.dataset.cardId);
        
        if (isSelected) {
          item.style.display = 'none';
          return;
        }
        
        // Check faction filter
        const passesFactionFilter = !selectedFactionId || cardFactionId === selectedFactionId;
        
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
      
      if (selectedCards.has(cardId)) {
        return;
      }

      // Check if adding this card would exceed max cards
      if (totalCards >= maxCards) {
        alert(`No puedes añadir más cartas. Máximo: ${maxCards}`);
        return;
      }

      // Hide from available list
      cardItem.style.display = 'none';
      
      // Clone and modify for selected list
      const selectedItem = createSelectedItem(cardItem);
      
      // Hide empty message if visible
      if (emptyMessage) {
        emptyMessage.style.display = 'none';
      }
      
      // Add to selected list
      selectedList.appendChild(selectedItem);
      
      // Update state
      selectedCards.set(cardId, 1);
      totalCards += 1;
      updateSelectedCount();
      updateTotalCardsDisplay();
      updateHiddenInputs();
      clearSearch();
    }
    
    // Create selected item from available item
    function createSelectedItem(cardItem) {
      const selectedItem = document.createElement('div');
      selectedItem.className = 'deck-card-selector__item deck-card-selector__item--selected';
      selectedItem.dataset.selectedItem = '';
      selectedItem.dataset.cardId = cardItem.dataset.cardId;
      selectedItem.dataset.cardUnique = cardItem.dataset.cardUnique || 'false';
      
      // Clone the card item
      const cardItemInner = cardItem.querySelector('[data-card-item-inner]');
      const clonedItem = cardItemInner.cloneNode(true);
      
      const isUnique = cardItem.dataset.cardUnique === 'true';
      
      // Add copies control
      const copiesControl = document.createElement('div');
      copiesControl.className = 'card-item__copies';
      copiesControl.dataset.copiesControl = '';
      copiesControl.innerHTML = `
        <button 
          type="button" 
          class="card-item__copies-btn" 
          data-decrease-copies
          aria-label="Decrease copies"
        >
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        
        <span class="card-item__copies-count" data-copies-count>1</span>
        
        <button 
          type="button" 
          class="card-item__copies-btn" 
          data-increase-copies
          aria-label="Increase copies"
          ${isUnique ? 'disabled title="Carta única"' : ''}
        >
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
      const copies = selectedCards.get(cardId) || 1;
      
      // Remove from selected list
      selectedItem.remove();
      
      // Show in available list
      const availableItem = availableList.querySelector(`[data-card-id="${cardId}"]`);
      if (availableItem) {
        const selectedFactionId = factionSelect ? factionSelect.value : null;
        const cardFactionId = availableItem.dataset.cardFaction;
        
        // Only show if matches faction filter (or no filter)
        if (!selectedFactionId || cardFactionId === selectedFactionId) {
          availableItem.style.display = '';
        }
      }
      
      // Update state
      selectedCards.delete(cardId);
      totalCards -= copies;
      updateSelectedCount();
      updateTotalCardsDisplay();
      updateHiddenInputs();
      
      // Show empty message if no items selected
      if (selectedCards.size === 0 && emptyMessage) {
        emptyMessage.style.display = '';
      }
    }
    
    // Increase copies
    function increaseCopies(selectedItem) {
      const cardId = selectedItem.dataset.cardId;
      const currentCopies = selectedCards.get(cardId) || 1;
      const isUnique = selectedItem.dataset.cardUnique === 'true';
      
      // Check if card is unique
      if (isUnique) {
        return;
      }
      
      // Check max copies per card
      if (currentCopies >= maxCopies) {
        return;
      }
      
      // Check if adding one more would exceed max total cards
      if (totalCards >= maxCards) {
        alert(`No puedes añadir más cartas. Máximo: ${maxCards}`);
        return;
      }
      
      const newCopies = currentCopies + 1;
      selectedCards.set(cardId, newCopies);
      totalCards += 1;
      
      const copiesElement = selectedItem.querySelector('[data-copies-count]');
      if (copiesElement) {
        copiesElement.textContent = newCopies;
      }
      
      // Disable increase button if max reached
      const increaseBtn = selectedItem.querySelector('[data-increase-copies]');
      if (increaseBtn && (newCopies >= maxCopies || totalCards >= maxCards)) {
        increaseBtn.disabled = true;
      }
      
      updateTotalCardsDisplay();
      updateHiddenInputs();
      
      // Re-enable other increase buttons if we were at max
      updateAllIncreaseButtons();
    }
    
    // Decrease copies
    function decreaseCopies(selectedItem) {
      const cardId = selectedItem.dataset.cardId;
      const currentCopies = selectedCards.get(cardId) || 1;
      
      if (currentCopies <= 1) {
        removeCard(selectedItem);
        return;
      }
      
      const newCopies = currentCopies - 1;
      selectedCards.set(cardId, newCopies);
      totalCards -= 1;
      
      const copiesElement = selectedItem.querySelector('[data-copies-count]');
      if (copiesElement) {
        copiesElement.textContent = newCopies;
      }
      
      // Re-enable increase button if it was disabled
      const increaseBtn = selectedItem.querySelector('[data-increase-copies]');
      const isUnique = selectedItem.dataset.cardUnique === 'true';
      if (increaseBtn && !isUnique && newCopies < maxCopies && totalCards < maxCards) {
        increaseBtn.disabled = false;
      }
      
      updateTotalCardsDisplay();
      updateHiddenInputs();
      
      // Re-enable other increase buttons
      updateAllIncreaseButtons();
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
        totalCardsDisplay.textContent = totalCards;
        
        // Add visual feedback if limits exceeded
        const display = totalCardsDisplay.parentElement;
        if (display) {
          display.classList.remove('deck-card-selector__total-cards--warning', 'deck-card-selector__total-cards--error');
          
          if (totalCards < minCards) {
            display.classList.add('deck-card-selector__total-cards--warning');
          } else if (totalCards > maxCards) {
            display.classList.add('deck-card-selector__total-cards--error');
          }
        }
      }
    }
    
    // Update all increase buttons state
    function updateAllIncreaseButtons() {
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      
      selectedItems.forEach(item => {
        const increaseBtn = item.querySelector('[data-increase-copies]');
        const cardId = item.dataset.cardId;
        const copies = selectedCards.get(cardId) || 1;
        const isUnique = item.dataset.cardUnique === 'true';
        
        if (increaseBtn && !isUnique) {
          // Disable if: card at max copies OR total cards at max
          increaseBtn.disabled = (copies >= maxCopies || totalCards >= maxCards);
        }
      });
    }
    
    // Update hidden form inputs
    function updateHiddenInputs() {
      hiddenInputsContainer.innerHTML = '';
      
      let index = 0;
      selectedCards.forEach((copies, cardId) => {
        // Hidden input for card ID
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `cards[${index}][id]`;
        idInput.value = cardId;
        
        // Hidden input for copies
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
      factionSelect.addEventListener('change', (e) => {
        filterByFaction(e.target.value);
      });
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
      } else if (increaseButton) {
        const selectedItem = increaseButton.closest('[data-selected-item]');
        if (selectedItem) {
          increaseCopies(selectedItem);
        }
      } else if (decreaseButton) {
        const selectedItem = decreaseButton.closest('[data-selected-item]');
        if (selectedItem) {
          decreaseCopies(selectedItem);
        }
      }
    });
    
    // Initialize
    initializeSelectedCards();
    updateSelectedCount();
    updateTotalCardsDisplay();
    updateAllIncreaseButtons();
    updateHiddenInputs();
    
    // Apply initial faction filter if faction is already selected
    if (factionSelect && factionSelect.value) {
      filterByFaction(factionSelect.value);
    }
  });
}