import { initWysiwygEditors } from '../../form/wysiwyg-editor';
import { initImageUploaders } from '../../form/image-uploader';

/**
 * Base module controller for admin CRUD pages
 * To be extended by specific module controllers
 */
export default class BaseModule {
  /**
   * Handle module actions
   * @param {string} action - Current CRUD action
   */
  handleAction(action) {
    switch (action) {
      case 'create':
        this.create();
        break;
      case 'edit':
        this.edit();
        break;
      case 'show':
        this.show();
        break;
      case 'index':
        this.index();
        break;
    }
  }
  
  /**
   * Default handlers that can be overridden
   */
  create() {
    this.setupFormPage();
  }
  
  edit() {
    this.setupFormPage();
  }
  
  show() {
    // Default show page handler
  }
  
  index() {
    // Default index page handler
  }
  
  /**
   * Common form page setup
   */
  setupFormPage() {
    // Common form elements initialization
    // Specific modules should override this
  }
}

/**
 * Create a basic CRUD module with standard functionality
 * @param {Function} setupFunction - Custom setup function for forms
 * @returns {Object} Module with CRUD handlers
 */
export function createBasicCrudModule(setupFunction = null) {
  const setupFormPage = setupFunction || function() {
    // Default form setup - common components
    initImageUploaders();
    
    // Initialize TinyMCE editors if any
    if (document.querySelector('.wysiwyg-editor')) {
      initWysiwygEditors();
    }
  };
  
  return {
    default: function(action) {
      switch (action) {
        case 'create':
        case 'edit':
          setupFormPage();
          break;
      }
    },
    create: setupFormPage,
    edit: setupFormPage,
    show: function() {}
  };
}