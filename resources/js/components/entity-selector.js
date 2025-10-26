// resources/js/components/entity-selector.js
import Sortable from 'sortablejs'; // [sortable] requiere que sortablejs esté instalado

export default function initEntitySelector() {
  const selectors = document.querySelectorAll('.entity-selector');
  if (!selectors.length) return;
  
  selectors.forEach(selector => {
    const entityType = selector.dataset.entityType || 'entity';
    const fieldName = selector.dataset.fieldName || entityType + 's';
    const checkboxes = selector.querySelectorAll('.entity-selector__checkbox');
    const selectedList = selector.querySelector('.entity-selector__selected-list');
    const placeholder = selector.querySelector('.entity-selector__placeholder');
    const searchInput = selector.querySelector('.entity-selector__search-input');
    const clearSearchBtn = selector.querySelector('.entity-selector__search-clear');
    const showCopies = selector.querySelector('.entity-selector__copies') !== null;
    const formInputsContainer = selector.querySelector('.entity-selector__form-inputs');

    // [sortable] flag opcional: activar si el root tiene data-sortable-selected="1"
    const sortableEnabled = selector.getAttribute('data-sortable-selected') === '1';
    let sortableInstance = null;

    // Initialize
    updateSelectedList();
    initSortable(); // [sortable]
    updateFormInputs();
    
    // Handle entity selection
    selector.addEventListener('click', (e) => {
      const entityItem = e.target.closest('.entity-selector__item');
      if (!entityItem || e.target.closest('.entity-selector__copies-controls')) {
        return;
      }
      
      const checkbox = entityItem.querySelector('.entity-selector__checkbox');
      if (!checkbox) return;
      
      checkbox.checked = !checkbox.checked;
      
      if (checkbox.checked) {
        entityItem.classList.add('is-selected');
        
        if (showCopies) {
          const copiesInput = entityItem.querySelector('.entity-selector__copies-input');
          if (copiesInput) copiesInput.disabled = false;
        }
      } else {
        entityItem.classList.remove('is-selected');
        
        if (showCopies) {
          const copiesInput = entityItem.querySelector('.entity-selector__copies-input');
          if (copiesInput) {
            copiesInput.disabled = true;
            copiesInput.value = 1; // Reset to 1
          }
        }
      }
      
      clearSearch();
      
      updateSelectedList();
      updateFormInputs();
      dispatchChangeEvent();
    });
    
    // Setup copy buttons if needed
    if (showCopies) {
      setupCopyButtons();
    }
    
    // Search filtering
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        filterEntities(searchTerm);
        if (clearSearchBtn) {
          clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
        }
      });
      if (clearSearchBtn) {
        clearSearchBtn.style.display = 'none';
      }
    }
    
    // Clear search button
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        clearSearch();
        searchInput.focus();
      });
    }
    
    // Function to setup copy buttons
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
      const maxCopies = parseInt(button.dataset.maxCopies || '3', 10);
      
      const currentValue = parseInt(input.value, 10);
      if (currentValue > 1) {
        input.value = currentValue - 1;
        updateCopyDisplay(entityItem, currentValue - 1);
        updateFormInputs();
        dispatchChangeEvent();
      }
    }
    
    function handleIncreaseCopies(button) {
      const controls = button.closest('.entity-selector__copies-controls');
      const input = controls.querySelector('.entity-selector__copies-input');
      const entityItem = button.closest('.entity-selector__item');
      const maxCopies = parseInt(button.dataset.maxCopies || '3', 10);
      
      const currentValue = parseInt(input.value, 10);
      if (currentValue < maxCopies) {
        input.value = currentValue + 1;
        updateCopyDisplay(entityItem, currentValue + 1);
        updateFormInputs();
        dispatchChangeEvent();
      }
    }
    
    function handleCopiesInputChange(input) {
      const entityItem = input.closest('.entity-selector__item');
      const maxCopies = parseInt(input.dataset.maxCopies || '3', 10);
      let value = parseInt(input.value, 10);
      
      if (isNaN(value) || value < 1) {
        value = 1;
      } else if (value > maxCopies) {
        value = maxCopies;
      }
      
      input.value = value;
      updateCopyDisplay(entityItem, value);
      updateFormInputs();
      dispatchChangeEvent();
    }
    
    function updateCopyDisplay(item, value) {
      const entityId = item.getAttribute('data-entity-id');
      const allItems = selector.querySelectorAll(`[data-entity-id="${entityId}"]`);
      allItems.forEach(itemToUpdate => {
        const copiesInput = itemToUpdate.querySelector('.entity-selector__copies-input');
        if (copiesInput && copiesInput.value !== value.toString()) {
          copiesInput.value = value;
        }
      });
    }
    
    // Function to update selected list
    function updateSelectedList() {
      if (!selectedList) return;

      // [sortable] guardamos el orden previo (por IDs) para respetarlo
      const previousOrder = sortableEnabled
        ? Array.from(selectedList.querySelectorAll('.entity-selector__item')).map(i => i.getAttribute('data-entity-id'))
        : [];

      // Limpiar (excepto placeholder)
      const existingItems = selectedList.querySelectorAll('.entity-selector__item');
      existingItems.forEach(item => item.remove());
      
      // Get selected entities
      const selectedEntities = getSelectedEntities();

      if (selectedEntities.length > 0) {
        if (placeholder) placeholder.style.display = 'none';

        // [sortable] ordenar los seleccionados según el orden previo; los nuevos al final
        const orderedEntities = (function() {
          if (!sortableEnabled) return selectedEntities;

          // 1) Si ya había una lista dibujada, mantén ese orden (previousOrder)
          if (previousOrder.length) {
            return selectedEntities.slice().sort((a, b) => {
              const ia = previousOrder.indexOf(a.id);
              const ib = previousOrder.indexOf(b.id);
              if (ia === -1 && ib === -1) return 0;
              if (ia === -1) return 1;
              if (ib === -1) return -1;
              return ia - ib;
            });
          }

          // 2) Primera carga: usa selectedOrder (del Blade). Los que no lo tengan, al final.
          const withOrder = [], withoutOrder = [];
          selectedEntities.forEach(e => (Number.isFinite(e.selectedOrder) ? withOrder : withoutOrder)
            [(Number.isFinite(e.selectedOrder) ? 'push' : 'push')](e));
          withOrder.sort((a, b) => a.selectedOrder - b.selectedOrder);
          return withOrder.concat(withoutOrder);
        })();


        orderedEntities.forEach(entity => {
          const originalItem = selector.querySelector(`.entity-selector__list [data-entity-id="${entity.id}"]`);
          if (!originalItem) return;
          
          const clonedItem = originalItem.cloneNode(true);
          clonedItem.classList.add('in-selected-list');
          clonedItem.style.display = '';

          if (sortableEnabled) {
            const dragIcon = document.createElement('span');
            dragIcon.className = 'entity-selector__drag-icon'; // clase “hook” por si quieres estilizar
            dragIcon.setAttribute('aria-hidden', 'true');
            dragIcon.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <circle cx="5" cy="5" r="1"></circle>
                <circle cx="12" cy="5" r="1"></circle>
                <circle cx="19" cy="5" r="1"></circle>
                <circle cx="5" cy="12" r="1"></circle>
                <circle cx="12" cy="12" r="1"></circle>
                <circle cx="19" cy="12" r="1"></circle>
                <circle cx="5" cy="19" r="1"></circle>
                <circle cx="12" cy="19" r="1"></circle>
                <circle cx="19" cy="19" r="1"></circle>
              </svg>
            `;

            // lo colocamos al inicio del contenido del item clonado
            const content = clonedItem.querySelector('.entity-selector__content') || clonedItem;
            content.insertBefore(dragIcon, content.firstChild);
          }
          
          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'entity-selector__remove';
          removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
          removeBtn.setAttribute('aria-label', 'Remove');
          
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const originalCheckbox = originalItem.querySelector('.entity-selector__checkbox');
            if (originalCheckbox) {
              originalCheckbox.checked = false;
              originalItem.classList.remove('is-selected');
            }
            if (showCopies) {
              const copiesInput = originalItem.querySelector('.entity-selector__copies-input');
              if (copiesInput) {
                copiesInput.disabled = true;
                copiesInput.value = 1;
              }
            }
            originalItem.removeAttribute('data-selected-order');
            clearSearch();
            updateSelectedList();
            updateFormInputs();
            dispatchChangeEvent();
          });
          
          clonedItem.appendChild(removeBtn);
          selectedList.appendChild(clonedItem);
        });

        // [sortable] re-inicializar tras volver a pintar
        if (sortableEnabled) {
          initSortable(true);
        }
      } else {
        if (placeholder) placeholder.style.display = 'block';
      }
    }
    
    // Function to update form inputs
    function updateFormInputs() {
      if (!formInputsContainer) return;
      formInputsContainer.innerHTML = '';

      const selectedEntitiesOrdered = getSelectedEntitiesInVisualOrder(); // [sortable]

      selectedEntitiesOrdered.forEach((entity, index) => {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `${fieldName}[${index}][id]`;
        idInput.value = entity.id;
        formInputsContainer.appendChild(idInput);
        
        if (showCopies) {
          const copiesInput = document.createElement('input');
          copiesInput.type = 'hidden';
          copiesInput.name = `${fieldName}[${index}][copies]`;
          copiesInput.value = entity.copies;
          formInputsContainer.appendChild(copiesInput);
        }
      });
    }

    // [sortable] Devuelve los seleccionados siguiendo el orden visual del selectedList (si está activo)
    function getSelectedEntitiesInVisualOrder() {
      const base = getSelectedEntities();
      if (!sortableEnabled || !selectedList) return base;

      const byId = new Map(base.map(e => [String(e.id), e]));
      const ordered = [];
      selectedList.querySelectorAll('.entity-selector__item').forEach(item => {
        const id = String(item.getAttribute('data-entity-id'));
        if (byId.has(id)) ordered.push(byId.get(id));
      });
      // por si hubiera algún seleccionado que no esté pintado aún (debería no pasar)
      base.forEach(e => { if (!ordered.includes(e)) ordered.push(e); });
      return ordered;
    }

    // [sortable] Inicializa Sortable sobre la lista de seleccionados
    function initSortable(recreate = false) {
      if (!sortableEnabled || !selectedList) return;

      if (recreate && sortableInstance) {
        sortableInstance.destroy();
        sortableInstance = null;
      }
      if (sortableInstance) return;

      sortableInstance = Sortable.create(selectedList, {
        animation: 150,
        draggable: '.entity-selector__item',
        // handle opcional: si quisieras limitar el arrastre a alguna zona añade aquí el selector
        onEnd: () => {
          // tras reordenar, actualizamos inputs ocultos y disparamos evento
          updateFormInputs();
          dispatchChangeEvent();
        }
      });
    }
    
    // Function to filter entities
    function filterEntities(searchTerm) {
      const availableItems = selector.querySelectorAll('.entity-selector__list .entity-selector__item');
      availableItems.forEach(item => {
        const name = (item.getAttribute('data-entity-name') || '').toLowerCase();
        const type = (item.getAttribute('data-entity-type') || '').toLowerCase();
        const detailsText = Array.from(item.querySelectorAll('.entity-selector__details'))
          .map(el => el.textContent.toLowerCase())
          .join(' ');
        
        if (searchTerm === '' || name.includes(searchTerm) || type.includes(searchTerm) || detailsText.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      
      const selectedItems = selector.querySelectorAll('.entity-selector__selected-list .entity-selector__item');
      selectedItems.forEach(item => {
        const name = (item.getAttribute('data-entity-name') || '').toLowerCase();
        const type = (item.getAttribute('data-entity-type') || '').toLowerCase();
        const detailsText = Array.from(item.querySelectorAll('.entity-selector__details'))
          .map(el => el.textContent.toLowerCase())
          .join(' ');
        
        if (searchTerm === '' || name.includes(searchTerm) || type.includes(searchTerm) || detailsText.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
      
      updateEmptyMessages();
    }
    
    function clearSearch() {
      if (searchInput) {
        searchInput.value = '';
        filterEntities('');
        if (clearSearchBtn) {
          clearSearchBtn.style.display = 'none';
        }
      }
    }
    
    function updateEmptyMessages() {
      const availableList = selector.querySelector('.entity-selector__list');
      const selectedListContainer = selector.querySelector('.entity-selector__selected');
      
      if (availableList) {
        const visibleItems = availableList.querySelectorAll('.entity-selector__item:not([style*="display: none"])');
        let emptyMessage = availableList.querySelector('.entity-selector__empty-search');
        
        if (visibleItems.length === 0 && searchInput && searchInput.value.trim() !== '') {
          if (!emptyMessage) {
            emptyMessage = document.createElement('div');
            emptyMessage.className = 'entity-selector__empty-search';
            emptyMessage.textContent = 'No results found';
            availableList.appendChild(emptyMessage);
          }
          emptyMessage.style.display = 'block';
        } else if (emptyMessage) {
          emptyMessage.style.display = 'none';
        }
      }
      
      if (selectedListContainer && selectedList) {
        const visibleItems = selectedList.querySelectorAll('.entity-selector__item:not([style*="display: none"])');
        const hasSelectedItems = selectedList.querySelectorAll('.entity-selector__item').length > 0;
        
        if (hasSelectedItems && visibleItems.length === 0 && searchInput && searchInput.value.trim() !== '') {
          if (placeholder) {
            placeholder.textContent = 'No selected items match the search';
            placeholder.style.display = 'block';
          }
        } else if (!hasSelectedItems) {
          if (placeholder) {
            placeholder.textContent = placeholder.getAttribute('data-original-text') || 'No items selected';
            placeholder.style.display = 'block';
          }
        } else {
          if (placeholder) {
            placeholder.style.display = 'none';
          }
        }
      }
    }
    
    function getSelectedCount() {
      return Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
    }
    
    function getSelectedEntities() {
      return Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => {
          const item = checkbox.closest('.entity-selector__item');
          const result = {
            id: String(checkbox.value),
            name: item.getAttribute('data-entity-name'),
            // ⬇️ nuevo: orden inicial que viene desde Blade (string o vacío)
            selectedOrder: parseInt(item.getAttribute('data-selected-order') || '', 10)
          };
          if (showCopies) {
            const copiesInput = item.querySelector('.entity-selector__copies-input');
            result.copies = copiesInput ? parseInt(copiesInput.value, 10) : 1;
          }
          return result;
        });
    }
    
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
    
    function dispatchChangeEvent() {
      const event = new CustomEvent('entity-selection-changed', {
        detail: {
          entityType: entityType,
          selectedCount: getSelectedCount(),
          selectedEntities: getSelectedEntitiesInVisualOrder(), // [sortable]
          totalCount: getTotalCount()
        }
      });
      selector.dispatchEvent(event);
    }
    
    if (placeholder) {
      placeholder.setAttribute('data-original-text', placeholder.textContent);
    }
  });
}