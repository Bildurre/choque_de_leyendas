// resources/js/components/hero-abilities-selector.js
export default function initHeroAbilitiesSelector() {
  const selectors = document.querySelectorAll('.hero-abilities-selector');
  if (!selectors.length) return;
  
  selectors.forEach(selector => {
    const abilityItems = selector.querySelectorAll('.hero-ability-item');
    const checkboxes = selector.querySelectorAll('.hero-ability-item__checkbox');
    const selectedList = selector.querySelector('.hero-abilities-selector__selected-list');
    const placeholder = selector.querySelector('.hero-abilities-selector__placeholder');
    const searchInput = selector.querySelector('.hero-abilities-selector__search-input');
    const clearSearchBtn = selector.querySelector('.hero-abilities-selector__search-clear');
    
    // Inicializar la lista de habilidades seleccionadas
    updateSelectedList();
    
    // Manejar selección de habilidades
    abilityItems.forEach(item => {
      item.addEventListener('click', () => {
        const checkbox = item.querySelector('.hero-ability-item__checkbox');
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
          item.classList.add('is-selected');
        } else {
          item.classList.remove('is-selected');
        }
        
        updateSelectedList();
      });
    });
    
    // Función para actualizar la lista de habilidades seleccionadas
    function updateSelectedList() {
      const selectedAbilities = Array.from(checkboxes).filter(checkbox => checkbox.checked);
      
      // Limpiar la lista existente siempre, independientemente de si hay seleccionados
      const existingItems = selectedList.querySelectorAll('.hero-ability-item.in-selected-list');
      existingItems.forEach(item => item.remove());
      
      if (selectedAbilities.length > 0) {
        placeholder.style.display = 'none';
        
        // Añadir las habilidades seleccionadas
        selectedAbilities.forEach(checkbox => {
          const originalItem = checkbox.closest('.hero-ability-item');
          const clonedItem = originalItem.cloneNode(true);
          
          // Añadir clase para identificar que está en la lista de seleccionados
          clonedItem.classList.add('in-selected-list');
          
          // Añadir botón para quitar de la selección con icono de papelera
          const removeBtn = document.createElement('button');
          removeBtn.className = 'hero-ability-item__remove';
          removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
          removeBtn.setAttribute('type', 'button');
          removeBtn.setAttribute('aria-label', 'Remove ability');
          
          // Manejar clic en el botón de eliminar
          removeBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Evitar que se propague al ítem
            
            // Desmarcar el checkbox original
            checkbox.checked = false;
            originalItem.classList.remove('is-selected');
            
            // Actualizar la lista
            updateSelectedList();
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
        filterAbilities(searchTerm);
      });
    }
    
    // Botón para limpiar la búsqueda
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterAbilities('');
        searchInput.focus();
      });
    }
    
    // Función para filtrar habilidades
    function filterAbilities(searchTerm) {
      abilityItems.forEach(item => {
        // Solo filtramos en la lista de disponibles, no en los seleccionados
        if (!item.classList.contains('in-selected-list')) {
          const name = item.querySelector('.hero-ability-item__name').textContent.toLowerCase();
          const range = item.querySelector('.hero-ability-item__range')?.textContent.toLowerCase() || '';
          const subtype = item.querySelector('.hero-ability-item__subtype')?.textContent.toLowerCase() || '';
          
          if (name.includes(searchTerm) || range.includes(searchTerm) || subtype.includes(searchTerm)) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        }
      });
    }
  });
}