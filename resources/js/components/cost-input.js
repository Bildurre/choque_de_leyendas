export default function initCostInput() {
  const costInputs = document.querySelectorAll('.cost-input');
  
  if (!costInputs.length) return;
  
  costInputs.forEach(container => {
    const input = container.querySelector('.cost-input__field');
    const diceButtons = container.querySelectorAll('.cost-input__button[data-dice-type]');
    const clearButton = container.querySelector('.cost-input__button[data-action="clear"]');
    const previewContainer = container.querySelector('.cost-input__dice-container');
    
    // Initial preview
    updatePreview(input.value, previewContainer);
    
    // Input handling
    input.addEventListener('input', (e) => {
      // Filter and validate input
      const value = e.target.value;
      const filteredValue = value.replace(/[^RGBrgb]/g, '').toUpperCase();
      
      if (value !== filteredValue) {
        input.value = filteredValue;
      }
      
      // Reorder and update
      const orderedValue = orderCost(filteredValue);
      if (orderedValue !== filteredValue) {
        input.value = orderedValue;
      }
      
      // Update preview
      updatePreview(orderedValue, previewContainer);
    });
    
    // Dice buttons
    diceButtons.forEach(button => {
      button.addEventListener('click', () => {
        const diceType = button.getAttribute('data-dice-type');
        
        // Don't add if already at max length
        if (input.value.length >= 5) return;
        
        // Add the dice and reorder
        const newValue = orderCost(input.value + diceType);
        input.value = newValue;
        
        // Update preview
        updatePreview(newValue, previewContainer);
        
        // Focus input
        input.focus();
      });
    });
    
    // Clear button
    if (clearButton) {
      clearButton.addEventListener('click', () => {
        input.value = '';
        updatePreview('', previewContainer);
        input.focus();
      });
    }
  });
}

/**
 * Order cost string in RGB format
 * 
 * @param {string} cost - The cost string to order
 * @return {string} - The ordered cost string
 */
function orderCost(cost) {
  if (!cost) return '';
  
  // Count occurrences
  let red = 0, green = 0, blue = 0;
  for (const char of cost.toUpperCase()) {
    if (char === 'R') red++;
    else if (char === 'G') green++;
    else if (char === 'B') blue++;
  }
  
  // Rebuild in RGB order
  return 'R'.repeat(red) + 'G'.repeat(green) + 'B'.repeat(blue);
}

/**
 * Update the dice preview
 * 
 * @param {string} cost - The cost string
 * @param {HTMLElement} container - The container for the preview
 */
function updatePreview(cost, container) {
  // Clear existing preview
  container.innerHTML = '';
  
  if (!cost) {
    container.innerHTML = '<span class="cost-input__empty-preview">' + 
      (window.translations?.game?.cost?.free || 'Free') + '</span>';
    return;
  }
  
  // Add dice icons
  for (const char of cost.toUpperCase()) {
    if (char === 'R' || char === 'G' || char === 'B') {
      const diceElement = document.createElement('div');
      diceElement.className = `cost-input__dice cost-input__dice--${char === 'R' ? 'red' : char === 'G' ? 'green' : 'blue'}`;
      
      // Color for the SVG
      const color = char === 'R' ? '#f15959' : char === 'G' ? '#29ab5f' : '#408cfd';
      
      diceElement.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" stroke-linejoin="round" width="24px" height="24px">
          <polygon points="100,180 30,140 30,60 100,100" fill="${color}" stroke="black" stroke-width="2"/>
          <polygon points="100,180 100,100 170,60 170,140" fill="${color}" stroke="black" stroke-width="2"/>
          <polygon points="100,100 30,60 100,20 170,60" fill="${color}" stroke="black" stroke-width="2"/>
        </svg>
      `;
      
      container.appendChild(diceElement);
    }
  }
}