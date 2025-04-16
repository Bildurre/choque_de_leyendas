/**
 * Setup validation for attribute modifiers
 * 
 * @param {Array} modifierInputs - Array of input field IDs
 * @param {Number} maxTotal - Maximum total of absolutes values
 */
export function setupModifiersValidation(modifierInputs, maxTotal) {
  const submitButton = document.querySelector('button[type="submit"]');
  if (!submitButton) return;

  function calculateTotalModifiers() {
    return modifierInputs.reduce((total, inputId) => {
      const input = document.getElementById(inputId);
      return total + Math.abs(parseInt(input?.value || 0));
    }, 0);
  }

  function updateValidation() {
    const total = calculateTotalModifiers();
    const isValid = total <= maxTotal;

    submitButton.disabled = !isValid;

    // Visual feedback
    modifierInputs.forEach(inputId => {
      const input = document.getElementById(inputId);
      if (input) {
        input.classList.toggle('is-invalid', !isValid);
      }
    });
    
    // Display the total
    const totalDisplay = document.getElementById('modifiers-total');
    if (totalDisplay) {
      totalDisplay.textContent = total;
      totalDisplay.classList.toggle('text-danger', !isValid);
    }
  }

  modifierInputs.forEach(inputId => {
    const input = document.getElementById(inputId);
    if (input) {
      input.addEventListener('change', updateValidation);
      input.addEventListener('input', updateValidation);
    }
  });

  // Initial validation
  updateValidation();
}