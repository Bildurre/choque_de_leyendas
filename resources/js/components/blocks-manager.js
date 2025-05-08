import Sortable from 'sortablejs';

export default function initBlocksManager() {
  const container = document.getElementById('blocks-container');
  if (!container) return;
  
  const reorderUrl = container.dataset.reorderUrl;
  let orderChanged = false;
  let saveOrderButton = document.getElementById('save-block-order');
  let initialOrder = [];
  
  // Guardar el orden inicial
  initialOrder = Array.from(container.querySelectorAll('.block-item'))
    .map(item => item.dataset.blockId);
  
  const sortable = Sortable.create(container, {
    animation: 150,
    ghostClass: 'block-item--ghost',
    chosenClass: 'block-item--chosen',
    dragClass: 'block-item--drag',
    filter: '.action-button, button, a, input, textarea',
    preventOnFilter: true,
    onEnd: function() {
      // Comprobar si el orden ha cambiado
      const currentOrder = Array.from(container.querySelectorAll('.block-item'))
        .map(item => item.dataset.blockId);
        
      // Compara si el orden actual es diferente del inicial
      orderChanged = !arraysEqual(initialOrder, currentOrder);
      
      // Mostrar u ocultar el botón de guardar según corresponda
      toggleSaveButton(orderChanged);
    }
  });
  
  // Si el botón no existe, crearlo
  if (!saveOrderButton) {
    createSaveOrderButton();
    saveOrderButton = document.getElementById('save-block-order');
  }
  
  // Inicialmente ocultar el botón
  toggleSaveButton(false);
  
  // Agregar evento al botón de guardar
  if (saveOrderButton) {
    saveOrderButton.addEventListener('click', function() {
      updateBlockOrder();
    });
  }
  
  function createSaveOrderButton() {
    const button = document.createElement('button');
    button.id = 'save-block-order';
    button.className = 'btn btn--primary btn--save-order';
    button.textContent = saveOrderButtonText();
    
    // Agregar el botón después del contenedor de bloques
    container.parentNode.insertBefore(button, container.nextSibling);
    
    // Ocultar inicialmente
    button.style.display = 'none';
  }
  
  function toggleSaveButton(show) {
    if (!saveOrderButton) return;
    
    if (show) {
      saveOrderButton.style.display = 'block';
      // Opcional: Añadir una clase para un estilo llamativo
      saveOrderButton.classList.add('btn--attention');
    } else {
      saveOrderButton.style.display = 'none';
      saveOrderButton.classList.remove('btn--attention');
    }
  }
  
  function updateBlockOrder() {
    const blockIds = Array.from(container.querySelectorAll('.block-item'))
      .map(item => item.dataset.blockId);
    
    const input = document.getElementById('block-ids-input');
    input.value = JSON.stringify(blockIds);
    
    const form = document.getElementById('reorder-form');
    form.action = reorderUrl;
    form.submit();
  }
  
  function arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    for (let i = 0; i < a.length; i++) {
      if (a[i] !== b[i]) return false;
    }
    return true;
  }
  
  function saveOrderButtonText() {
    // Usar texto localizado si está disponible
    const saveText = document.body.dataset.saveOrderText || 'Guardar orden';
    return saveText;
  }
}