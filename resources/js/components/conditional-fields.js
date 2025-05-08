export default function initConditionalFields() {
  // Handle page parent_id and order field relationship
  const parentSelect = document.getElementById('parent_id');
  const orderFieldContainer = document.getElementById('order-field-container');
  
  if (parentSelect && orderFieldContainer) {
    // Set initial state
    updateOrderFieldVisibility();
    
    // Add event listener for changes
    parentSelect.addEventListener('change', updateOrderFieldVisibility);
    
    function updateOrderFieldVisibility() {
      const hasParent = parentSelect.value !== '' && parentSelect.value !== null;
      orderFieldContainer.style.display = hasParent ? 'block' : 'none';
    }
  }
}