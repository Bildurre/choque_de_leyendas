import { setupModifiersValidation } from '../common/modifier-validation';
import { initWysiwygEditors } from '../../components/wysiwyg-editor';

/**
 * Default handler for hero class pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
 */
export default function heroClassHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup hero class form page
 */
function setupFormPage() {
  setupModifiersValidation();
  initWysiwygEditors();
}

/**
 * Setup hero class form page
 */
function setupFormPage() {
  const modifierFields = [
    'agility_modifier', 
    'mental_modifier', 
    'will_modifier', 
    'strength_modifier', 
    'armor_modifier'
  ];
  
  setupModifiersValidation(modifierFields, {
    maxAbsoluteSum: 3,
    attributeLimits: {
      agility: 2,
      mental: 2,
      will: 2,
      strength: 2,
      armor: 2
    }
  });
  
  initWysiwygEditors();
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}