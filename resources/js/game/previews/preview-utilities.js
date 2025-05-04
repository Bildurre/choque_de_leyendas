import { getInputValue, getWysiwygContent } from '../../core/utilities';

/**
 * Create a preview updater for a specific type of content
 * @param {string} formSelector - Selector for the form
 * @param {string} previewSelector - Selector for the preview container
 * @param {Function} updateFunction - Function to update the preview with form data
 */
export function createPreviewUpdater(formSelector, previewSelector, updateFunction) {
  const form = document.querySelector(formSelector);
  const preview = document.querySelector(previewSelector);
  
  if (!form || !preview) return;
  
  // Initial preview update
  updateFunction(form, preview);
  
  // Update preview on form changes
  const formInputs = form.querySelectorAll('input, select, textarea');
  formInputs.forEach(input => {
    input.addEventListener('input', () => updateFunction(form, preview));
    
    // For select elements, also listen for change event
    if (input.tagName === 'SELECT') {
      input.addEventListener('change', () => updateFunction(form, preview));
    }
  });
}

/**
 * Get input value from form
 * @param {HTMLElement} form - The form element
 * @param {string} name - Input name
 * @returns {string} - Input value
 */
export function getInputValue(form, name) {
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
export function getWysiwygContent(form, name) {
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