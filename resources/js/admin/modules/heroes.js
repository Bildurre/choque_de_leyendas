import { initHeroAbilitiesSelector } from '../hero-abilities-selector';

/**
 * Default handler for hero pages
 * @param {string} action - Current CRUD action
 */
export default function heroHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
    case 'show':
      setupShowPage();
      break;
  }
}

/**
 * Setup hero form page
 */
function setupFormPage() {
  setupAttributeCalculations();
  initHeroAbilitiesSelector();
}

/**
 * Setup hero show page
 */
function setupShowPage() {
  // Add any specific functionality for show page if needed
}

/**
 * Setup attribute calculations
 */
function setupAttributeCalculations() {
  const attributeInputs = document.querySelectorAll('[data-attribute="true"]');
  const totalAttributesElement = document.getElementById('total-attributes-value');
  const calculatedHealthElement = document.getElementById('calculated-health');
  
  if (!attributeInputs.length || !totalAttributesElement || !calculatedHealthElement) return;
  
  // Get configuration values from data attributes
  const form = document.getElementById('hero-form');
  const config = {
    minValue: parseInt(form.dataset.minValue) || 1,
    maxValue: parseInt(form.dataset.maxValue) || 5,
    minTotal: parseInt(form.dataset.minTotal) || 5,
    maxTotal: parseInt(form.dataset.maxTotal) || 25,
    baseHealth: parseInt(form.dataset.baseHealth) || 25,
    multipliers: {
      agility: parseInt(form.dataset.agilityMultiplier) || -1,
      mental: parseInt(form.dataset.mentalMultiplier) || -1,
      will: parseInt(form.dataset.willMultiplier) || 1,
      strength: parseInt(form.dataset.strengthMultiplier) || -1,
      armor: parseInt(form.dataset.armorMultiplier) || 1
    }
  };
  
  // Calculate total and health when inputs change
  function updateTotals() {
    let total = 0;
    let healthValue = config.baseHealth;
    
    attributeInputs.forEach(input => {
      const value = parseInt(input.value) || 0;
      total += value;
      
      // Add to health based on attribute type and multiplier
      const attributeName = input.name;
      if (config.multipliers[attributeName]) {
        healthValue += value * config.multipliers[attributeName];
      }
    });
    
    // Update display
    totalAttributesElement.textContent = total;
    calculatedHealthElement.textContent = healthValue;
    
    // Visual feedback for total
    if (total < config.minTotal || total > config.maxTotal) {
      totalAttributesElement.classList.add('invalid-total');
      
      // Show or update error message
      let errorMessage = document.getElementById('attributes-error-message');
      if (!errorMessage) {
        errorMessage = document.createElement('div');
        errorMessage.id = 'attributes-error-message';
        errorMessage.className = 'attributes-error';
        totalAttributesElement.parentNode.parentNode.parentNode.appendChild(errorMessage);
      }
      errorMessage.textContent = `La suma total de atributos debe estar entre ${config.minTotal} y ${config.maxTotal}.`;
    } else {
      totalAttributesElement.classList.remove('invalid-total');
      
      // Remove error message if exists
      const errorMessage = document.getElementById('attributes-error-message');
      if (errorMessage) {
        errorMessage.remove();
      }
    }
  }
  
  // Initialize calculations
  updateTotals();
  
  // Add event listeners to update calculations when inputs change
  attributeInputs.forEach(input => {
    input.addEventListener('input', updateTotals);
  });
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}

export function show() {
  setupShowPage();
}