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
   * @param {Number} options.maxTotalSum Maximum sum of all modifiers (optional)
   * @param {Object|Number} options.attributeLimits Per-attribute limits or a single limit for all (optional)
   * @param {String} options.totalElementId ID of element to display the total (optional)
   * @param {String} options.countElementId ID of element to display the count (optional)
   * @param {String} options.errorClass CSS class to apply to invalid fields (optional)
   */
  constructor(options) {
    this.fieldIds = options.fieldIds || [];
    this.maxModifiableAttributes = options.maxModifiableAttributes || null;
    this.maxTotalSum = options.maxTotalSum || null;
    this.attributeLimits = this.processAttributeLimits(options.attributeLimits);
    this.totalElementId = options.totalElementId || null;
    this.countElementId = options.countElementId || null;
    this.errorClass = options.errorClass || 'is-invalid';
    this.submitButton = document.querySelector('button[type="submit"]');
    
    this.init();
  }
  
  /**
   * Process attribute limits - handle both object and single value
   * @param {Object|Number} limits 
   * @returns {Object} Processed attribute limits
   */
  processAttributeLimits(limits) {
    if (limits === null || limits === undefined) {
      return null;
    }
    
    // If it's already an object, return it as is
    if (typeof limits === 'object' && !Array.isArray(limits)) {
      return limits;
    }
    
    // If it's a single value (number), apply to all attributes
    if (typeof limits === 'number') {
      const processedLimits = {};
      
      this.fieldIds.forEach(fieldId => {
        const attributeName = fieldId.replace('_modifier', '');
        processedLimits[attributeName] = limits;
      });
      
      return processedLimits;
    }
    
    return null;
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
    const totalSum = this.calculateTotalSum();
    const fieldValues = this.getFieldValues();
    
    // Reset validation state
    this.clearValidation();
    
    // Validate max modifiable attributes
    if (this.maxModifiableAttributes !== null && modifiedCount > this.maxModifiableAttributes) {
      this.setInvalid(`No puedes modificar más de ${this.maxModifiableAttributes} atributos`);
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
    
    // Update display elements
    this.updateDisplay(modifiedCount);
    
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
   */
  updateDisplay(count) {
    // Update count display
    if (this.countElementId) {
      const countElement = document.getElementById(this.countElementId);
      if (countElement) {
        countElement.textContent = count;
      }
    }
    
    // Update total display
    if (this.totalElementId) {
      const totalElement = document.getElementById(this.totalElementId);
      const totalValue = Math.abs(this.calculateTotalSum());
      
      if (totalElement) {
        totalElement.textContent = totalValue;
      }
    }
  }
}