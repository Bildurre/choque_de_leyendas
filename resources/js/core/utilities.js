/**
 * Utility functions for the application
 */

/**
 * Debounce function to limit execution frequency
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} - Debounced function
 */
export function debounce(func, wait) {
  let timeout;
  return function() {
    const context = this;
    const args = arguments;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
}

/**
 * Simple function to format a date
 * @param {Date|string} date - Date to format
 * @param {string} format - Format to use (default: 'dd/mm/yyyy')
 * @returns {string} - Formatted date
 */
export function formatDate(date, format = 'dd/mm/yyyy') {
  const d = new Date(date);
  
  if (isNaN(d)) {
    return '';
  }
  
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  
  return format
    .replace('dd', day)
    .replace('mm', month)
    .replace('yyyy', year);
}

/**
 * Validate a form field
 * @param {HTMLElement} field - Form field to validate
 * @returns {boolean} - Whether the field is valid
 */
export function validateField(field) {
  if (!field) return true;
  
  // Check for required fields
  if (field.required && !field.value.trim()) {
    return false;
  }
  
  // Check for min/max values for number inputs
  if (field.type === 'number') {
    const value = parseFloat(field.value);
    
    if (field.min && value < parseFloat(field.min)) {
      return false;
    }
    
    if (field.max && value > parseFloat(field.max)) {
      return false;
    }
  }
  
  return true;
}