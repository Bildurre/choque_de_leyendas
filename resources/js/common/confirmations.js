/**
 * Handle delete confirmation dialog
 * @param {string} selector - CSS selector for the form or button
 * @param {string} nameAttribute - Data attribute containing entity name
 * @param {string} entityType - Type of entity (e.g., "facción", "clase")
 */
export function setupDeleteConfirmation(selector, nameAttribute, entityType) {
  const elements = document.querySelectorAll(selector);
  
  elements.forEach(element => {
    element.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const nameElement = this.querySelector(`[${nameAttribute}]`);
      const entityName = nameElement ? nameElement.getAttribute(nameAttribute) : 'este elemento';
      
      const article = 
        ['a', 'e', 'i', 'o', 'u'].includes(entityType.charAt(0).toLowerCase()) ? 'la' : 'el';
      
      if (confirm(`¿Estás seguro de querer eliminar ${article} ${entityType} "${entityName}"?`)) {
        this.submit();
      }
    });
  });
}

/**
 * Setup delete confirmations for multiple elements
 * @param {string} selector - CSS selector for delete buttons
 * @param {string} nameAttribute - Data attribute containing entity name
 * @param {string} entityType - Type of entity (e.g., "facción", "clase")
 */
export function setupDeleteConfirmations(selector, nameAttribute, entityType) {
  document.addEventListener('DOMContentLoaded', function() {
    setupDeleteConfirmation(selector, nameAttribute, entityType);
  });
}