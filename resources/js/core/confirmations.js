/**
 * Set up delete confirmation handlers
 */
export function setupConfirmations() {
  const deleteButtons = document.querySelectorAll('.delete-btn');
  
  deleteButtons.forEach(button => {
    const form = button.closest('form.delete-form');
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get entity name from data attribute
        const entityName = button.getAttribute('data-entity-name') || 'este elemento';
        const entityType = button.getAttribute('data-entity-type') || 'elemento';
        
        // Determine article based on first letter
        const article = ['a', 'e', 'i', 'o', 'u'].includes(entityType.charAt(0).toLowerCase()) ? 'la' : 'el';
        
        // Show confirmation dialog
        if (confirm(`¿Estás seguro de querer eliminar ${article} ${entityType} "${entityName}"?`)) {
          form.submit();
        }
      });
    }
  });
}