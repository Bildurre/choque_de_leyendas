import { setupDeleteConfirmations } from './confirmations';
import { setupAlertDismissal } from './alerts';
import { initWysiwygEditors } from '../components/wysiwyg-editor';
import CostInput from '../components/cost-input';

/**
 * Setup delete confirmations for entity list pages
 * 
 * @param {string} selector - CSS selector for delete buttons
 * @param {string} entityAttribute - Data attribute containing entity name
 * @param {string} entityType - Type of entity (e.g., "facción", "clase")
 */
export function initEntityListPage(selector, entityAttribute, entityType) {
  setupDeleteConfirmations(selector, entityAttribute, entityType);
  setupAlertDismissal();
}

/**
 * Initialize form page functionality
 * 
 * @param {Object} options - Configuration options
 * @param {boolean} options.hasColorInputs - Whether page has color inputs
 * @param {boolean} options.hasFileUploads - Whether page has file upload fields
 * @param {boolean} options.hasWysiwygEditors - Whether page has WYSIWYG editors
 * @param {boolean} options.hasCostInputs - Whether page has cost inputs
 * @param {Function} options.customValidation - Custom validation function
 */
export function initFormPage(options = {}) {
  const { 
    hasColorInputs = false, 
    hasFileUploads = false,
    hasWysiwygEditors = true,
    hasCostInputs = true,
    customValidation = null
  } = options;
  
  setupAlertDismissal();
  
  if (hasColorInputs) {
    setupColorInputs();
  }
  
  if (hasFileUploads) {
    setupFileInputs();
    setupFileValidation();
  }
  
  if (hasWysiwygEditors && document.querySelector('.wysiwyg-editor')) {
    initWysiwygEditors();
  }
  
  if (hasCostInputs && document.querySelector('.cost-input')) {
    CostInput.init();
  }
  
  if (customValidation && typeof customValidation === 'function') {
    customValidation();
  }
}

/**
 * Setup color input synchronization
 */
function setupColorInputs() {
  const colorInputs = document.querySelectorAll('input[type="color"]');
  
  colorInputs.forEach(colorInput => {
    const colorId = colorInput.id;
    const textInput = document.getElementById(`${colorId}_text`);
    
    if (textInput) {
      colorInput.addEventListener('input', function() {
        textInput.value = this.value;
      });
    }
  });
}

/**
 * Setup file input display and functionality
 */
function setupFileInputs() {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  
  fileInputs.forEach(fileInput => {
    const fileNameElement = document.querySelector('.file-name');
    const removeCheckbox = document.getElementById(`remove_${fileInput.id}`);
    
    if (fileInput && fileNameElement) {
      fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
          fileNameElement.textContent = this.files[0].name;
          
          if (removeCheckbox) {
            removeCheckbox.checked = false;
          }
        } else {
          fileNameElement.textContent = 'Ningún archivo seleccionado';
        }
      });
    }
    
    if (removeCheckbox) {
      removeCheckbox.addEventListener('change', function() {
        if (this.checked && fileInput) {
          fileInput.value = '';
          if (fileNameElement) {
            fileNameElement.textContent = 'Ningún archivo seleccionado';
          }
        }
      });
    }
  });
}

/**
 * Setup file validation before form submission
 */
function setupFileValidation() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(event) {
      const fileInputs = form.querySelectorAll('input[type="file"]');
      
      fileInputs.forEach(fileInput => {
        if (fileInput.files.length > 0) {
          const file = fileInput.files[0];
          const fileSize = file.size / 1024 / 1024; // tamaño en MB
          const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
          const fileExtension = file.name.split('.').pop().toLowerCase();
          
          // Validar tamaño (2MB máximo)
          if (fileSize > 2) {
            event.preventDefault();
            alert('El archivo es demasiado grande. El tamaño máximo permitido es 2MB.');
            return;
          }
          
          // Validar formato
          if (!allowedExtensions.includes(fileExtension)) {
            event.preventDefault();
            alert('Formato de archivo no válido. Los formatos permitidos son: ' + allowedExtensions.join(', '));
            return;
          }
        }
      });
    });
  });
}