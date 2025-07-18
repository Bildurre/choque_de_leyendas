/**
 * Add Button Component
 * Handles adding entities to the PDF collection
 */

export default function initPdfAddButton() {
  // Usar delegación de eventos para capturar todos los clicks en botones add
  document.addEventListener('click', function(e) {
    const button = e.target.closest('[data-collection-add]');
    if (!button) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const entityType = button.dataset.entityType;
    const entityId = parseInt(button.dataset.entityId);
    
    handleAddToCollection(entityType, entityId);
  });
  
  // Función para manejar la adición a la colección
  function handleAddToCollection(entityType, entityId) {
    const event = new CustomEvent('collection:add', {
      detail: {
        type: entityType,
        entity_id: entityId,
        copies: entityType === 'card' ? 2 : 1
      },
      bubbles: true
    });
    
    document.dispatchEvent(event);
  }
  
  // Verificar el estado inicial de todos los botones
  function checkAllButtonsStatus() {
    const buttons = document.querySelectorAll('[data-collection-add]');
    
    if (buttons.length === 0) return;
    
    // Verificar estado de la colección
    fetch('/pdf-collection/status', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.hasItems) {
        checkItemsStatus(buttons);
      }
    })
    .catch(error => {
      console.error('Error checking collection status:', error);
    });
  }
  
  // Verificar estado de items específicos
  function checkItemsStatus(buttons) {
    fetch('/pdf-collection/items', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      }
    })
    .then(response => response.json())
    .then(data => {
      buttons.forEach(button => {
        const entityType = button.dataset.entityType;
        const entityId = parseInt(button.dataset.entityId);
        
        const isInCollection = data.items.some(item => 
          item.type === entityType && 
          item.entity_id === entityId
        );
        
        updateButtonState(button, isInCollection);
      });
    })
    .catch(error => {
      console.error('Error checking items status:', error);
    });
  }
  
  // Actualizar estado visual del botón
  function updateButtonState(button, isInCollection) {
    button.classList.toggle('add-button--active', isInCollection);
    button.title = isInCollection ? 
      'Remove from collection' : 
      'Add to collection';
  }
  
  // Escuchar actualizaciones de la colección
  document.addEventListener('collection:updated', function() {
    checkAllButtonsStatus();
  });
  
  // Verificar estado inicial con un pequeño delay
  setTimeout(() => {
    checkAllButtonsStatus();
  }, 100);
}