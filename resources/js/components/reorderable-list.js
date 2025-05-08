import Sortable from 'sortablejs';

export default function initReorderableLists() {
  const container = document.getElementById('reorderable-items');
  if (!container) return;
  
  const reorderUrl = container.dataset.reorderUrl;
  const idField = container.dataset.idField || 'id';
  let orderChanged = false;
  let reorderButtons = document.getElementById('reorder-buttons');
  let saveOrderButton = document.getElementById('save-reorder-button');
  let cancelOrderButton = document.getElementById('cancel-reorder-button');
  let initialOrder = [];
  let initialPositions = [];
  
  // Obtener todos los elementos reordenables
  const reorderableItems = container.children;
  
  // Guardar el orden inicial y las posiciones
  initialOrder = Array.from(reorderableItems)
    .map(item => item.dataset[toCamelCase(idField)]);
  
  // Guardar las posiciones iniciales para poder restaurarlas
  initialPositions = saveElementPositions(reorderableItems);
  
  const sortable = Sortable.create(container, {
    animation: 150,
    ghostClass: 'entity-list-card--ghost',
    chosenClass: 'entity-list-card--chosen',
    dragClass: 'entity-list-card--drag',
    handle: '.entity-list-card__header', // Usar el encabezado como selector para arrastrar
    filter: '.action-button, button, a, input, textarea',
    preventOnFilter: true,
    onEnd: function() {
      // Comprobar si el orden ha cambiado
      const currentOrder = Array.from(reorderableItems)
        .map(item => item.dataset[toCamelCase(idField)]);
        
      // Compara si el orden actual es diferente del inicial
      orderChanged = !arraysEqual(initialOrder, currentOrder);
      
      // Mostrar u ocultar los botones de reordenamiento según corresponda
      toggleReorderButtons(orderChanged);
    }
  });
  
  // Inicialmente ocultar los botones
  if (reorderButtons) {
    toggleReorderButtons(false);
    
    // Agregar evento al botón de guardar
    if (saveOrderButton) {
      saveOrderButton.addEventListener('click', function() {
        updateItemsOrder();
      });
    }
    
    // Agregar evento al botón de cancelar
    if (cancelOrderButton) {
      cancelOrderButton.addEventListener('click', function() {
        cancelReorder();
      });
    }
  }
  
  function toggleReorderButtons(show) {
    if (!reorderButtons) return;
    
    if (show) {
      reorderButtons.style.display = 'flex';
    } else {
      reorderButtons.style.display = 'none';
    }
  }
  
  function updateItemsOrder() {
    const itemIds = Array.from(reorderableItems)
      .map(item => item.dataset[toCamelCase(idField)]);
    
    const input = document.getElementById('item-ids-input');
    input.value = JSON.stringify(itemIds);
    
    const form = document.getElementById('reorder-form');
    form.action = reorderUrl;
    form.submit();
  }
  
  function cancelReorder() {
    // Restaurar el orden original
    restoreOriginalOrder(reorderableItems, initialPositions);
    
    // Ocultar los botones
    toggleReorderButtons(false);
  }
  
  function saveElementPositions(elements) {
    return Array.from(elements).map((element, index) => {
      const id = element.dataset[toCamelCase(idField)];
      return { id, index };
    });
  }
  
  function restoreOriginalOrder(elements, originalPositions) {
    // Crear una copia ordenada según las posiciones originales
    const orderedElements = Array.from(elements);
    
    // Ordenar según las posiciones originales
    orderedElements.sort((a, b) => {
      const aId = a.dataset[toCamelCase(idField)];
      const bId = b.dataset[toCamelCase(idField)];
      
      const aPos = originalPositions.find(pos => pos.id === aId)?.index || 0;
      const bPos = originalPositions.find(pos => pos.id === bId)?.index || 0;
      
      return aPos - bPos;
    });
    
    // Reinsertarlos en el orden correcto
    const parent = elements[0].parentNode;
    orderedElements.forEach(element => {
      parent.appendChild(element);
    });
  }
  
  function arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    for (let i = 0; i < a.length; i++) {
      if (a[i] !== b[i]) return false;
    }
    return true;
  }
  
  // Función auxiliar para convertir kebab-case o snake_case a camelCase
  function toCamelCase(str) {
    return str.replace(/[-_]([a-z])/g, (g) => g[1].toUpperCase());
  }
}