// resources/js/components/faction-deck-form.js
export default function initFactionDeckForm() {
  const form = document.querySelector('#faction-deck-form');
  if (!form) return;
  
  // Get relevant elements
  const gameModeId = form.querySelector('input[name="game_mode_id"]');
  const deckConfigElement = form.querySelector('#deck-config-data');
  const factionSelect = form.querySelector('select[name="faction_id"]');
  
  if (!gameModeId || !deckConfigElement) return;
  
  // Read configuration from data element
  const deckConfig = JSON.parse(deckConfigElement.textContent);
  
  // Variables for counters
  let totalCards = 0;
  let totalHeroes = 0;
  
  // Interface elements
  const cardSelector = form.querySelector('.entity-selector[data-entity-type="card"]');
  const heroSelector = form.querySelector('.entity-selector[data-entity-type="hero"]');
  
  // Find fieldset legends to update counters
  const cardFieldset = cardSelector?.closest('fieldset');
  const heroFieldset = heroSelector?.closest('fieldset');
  const cardLegend = cardFieldset?.querySelector('legend');
  const heroLegend = heroFieldset?.querySelector('legend');
  
  // Store original legend texts
  const originalCardLegendText = cardLegend?.textContent.replace(/\s*\d+\/.*$/, '') || '';
  const originalHeroLegendText = heroLegend?.textContent.replace(/\s*\d+\/.*$/, '') || '';
  
  // Listen for card selection change events
  if (cardSelector) {
    // Initialize card counter on load
    totalCards = getInitialCount(cardSelector);
    updateCardCounter();
    
    cardSelector.addEventListener('entity-selection-changed', function(e) {
      // Update counter with total provided in event
      totalCards = e.detail.totalCount;
      updateCardCounter();
    });
    
    cardSelector.addEventListener('entity-copies-changed', function(e) {
      totalCards = e.detail.totalCount;
      updateCardCounter();
    });
  }
  
  // Listen for hero selection change events
  if (heroSelector) {
    // Initialize hero counter on load
    totalHeroes = getInitialCount(heroSelector);
    updateHeroCounter();
    
    heroSelector.addEventListener('entity-selection-changed', function(e) {
      // Update counter with total provided in event
      totalHeroes = e.detail.totalCount;
      updateHeroCounter();
    });
    
    heroSelector.addEventListener('entity-copies-changed', function(e) {
      totalHeroes = e.detail.totalCount;
      updateHeroCounter();
    });
  }
  
  // Function to get initial entity count
  function getInitialCount(selector) {
    const selectedCheckboxes = selector.querySelectorAll('.entity-selector__checkbox:checked');
    let count = 0;
    
    selectedCheckboxes.forEach(checkbox => {
      const item = checkbox.closest('.entity-selector__item');
      const copiesInput = item.querySelector('.entity-selector__copies-input');
      
      if (copiesInput) {
        count += parseInt(copiesInput.value, 10);
      } else {
        count += 1;
      }
    });
    
    return count;
  }
  
  // Function to update card counter in legend
  function updateCardCounter() {
    if (!cardLegend) return;
    
    const counterText = ` ${totalCards}/${deckConfig.min_cards}-${deckConfig.max_cards}`;
    cardLegend.textContent = originalCardLegendText + counterText;
    
    // Add visual validation classes
    if (totalCards < deckConfig.min_cards || totalCards > deckConfig.max_cards) {
      cardLegend.classList.add('legend--invalid');
    } else {
      cardLegend.classList.remove('legend--invalid');
    }
  }
  
  // Function to update hero counter in legend
  function updateHeroCounter() {
    if (!heroLegend) return;
    
    const counterText = ` ${totalHeroes}/${deckConfig.required_heroes}`;
    heroLegend.textContent = originalHeroLegendText + counterText;
    
    // Add visual validation classes
    if (deckConfig.required_heroes > 0 && totalHeroes !== parseInt(deckConfig.required_heroes)) {
      heroLegend.classList.add('legend--invalid');
    } else {
      heroLegend.classList.remove('legend--invalid');
    }
  }
  
  // Handle faction change
  if (factionSelect) {
    // Apply initial filter if faction is selected
    if (factionSelect.value) {
      filterEntitiesByFaction(factionSelect.value);
    } else {
      // If no faction selected, hide all items
      hideAllEntityItems();
    }
    
    factionSelect.addEventListener('change', function() {
      const factionId = this.value;
      
      if (factionId) {
        filterEntitiesByFaction(factionId);
      } else {
        hideAllEntityItems();
      }
      
      // Reset selections and counters
      resetSelections();
    });
  }
  
  // Function to filter entities by faction
  function filterEntitiesByFaction(factionId) {
    // Convert to number for safe comparison
    const factionIdNum = parseInt(factionId, 10);
    
    // Filter card items
    if (cardSelector) {
      const cardItems = cardSelector.querySelectorAll('.entity-selector__item:not(.in-selected-list)');
      cardItems.forEach(item => {
        const entityId = parseInt(item.getAttribute('data-entity-id'), 10);
        const card = findCardById(entityId);
        
        if (card && card.faction_id === factionIdNum) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }
    
    // Filter hero items
    if (heroSelector) {
      const heroItems = heroSelector.querySelectorAll('.entity-selector__item:not(.in-selected-list)');
      heroItems.forEach(item => {
        const entityId = parseInt(item.getAttribute('data-entity-id'), 10);
        const hero = findHeroById(entityId);
        
        if (hero && hero.faction_id === factionIdNum) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }
    
    // Update empty messages
    updateEmptyMessages();
  }
  
  // Function to find a card by ID
  function findCardById(id) {
    return window.entityData?.cards?.find(card => card.id === id);
  }
  
  // Function to find a hero by ID
  function findHeroById(id) {
    return window.entityData?.heroes?.find(hero => hero.id === id);
  }
  
  // Function to hide all entity items
  function hideAllEntityItems() {
    document.querySelectorAll('.entity-selector[data-faction-filter="true"] .entity-selector__item:not(.in-selected-list)').forEach(item => {
      item.style.display = 'none';
    });
    
    // Update empty messages
    updateEmptyMessages();
  }
  
  // Function to update empty messages
  function updateEmptyMessages() {
    document.querySelectorAll('.entity-selector[data-faction-filter="true"]').forEach(selector => {
      const visibleItems = selector.querySelectorAll('.entity-selector__item:not(.in-selected-list):not([style*="display: none"])');
      const emptyMessage = selector.querySelector('.entity-selector__empty');
      
      if (visibleItems.length === 0 && emptyMessage) {
        emptyMessage.style.display = 'block';
      } else if (emptyMessage) {
        emptyMessage.style.display = 'none';
      }
    });
  }
  
  // Function to reset selections
  function resetSelections() {
    // Uncheck all checkboxes and reset copies
    document.querySelectorAll('.entity-selector__checkbox:checked').forEach(checkbox => {
      checkbox.checked = false;
      const item = checkbox.closest('.entity-selector__item');
      item.classList.remove('is-selected');
      
      // Disable copy inputs
      const copiesInput = item.querySelector('.entity-selector__copies-input');
      
      if (copiesInput) {
        copiesInput.disabled = true;
        copiesInput.value = 1;
      }
    });
    
    // Clear generated hidden inputs
    document.querySelectorAll('.entity-selector__form-inputs').forEach(container => {
      container.innerHTML = '';
    });
    
    // Clear selected lists
    document.querySelectorAll('.entity-selector__selected-list').forEach(list => {
      const placeholder = list.closest('.entity-selector').querySelector('.entity-selector__placeholder');
      list.querySelectorAll('.entity-selector__item.in-selected-list').forEach(item => item.remove());
      if (placeholder) placeholder.style.display = 'block';
    });
    
    // Reset counters
    totalCards = 0;
    totalHeroes = 0;
    updateCardCounter();
    updateHeroCounter();
  }
  
  // Validate form before submission
  form.addEventListener('submit', function(e) {
    // First check for errors in basic fields
    let valid = true;
    
    // Validate name
    const nameInputs = form.querySelectorAll('input[name^="name["]');
    let hasName = false;
    
    nameInputs.forEach(input => {
      if (input.value.trim() !== '') {
        hasName = true;
      }
    });
    
    if (!hasName) {
      valid = false;
      alert(window.translations?.faction_decks?.name_required || 'Please enter a name for the deck.');
    }
    
    // Validate faction
    if (!factionSelect || !factionSelect.value) {
      valid = false;
      alert(window.translations?.faction_decks?.faction_required || 'Please select a faction.');
    }
    
    // Validate decks
    if (valid && deckConfig) {
      // Validate cards
      if (totalCards < deckConfig.min_cards) {
        valid = false;
        alert(window.translations?.faction_decks?.min_cards_error || 
          `The deck must have at least ${deckConfig.min_cards} cards.`);
      } else if (totalCards > deckConfig.max_cards) {
        valid = false;
        alert(window.translations?.faction_decks?.max_cards_error || 
          `The deck cannot have more than ${deckConfig.max_cards} cards.`);
      }
      
      // Validate heroes
      if (deckConfig.required_heroes > 0 && totalHeroes !== parseInt(deckConfig.required_heroes)) {
        valid = false;
        alert(window.translations?.faction_decks?.required_heroes_error || 
          `The deck must have exactly ${deckConfig.required_heroes} heroes.`);
      }
    }
    
    if (!valid) {
      e.preventDefault();
    }
  });
}