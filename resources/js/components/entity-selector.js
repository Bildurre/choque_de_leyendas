// resources/js/components/entity-selector.js
export default function initEntitySelector() {
  const selectors = document.querySelectorAll('.entity-selector');
  if (!selectors.length) return;
  
  selectors.forEach(selector => {
    const entityType = selector.dataset.entityType || 'entity';
    const fieldName = selector.dataset.fieldName || entityType + 's';
    const entityItems = selector.querySelectorAll('.entity-selector__item');
    const checkboxes = selector.querySelectorAll('.entity-selector__checkbox');
    const selectedList = selector.querySelector('.entity-selector__selected-list');
    const placeholder = selector.querySelector('.entity-selector__placeholder');
    const searchInput = selector.querySelector('.entity-selector__search-input');
    const clearSearchBtn = selector.querySelector('.entity-selector__search-clear');
    const showCopies = selector.querySelector('.entity-selector__copies') !== null;
    const formInputsContainer = selector.querySelector('.entity-selector__form-inputs');
    
    // Inicializar
    updateSelectedList();
    updateFormInputs();
    
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
          
          // Si hay control de copias, habilitar el input
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            if (copiesInput) copiesInput.disabled = false;
          }
        } else {
          item.classList.remove('is-selected');
          
          // Si hay control de copias, deshabilitar el input
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            if (copiesInput) {
              copiesInput.disabled = true;
              copiesInput.value = 1; // Reset a 1
            }
          }
        }
        
        updateSelectedList();
        updateFormInputs();
        
        // Disparar evento para notificar cambios
        const event = new CustomEvent('entity-selection-changed', {
          detail: {
            entityType: entityType,
            selectedCount: getSelectedCount(),
            selectedEntities: getSelectedEntities(),
            totalCount: getTotalCount()
          }
        });
        selector.dispatchEvent(event);
      });
    });
    
    // Manejar botones de control de copias
    if (showCopies) {
      setupCopyButtons();
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
    
    // Función para configurar los botones de copias
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
      const entityId = entityItem.getAttribute('data-entity-id');
      const maxCopies = parseInt(button.dataset.maxCopies || 1, 10);
      let value = parseInt(input.value, 10);
      
      if (value > 1) {
        value--;
        input.value = value;
        
        // Actualizar estado de botones
        button.disabled = value <= 1;
        controls.querySelector('.entity-selector__copies-btn--increase').disabled = value >= maxCopies;
        
        // Sincronizar con la otra instancia del mismo item si existe
        syncCopiesValue(entityId, value);
        
        // Actualizar los inputs del formulario
        updateFormInputs();
        
        // Disparar evento
        const event = new CustomEvent('entity-copies-changed', {
          detail: {
            entityType: entityType,
            totalCount: getTotalCount(),
            entityId: entityId,
            copies: value
          }
        });
        selector.dispatchEvent(event);
      }
    }
    
    function handleIncreaseCopies(button) {
      const controls = button.closest('.entity-selector__copies-controls');
      const input = controls.querySelector('.entity-selector__copies-input');
      const entityItem = button.closest('.entity-selector__item');
      const entityId = entityItem.getAttribute('data-entity-id');
      const maxCopies = parseInt(button.dataset.maxCopies || 1, 10);
      let value = parseInt(input.value, 10);
      
      if (value < maxCopies) {
        value++;
        input.value = value;
        
        // Actualizar estado de botones
        button.disabled = value >= maxCopies;
        controls.querySelector('.entity-selector__copies-btn--decrease').disabled = value <= 1;
        
        // Sincronizar con la otra instancia del mismo item si existe
        syncCopiesValue(entityId, value);
        
        // Actualizar los inputs del formulario
        updateFormInputs();
        
        // Disparar evento
        const event = new CustomEvent('entity-copies-changed', {
          detail: {
            entityType: entityType,
            totalCount: getTotalCount(),
            entityId: entityId,
            copies: value
          }
        });
        selector.dispatchEvent(event);
      }
    }
    
    function handleCopiesInputChange(input) {
      const controls = input.closest('.entity-selector__copies-controls');
      const entityItem = input.closest('.entity-selector__item');
      const entityId = entityItem.getAttribute('data-entity-id');
      const maxCopies = parseInt(controls.querySelector('.entity-selector__copies-btn--increase').dataset.maxCopies || 1, 10);
      let value = parseInt(input.value, 10);
      
      // Validar límites
      if (isNaN(value) || value < 1) value = 1;
      if (value > maxCopies) value = maxCopies;
      
      input.value = value;
      
      // Actualizar estado de botones
      controls.querySelector('.entity-selector__copies-btn--decrease').disabled = value <= 1;
      controls.querySelector('.entity-selector__copies-btn--increase').disabled = value >= maxCopies;
      
      // Sincronizar con la otra instancia del mismo item si existe
      syncCopiesValue(entityId, value);
      
      // Actualizar los inputs del formulario
      updateFormInputs();
      
      // Disparar evento
      const event = new CustomEvent('entity-copies-changed', {
        detail: {
          entityType: entityType,
          totalCount: getTotalCount(),
          entityId: entityId,
          copies: value
        }
      });
      selector.dispatchEvent(event);
    }
    
    // Función para sincronizar el valor de copias entre el item original y el clonado
    function syncCopiesValue(entityId, value) {
      const allItems = selector.querySelectorAll(`.entity-selector__item[data-entity-id="${entityId}"]`);
      
      allItems.forEach(item => {
        const copiesInput = item.querySelector('.entity-selector__copies-input');
        if (copiesInput && copiesInput.value != value) {
          copiesInput.value = value;
          
          // Actualizar estado de botones
          const controls = copiesInput.closest('.entity-selector__copies-controls');
          if (controls) {
            const maxCopies = parseInt(controls.querySelector('.entity-selector__copies-btn--increase').dataset.maxCopies || 1, 10);
            controls.querySelector('.entity-selector__copies-btn--decrease').disabled = value <= 1;
            controls.querySelector('.entity-selector__copies-btn--increase').disabled = value >= maxCopies;
          }
        }
      });
    }
    
    // Función para actualizar la lista de entidades seleccionadas
    function updateSelectedList() {
      const selectedEntities = Array.from(checkboxes).filter(checkbox => checkbox.checked);
      
      // Limpiar la lista existente
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
          
          // Habilitar el input de copias si existe
          const copiesInput = clonedItem.querySelector('.entity-selector__copies-input');
          if (copiesInput) {
            copiesInput.disabled = false;
          }
          
          // Añadir botón para quitar de la selección
          const removeBtn = document.createElement('button');
          removeBtn.className = 'entity-selector__remove';
          removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
          removeBtn.setAttribute('type', 'button');
          removeBtn.setAttribute('aria-label', 'Remove entity');
          
          // Manejar clic en el botón de eliminar
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Desmarcar el checkbox original
            checkbox.checked = false;
            originalItem.classList.remove('is-selected');
            
            // Deshabilitar y resetear copias
            if (showCopies) {
              const copiesInput = originalItem.querySelector('.entity-selector__copies-input');
              if (copiesInput) {
                copiesInput.disabled = true;
                copiesInput.value = 1;
              }
            }
            
            // Actualizar la lista
            updateSelectedList();
            updateFormInputs();
            
            // Disparar evento
            const event = new CustomEvent('entity-selection-changed', {
              detail: {
                entityType: entityType,
                selectedCount: getSelectedCount(),
                selectedEntities: getSelectedEntities(),
                totalCount: getTotalCount()
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
    
    // Función para actualizar los inputs del formulario
    function updateFormInputs() {
      // Limpiar contenedor
      formInputsContainer.innerHTML = '';
      
      // Obtener entidades seleccionadas
      const selectedEntities = getSelectedEntities();
      
      // Crear inputs para cada entidad seleccionada
      selectedEntities.forEach((entity, index) => {
        // Input para el ID
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `${fieldName}[${index}][id]`;
        idInput.value = entity.id;
        formInputsContainer.appendChild(idInput);
        
        // Input para las copias si es necesario
        if (showCopies) {
          const copiesInput = document.createElement('input');
          copiesInput.type = 'hidden';
          copiesInput.name = `${fieldName}[${index}][copies]`;
          copiesInput.value = entity.copies;
          formInputsContainer.appendChild(copiesInput);
        }
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
            } else {
              result.copies = 1;
            }
          }
          
          return result;
        });
    }
    
    // Función para obtener el total de copias
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