import { AttributeModifiersValidator } from '../../../common/attribute-modifiers-validator';

/**
 * Setup validation for attribute modifiers
 * 
 * @param {Array} modifierFields - Array of modifier field IDs
 * @param {Object} options - Additional validation options
 */
export function setupModifiersValidation(modifierFields, options = {}) {
  const defaultOptions = {
    maxAbsoluteSum: 3,
    totalElementId: 'modifiers-total',
    maxElementId: 'modifiers-max'
  };
  
  const config = {
    ...defaultOptions,
    ...options,
    fieldIds: modifierFields
  };
  
  return new AttributeModifiersValidator(config);
}