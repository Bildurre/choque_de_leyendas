/**
 * Hero attributes component
 * 
 * Manages hero attribute controls and calculations
 */
export default function initHeroAttributes() {
  const attributesFieldset = document.getElementById('attributes-fieldset');
  
  if (!attributesFieldset) return;
  
  // Get attribute inputs
  const agilityInput = document.getElementById('agility');
  const mentalInput = document.getElementById('mental');
  const willInput = document.getElementById('will');
  const strengthInput = document.getElementById('strength');
  const armorInput = document.getElementById('armor');
  
  // Get summary elements
  const totalAttributesElement = document.getElementById('total-attributes');
  const calculatedHealthElement = document.getElementById('calculated-health');
  const pointsAvailableElement = document.getElementById('points-available');
  
  // Instead of fetching, extract config from data attributes we'll add to the fieldset
  const config = {
    minAttributeValue: parseInt(attributesFieldset.dataset.minValue || 1, 10),
    maxAttributeValue: parseInt(attributesFieldset.dataset.maxValue || 5, 10),
    minTotalAttributes: parseInt(attributesFieldset.dataset.minTotal || 12, 10),
    maxTotalAttributes: parseInt(attributesFieldset.dataset.maxTotal || 18, 10),
    baseHealth: parseInt(attributesFieldset.dataset.baseHealth || 30, 10),
    multipliers: {
      agility: parseInt(attributesFieldset.dataset.agilityMult || -1, 10),
      mental: parseInt(attributesFieldset.dataset.mentalMult || -1, 10),
      will: parseInt(attributesFieldset.dataset.willMult || 1, 10),
      strength: parseInt(attributesFieldset.dataset.strengthMult || -1, 10),
      armor: parseInt(attributesFieldset.dataset.armorMult || 1, 10)
    }
  };
  
  // Get all attribute plus and minus buttons
  const plusButtons = attributesFieldset.querySelectorAll('.attribute-control__button--plus');
  const minusButtons = attributesFieldset.querySelectorAll('.attribute-control__button--minus');
  
  // Add event listeners to buttons
  plusButtons.forEach(button => {
    button.addEventListener('click', () => {
      const attribute = button.dataset.attribute;
      incrementAttribute(attribute);
    });
  });
  
  minusButtons.forEach(button => {
    button.addEventListener('click', () => {
      const attribute = button.dataset.attribute;
      decrementAttribute(attribute);
    });
  });
  
  // Initialize the form
  updateSummary();
  
  /**
   * Increment attribute value
   * 
   * @param {string} attribute 
   */
  function incrementAttribute(attribute) {
    const input = document.getElementById(attribute);
    if (!input) return;
    
    const currentValue = parseInt(input.value, 10);
    const maxValue = config.maxAttributeValue;
    
    // Check if we have available points
    const currentTotal = calculateTotalAttributes();
    if (currentTotal >= config.maxTotalAttributes) {
      // No more points available
      return;
    }
    
    // Check if the attribute is already at max
    if (currentValue >= maxValue) {
      return;
    }
    
    // Increment the attribute
    input.value = currentValue + 1;
    
    // Update summary
    updateSummary();
  }
  
  /**
   * Decrement attribute value
   * 
   * @param {string} attribute 
   */
  function decrementAttribute(attribute) {
    const input = document.getElementById(attribute);
    if (!input) return;
    
    const currentValue = parseInt(input.value, 10);
    const minValue = config.minAttributeValue;
    
    // Check if the attribute is already at min
    if (currentValue <= minValue) {
      return;
    }
    
    // Check if total would go below minimum
    const currentTotal = calculateTotalAttributes();
    if (currentTotal <= config.minTotalAttributes) {
      return;
    }
    
    // Decrement the attribute
    input.value = currentValue - 1;
    
    // Update summary
    updateSummary();
  }
  
  /**
   * Calculate total attributes
   * 
   * @returns {number}
   */
  function calculateTotalAttributes() {
    return parseInt(agilityInput.value, 10) +
      parseInt(mentalInput.value, 10) +
      parseInt(willInput.value, 10) +
      parseInt(strengthInput.value, 10) +
      parseInt(armorInput.value, 10);
  }
  
  /**
   * Calculate health based on attributes and configuration
   * 
   * @returns {number}
   */
  function calculateHealth() {
    const agility = parseInt(agilityInput.value, 10);
    const mental = parseInt(mentalInput.value, 10);
    const will = parseInt(willInput.value, 10);
    const strength = parseInt(strengthInput.value, 10);
    const armor = parseInt(armorInput.value, 10);
    
    let health = config.baseHealth;
    
    health += agility * config.multipliers.agility;
    health += mental * config.multipliers.mental;
    health += will * config.multipliers.will;
    health += strength * config.multipliers.strength;
    health += armor * config.multipliers.armor;
    
    // Ensure health is at least 1
    return Math.max(1, health);
  }
  
  /**
   * Update summary information
   */
  function updateSummary() {
    const totalAttributes = calculateTotalAttributes();
    const health = calculateHealth();
    const pointsAvailable = config.maxTotalAttributes - totalAttributes;
    
    totalAttributesElement.textContent = totalAttributes;
    calculatedHealthElement.textContent = health;
    pointsAvailableElement.textContent = pointsAvailable;
    
    // Update button states
    updateButtonStates();
  }
  
  /**
   * Update button states based on constraints
   */
  function updateButtonStates() {
    const totalAttributes = calculateTotalAttributes();
    const noMorePoints = totalAttributes >= config.maxTotalAttributes;
    const noLessPoints = totalAttributes <= config.minTotalAttributes;
    
    // Update plus buttons
    plusButtons.forEach(button => {
      const attribute = button.dataset.attribute;
      const input = document.getElementById(attribute);
      const currentValue = parseInt(input.value, 10);
      
      button.disabled = noMorePoints || currentValue >= config.maxAttributeValue;
    });
    
    // Update minus buttons
    minusButtons.forEach(button => {
      const attribute = button.dataset.attribute;
      const input = document.getElementById(attribute);
      const currentValue = parseInt(input.value, 10);
      
      button.disabled = noLessPoints || currentValue <= config.minAttributeValue;
    });
  }
}