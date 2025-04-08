import { setupColorInputs } from '../../common/forms';

/**
 * Default handler for hero class pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
 */
export default function heroClassHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup hero class form page
 */
function setupFormPage() {
  setupColorInputs();
  
  // Setup modifiers validation if needed
  setupModifiersValidation();
}

/**
 * Setup validation for hero class modifiers
 */
function setupModifiersValidation() {
  const modifierInputs = [
    'agility_modifier', 
    'mental_modifier', 
    'will_modifier', 
    'strength_modifier', 
    'armor_modifier'
  ];

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
    const isValid = total <= 3;

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

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}