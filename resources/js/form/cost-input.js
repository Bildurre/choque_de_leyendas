/**
 * Cost Input Component
 * Handles cost input for hero abilities and cards
 */
export function initCostInputs() {
  document.querySelectorAll('.cost-input:not([data-initialized])').forEach(input => {
    setupCostInput(input);
    input.setAttribute('data-initialized', 'true');
  });
}

/**
 * Setup a single cost input component
 * @param {HTMLElement} input - The input element
 */
function setupCostInput(input) {
  const previewContainer = document.getElementById(`${input.id}-preview`);
  const maxLength = 5; // Maximum cost length
  
  // Remove any existing listeners to prevent duplication
  const oldInput = input.cloneNode(true);
  input.parentNode.replaceChild(oldInput, input);
  input = oldInput;
  
  // Input change/keyup event
  input.addEventListener('input', updatePreview);
  
  // Find cost buttons
  const buttonContainer = input.closest('.cost-input-container');
  if (buttonContainer) {
    // Cost buttons (R, G, B)
    const costButtons = buttonContainer.querySelectorAll('.cost-button[data-cost]');
    costButtons.forEach(button => {
      // Remove existing listeners
      const oldButton = button.cloneNode(true);
      button.parentNode.replaceChild(oldButton, button);
      button = oldButton;
      
      button.addEventListener('click', handleCostButtonClick);
    });
    
    // Clear button
    const clearButton = buttonContainer.querySelector('.cost-button-clear');
    if (clearButton) {
      // Remove existing listeners
      const oldClearButton = clearButton.cloneNode(true);
      clearButton.parentNode.replaceChild(oldClearButton, clearButton);
      
      oldClearButton.addEventListener('click', () => {
        input.value = '';
        updatePreview();
      });
    }
  }
  
  // Initial preview
  updatePreview();
  
  function updatePreview() {
    let cost = input.value.toUpperCase();
    
    // Validate the cost (only R, G, B allowed)
    if (cost && !/^[RGB]*$/.test(cost)) {
      input.value = input.value.replace(/[^RGBrgb]/g, '').toUpperCase();
      cost = input.value;
    }
    
    // Reorder the input value
    if (cost) {
      const orderedCost = orderCostString(cost);
      
      // Only update the input if the order has changed
      if (orderedCost !== cost) {
        // Save cursor position relative to the end of the input
        const distanceFromEnd = cost.length - input.selectionStart;
        
        // Update input value with ordered cost
        input.value = orderedCost;
        
        // Restore cursor position from the end
        const newPosition = Math.max(0, orderedCost.length - distanceFromEnd);
        input.setSelectionRange(newPosition, newPosition);
      }
    }
    
    if (previewContainer) {
      // Clear previous preview
      previewContainer.innerHTML = '';
      
      if (cost) {
        // Render the dice SVGs directly in the preview container
        renderDicePreview(input.value);
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
  
  function renderDicePreview(cost) {
    // Process each character in the cost string
    [...cost].forEach(costChar => {
      // Create dice SVG based on the cost character and append directly to previewContainer
      previewContainer.innerHTML += createDiceSVG(costChar);
    });
  }
  
  function orderCostString(cost) {
    // Count each color
    const rCount = (cost.match(/R/g) || []).length;
    const gCount = (cost.match(/G/g) || []).length;
    const bCount = (cost.match(/B/g) || []).length;
    
    // Create ordered string
    return 'R'.repeat(rCount) + 'G'.repeat(gCount) + 'B'.repeat(bCount);
  }
  
  function createDiceSVG(costChar) {
    // Map cost types to colors
    const colorMap = {
      'R': '#f53d3d', // Red
      'G': '#3df53d', // Green
      'B': '#3d3df5'  // Blue
    };
    
    const color = colorMap[costChar] || '#f53d3d';
    
    // Using the same classes as game-dice component
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="dice dice--static dice--xl">
      <polygon 
        points="100,180 30,140 30,60 100,100" 
        fill="${color}" 
        stroke="#000" 
        stroke-width="2"
        stroke-linejoin="round"
      />
      <polygon 
        points="100,180 100,100 170,60 170,140" 
        fill="${color}" 
        stroke="#000" 
        stroke-width="2"
        stroke-linejoin="round"
      />
      <polygon 
        points="100,100 30,60 100,20 170,60" 
        fill="${color}" 
        stroke="#000" 
        stroke-width="2"
        stroke-linejoin="round"
      />
    </svg>`;
  }
}