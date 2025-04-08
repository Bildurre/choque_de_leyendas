// resources/js/pages/attack-subtypes/edit.js
import { initFormPage } from '../../common/page-initializers';

/**
 * Initialize attack subtype edit page
 */
export function initEditPage() {
  // Initialize form with color input functionality
  initFormPage({
    hasColorInputs: true
  });
  
  // Setup type selection to auto-set color based on parent type
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

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initEditPage);