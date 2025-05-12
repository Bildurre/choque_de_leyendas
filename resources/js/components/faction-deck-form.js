/**
 * Initialize dynamic faction deck form behavior
 */
const initFactionDeckForm = () => {
  // Check if the faction deck form exists in the page
  const form = document.getElementById('faction-deck-form');
  if (!form) return;
  
  // Ajustamos los selectores para que coincidan con los nombres de los campos
  const gameModesSelector = document.getElementById('game_mode_id');
  const factionsSelector = document.getElementById('faction_id');
  
  // Si no encontramos alguno de los selectores, intentamos con los IDs alternativos
  const gameModesSelectorAlt = !gameModesSelector ? document.getElementById('game-mode-selector') : null;
  const factionsSelectorAlt = !factionsSelector ? document.getElementById('faction-selector') : null;
  
  // Usamos los selectores que encontramos
  const finalGameModesSelector = gameModesSelector || gameModesSelectorAlt;
  const finalFactionsSelector = factionsSelector || factionsSelectorAlt;
  
  // Si no encontramos ningún selector, salimos
  if (!finalGameModesSelector || !finalFactionsSelector) {
    console.error('No se encontraron los selectores de modo de juego o facción');
    return;
  }
  
  const configInfoContainer = document.getElementById('deck-configuration-info');
  const cardsContainer = document.getElementById('cards-container');
  const heroesContainer = document.getElementById('heroes-container');
  
  // Configuration values display elements
  const minCardsValue = document.getElementById('min-cards-value');
  const maxCardsValue = document.getElementById('max-cards-value');
  const maxCopiesCardValue = document.getElementById('max-copies-card-value');
  const maxCopiesHeroValue = document.getElementById('max-copies-hero-value');
  
  // Variables to store configuration
  let currentGameModeId = finalGameModesSelector.value;
  let currentFactionId = finalFactionsSelector.value;
  let deckConfig = null;

  /**
   * Load deck configuration for the selected game mode
   * @param {string} gameModeId 
   */
  const loadDeckConfiguration = async (gameModeId) => {
    if (!gameModeId) {
      configInfoContainer.style.display = 'none';
      return;
    }
    
    try {
      const response = await fetch(`/api/game-modes/${gameModeId}/configuration`);
      if (!response.ok) {
        throw new Error('Failed to load deck configuration');
      }
      
      deckConfig = await response.json();
      
      // Update displayed configuration
      minCardsValue.textContent = deckConfig.min_cards;
      maxCardsValue.textContent = deckConfig.max_cards;
      maxCopiesCardValue.textContent = deckConfig.max_copies_per_card;
      maxCopiesHeroValue.textContent = deckConfig.max_copies_per_hero;
      
      // Show configuration info
      configInfoContainer.style.display = 'block';
      
    } catch (error) {
      console.error('Error loading deck configuration:', error);
      configInfoContainer.style.display = 'none';
    }
  };

  /**
 * Load cards for the selected faction
 * @param {string} factionId 
 */
const loadCards = async (factionId) => {
  if (!factionId) {
    cardsContainer.innerHTML = `
      <div class="faction-deck-empty-message" id="cards-placeholder">
        <p>${cardsContainer.dataset.emptyMessage || 'Please select a faction first'}</p>
      </div>
    `;
    return;
  }
  
  try {
    // Show loading state
    cardsContainer.innerHTML = '<div class="loader-container"><div class="loader"></div></div>';
    
    // Cambiar de POST a GET
    const url = `${window.location.origin}/api/components/cards-selector?faction_id=${factionId}&max_copies=${deckConfig?.max_copies_per_card || 2}`;
    console.log(`Fetching cards selector: ${url}`);
    
    // Usar método GET
    const response = await fetch(url, {
      method: 'GET', // Cambiado de POST a GET
      headers: {
        'Accept': 'text/html',
      }
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error(`Server response error: ${response.status} ${response.statusText}`, errorText);
      throw new Error(`Failed to load cards selector component`);
    }
    
    // Obtener el HTML del componente
    const html = await response.text();
    cardsContainer.innerHTML = html;
    
    // Inicializar el componente
    initCardsSelectorComponent();
    
  } catch (error) {
    console.error('Error loading faction cards:', error);
    cardsContainer.innerHTML = `
      <div class="faction-deck-empty-message error-message">
        <p>Error loading cards. Please try again.</p>
      </div>
    `;
  }
};

  /**
 * Load heroes for the selected faction
 * @param {string} factionId 
 */
const loadHeroes = async (factionId) => {
  if (!factionId) {
    heroesContainer.innerHTML = `
      <div class="faction-deck-empty-message" id="heroes-placeholder">
        <p>${heroesContainer.dataset.emptyMessage || 'Please select a faction first'}</p>
      </div>
    `;
    return;
  }
  
  try {
    // Show loading state
    heroesContainer.innerHTML = '<div class="loader-container"><div class="loader"></div></div>';
    
    // Cambiar de POST a GET
    const url = `${window.location.origin}/api/components/heroes-selector?faction_id=${factionId}&max_copies=${deckConfig?.max_copies_per_hero || 1}`;
    console.log(`Fetching heroes selector: ${url}`);
    
    // Usar método GET
    const response = await fetch(url, {
      method: 'GET', // Cambiado de POST a GET
      headers: {
        'Accept': 'text/html',
      }
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error(`Server response error: ${response.status} ${response.statusText}`, errorText);
      throw new Error(`Failed to load heroes selector component`);
    }
    
    // Obtener el HTML del componente
    const html = await response.text();
    heroesContainer.innerHTML = html;
    
    // Inicializar el componente
    initHeroesSelectorComponent();
    
  } catch (error) {
    console.error('Error loading faction heroes:', error);
    heroesContainer.innerHTML = `
      <div class="faction-deck-empty-message error-message">
        <p>Error loading heroes. Please try again.</p>
      </div>
    `;
  }
};

  /**
   * Initialize card selector component
   */
  const initCardsSelectorComponent = () => {
    // Add behavior for card checkboxes, search, and counters
    const cardItems = document.querySelectorAll('.cards-selector__item');
    const searchInput = document.querySelector('.cards-selector__search-input');
    const totalCardsElement = document.getElementById('total-cards');
    const uniqueCardsElement = document.getElementById('unique-cards');
    const selectedListElement = document.getElementById('selected-cards-list');
    const noSelectionElement = document.querySelector('.cards-selector__no-selection');
    
    let selectedCards = [];
    
    // Update counters and selected list
    const updateCardCounters = () => {
      const totalCards = selectedCards.reduce((sum, card) => sum + card.copies, 0);
      const uniqueCards = selectedCards.length;
      
      totalCardsElement.textContent = totalCards;
      uniqueCardsElement.textContent = uniqueCards;
      
      // Update selected cards list
      if (uniqueCards === 0) {
        noSelectionElement.style.display = 'block';
        selectedListElement.innerHTML = '';
      } else {
        noSelectionElement.style.display = 'none';
        
        // Group cards by type
        const cardsByType = {};
        selectedCards.forEach(card => {
          if (!cardsByType[card.type]) {
            cardsByType[card.type] = [];
          }
          cardsByType[card.type].push(card);
        });
        
        let html = '';
        for (const type in cardsByType) {
          html += `<div class="cards-selector__type-group"><h4>${type}</h4><ul>`;
          cardsByType[type].forEach(card => {
            html += `<li>${card.name} <span class="cards-selector__count">x${card.copies}</span></li>`;
          });
          html += '</ul></div>';
        }
        
        selectedListElement.innerHTML = html;
      }
    };
    
    // Initialize card search
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        
        cardItems.forEach(item => {
          const cardName = item.dataset.cardName.toLowerCase();
          const cardType = item.dataset.cardType.toLowerCase();
          
          if (cardName.includes(searchTerm) || cardType.includes(searchTerm)) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      });
    }
    
    // Initialize card selection and copies controls
    cardItems.forEach(item => {
      const checkbox = item.querySelector('.cards-selector__checkbox-input');
      const copiesInput = item.querySelector('.cards-selector__copies-input');
      const decreaseBtn = item.querySelector('.cards-selector__copies-btn--decrease');
      const increaseBtn = item.querySelector('.cards-selector__copies-btn--increase');
      const hiddenIdInput = item.querySelector('input[type="hidden"]');
      
      const cardId = item.dataset.cardId;
      const cardName = item.dataset.cardName;
      const cardType = item.dataset.cardType;
      
      // Toggle card selection
      checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
          item.classList.add('is-selected');
          copiesInput.disabled = false;
          hiddenIdInput.disabled = false;
          
          selectedCards.push({
            id: cardId,
            name: cardName,
            type: cardType,
            copies: parseInt(copiesInput.value)
          });
        } else {
          item.classList.remove('is-selected');
          copiesInput.disabled = true;
          hiddenIdInput.disabled = true;
          
          selectedCards = selectedCards.filter(card => card.id !== cardId);
        }
        
        updateCardCounters();
      });
      
      // Handle copies controls
      if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
          let value = parseInt(copiesInput.value);
          if (value > 1) {
            value--;
            copiesInput.value = value;
            
            if (value === 1) {
              decreaseBtn.disabled = true;
            }
            
            increaseBtn.disabled = false;
            
            // Update selected cards array
            const selectedCard = selectedCards.find(card => card.id === cardId);
            if (selectedCard) {
              selectedCard.copies = value;
            }
            
            updateCardCounters();
          }
        });
      }
      
      if (increaseBtn) {
        increaseBtn.addEventListener('click', () => {
          let value = parseInt(copiesInput.value);
          const maxCopies = deckConfig?.max_copies_per_card || parseInt(increaseBtn.dataset.maxCopies) || 2;
          
          if (value < maxCopies) {
            value++;
            copiesInput.value = value;
            
            if (value === maxCopies) {
              increaseBtn.disabled = true;
            }
            
            decreaseBtn.disabled = false;
            
            // Update selected cards array
            const selectedCard = selectedCards.find(card => card.id === cardId);
            if (selectedCard) {
              selectedCard.copies = value;
            }
            
            updateCardCounters();
          }
        });
      }
      
      if (copiesInput) {
        copiesInput.addEventListener('change', () => {
          let value = parseInt(copiesInput.value);
          const maxCopies = deckConfig?.max_copies_per_card || parseInt(increaseBtn.dataset.maxCopies) || 2;
          
          // Ensure value is within range
          if (isNaN(value) || value < 1) value = 1;
          if (value > maxCopies) value = maxCopies;
          
          copiesInput.value = value;
          
          // Update buttons state
          decreaseBtn.disabled = value <= 1;
          increaseBtn.disabled = value >= maxCopies;
          
          // Update selected cards array
          const selectedCard = selectedCards.find(card => card.id === cardId);
          if (selectedCard) {
            selectedCard.copies = value;
          }
          
          updateCardCounters();
        });
      }
      
      // Initialize from current state
      if (checkbox.checked) {
        item.classList.add('is-selected');
        copiesInput.disabled = false;
        hiddenIdInput.disabled = false;
        
        selectedCards.push({
          id: cardId,
          name: cardName,
          type: cardType,
          copies: parseInt(copiesInput.value)
        });
      }
    });
    
    // Initial counter update
    updateCardCounters();
  };

  /**
   * Initialize heroes selector component
   */
  const initHeroesSelectorComponent = () => {
    // Similar to initCardsSelectorComponent but for heroes
    const heroItems = document.querySelectorAll('.heroes-selector__item');
    const searchInput = document.querySelector('.heroes-selector__search-input');
    const totalHeroesElement = document.getElementById('total-heroes');
    const uniqueHeroesElement = document.getElementById('unique-heroes');
    const selectedListElement = document.getElementById('selected-heroes-list');
    const noSelectionElement = document.querySelector('.heroes-selector__no-selection');
    
    let selectedHeroes = [];
    
    // Update counters and selected list
    const updateHeroCounters = () => {
      const totalHeroes = selectedHeroes.reduce((sum, hero) => sum + hero.copies, 0);
      const uniqueHeroes = selectedHeroes.length;
      
      totalHeroesElement.textContent = totalHeroes;
      uniqueHeroesElement.textContent = uniqueHeroes;
      
      // Update selected heroes list
      if (uniqueHeroes === 0) {
        noSelectionElement.style.display = 'block';
        selectedListElement.innerHTML = '';
      } else {
        noSelectionElement.style.display = 'none';
        
        // Group heroes by class
        const heroesByClass = {};
        selectedHeroes.forEach(hero => {
          if (!heroesByClass[hero.class]) {
            heroesByClass[hero.class] = [];
          }
          heroesByClass[hero.class].push(hero);
        });
        
        let html = '';
        for (const heroClass in heroesByClass) {
          html += `<div class="heroes-selector__class-group"><h4>${heroClass}</h4><ul>`;
          heroesByClass[heroClass].forEach(hero => {
            html += `<li>${hero.name} <span class="heroes-selector__count">x${hero.copies}</span></li>`;
          });
          html += '</ul></div>';
        }
        
        selectedListElement.innerHTML = html;
      }
    };
    
    // Initialize hero search
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        
        heroItems.forEach(item => {
          const heroName = item.dataset.heroName.toLowerCase();
          const heroClass = item.dataset.heroClass.toLowerCase();
          
          if (heroName.includes(searchTerm) || heroClass.includes(searchTerm)) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      });
    }
    
    // Initialize hero selection and copies controls
    heroItems.forEach(item => {
      const checkbox = item.querySelector('.heroes-selector__checkbox-input');
      const copiesInput = item.querySelector('.heroes-selector__copies-input');
      const decreaseBtn = item.querySelector('.heroes-selector__copies-btn--decrease');
      const increaseBtn = item.querySelector('.heroes-selector__copies-btn--increase');
      const hiddenIdInput = item.querySelector('input[type="hidden"]');
      
      const heroId = item.dataset.heroId;
      const heroName = item.dataset.heroName;
      const heroClass = item.dataset.heroClass;
      
      // Toggle hero selection
      checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
          item.classList.add('is-selected');
          copiesInput.disabled = false;
          hiddenIdInput.disabled = false;
          
          selectedHeroes.push({
            id: heroId,
            name: heroName,
            class: heroClass,
            copies: parseInt(copiesInput.value)
          });
        } else {
          item.classList.remove('is-selected');
          copiesInput.disabled = true;
          hiddenIdInput.disabled = true;
          
          selectedHeroes = selectedHeroes.filter(hero => hero.id !== heroId);
        }
        
        updateHeroCounters();
      });
      
      // Handle copies controls
      if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
          let value = parseInt(copiesInput.value);
          if (value > 1) {
            value--;
            copiesInput.value = value;
            
            if (value === 1) {
              decreaseBtn.disabled = true;
            }
            
            increaseBtn.disabled = false;
            
            // Update selected heroes array
            const selectedHero = selectedHeroes.find(hero => hero.id === heroId);
            if (selectedHero) {
              selectedHero.copies = value;
            }
            
            updateHeroCounters();
          }
        });
      }
      
      if (increaseBtn) {
        increaseBtn.addEventListener('click', () => {
          let value = parseInt(copiesInput.value);
          const maxCopies = deckConfig?.max_copies_per_hero || parseInt(increaseBtn.dataset.maxCopies) || 1;
          
          if (value < maxCopies) {
            value++;
            copiesInput.value = value;
            
            if (value === maxCopies) {
              increaseBtn.disabled = true;
            }
            
            decreaseBtn.disabled = false;
            
            // Update selected heroes array
            const selectedHero = selectedHeroes.find(hero => hero.id === heroId);
            if (selectedHero) {
              selectedHero.copies = value;
            }
            
            updateHeroCounters();
          }
        });
      }
      
      if (copiesInput) {
        copiesInput.addEventListener('change', () => {
          let value = parseInt(copiesInput.value);
          const maxCopies = deckConfig?.max_copies_per_hero || parseInt(increaseBtn.dataset.maxCopies) || 1;
          
          // Ensure value is within range
          if (isNaN(value) || value < 1) value = 1;
          if (value > maxCopies) value = maxCopies;
          
          copiesInput.value = value;
          
          // Update buttons state
          decreaseBtn.disabled = value <= 1;
          increaseBtn.disabled = value >= maxCopies;
          
          // Update selected heroes array
          const selectedHero = selectedHeroes.find(hero => hero.id === heroId);
          if (selectedHero) {
            selectedHero.copies = value;
          }
          
          updateHeroCounters();
        });
      }
      
      // Initialize from current state
      if (checkbox.checked) {
        item.classList.add('is-selected');
        copiesInput.disabled = false;
        hiddenIdInput.disabled = false;
        
        selectedHeroes.push({
          id: heroId,
          name: heroName,
          class: heroClass,
          copies: parseInt(copiesInput.value)
        });
      }
    });
    
    // Initial counter update
    updateHeroCounters();
  };

  // Event listeners for selectors
  if (finalGameModesSelector) {
    finalGameModesSelector.addEventListener('change', (e) => {
      currentGameModeId = e.target.value;
      loadDeckConfiguration(currentGameModeId);
    });
  }
  
  if (finalFactionsSelector) {
    finalFactionsSelector.addEventListener('change', (e) => {
      currentFactionId = e.target.value;
      loadCards(currentFactionId);
      loadHeroes(currentFactionId);
    });
  }
  
  // Initial loads if values are preset
  if (currentGameModeId) {
    loadDeckConfiguration(currentGameModeId);
  }
  
  if (currentFactionId) {
    loadCards(currentFactionId);
    loadHeroes(currentFactionId);
  }
};

export default initFactionDeckForm;