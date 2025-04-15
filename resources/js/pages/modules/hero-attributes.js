import {setupLifePointsCalculator} from "./hero-attributes-helper.js"

/**
 * Default handler for hero attributes pages
 * @param {string} action - Current CRUD action
 */
export default function heroAttributesHandler(action) {
  // This module primarily handles the attributes configuration page
  if (action === 'index') {
    setupAttributesForm();
  }
}

/**
 * Setup hero attributes form page
 */
function setupAttributesForm() {
  setupLifePointsCalculator();
}

export function edit() {
  setupAttributesForm();
}