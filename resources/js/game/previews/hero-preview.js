/**
 * Hero Preview functionality
 * Handles the live preview of hero data in the hero editor
 */

/**
 * Initialize a hero preview
 * @param {string} formSelector - Selector for the hero form
 * @param {string} previewSelector - Selector for the preview container
 */
export function initHeroPreview(formSelector = '#hero-form', previewSelector = '.preview-hero') {
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
 * Update the hero preview with form data
 * @param {HTMLElement} form - The hero form element
 * @param {HTMLElement} preview - The preview container element
 */
function updatePreview(form, preview) {
  // Get form values
  const name = getInputValue(form, 'name');
  const raceId = getInputValue(form, 'hero_race_id');
  const classId = getInputValue(form, 'hero_class_id');
  
  // Get attribute values
  const agility = parseInt(getInputValue(form, 'agility')) || 1;
  const mental = parseInt(getInputValue(form, 'mental')) || 1;
  const will = parseInt(getInputValue(form, 'will')) || 1;
  const strength = parseInt(getInputValue(form, 'strength')) || 1;
  const armor = parseInt(getInputValue(form, 'armor')) || 1;
  
  // Calculate health
  const healthBase = parseInt(form.dataset.baseHealth) || 25;
  const agilityMultiplier = parseInt(form.dataset.agilityMultiplier) || 0;
  const mentalMultiplier = parseInt(form.dataset.mentalMultiplier) || 0;
  const willMultiplier = parseInt(form.dataset.willMultiplier) || 0;
  const strengthMultiplier = parseInt(form.dataset.strengthMultiplier) || 0;
  const armorMultiplier = parseInt(form.dataset.armorMultiplier) || 0;
  
  const calculatedHealth = healthBase + 
    (agility * agilityMultiplier) +
    (mental * mentalMultiplier) + 
    (will * willMultiplier) + 
    (strength * strengthMultiplier) + 
    (armor * armorMultiplier);
  
  // Update preview elements
  if (preview.querySelector('.preview-card__text--name')) {
    preview.querySelector('.preview-card__text--name').textContent = name || 'Nombre del Héroe';
  }
  
  // Update race and class in header
  let headerText = '';
  if (raceId) {
    const raceElem = form.querySelector(`#hero_race_id option[value="${raceId}"]`);
    if (raceElem) {
      headerText = raceElem.textContent;
    }
  }
  
  if (classId) {
    const classElem = form.querySelector(`#hero_class_id option[value="${classId}"]`);
    if (classElem) {
      headerText += headerText ? ' - ' : '';
      headerText += classElem.textContent;
    }
  }
  
  // Update header text
  if (preview.querySelector('.preview-card__header .preview-card__text')) {
    preview.querySelector('.preview-card__header .preview-card__text').textContent = headerText;
  }
  
  // Update attributes
  updateAttributeValue(preview, 'agility', agility);
  updateAttributeValue(preview, 'mental', mental);
  updateAttributeValue(preview, 'will', will);
  updateAttributeValue(preview, 'strength', strength);
  updateAttributeValue(preview, 'armor', armor);
  updateAttributeValue(preview, 'health', calculatedHealth);
  
  // Update passives and abilities
  updateHeroPassives(form, preview);
}

/**
 * Update hero passive abilities in preview
 * @param {HTMLElement} form - The form element
 * @param {HTMLElement} preview - The preview element
 */
function updateHeroPassives(form, preview) {
  const passiveName = getInputValue(form, 'passive_name');
  const passiveDescription = getWysiwygContent(form, 'passive_description');
  
  // Get content container
  const contentElem = preview.querySelector('.preview-card__box--content');
  if (!contentElem) return;
  
  let content = '';
  
  // Add passive if available
  if (passiveName || passiveDescription) {
    content += `<div class="preview-hero-passive">`;
    if (passiveName) {
      content += `<span class="preview-hero-passive__title">${passiveName}: </span>`;
    }
    if (passiveDescription) {
      content += passiveDescription;
    }
    content += `</div>`;
  }
  
  // Class passive is not editable in the form, so we don't update it
  // Abilities are handled separately by the hero-abilities-selector component
  
  contentElem.innerHTML = content;
}

/**
 * Update an attribute value in the preview
 * @param {HTMLElement} preview - The preview element
 * @param {string} attribute - Attribute name
 * @param {number} value - Attribute value
 */
function updateAttributeValue(preview, attribute, value) {
  const attributeElem = preview.querySelector(`.preview-hero-attribute--${attribute} .preview-hero-attribute__value`);
  if (attributeElem) {
    attributeElem.textContent = value;
  }
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
 * Initialize the hero preview when the DOM is loaded
 */
export function setupHeroPreview() {
  document.addEventListener('DOMContentLoaded', function() {
    initHeroPreview();
  });
}

export default {
  initHeroPreview,
  setupHeroPreview
};