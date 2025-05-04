import { initImageUploaders } from './image-uploader';
import { initWysiwygEditors } from './wysiwyg-editor';
import { initCostInputs } from './cost-input';
import { setupTranslateFields } from './translate-fields';
import { setupColorInputs } from './color-input';

/**
 * Initialize all common form components
 */
export function initFormComponents() {
  // Initialize all form components
  initImageUploaders();
  setupTranslateFields();
  initCostInputs();
  setupColorInputs();
  
  // Initialize TinyMCE if we have wysiwyg-editor elements
  if (document.querySelector('.wysiwyg-editor:not([data-initialized])')) {
    initWysiwygEditors();
  }
}