/**
 * Initialize the hero attributes component
 */
const initHeroAttributes = () => {
  const attributesFieldset = document.getElementById('attributes-fieldset');
  if (!attributesFieldset) return;

  // Elements
  const totalAttributesElement = document.getElementById('total-attributes');
  const pointsAvailableElement = document.getElementById('points-available');
  const calculatedHealthElement = document.getElementById('calculated-health');
  const attributeInputs = attributesFieldset.querySelectorAll('.attribute-control__number');
  const minusButtons = attributesFieldset.querySelectorAll('.attribute-control__button--minus');
  const plusButtons = attributesFieldset.querySelectorAll('.attribute-control__button--plus');
  
  // Configuration
  const minValue = parseInt(attributeInputs[0].min);
  const maxValue = parseInt(attributeInputs[0].max);
  const maxTotal = parseInt(pointsAvailableElement.textContent) + calculateTotalAttributes();
  const minTotal = 0; // We'll always allow lowering the total, since there's a minimum per attribute
  
  // Health calculation multipliers (from hidden inputs or data attributes)
  const agilityMultiplier = parseInt(attributesFieldset.dataset.agilityMultiplier || '-1');
  const mentalMultiplier = parseInt(attributesFieldset.dataset.mentalMultiplier || '-1');
  const willMultiplier = parseInt(attributesFieldset.dataset.willMultiplier || '1');
  const strengthMultiplier = parseInt(attributesFieldset.dataset.strengthMultiplier || '-1');
  const armorMultiplier = parseInt(attributesFieldset.dataset.armorMultiplier || '1');
  const healthBase = parseInt(attributesFieldset.dataset.healthBase || '30');
  
  // Calculate total attributes
  function calculateTotalAttributes() {
    let total = 0;
    attributeInputs.forEach(input => {
      total += parseInt(input.value);
    });
    return total;
  }
  
  // Calculate health based on attributes
  function calculateHealth() {
    const agility = parseInt(document.getElementById('agility').value);
    const mental = parseInt(document.getElementById('mental').value);
    const will = parseInt(document.getElementById('will').value);
    const strength = parseInt(document.getElementById('strength').value);
    const armor = parseInt(document.getElementById('armor').value);
    
    let health = healthBase;
    health += agility * agilityMultiplier;
    health += mental * mentalMultiplier;
    health += will * willMultiplier;
    health += strength * strengthMultiplier;
    health += armor * armorMultiplier;
    
    return Math.max(1, health); // Ensure health is at least 1
  }
  
  // Update summary displays
  function updateSummary() {
    const totalAttributes = calculateTotalAttributes();
    const pointsAvailable = maxTotal - totalAttributes;
    const health = calculateHealth();
    
    totalAttributesElement.textContent = totalAttributes;
    pointsAvailableElement.textContent = pointsAvailable;
    calculatedHealthElement.textContent = health;
    
    // Update button states
    updateButtonStates();
  }
  
  // Update plus/minus button states based on constraints
  function updateButtonStates() {
    const totalAttributes = calculateTotalAttributes();
    const pointsAvailable = maxTotal - totalAttributes;
    
    // Update each attribute's buttons
    attributeInputs.forEach(input => {
      const attributeName = input.id;
      const value = parseInt(input.value);
      const minusButton = attributesFieldset.querySelector(`.attribute-control__button--minus[data-attribute="${attributeName}"]`);
      const plusButton = attributesFieldset.querySelector(`.attribute-control__button--plus[data-attribute="${attributeName}"]`);
      
      // Disable minus button if at minimum value
      if (value <= minValue) {
        minusButton.setAttribute('disabled', 'disabled');
      } else {
        minusButton.removeAttribute('disabled');
      }
      
      // Disable plus button if at maximum value or no points available
      if (value >= maxValue || pointsAvailable <= 0) {
        plusButton.setAttribute('disabled', 'disabled');
      } else {
        plusButton.removeAttribute('disabled');
      }
    });
  }
  
  // Handle attribute decrease
  function decreaseAttribute(attributeName) {
    const input = document.getElementById(attributeName);
    const currentValue = parseInt(input.value);
    
    if (currentValue > minValue) {
      input.value = currentValue - 1;
      updateSummary();
    }
  }
  
  // Handle attribute increase
  function increaseAttribute(attributeName) {
    const input = document.getElementById(attributeName);
    const currentValue = parseInt(input.value);
    const totalAttributes = calculateTotalAttributes();
    
    if (currentValue < maxValue && totalAttributes < maxTotal) {
      input.value = currentValue + 1;
      updateSummary();
    }
  }
  
  // Add event listeners to minus buttons
  minusButtons.forEach(button => {
    const attributeName = button.dataset.attribute;
    button.addEventListener('click', () => {
      decreaseAttribute(attributeName);
    });
  });
  
  // Add event listeners to plus buttons
  plusButtons.forEach(button => {
    const attributeName = button.dataset.attribute;
    button.addEventListener('click', () => {
      increaseAttribute(attributeName);
    });
  });
  
  // Add multipliers data attributes if they don't exist yet
  if (!attributesFieldset.dataset.agilityMultiplier) {
    // Use a hidden form with the values or fetch from an API
    fetch('/api/hero-attributes-configuration')
      .then(response => response.json())
      .then(data => {
        attributesFieldset.dataset.agilityMultiplier = data.agility_multiplier;
        attributesFieldset.dataset.mentalMultiplier = data.mental_multiplier;
        attributesFieldset.dataset.willMultiplier = data.will_multiplier;
        attributesFieldset.dataset.strengthMultiplier = data.strength_multiplier;
        attributesFieldset.dataset.armorMultiplier = data.armor_multiplier;
        attributesFieldset.dataset.healthBase = data.total_health_base;
        
        // Calculate initial health with new multipliers
        calculatedHealthElement.textContent = calculateHealth();
      })
      .catch(error => {
        console.error('Error fetching attribute configuration:', error);
      });
  }
  
  // Initialize summary
  updateSummary();
};

export default initHeroAttributes;