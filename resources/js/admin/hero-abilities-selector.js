/**
 * Initialize the hero abilities selector component
 */
export function initHeroAbilitiesSelector() {
  const container = document.querySelector('.hero-abilities-selector');
  if (!container) return;
  
  const searchInput = document.getElementById('abilities-search');
  const typeFilter = document.getElementById('abilities-type-filter');
  const subtypeFilter = document.getElementById('abilities-subtype-filter');
  const rangeFilter = document.getElementById('abilities-range-filter');
  const availableList = document.getElementById('available-abilities-list');
  const selectedList = document.getElementById('selected-abilities-list');
  
  // Add event listeners for adding abilities
  container.querySelectorAll('.add-ability-btn').forEach(button => {
    button.addEventListener('click', addAbility);
  });
  
  // Add event listeners for removing abilities
  container.querySelectorAll('.remove-ability-btn').forEach(button => {
    button.addEventListener('click', removeAbility);
  });
  
  // Add event listener for search
  if (searchInput) {
    searchInput.addEventListener('input', filterAbilities);
  }
  
  // Add event listeners for filters
  if (typeFilter) {
    typeFilter.addEventListener('change', filterAbilities);
  }
  
  if (subtypeFilter) {
    subtypeFilter.addEventListener('change', filterAbilities);
  }
  
  if (rangeFilter) {
    rangeFilter.addEventListener('change', filterAbilities);
  }
  
  /**
   * Add an ability to the selected list
   */
  function addAbility(event) {
    const abilityId = event.currentTarget.dataset.id;
    const abilityCard = container.querySelector(`.available-abilities .ability-card[data-id="${abilityId}"]`);
    
    if (!abilityCard) return;
    
    // Clone the card
    const clone = abilityCard.cloneNode(true);
    
    // Replace the add button with a remove button
    const addButton = clone.querySelector('.add-ability-btn');
    if (addButton) {
      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'remove-ability-btn';
      removeButton.dataset.id = abilityId;
      removeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon--delete"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>';
      removeButton.addEventListener('click', removeAbility);
      
      addButton.parentNode.replaceChild(removeButton, addButton);
    }
    
    // Add hidden input for form submission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'abilities[]';
    hiddenInput.value = abilityId;
    clone.appendChild(hiddenInput);
    
    // Add to selected list
    selectedList.appendChild(clone);
    
    // Remove from available list
    abilityCard.remove();
  }
  
  /**
   * Remove an ability from the selected list
   */
  function removeAbility(event) {
    const abilityId = event.currentTarget.dataset.id;
    const abilityCard = container.querySelector(`.selected-abilities .ability-card[data-id="${abilityId}"]`);
    
    if (!abilityCard) return;
    
    // Clone the card
    const clone = abilityCard.cloneNode(true);
    
    // Replace the remove button with an add button
    const removeButton = clone.querySelector('.remove-ability-btn');
    if (removeButton) {
      const addButton = document.createElement('button');
      addButton.type = 'button';
      addButton.className = 'add-ability-btn';
      addButton.dataset.id = abilityId;
      addButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon--plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
      addButton.addEventListener('click', addAbility);
      
      removeButton.parentNode.replaceChild(addButton, removeButton);
    }
    
    // Remove hidden input for form submission
    const hiddenInput = clone.querySelector('input[name="abilities[]"]');
    if (hiddenInput) {
      hiddenInput.remove();
    }
    
    // Add to available list and apply current filters
    availableList.appendChild(clone);
    
    // Remove from selected list
    abilityCard.remove();
    
    // Re-apply filters to make sure the item respects current filter settings
    filterAbilities();
  }
  
  /**
   * Filter abilities based on search and filters
   */
  function filterAbilities() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selectedType = typeFilter ? typeFilter.value : '';
    const selectedSubtype = subtypeFilter ? subtypeFilter.value : '';
    const selectedRange = rangeFilter ? rangeFilter.value : '';
    
    // Filter only available abilities, not selected ones
    const cards = availableList.querySelectorAll('.ability-card');
    
    cards.forEach(card => {
      const name = card.dataset.name.toLowerCase();
      const type = card.dataset.type;
      const subtype = card.dataset.subtype;
      const range = card.dataset.range;
      
      const matchesSearch = !searchTerm || name.includes(searchTerm);
      const matchesType = !selectedType || type === selectedType;
      const matchesSubtype = !selectedSubtype || subtype === selectedSubtype;
      const matchesRange = !selectedRange || range === selectedRange;
      
      card.style.display = (matchesSearch && matchesType && matchesSubtype && matchesRange) ? 'grid' : 'none';
    });
  }
}