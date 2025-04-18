/**
 * Default handler for hero attributes pages
 * @param {string} action - Current CRUD action
 */
export default function heroAttributesHandler(action) {
  if (action === 'edit') {
    setupAttributesForm();
  }
}

/**
 * Setup hero attributes form page
 */
function setupAttributesForm() {
  setupFormulaPreview();
}

/**
 * Sets up the preview of the health calculation formula
 * Updates the formula preview when multiplicators change
 */
function setupFormulaPreview() {
  // Get all multiplier inputs
  const multiplierInputs = [
    document.querySelector('input[name="agility_multiplier"]'),
    document.querySelector('input[name="mental_multiplier"]'),
    document.querySelector('input[name="will_multiplier"]'),
    document.querySelector('input[name="strength_multiplier"]'),
    document.querySelector('input[name="armor_multiplier"]')
  ];
  
  const baseHealthInput = document.querySelector('input[name="total_health_base"]');
  const formulaPreview = document.querySelector('.formula-preview p');
  
  if (!multiplierInputs.every(input => input) || !baseHealthInput || !formulaPreview) {
    console.log("Error: No se pudieron encontrar todos los elementos necesarios para la vista previa de la fórmula");
    return;
  }
  
  function updateFormulaPreview() {
    const baseHealth = parseInt(baseHealthInput.value) || 0;
    
    // Get multiplier values
    const agilityMultiplier = parseInt(multiplierInputs[0].value) || 0;
    const mentalMultiplier = parseInt(multiplierInputs[1].value) || 0;
    const willMultiplier = parseInt(multiplierInputs[2].value) || 0;
    const strengthMultiplier = parseInt(multiplierInputs[3].value) || 0;
    const armorMultiplier = parseInt(multiplierInputs[4].value) || 0;
    
    // Create formula string
    const formula = `Salud = ${baseHealth} ` +
      `${agilityMultiplier >= 0 ? '+' : ''}${agilityMultiplier} × Agilidad ` +
      `${mentalMultiplier >= 0 ? '+' : ''}${mentalMultiplier} × Mental ` +
      `${willMultiplier >= 0 ? '+' : ''}${willMultiplier} × Voluntad ` +
      `${strengthMultiplier >= 0 ? '+' : ''}${strengthMultiplier} × Fuerza ` +
      `${armorMultiplier >= 0 ? '+' : ''}${armorMultiplier} × Armadura`;
    
    formulaPreview.textContent = formula;
  }
  
  // Add event listeners to all inputs
  multiplierInputs.forEach(input => {
    if (input) input.addEventListener('input', updateFormulaPreview);
  });
  
  if (baseHealthInput) baseHealthInput.addEventListener('input', updateFormulaPreview);
  
  // Initial update
  updateFormulaPreview();
}

export function edit() {
  setupAttributesForm();
}