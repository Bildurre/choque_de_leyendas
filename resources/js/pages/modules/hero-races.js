import { setupModifiersValidation } from './common/modifier-validation';

/**
 * Default handler for hero race pages
 * @param {string} action - Current CRUD action (create, edit, index, show)
 */
export default function heroRaceHandler(action) {
  switch (action) {
    case 'create':
    case 'edit':
      setupFormPage();
      break;
  }
}

/**
 * Setup hero race form page
 */
function setupFormPage() {
  setupModifiersValidation([
    'agility_modifier', 
    'mental_modifier', 
    'will_modifier', 
    'strength_modifier', 
    'armor_modifier'
  ], 3);
}

export function create() {
  setupFormPage();
}

export function edit() {
  setupFormPage();
}