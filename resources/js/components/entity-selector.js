export default function initEntitySelector() {
  const selectors = document.querySelectorAll('.entity-selector');
  if (!selectors.length) return;
  
  selectors.forEach(selector => {
    const entityType = selector.dataset.entityType || 'entity';
    const entityItems = selector.querySelectorAll('.entity-selector__item');
    const checkboxes = selector.querySelectorAll('.entity-selector__checkbox');
    const selectedList = selector.querySelector('.entity-selector__selected-list');
    const placeholder = selector.querySelector('.entity-selector__placeholder');
    const searchInput = selector.querySelector('.entity-selector__search-input');
    const clearSearchBtn = selector.querySelector('.entity-selector__search-clear');
    const showCopies = selector.querySelector('.entity-selector__copies') !== null;
    
    // Inicializar la lista de entidades seleccionadas
    updateSelectedList();
    
    // Manejar selección de entidades
    entityItems.forEach(item => {
      item.addEventListener('click', (e) => {
        // Si el clic fue en un botón o input de copias, no activar checkbox
        if (e.target.closest('.entity-selector__copies-controls')) {
          return;
        }
        
        const checkbox = item.querySelector('.entity-selector__checkbox');
        if (!checkbox) return;
        
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
          item.classList.add('is-selected');
          
          // Si hay control de copias, habilitar los inputs
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            const idInput = item.querySelector('input[type="hidden"]');
            
            if (copiesInput) copiesInput.disabled = false;
            if (idInput) idInput.disabled = false;
          }
        } else {
          item.classList.remove('is-selected');
          
          // Si hay control de copias, deshabilitar los inputs
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            const idInput = item.querySelector('input[type="hidden"]');
            
            if (copiesInput) copiesInput.disabled = true;
            if (idInput) idInput.disabled = true;
          }
        }
        
        updateSelectedList();
        
        // Disparar evento para notificar cambios (útil para actualizar contadores externos)
        const event = new CustomEvent('entity-selection-changed', {
          detail: {
            entityType: entityType,
            selectedCount: getSelectedCount(),
            selectedEntities: getSelectedEntities()
          }
        });
        selector.dispatchEvent(event);
      });
    });
    
    // Manejar botones de control de copias
    if (showCopies) {
      const decreaseButtons = selector.querySelectorAll('.entity-selector__copies-btn--decrease');
      const increaseButtons = selector.querySelectorAll('.entity-selector__copies-btn--increase');
      
      decreaseButtons.forEach(button => {
        button.addEventListener('click', () => {
          const controls = button.closest('.entity-selector__copies-controls');
          const input = controls.querySelector('.entity-selector__copies-input');
          const maxCopies = parseInt(button.dataset.maxCopies || 1, 10);
          let value = parseInt(input.value, 10);
          
          if (value > 1) {
            value--;
            input.value = value;
            
            // Actualizar estado de botones
            button.disabled = value <= 1;
            controls.querySelector('.entity-selector__copies-btn--increase').disabled = value >= maxCopies;
            
            // Disparar evento para notificar cambios
            const event = new CustomEvent('entity-copies-changed', {
              detail: {
                entityType: entityType,
                totalCount: getTotalCount()
              }
            });
            selector.dispatchEvent(event);
          }
        });
      });
      
      increaseButtons.forEach(button => {
        button.addEventListener('click', () => {
          const controls = button.closest('.entity-selector__copies-controls');
          const input = controls.querySelector('.entity-selector__copies-input');
          const maxCopies = parseInt(button.dataset.maxCopies || 1, 10);
          let value = parseInt(input.value, 10);
          
          if (value < maxCopies) {
            value++;
            input.value = value;
            
            // Actualizar estado de botones
            button.disabled = value >= maxCopies;
            controls.querySelector('.entity-selector__copies-btn--decrease').disabled = value <= 1;
            
            // Disparar evento para notificar cambios
            const event = new CustomEvent('entity-copies-changed', {
              detail: {
                entityType: entityType,
                totalCount: getTotalCount()
              }
            });
            selector.dispatchEvent(event);
          }
        });
      });
      
      // También escuchar cambios directos en los inputs
      const copiesInputs = selector.querySelectorAll('.entity-selector__copies-input');
      copiesInputs.forEach(input => {
        input.addEventListener('change', () => {
          const controls = input.closest('.entity-selector__copies-controls');
          const maxCopies = parseInt(controls.querySelector('.entity-selector__copies-btn--increase').dataset.maxCopies || 1, 10);
          let value = parseInt(input.value, 10);
          
          // Validar límites
          if (value < 1) value = 1;
          if (value > maxCopies) value = maxCopies;
          
          input.value = value;
          
          // Actualizar estado de botones
          controls.querySelector('.entity-selector__copies-btn--decrease').disabled = value <= 1;
          controls.querySelector('.entity-selector__copies-btn--increase').disabled = value >= maxCopies;
          
          // Disparar evento para notificar cambios
          const event = new CustomEvent('entity-copies-changed', {
            detail: {
              entityType: entityType,
              totalCount: getTotalCount()
            }
          });
          selector.dispatchEvent(event);
        });
      });
    }
    
    // Función para actualizar la lista de entidades seleccionadas
    function updateSelectedList() {
      const selectedEntities = Array.from(checkboxes).filter(checkbox => checkbox.checked);
      
      // Limpiar la lista existente siempre, independientemente de si hay seleccionados
      const existingItems = selectedList.querySelectorAll('.entity-selector__item.in-selected-list');
      existingItems.forEach(item => item.remove());
      
      if (selectedEntities.length > 0) {
        placeholder.style.display = 'none';
        
        // Añadir las entidades seleccionadas
        selectedEntities.forEach(checkbox => {
          const originalItem = checkbox.closest('.entity-selector__item');
          const clonedItem = originalItem.cloneNode(true);
          
          // Añadir clase para identificar que está en la lista de seleccionados
          clonedItem.classList.add('in-selected-list');
          
          // Añadir botón para quitar de la selección con icono de papelera
          const removeBtn = document.createElement('button');
          removeBtn.className = 'entity-selector__remove';
          removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
          removeBtn.setAttribute('type', 'button');
          removeBtn.setAttribute('aria-label', 'Remove entity');
          
          // Manejar clic en el botón de eliminar
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Evitar que se propague al ítem
            
            // Desmarcar el checkbox original
            checkbox.checked = false;
            originalItem.classList.remove('is-selected');
            
            // Si hay control de copias, deshabilitar los inputs
            if (showCopies) {
              const copiesInput = originalItem.querySelector('.entity-selector__copies-input');
              const idInput = originalItem.querySelector('input[type="hidden"]');
              
              if (copiesInput) copiesInput.disabled = true;
              if (idInput) idInput.disabled = true;
            }
            
            // Actualizar la lista
            updateSelectedList();
            
            // Disparar evento para notificar cambios
            const event = new CustomEvent('entity-selection-changed', {
              detail: {
                entityType: entityType,
                selectedCount: getSelectedCount(),
                selectedEntities: getSelectedEntities()
              }
            });
            selector.dispatchEvent(event);
          });
          
          clonedItem.appendChild(removeBtn);
          selectedList.appendChild(clonedItem);
        });
      } else {
        // Mostrar el placeholder cuando no hay elementos seleccionados
        placeholder.style.display = 'block';
      }
    }
    
    // Filtrado de búsqueda
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        filterEntities(searchTerm);
      });
    }
    
    // Botón para limpiar la búsqueda
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterEntities('');
        searchInput.focus();
      });
    }
    
    // Función para filtrar entidades
    function filterEntities(searchTerm) {
      entityItems.forEach(item => {
        // Solo filtramos en la lista de disponibles, no en los seleccionados
        if (!item.classList.contains('in-selected-list')) {
          const name = item.getAttribute('data-entity-name').toLowerCase();
          const type = item.getAttribute('data-entity-type').toLowerCase();
          const detailsText = Array.from(item.querySelectorAll('.entity-selector__details'))
            .map(el => el.textContent.toLowerCase())
            .join(' ');
          
          if (name.includes(searchTerm) || type.includes(searchTerm) || detailsText.includes(searchTerm)) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        }
      });
    }
    
    // Función para obtener el número de entidades seleccionadas
    function getSelectedCount() {
      return Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
    }
    
    // Función para obtener las entidades seleccionadas
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
            }
          }
          
          return result;
        });
    }
    
    // Función para obtener el total de copias (para cartas o héroes)
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
  });
}