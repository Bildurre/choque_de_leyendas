// resources/js/pages/modules/attack-subtypes.js
import { setupColorInputs } from '../../common/forms';

/**
 * Default handler for attack subtype pages
 * @param {string} action - Current CRUD action
 */
export default function attackSubtypeHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup attack subtype form page
 */
function setupFormPage() {
  setupColorInputs();
  setupTypeColorSync();
}

/**
 * Setup synchronization between type selection and color
 */
function setupTypeColorSync() {
  const typeSelect = document.getElementById('attack_type_id');
  const colorInput = document.getElementById('color');
  const colorText = document.getElementById('color_text');
  
  if (typeSelect && colorInput && colorText) {
    // Store original colors to allow reverting
    let originalColor = colorInput.value || '';
    let colorChanged = colorInput.value !== '';
    
    // When type changes, update color if not manually set
    typeSelect.addEventListener('change', async function() {
      if (!colorChanged && this.value) {
        try {
          // Fetch the parent type info to get its color
          const response = await fetch(`/admin/attack-types/${this.value}`);
          if (response.ok) {
            const data = await response.json();
            if (data.color) {
              colorInput.value = data.color;
              colorText.value = data.color;
            }
          }
        } catch (error) {
          console.error('Error fetching type color:', error);
        }
      }
    });
    
    // Track if user manually changes the color
    colorInput.addEventListener('input', function() {
      colorChanged = true;
    });
  }
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}