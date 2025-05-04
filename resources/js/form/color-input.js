/**
 * Setup color input synchronization
 * Synchronizes color inputs with their corresponding text field
 */
export function setupColorInputs() {
  const colorInputs = document.querySelectorAll('input[type="color"]');
  
  colorInputs.forEach(colorInput => {
    const colorId = colorInput.id;
    const textInput = document.getElementById(`${colorId}_text`);
    
    if (textInput) {
      // Update text input when color changes
      colorInput.addEventListener('input', function() {
        textInput.value = this.value;
      });
      
      // Initialize text input with color value
      if (colorInput.value && !textInput.value) {
        textInput.value = colorInput.value;
      }
    }
  });
}

/**
 * Initialize color inputs on a page
 */
export function initColorInputs() {
  document.addEventListener('DOMContentLoaded', function() {
    setupColorInputs();
  });
  
  // Re-initialize on dynamic content load
  document.addEventListener('contentLoaded', function() {
    setupColorInputs();
  });
}