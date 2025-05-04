import { initImageUploaders } from '../../form/image-uploader.js';

/**
 * Default handler for attack range pages
 * @param {string} action - Current CRUD action
 */
export default function attackRangeHandler(action) {
  console.log('Attack range handler initialized with action:', action);
  
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
 * Setup attack range form page
 */
function setupFormPage() {
  console.log('Setting up attack range form page');
  // Initialize image uploaders immediately
  initImageUploaders();
}

/**
 * Setup attack range show page
 */
function setupShowPage() {
  console.log('Setting up attack range show page');
  // Add any specific functionality for show page if needed
}

/**
 * Explicitly named exports for direct calls from router
 */
export function create() {
  console.log('Attack range create page initialized');
  setupFormPage();
}

export function edit() {
  console.log('Attack range edit page initialized');
  setupFormPage();
}

export function show() {
  console.log('Attack range show page initialized');
  setupShowPage();
}