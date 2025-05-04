/**
 * Card Preview functionality
 * Handles the live preview of card data in the card editor
 */

/**
 * Initialize a card preview
 * @param {string} formSelector - Selector for the card form
 * @param {string} previewSelector - Selector for the preview container
 */
export function initCardPreview(formSelector = '#card-form', previewSelector = '.preview-card') {
  const form = document.querySelector(formSelector);
  const preview = document.querySelector(previewSelector);
  
  if (!form || !preview) return;
  
  // Initial preview update
  updatePreview(form, preview);
  
  // Update preview on form changes
  const formInputs = form.querySelectorAll('input, select, textarea');
  formInputs.forEach(input => {
    input.addEventListener('input', function() {
      updatePreview(form, preview);
    });
    
    // For select elements, also listen for change event
    if (input.tagName === 'SELECT') {
      input.addEventListener('change', function() {
        updatePreview(form, preview);
      });
    }
  });
}

/**
 * Update the card preview with form data
 * @param {HTMLElement} form - The card form element
 * @param {HTMLElement} preview - The preview container element
 */
function updatePreview(form, preview) {
  // Get form values
  const name = getInputValue(form, 'name');
  const cost = getInputValue(form, 'cost');
  const cardTypeId = getInputValue(form, 'card_type_id');
  const equipmentTypeId = getInputValue(form, 'equipment_type_id');
  const hands = getInputValue(form, 'hands');
  const isAttack = form.querySelector('input[name="is_attack"]')?.checked;
  const attackRangeId = getInputValue(form, 'attack_range_id');
  const attackSubtypeId = getInputValue(form, 'attack_subtype_id');
  const area = form.querySelector('input[name="area"]')?.checked;
  const effect = getWysiwygContent(form, 'effect');
  const restriction = getWysiwygContent(form, 'restriction');
  
  // Update preview elements
  if (preview.querySelector('.preview-card__text--name')) {
    preview.querySelector('.preview-card__text--name').textContent = name || 'Nombre de la Carta';
  }
  
  // Update card type in header
  let headerText = '';
  if (cardTypeId) {
    const cardTypeElem = form.querySelector(`#card_type_id option[value="${cardTypeId}"]`);
    headerText = cardTypeElem ? cardTypeElem.textContent : '';
    
    if (equipmentTypeId) {
      const equipmentTypeElem = form.querySelector(`#equipment_type_id option[value="${equipmentTypeId}"]`);
      if (equipmentTypeElem) {
        headerText += ` - ${equipmentTypeElem.textContent}`;
        
        if (hands && hands > 0) {
          headerText += ` - ${hands} ${hands == 1 ? 'Mano' : 'Manos'}`;
        }
      }
    }
  }
  
  // Update header text
  if (preview.querySelector('.preview-card__header .preview-card__text')) {
    preview.querySelector('.preview-card__header .preview-card__text').textContent = headerText;
  }
  
  // Update attack info
  if (isAttack) {
    let attackInfo = '';
    
    if (attackRangeId) {
      const rangeElem = form.querySelector(`#attack_range_id option[value="${attackRangeId}"]`);
      attackInfo = rangeElem ? rangeElem.textContent : '';
    }
    
    if (attackSubtypeId) {
      const subtypeElem = form.querySelector(`#attack_subtype_id option[value="${attackSubtypeId}"]`);
      if (subtypeElem) {
        attackInfo += attackInfo ? ' - ' : '';
        attackInfo += subtypeElem.textContent;
      }
    }
    
    if (area) {
      attackInfo += attackInfo ? ' - Área' : 'Área';
    }
    
    // Update attack info text
    const headerRightTextElem = preview.querySelector('.preview-card__header .preview-card__text:last-child');
    if (headerRightTextElem) {
      headerRightTextElem.textContent = attackInfo;
    }
  }
  
  // Update card content
  const contentElem = preview.querySelector('.preview-card__box--content');
  if (contentElem) {
    let content = '';
    
    if (restriction) {
      content += `<div class="preview-card-restriction">${restriction}</div>`;
    }
    
    if (effect) {
      content += `<div class="preview-card-effect">${effect}</div>`;
    }
    
    contentElem.innerHTML = content;
  }
  
  // Update cost display
  updateCardCost(form, preview, cost);
}

/**
 * Update the card cost display
 * @param {HTMLElement} form - The card form
 * @param {HTMLElement} preview - The preview element
 * @param {string} cost - Cost string (e.g., "RGG")
 */
function updateCardCost(form, preview, cost) {
  const costDisplay = preview.querySelector('.preview-card-cost');
  if (!costDisplay) return;
  
  // Clear current cost display
  costDisplay.innerHTML = '';
  
  if (!cost) return;
  
  // Create dice for each cost letter
  [...cost.toUpperCase()].forEach(costChar => {
    let diceColor = '';
    
    switch (costChar) {
      case 'R': diceColor = 'red'; break;
      case 'G': diceColor = 'green'; break;
      case 'B': diceColor = 'blue'; break;
    }
    
    if (diceColor) {
      const diceElem = document.createElement('div');
      diceElem.className = `game-dice game-dice--${diceColor} game-dice--sm`;
      costDisplay.appendChild(diceElem);
    }
  });
}

/**
 * Get input value from form
 * @param {HTMLElement} form - The form element
 * @param {string} name - Input name
 * @returns {string} - Input value
 */
function getInputValue(form, name) {
  // Check for translatable fields
  const translatedInputs = form.querySelectorAll(`[name^="${name}["]`);
  if (translatedInputs.length > 0) {
    // Try to get current locale value
    const currentLocale = document.documentElement.lang || 'es';
    const localizedInput = form.querySelector(`[name="${name}[${currentLocale}]"]`);
    
    if (localizedInput && localizedInput.value) {
      return localizedInput.value;
    }
    
    // If no value for current locale, use first non-empty value
    for (const input of translatedInputs) {
      if (input.value) {
        return input.value;
      }
    }
    
    return '';
  }
  
  // Regular inputs
  return form.querySelector(`[name="${name}"]`)?.value || '';
}

/**
 * Get wysiwyg editor content
 * @param {HTMLElement} form - The form element 
 * @param {string} name - Editor name
 * @returns {string} - Editor content
 */
function getWysiwygContent(form, name) {
  // Check if TinyMCE is available
  if (window.tinymce) {
    // Check for translatable fields
    const translatedEditors = form.querySelectorAll(`[name^="${name}["]`);
    if (translatedEditors.length > 0) {
      // Try to get current locale editor
      const currentLocale = document.documentElement.lang || 'es';
      const localizedEditorName = `${name}[${currentLocale}]`;
      const editor = tinymce.get(localizedEditorName);
      
      if (editor) {
        return editor.getContent();
      }
    }
    
    // Regular editor
    const editor = tinymce.get(name);
    if (editor) {
      return editor.getContent();
    }
  }
  
  // Fallback to textarea content
  return getInputValue(form, name);
}

/**
 * Initialize the card preview when the DOM is loaded
 */
export function setupCardPreview() {
  document.addEventListener('DOMContentLoaded', function() {
    initCardPreview();
  });
}

export default {
  initCardPreview,
  setupCardPreview
};