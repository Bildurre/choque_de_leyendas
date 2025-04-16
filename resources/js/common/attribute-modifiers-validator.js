/**
 * Attribute Modifiers Validator
 * Reusable tool for validating attribute modifiers
 */
export class AttributeModifiersValidator {
  /**
   * Constructor
   * @param {Object} options Configuration options
   * @param {Array} options.fieldIds Array of input field IDs to track
   * @param {Number} options.maxModifiableAttributes Maximum number of attributes that can be modified (optional)
   * @param {Number} options.maxAbsoluteSum Maximum sum of absolute values of modifiers (optional)
   * @param {Number} options.maxTotalSum Maximum sum of all modifiers (optional)
   * @param {Object} options.attributeLimits Per-attribute limits (optional)
   * @param {String} options.totalElementId ID of element to display the total (optional)
   * @param {String} options.countElementId ID of element to display the count (optional)
   * @param {String} options.errorClass CSS class to apply to invalid fields (optional)
   * @param {Function} options.customValidator Custom validation function (optional)
   */
  constructor(options) {
    this.fieldIds = options.fieldIds || [];
    this.maxModifiableAttributes = options.maxModifiableAttributes || null;
    this.maxAbsoluteSum = options.maxAbsoluteSum || null;
    this.maxTotalSum = options.maxTotalSum || null;
    this.attributeLimits = options.attributeLimits || null;
    this.totalElementId = options.totalElementId || null;
    this.countElementId = options.countElementId || null;
    this.errorClass = options.errorClass || 'is-invalid';
    this.customValidator = options.customValidator || null;
    this.submitButton = document.querySelector('button[type="submit"]');
    
    this.init();
  }
  
  /**
   * Initialize validator
   */
  init() {
    if (!this.fieldIds.length || !this.submitButton) return;
    
    // Set up event listeners
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        input.addEventListener('change', () => this.validate());
        input.addEventListener('input', () => this.validate());
      }
    });
    
    // Initial validation
    this.validate();
  }
  
  /**
   * Calculate non-zero modifiers count
   * @returns {Number}
   */
  calculateModifiedCount() {
    let count = 0;
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input && parseInt(input.value || 0) !== 0) {
        count++;
      }
    });
    
    return count;
  }
  
  /**
   * Calculate sum of absolute values
   * @returns {Number}
   */
  calculateAbsoluteSum() {
    let sum = 0;
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        sum += Math.abs(parseInt(input.value || 0));
      }
    });
    
    return sum;
  }
  
  /**
   * Calculate sum of all values
   * @returns {Number}
   */
  calculateTotalSum() {
    let sum = 0;
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        sum += parseInt(input.value || 0);
      }
    });
    
    return sum;
  }
  
  /**
   * Get current values of all fields
   * @returns {Object} Object with field IDs as keys and values as values
   */
  getFieldValues() {
    const values = {};
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        values[fieldId] = parseInt(input.value || 0);
      }
    });
    
    return values;
  }
  
  /**
   * Validate all configured rules
   * @returns {Boolean} Whether validation passed
   */
  validate() {
    // Get all the values we need for validation
    const modifiedCount = this.calculateModifiedCount();
    const absoluteSum = this.calculateAbsoluteSum();
    const totalSum = this.calculateTotalSum();
    const fieldValues = this.getFieldValues();
    
    // Reset validation state
    this.clearValidation();
    
    // Validate max modifiable attributes
    if (this.maxModifiableAttributes !== null && modifiedCount > this.maxModifiableAttributes) {
      this.setInvalid(`No puedes modificar más de ${this.maxModifiableAttributes} atributos`);
      return false;
    }
    
    // Validate absolute sum
    if (this.maxAbsoluteSum !== null && absoluteSum > this.maxAbsoluteSum) {
      this.setInvalid(`La suma de modificadores no puede superar ${this.maxAbsoluteSum}`);
      return false;
    }
    
    // Validate total sum
    if (this.maxTotalSum !== null && Math.abs(totalSum) > this.maxTotalSum) {
      this.setInvalid(`La suma total de modificadores no puede superar ${this.maxTotalSum}`);
      return false;
    }
    
    // Validate per-attribute limits
    if (this.attributeLimits) {
      for (const fieldId in fieldValues) {
        const value = fieldValues[fieldId];
        const attributeName = fieldId.replace('_modifier', '');
        
        if (this.attributeLimits[attributeName]) {
          const limit = this.attributeLimits[attributeName];
          
          if (Array.isArray(limit)) {
            // If limit is [min, max]
            if (value < limit[0] || value > limit[1]) {
              this.setInvalid(`${attributeName} debe estar entre ${limit[0]} y ${limit[1]}`);
              return false;
            }
          } else {
            // If limit is just a max absolute value
            if (Math.abs(value) > limit) {
              this.setInvalid(`${attributeName} no puede modificarse más de ${limit}`);
              return false;
            }
          }
        }
      }
    }
    
    // Custom validation
    if (this.customValidator && !this.customValidator(fieldValues)) {
      this.setInvalid('Validación personalizada fallida');
      return false;
    }
    
    // Update display elements
    this.updateDisplay(modifiedCount, absoluteSum);
    
    return true;
  }
  
  /**
   * Clear validation state
   */
  clearValidation() {
    if (this.submitButton) {
      this.submitButton.disabled = false;
    }
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        input.classList.remove(this.errorClass);
      }
    });
  }
  
  /**
   * Set invalid state
   * @param {String} reason Error message
   */
  setInvalid(reason) {
    if (this.submitButton) {
      this.submitButton.disabled = true;
    }
    
    this.fieldIds.forEach(fieldId => {
      const input = document.getElementById(fieldId);
      if (input) {
        input.classList.add(this.errorClass);
      }
    });
    
    console.log('Validation failed:', reason);
  }
  
  /**
   * Update display elements with current values
   * @param {Number} count Number of modified attributes
   * @param {Number} sum Sum of absolute values
   */
  updateDisplay(count, sum) {
    // Update count display
    if (this.countElementId) {
      const countElement = document.getElementById(this.countElementId);
      if (countElement) {
        countElement.textContent = count;
      }
    }
    
    // Update sum display
    if (this.totalElementId) {
      const totalElement = document.getElementById(this.totalElementId);
      if (totalElement) {
        totalElement.textContent = sum;
        
        // Add visual feedback if approaching limit
        if (this.maxAbsoluteSum) {
          totalElement.classList.toggle('text-warning', sum > this.maxAbsoluteSum * 0.7);
          totalElement.classList.toggle('text-danger', sum > this.maxAbsoluteSum);
        }
      }
    }
  }
}