export default function initFactionDeckForm() {
  const form = document.querySelector('#faction-deck-form');
  if (!form) return;
  
  // Obtener los elementos relevantes
  const gameModeId = form.querySelector('input[name="game_mode_id"]');
  const deckConfigElement = form.querySelector('#deck-config-data');
  const factionSelect = form.querySelector('select[name="faction_id"]');
  
  if (!gameModeId || !deckConfigElement) return;
  
  // Leer la configuración del elemento de datos
  const deckConfig = JSON.parse(deckConfigElement.textContent);
  
  // Variables para contadores
  let totalCards = 0;
  let totalHeroes = 0;
  
  // Elementos de la interfaz
  const statsContainer = form.querySelector('.deck-stats');
  const cardSelector = form.querySelector('.entity-selector[data-entity-type="card"]');
  const heroSelector = form.querySelector('.entity-selector[data-entity-type="hero"]');
  
  // Escuchar eventos de cambios en selección de cartas
  if (cardSelector) {
    cardSelector.addEventListener('entity-selection-changed', function(e) {
      // No necesitamos hacer nada aquí porque las copias no cambian
    });
    
    cardSelector.addEventListener('entity-copies-changed', function(e) {
      totalCards = e.detail.totalCount;
      updateStats();
    });
  }
  
  // Escuchar eventos de cambios en selección de héroes
  if (heroSelector) {
    heroSelector.addEventListener('entity-selection-changed', function(e) {
      // No necesitamos hacer nada aquí porque las copias no cambian
    });
    
    heroSelector.addEventListener('entity-copies-changed', function(e) {
      totalHeroes = e.detail.totalCount;
      updateStats();
    });
  }
  
  // Manejar cambio de facción
  if (factionSelect) {
    // Aplicar filtro inicial si hay una facción seleccionada
    if (factionSelect.value) {
      filterEntitiesByFaction(factionSelect.value);
    } else {
      // Si no hay facción seleccionada, ocultar todos los items
      hideAllEntityItems();
    }
    
    factionSelect.addEventListener('change', function() {
      const factionId = this.value;
      
      if (factionId) {
        filterEntitiesByFaction(factionId);
      } else {
        hideAllEntityItems();
      }
      
      // Resetear selecciones y contadores
      resetSelections();
    });
  }
  
  // Función para filtrar entidades por facción
  function filterEntitiesByFaction(factionId) {
    // Convertir a número para comparación segura
    const factionIdNum = parseInt(factionId, 10);
    
    // Filtrar items de cartas
    if (cardSelector) {
      const cardItems = cardSelector.querySelectorAll('.entity-selector__item:not(.in-selected-list)');
      cardItems.forEach(item => {
        // Buscar el ID de facción en el atributo data o dentro del contenido
        const entityId = parseInt(item.getAttribute('data-entity-id'), 10);
        const card = findCardById(entityId);
        
        if (card && card.faction_id === factionIdNum) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }
    
    // Filtrar items de héroes
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
    
    // Actualizar mensajes de vacío
    updateEmptyMessages();
  }
  
  // Función para encontrar una carta por su ID
  function findCardById(id) {
    // Aquí necesitamos acceso a los datos de las cartas
    // Podemos añadir un script con los datos en la vista
    return window.entityData?.cards?.find(card => card.id === id);
  }
  
  // Función para encontrar un héroe por su ID
  function findHeroById(id) {
    return window.entityData?.heroes?.find(hero => hero.id === id);
  }
  
  // Función para ocultar todos los items de entidades
  function hideAllEntityItems() {
    document.querySelectorAll('.entity-selector[data-faction-filter="true"] .entity-selector__item:not(.in-selected-list)').forEach(item => {
      item.style.display = 'none';
    });
    
    // Actualizar mensajes de vacío
    updateEmptyMessages();
  }
  
  // Función para actualizar los mensajes de vacío
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
  
  // Función para resetear las selecciones
  function resetSelections() {
    // Desmarcar todos los checkboxes y resetear copias
    document.querySelectorAll('.entity-selector__checkbox:checked').forEach(checkbox => {
      checkbox.checked = false;
      const item = checkbox.closest('.entity-selector__item');
      item.classList.remove('is-selected');
      
      // Deshabilitar inputs de copias
      const copiesInput = item.querySelector('.entity-selector__copies-input');
      const idInput = item.querySelector('input[type="hidden"]');
      
      if (copiesInput) {
        copiesInput.disabled = true;
        copiesInput.value = 1;
      }
      
      if (idInput) {
        idInput.disabled = true;
      }
    });
    
    // Limpiar listas de seleccionados
    document.querySelectorAll('.entity-selector__selected-list').forEach(list => {
      const placeholder = list.closest('.entity-selector').querySelector('.entity-selector__placeholder');
      list.querySelectorAll('.entity-selector__item.in-selected-list').forEach(item => item.remove());
      if (placeholder) placeholder.style.display = 'block';
    });
    
    // Resetear contadores
    totalCards = 0;
    totalHeroes = 0;
    updateStats();
  }
  
  // Inicializar estadísticas
  if (statsContainer) {
    updateStats();
  }
  
  // Función para actualizar estadísticas
  function updateStats() {
    if (!statsContainer) return;
    
    const cardStatsElement = statsContainer.querySelector('.deck-stats__cards');
    const heroStatsElement = statsContainer.querySelector('.deck-stats__heroes');
    
    if (cardStatsElement) {
      cardStatsElement.textContent = `${totalCards}/${deckConfig.min_cards}-${deckConfig.max_cards}`;
      
      // Añadir clase para validación visual
      if (totalCards < deckConfig.min_cards || totalCards > deckConfig.max_cards) {
        cardStatsElement.classList.add('invalid');
      } else {
        cardStatsElement.classList.remove('invalid');
      }
    }
    
    if (heroStatsElement) {
      const requiredHeroes = deckConfig.required_heroes;
      heroStatsElement.textContent = `${totalHeroes}/${requiredHeroes}`;
      
      // Añadir clase para validación visual
      if (requiredHeroes > 0 && totalHeroes !== parseInt(requiredHeroes)) {
        heroStatsElement.classList.add('invalid');
      } else {
        heroStatsElement.classList.remove('invalid');
      }
    }
  }
  
  // Validar el formulario antes de enviarlo
  form.addEventListener('submit', function(e) {
    // Primero comprobamos si hay errores en los campos básicos
    let valid = true;
    
    // Validar nombre
    const nameInputs = form.querySelectorAll('input[name^="name["]');
    let hasName = false;
    
    nameInputs.forEach(input => {
      if (input.value.trim() !== '') {
        hasName = true;
      }
    });
    
    if (!hasName) {
      valid = false;
      alert(window.translations?.faction_decks?.name_required || 'Por favor, introduce un nombre para el mazo.');
    }
    
    // Validar facción
    if (!factionSelect || !factionSelect.value) {
      valid = false;
      alert(window.translations?.faction_decks?.faction_required || 'Por favor, selecciona una facción.');
    }
    
    // Validar mazos
    if (valid && deckConfig) {
      // Validar cartas
      if (totalCards < deckConfig.min_cards) {
        valid = false;
        alert(window.translations?.faction_decks?.min_cards_error || 
          `El mazo debe tener al menos ${deckConfig.min_cards} cartas.`);
      } else if (totalCards > deckConfig.max_cards) {
        valid = false;
        alert(window.translations?.faction_decks?.max_cards_error || 
          `El mazo no puede tener más de ${deckConfig.max_cards} cartas.`);
      }
      
      // Validar héroes
      if (deckConfig.required_heroes > 0 && totalHeroes !== parseInt(deckConfig.required_heroes)) {
        valid = false;
        alert(window.translations?.faction_decks?.required_heroes_error || 
          `El mazo debe tener exactamente ${deckConfig.required_heroes} héroes.`);
      }
    }
    
    if (!valid) {
      e.preventDefault();
    }
  });
}