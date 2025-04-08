/**
 * Cost Input Component
 * Handles cost input for hero abilities and cards
 */
export function initCostInputs() {
  document.querySelectorAll('.cost-input').forEach(input => {
    setupCostInput(input);
  });
}

/**
 * Setup a single cost input component
 * @param {HTMLElement} input - The input element
 */
function setupCostInput(input) {
  const previewContainer = document.getElementById(`${input.id}-preview`);
  const maxLength = 5; // Maximum cost length
  
  // Input change/keyup event
  input.addEventListener('input', updatePreview);
  
  // Find cost buttons
  const buttonContainer = input.closest('.cost-input-container');
  if (buttonContainer) {
    // Cost buttons (R, G, B)
    const costButtons = buttonContainer.querySelectorAll('.cost-button[data-cost]');
    costButtons.forEach(button => {
      button.addEventListener('click', handleCostButtonClick);
    });
    
    // Clear button
    const clearButton = buttonContainer.querySelector('.cost-button-clear');
    if (clearButton) {
      clearButton.addEventListener('click', () => {
        input.value = '';
        updatePreview();
      });
    }
  }
  
  // Initial preview
  updatePreview();
  
  function updatePreview() {
    const cost = input.value.toUpperCase();
    
    // Validate the cost (only R, G, B allowed)
    if (cost && !/^[RGB]*$/.test(cost)) {
      input.value = input.value.replace(/[^RGBrgb]/g, '').toUpperCase();
      return;
    }
    
    if (previewContainer) {
      // Clear previous preview
      previewContainer.innerHTML = '';
      
      if (cost) {
        // Create a new cost display
        fetchCostPreview(cost);
      }
    }
  }
  
  function handleCostButtonClick(event) {
    const cost = event.target.getAttribute('data-cost');
    if (cost && input.value.length < maxLength) {
      input.value += cost;
      updatePreview();
    }
  }
  
  function fetchCostPreview(cost) {
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
        renderCostPreview(data.formattedCost);
      }
    })
    .catch(error => {
      console.error('Error validating cost:', error);
    });
  }
  
  function renderCostPreview(formattedCost) {
    // Create container for dice display
    const costDisplay = document.createElement('div');
    costDisplay.className = 'cost-display';
    
    // Add dice for each color
    for (const [color, count] of Object.entries(formattedCost)) {
      for (let i = 0; i < count; i++) {
        const diceWrapper = document.createElement('div');
        diceWrapper.className = 'cost-dice-wrapper';
        diceWrapper.innerHTML = createDiceSvg(color);
        costDisplay.appendChild(diceWrapper);
      }
    }
    
    // Add total count
    const totalCount = Object.values(formattedCost).reduce((a, b) => a + b, 0);
    if (totalCount > 0) {
      const totalElement = document.createElement('span');
      totalElement.className = 'cost-total';
      totalElement.textContent = totalCount;
      costDisplay.appendChild(totalElement);
    }
    
    previewContainer.appendChild(costDisplay);
  }
  
  function createDiceSvg(color) {
    const colorHex = getColorHex(color);
    return `<svg class="cost-dice" viewBox="0 0 200 200">
      <polygon points="100,180 30,140 30,60 100,100" fill="${colorHex}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
      <polygon points="100,180 100,100 170,60 170,140" fill="${colorHex}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
      <polygon points="100,100 30,60 100,20 170,60" fill="${colorHex}" stroke="#000" stroke-width="2" stroke-linejoin="round" />
    </svg>`;
  }
  
  function getColorHex(colorName) {
    const colorMap = {
      'red': '#f53d3d',
      'green': '#3df53d',
      'blue': '#3d3df5'
    };
    return colorMap[colorName] || '#999999';
  }
}