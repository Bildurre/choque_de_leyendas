// resources/js/common/confirmations.js
/**
 * Handle delete confirmation dialogs
 * @param {string} selector - CSS selector for delete buttons
 * @param {string} nameAttribute - Data attribute containing entity name
 * @param {string} entityType - Type of entity (e.g., "facción", "clase")
 */
export function setupDeleteConfirmations(selector, nameAttribute, entityType) {
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll(selector);
    
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const entityName = this.getAttribute(nameAttribute);
        
        if (confirm(`¿Estás seguro de querer eliminar ${entityType === 'la' ? 'la' : 'el'} ${entityType} "${entityName}"?`)) {
          this.closest('form').submit();
        }
      });
    });
  });
}