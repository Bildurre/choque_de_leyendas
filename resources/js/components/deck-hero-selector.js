export default function initDeckHeroSelector() {
  const selectors = document.querySelectorAll('[data-component="deck-hero-selector"]');
  
  if (!selectors.length) return;
  
  selectors.forEach(selectorElement => {
    const requiredHeroes = parseInt(selectorElement.dataset.requiredHeroes) || 0;
    const searchInput = selectorElement.querySelector('[data-search-input]');
    const searchClear = selectorElement.querySelector('[data-search-clear]');
    const availableList = selectorElement.querySelector('[data-available-list]');
    const selectedList = selectorElement.querySelector('[data-selected-list]');
    const selectedCount = selectorElement.querySelector('[data-selected-count]');
    const totalHeroesDisplay = selectorElement.querySelector('[data-total-heroes]');
    const emptyMessage = selectorElement.querySelector('[data-empty-message]');
    const hiddenInputsContainer = selectorElement.querySelector('[data-hidden-inputs]');
    
    if (!availableList || !selectedList || !hiddenInputsContainer) return;
    
    const selectedHeroes = new Set();
    
    // Get faction selector
    const factionSelect = document.querySelector('select[name="faction_id"]');
    
    // Initialize selected heroes from server-rendered content
    function initializeSelectedHeroes() {
      const selectedItems = selectedList.querySelectorAll('[data-selected-item]');
      selectedItems.forEach(item => {
        const heroId = item.dataset.heroId;
        selectedHeroes.add(heroId);
      });
    }
    
    // Filter heroes by faction
    function filterByFaction(selectedFactionId) {
      const availableItems = availableList.querySelectorAll('[data-hero-item]');
      
      availableItems.forEach(item => {
        const heroFactionId = item.dataset.heroFaction;
        const isSelected = selectedHeroes.has(item.dataset.heroId);
        
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
        if (heroFactionId === selectedFactionId) {
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

      const availableItems = availableList.querySelectorAll('[data-hero-item]');
      const selectedFactionId = factionSelect ? factionSelect.value : null;
      
      availableItems.forEach(item => {
        const heroName = item.dataset.heroName;
        const heroFactionId = item.dataset.heroFaction;
        const isSelected = selectedHeroes.has(item.dataset.heroId);
        
        if (isSelected) {
          item.style.display = 'none';
          return;
        }
        
        // Check faction filter
        const passesFactionFilter = !selectedFactionId || heroFactionId === selectedFactionId;
        
        // Check search filter
        const passesSearchFilter = !searchTerm || heroName.includes(searchTerm);
        
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
    
    // Add hero to selected list
    function addHero(heroItem) {
      const heroId = heroItem.dataset.heroId;
      
      if (selectedHeroes.has(heroId)) {
        return;
      }

      // Hide from available list
      heroItem.style.display = 'none';
      
      // Clone and modify for selected list
      const selectedItem = createSelectedItem(heroItem);
      
      // Hide empty message if visible
      if (emptyMessage) {
        emptyMessage.style.display = 'none';
      }
      
      // Add to selected list
      selectedList.appendChild(selectedItem);
      
      // Update state
      selectedHeroes.add(heroId);
      updateSelectedCount();
      updateTotalHeroesDisplay();
      updateHiddenInputs();
      clearSearch();
    }
    
    // Create selected item from available item
    function createSelectedItem(heroItem) {
      const selectedItem = document.createElement('div');
      selectedItem.className = 'deck-hero-selector__item deck-hero-selector__item--selected';
      selectedItem.dataset.selectedItem = '';
      selectedItem.dataset.heroId = heroItem.dataset.heroId;
      
      // Clone the hero item
      const heroItemInner = heroItem.querySelector('[data-hero-item-inner]');
      const clonedItem = heroItemInner.cloneNode(true);
      
      // Add remove button
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'hero-item__remove';
      removeButton.dataset.removeHero = '';
      removeButton.setAttribute('aria-label', 'Remove hero');
      removeButton.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      `;
      
      clonedItem.appendChild(removeButton);
      selectedItem.appendChild(clonedItem);
      
      return selectedItem;
    }
    
    // Remove hero from selected list
    function removeHero(selectedItem) {
      const heroId = selectedItem.dataset.heroId;
      
      // Remove from selected list
      selectedItem.remove();
      
      // Show in available list
      const availableItem = availableList.querySelector(`[data-hero-id="${heroId}"]`);
      if (availableItem) {
        const selectedFactionId = factionSelect ? factionSelect.value : null;
        const heroFactionId = availableItem.dataset.heroFaction;
        
        // Only show if matches faction filter (or no filter)
        if (!selectedFactionId || heroFactionId === selectedFactionId) {
          availableItem.style.display = '';
        }
      }
      
      // Update state
      selectedHeroes.delete(heroId);
      updateSelectedCount();
      updateTotalHeroesDisplay();
      updateHiddenInputs();
      
      // Show empty message if no items selected
      if (selectedHeroes.size === 0 && emptyMessage) {
        emptyMessage.style.display = '';
      }
    }
    
    // Update selected count badge
    function updateSelectedCount() {
      if (selectedCount) {
        selectedCount.textContent = selectedHeroes.size;
      }
    }
    
    // Update total heroes display
    function updateTotalHeroesDisplay() {
      if (totalHeroesDisplay) {
        totalHeroesDisplay.textContent = selectedHeroes.size;
        
        // Add visual feedback if required heroes not met
        const display = totalHeroesDisplay.parentElement;
        if (display) {
          display.classList.remove('deck-hero-selector__total-heroes--warning', 'deck-hero-selector__total-heroes--success');
          
          if (selectedHeroes.size < requiredHeroes) {
            display.classList.add('deck-hero-selector__total-heroes--warning');
          } else if (selectedHeroes.size >= requiredHeroes) {
            display.classList.add('deck-hero-selector__total-heroes--success');
          }
        }
      }
    }
    
    // Update hidden form inputs
    function updateHiddenInputs() {
      hiddenInputsContainer.innerHTML = '';
      
      let index = 0;
      selectedHeroes.forEach((heroId) => {
        // Hidden input for hero ID only (no copies)
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `heroes[${index}][id]`;
        idInput.value = heroId;
        
        hiddenInputsContainer.appendChild(idInput);
        
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

    // Add hero on click
    availableList.addEventListener('click', (e) => {
      const heroItem = e.target.closest('[data-hero-item]');
      if (heroItem) {
        addHero(heroItem);
      }
    });

    // Remove hero on button click
    selectedList.addEventListener('click', (e) => {
      const removeButton = e.target.closest('[data-remove-hero]');
      if (removeButton) {
        const selectedItem = removeButton.closest('[data-selected-item]');
        if (selectedItem) {
          removeHero(selectedItem);
        }
      }
    });
    
    // Initialize
    initializeSelectedHeroes();
    updateSelectedCount();
    updateTotalHeroesDisplay();
    updateHiddenInputs();
    
    // Apply initial faction filter if faction is already selected
    if (factionSelect && factionSelect.value) {
      filterByFaction(factionSelect.value);
    }
  });
}