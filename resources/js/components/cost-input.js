/**
 * Cost Input Component
 * Handles cost input for hero abilities and cards
 */
export default class CostInput {
  /**
   * Initialize cost input components
   */
  static init() {
    document.querySelectorAll('.cost-input').forEach(input => {
      new CostInput(input);
    });
  }

  /**
   * Constructor for the cost input
   * @param {HTMLElement} element - The input element
   */
  constructor(element) {
    this.input = element;
    this.previewContainer = document.getElementById(`${this.input.id}-preview`);
    this.maxLength = 5; // Maximum cost length
    
    this.setupListeners();
    this.updatePreview();
  }

  /**
   * Setup event listeners
   */
  setupListeners() {
    // Input change/keyup event
    this.input.addEventListener('input', this.updatePreview.bind(this));
    
    // Cost buttons (R, G, B)
    const buttonContainer = this.input.closest('.cost-input-container');
    if (buttonContainer) {
      const costButtons = buttonContainer.querySelectorAll('.cost-button[data-cost]');
      costButtons.forEach(button => {
        button.addEventListener('click', this.handleCostButtonClick.bind(this));
      });
      
      // Clear button
      const clearButton = buttonContainer.querySelector('.cost-button-clear');
      if (clearButton) {
        clearButton.addEventListener('click', this.handleClearButtonClick.bind(this));
      }
    }
  }

  /**
   * Update the cost preview
   */
  updatePreview() {
    const cost = this.input.value.toUpperCase();
    
    // Validate the cost (only R, G, B allowed)
    if (cost && !/^[RGB]*$/.test(cost)) {
      this.input.value = this.input.value.replace(/[^RGBrgb]/g, '').toUpperCase();
      return;
    }
    
    if (this.previewContainer) {
      // Clear previous preview
      this.previewContainer.innerHTML = '';
      
      if (cost) {
        // Create a new cost display
        this.fetchCostPreview(cost);
      }
    }
  }

  /**
   * Fetch the cost preview HTML via AJAX
   * @param {string} cost - The cost string
   */
  fetchCostPreview(cost) {
    // Use the CostTranslatorService via AJAX
    fetch('/admin/hero-abilities/validate-cost', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ cost })
    })
    .then(response => response.json())
    .then(data => {
      if (data.valid) {
        // Create dice elements based on the formatted cost
        this.renderCostPreview(data.formattedCost);
      }
    })
    .catch(error => {
      console.error('Error validating cost:', error);
    });
  }

  /**
   * Render the cost preview based on the formatted cost
   * @param {Object} formattedCost - The formatted cost object
   */
  renderCostPreview(formattedCost) {
    // Create a container for the dice
    const costDisplay = document.createElement('div');
    costDisplay.className = 'cost-display';
    
    // Add dice for each color
    for (const [color, count] of Object.entries(formattedCost)) {
      for (let i = 0; i < count; i++) {
        const diceWrapper = document.createElement('div');
        diceWrapper.className = 'cost-dice-wrapper';
        diceWrapper.innerHTML = `<svg class="cost-dice" viewBox="0 0 200 200">
          <polygon points="100,180 30,140 30,60 100,100" fill="${this.getColorHex(color)}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
          <polygon points="100,180 100,100 170,60 170,140" fill="${this.getColorHex(color)}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
          <polygon points="100,100 30,60 100,20 170,60" fill="${this.getColorHex(color)}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
        </svg>`;
        costDisplay.appendChild(diceWrapper);
      }
    }
    
    // Add total count if necessary
    const totalCount = Object.values(formattedCost).reduce((a, b) => a + b, 0);
    if (totalCount > 0) {
      const totalElement = document.createElement('span');
      totalElement.className = 'cost-total';
      totalElement.textContent = totalCount;
      costDisplay.appendChild(totalElement);
    }
    
    this.previewContainer.appendChild(costDisplay);
  }

  /**
   * Get the hex color value for a color name
   * @param {string} colorName - The color name
   * @returns {string} - The hex color value
   */
  getColorHex(colorName) {
    const colorMap = {
      'red': '#f53d3d',
      'green': '#3df53d',
      'blue': '#3d3df5'
    };
    return colorMap[colorName] || '#999999';
  }

  /**
   * Handle cost button click (R, G, B)
   * @param {Event} event - The click event
   */
  handleCostButtonClick(event) {
    const cost = event.target.getAttribute('data-cost');
    if (cost && this.input.value.length < this.maxLength) {
      this.input.value += cost;
      this.updatePreview();
    }
  }

  /**
   * Handle clear button click
   */
  handleClearButtonClick() {
    this.input.value = '';
    this.updatePreview();
  }
}