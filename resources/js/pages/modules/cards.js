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
      setupShowPage();
      break;
  }
}

/**
 * Setup card form page
 */
function setupFormPage() {
  setupCardTypeHandler();
  setupEquipmentTypeHandler();
  setupAttackFieldsHandler();
  setupHeroAbilityHandler();
}

/**
 * Setup card show page
 */
function setupShowPage() {
  // Add any specific show page functionality
}

/**
 * Setup card type handler
 * Controls visibility of equipment type field based on card type selection
 */
function setupCardTypeHandler() {
  const cardTypeSelect = document.getElementById('card_type_id');
  const equipmentTypeGroup = document.getElementById('equipment_type_id')?.closest('.form-group');
  const form = document.getElementById('card-form');
  
  if (!cardTypeSelect || !equipmentTypeGroup || !form) {
    console.error('Missing required elements for card type handler');
    return;
  }
  
  // Get equipment card types from data attribute
  let equipmentTypes = [];
  try {
    equipmentTypes = JSON.parse(form.getAttribute('data-equipment-types') || '[]');
  } catch (e) {
    console.error('Error parsing equipment types:', e);
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
      const handsFieldGroup = document.getElementById('hands_field')?.closest('.form-group');
      if (handsFieldGroup) {
        handsFieldGroup.style.display = 'none';
      }
      
      // Reset equipment type select
      const equipmentTypeSelect = document.getElementById('equipment_type_id');
      if (equipmentTypeSelect) {
        equipmentTypeSelect.value = '';
      }
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
  const handsFieldGroup = document.getElementById('hands_field')?.closest('.form-group');
  const form = document.getElementById('card-form');
  
  if (!equipmentTypeSelect || !handsFieldGroup || !form) {
    console.error('Missing required elements for equipment handler');
    return;
  }
  
  // Get weapon types from data attribute
  let weaponTypes = [];
  try {
    weaponTypes = JSON.parse(form.getAttribute('data-weapon-types') || '[]');
  } catch (e) {
    console.error('Error parsing weapon types:', e);
  }
  
  // Add event listener
  equipmentTypeSelect.addEventListener('change', updateHandsVisibility);
  
  function updateHandsVisibility() {
    const selectedValue = equipmentTypeSelect.value;
    const handsField = document.getElementById('hands_field');
    
    // Check if the equipment type field is visible
    const equipmentTypeGroup = equipmentTypeSelect.closest('.form-group');
    if (equipmentTypeGroup.style.display === 'none') {
      handsFieldGroup.style.display = 'none';
      return;
    }
    
    // Check if selected equipment type is a weapon
    if (selectedValue && weaponTypes.includes(parseInt(selectedValue))) {
      handsFieldGroup.style.display = 'grid';
      if (handsField) handsField.required = true;
    } else {
      handsFieldGroup.style.display = 'none';
      if (handsField) {
        handsField.required = false;
        handsField.value = '';
      }
    }
  }
}

/**
 * Setup attack fields handler
 * Controls visibility of attack fields based on is_attack checkbox
 */
function setupAttackFieldsHandler() {
  const isAttackCheckbox = document.getElementById('is_attack_checkbox');
  const attackFields = [
    'attack_range_id', 
    'attack_subtype_id', 
    'blast_checkbox'
  ];
  
  if (!isAttackCheckbox) {
    console.error('Missing is_attack checkbox');
    return;
  }
  
  // Add event listener
  isAttackCheckbox.addEventListener('change', updateAttackFieldsVisibility);
  
  function updateAttackFieldsVisibility() {
    const isChecked = isAttackCheckbox.checked;
    
    attackFields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
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
  const hasHeroAbilityCheckbox = document.getElementById('has_hero_ability_checkbox');
  const heroAbilityField = document.getElementById('hero_ability_id');
  
  if (!hasHeroAbilityCheckbox || !heroAbilityField) {
    console.error('Missing hero ability checkbox or field');
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

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}

export function show() {
  setupShowPage();
}