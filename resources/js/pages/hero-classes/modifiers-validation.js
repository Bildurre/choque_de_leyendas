// resources/js/pages/hero-classes/modifiers-validation.js
/**
 * Handle hero class modifiers validation
 */
export function setupModifiersValidation() {
  document.addEventListener('DOMContentLoaded', function() {
    const modifierInputs = [
      'agility_modifier', 
      'mental_modifier', 
      'will_modifier', 
      'strength_modifier', 
      'armor_modifier'
    ];

    const submitButton = document.querySelector('button[type="submit"]');

    function calculateTotalModifiers() {
      return modifierInputs.reduce((total, inputId) => {
        const input = document.getElementById(inputId);
        return total + Math.abs(parseInt(input.value || 0));
      }, 0);
    }

    function updateValidation() {
      const total = calculateTotalModifiers();
      const isValid = total <= 3;

      submitButton.disabled = !isValid;

      // Visual feedback
      modifierInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.classList.toggle('is-invalid', !isValid);
      });
      
      // Display the total somewhere
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
  });
}