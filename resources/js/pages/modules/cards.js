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
  setupEquipmentTypeHandler();
}

/**
 * Setup card show page
 */
function setupShowPage() {
  // Add any specific show page functionality
}

/**
 * Setup equipment type and hands field relationship
 */
function setupEquipmentTypeHandler() {
  const equipmentTypeSelect = document.getElementById('equipment_type_id');
  const handsField = document.querySelector('.hands-field');
  
  if (!equipmentTypeSelect || !handsField) return;
  
  // Get initial state
  updateHandsVisibility();
  
  // Add event listener
  equipmentTypeSelect.addEventListener('change', updateHandsVisibility);
  
  function updateHandsVisibility() {
    const selectedValue = equipmentTypeSelect.value;
    const equipmentType = selectedValue ? getEquipmentType(selectedValue) : null;
    
    if (equipmentType && equipmentType.category === 'weapon') {
      handsField.parentElement.style.display = 'grid';
      handsField.required = true;
    } else {
      handsField.parentElement.style.display = 'none';
      handsField.required = false;
      handsField.value = '';
    }
  }
  
  function getEquipmentType(id) {
    // This data should be populated via a data attribute on the form
    const equipmentTypes = JSON.parse(document.getElementById('card-form').dataset.equipmentTypes || '[]');
    return equipmentTypes.find(type => type.id === parseInt(id));
  }
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