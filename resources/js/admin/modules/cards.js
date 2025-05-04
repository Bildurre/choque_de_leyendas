import { initImageUploaders } from '../../form/image-uploader.js';

/**
 * Default handler for card pages
 * @param {string} action - Current CRUD action
 */
export default function cardHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
    case 'show':
      // Show page specific functionality if needed
      break;
  }
}

/**
 * Setup card form page
 */
function setupFormPage() {
  // Initialize image uploaders first to ensure they're ready
  initImageUploaders();
  
  // Setup form field handlers
  setupCardTypeHandler();
  setupEquipmentTypeHandler();
  setupAttackFieldsHandler();
  setupHeroAbilityHandler();
}

/**
 * Setup card type handler
 * Controls visibility of equipment type field based on card type selection
 */
function setupCardTypeHandler() {
  const cardTypeSelect = document.getElementById('card_type_id');
  const equipmentTypeSelect = document.getElementById('equipment_type_id');
  const form = document.getElementById('card-form');
  
  if (!cardTypeSelect || !equipmentTypeSelect || !form) {
    return;
  }

  const equipmentTypeGroup = equipmentTypeSelect.closest('.form-group');
  
  // Get equipment card types from data attribute
  let equipmentTypes = [];
  try {
    equipmentTypes = JSON.parse(form.getAttribute('data-equipment-types') || '[]');
  } catch (e) {
    if (process.env.NODE_ENV !== 'production') {
      console.error('Error parsing equipment types:', e);
    }
  }
  
  // Add event listener
  cardTypeSelect.addEventListener('change', updateEquipmentTypeVisibility);
  
  function updateEquipmentTypeVisibility() {
    const selectedValue = cardTypeSelect.value;
    
    // Check if selected card type is equipment
    if (selectedValue && equipmentTypes.includes(parseInt(selectedValue))) {
      equipmentTypeGroup.style.display = 'grid';
      
      // Also update the hands field visibility since it depends on equipment type
      updateHandsVisibility();
    } else {
      equipmentTypeGroup.style.display = 'none';
      
      // Hide hands field as well
      const handsFieldGroup = document.querySelector('.hands-field')?.closest('.form-group');
      if (handsFieldGroup) {
        handsFieldGroup.style.display = 'none';
      }
      
      // Reset equipment type select
      equipmentTypeSelect.value = '';
    }
  }
  
  // Update visibility initially
  updateEquipmentTypeVisibility();
}

/**
 * Setup equipment type handler
 * Controls visibility of hands field based on equipment type selection
 */
function setupEquipmentTypeHandler() {
  const equipmentTypeSelect = document.getElementById('equipment_type_id');
  const handsField = document.querySelector('.hands-field');
  const form = document.getElementById('card-form');
  
  if (!equipmentTypeSelect || !handsField || !form) {
    return;
  }

  const handsFieldGroup = handsField.closest('.form-group');
  
  // Get weapon types from data attribute
  let weaponTypes = [];
  try {
    weaponTypes = JSON.parse(form.getAttribute('data-weapon-types') || '[]');
  } catch (e) {
    if (process.env.NODE_ENV !== 'production') {
      console.error('Error parsing weapon types:', e);
    }
  }
  
  // Add event listener
  equipmentTypeSelect.addEventListener('change', updateHandsVisibility);
  
  function updateHandsVisibility() {
    const selectedValue = equipmentTypeSelect.value;
    
    // Check if the equipment type field is visible
    const equipmentTypeGroup = equipmentTypeSelect.closest('.form-group');
    if (equipmentTypeGroup.style.display === 'none') {
      handsFieldGroup.style.display = 'none';
      return;
    }
    
    // Check if selected equipment type is a weapon
    if (selectedValue && weaponTypes.includes(parseInt(selectedValue))) {
      handsFieldGroup.style.display = 'grid';
      handsField.required = true;
    } else {
      handsFieldGroup.style.display = 'none';
      handsField.required = false;
      handsField.value = '';
    }
  }
  
  // Update visibility initially
  updateHandsVisibility();
}

/**
 * Setup attack fields handler
 * Controls visibility of attack fields based on is_attack checkbox
 */
function setupAttackFieldsHandler() {
  const isAttackCheckbox = document.querySelector('input[name="is_attack"]');
  
  if (!isAttackCheckbox) {
    return;
  }
  
  const attackFields = [
    document.getElementById('attack_range_id'),
    document.getElementById('attack_subtype_id'),
    document.querySelector('input[name="area"]')
  ];
  
  if (attackFields.some(field => !field)) {
    return;
  }
  
  // Add event listener
  isAttackCheckbox.addEventListener('change', updateAttackFieldsVisibility);
  
  function updateAttackFieldsVisibility() {
    const isChecked = isAttackCheckbox.checked;
    
    attackFields.forEach(field => {
      if (field) {
        const fieldGroup = field.closest('.form-group');
        if (fieldGroup) {
          fieldGroup.style.display = isChecked ? 'grid' : 'none';
        }
      }
    });
  }
  
  // Update visibility initially
  updateAttackFieldsVisibility();
}

/**
 * Setup hero ability handler
 * Controls visibility of hero ability field based on has_hero_ability checkbox
 */
function setupHeroAbilityHandler() {
  const hasHeroAbilityCheckbox = document.querySelector('input[name="has_hero_ability"]');
  const heroAbilityField = document.getElementById('hero_ability_id');
  
  if (!hasHeroAbilityCheckbox || !heroAbilityField) {
    return;
  }
  
  const heroAbilityGroup = heroAbilityField.closest('.form-group');
  
  // Add event listener
  hasHeroAbilityCheckbox.addEventListener('change', updateHeroAbilityVisibility);
  
  function updateHeroAbilityVisibility() {
    const isChecked = hasHeroAbilityCheckbox.checked;
    
    if (heroAbilityGroup) {
      heroAbilityGroup.style.display = isChecked ? 'grid' : 'none';
      
      // Reset hero ability select if unchecked
      if (!isChecked) {
        heroAbilityField.value = '';
      }
    }
  }
  
  // Update visibility initially
  updateHeroAbilityVisibility();
}

export const create = setupFormPage;
export const edit = setupFormPage;