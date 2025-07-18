/**
 * Number Input Controller
 * Manages all number inputs in the application
 */

export default function initNumberInputController() {
  // Initialize existing inputs
  initializeExistingInputs();
  
  // Use event delegation for all number input interactions
  document.addEventListener('click', handleButtonClick);
  
  // Watch for new inputs added to the DOM
  observeNewInputs();
}

function initializeExistingInputs() {
  // Find all number inputs that need initialization
  document.querySelectorAll('input[type="number"].form-input').forEach(input => {
    setupNumberInput(input);
  });
}

function setupNumberInput(input) {
  // Skip if already has a wrapper (Blade rendered)
  if (input.parentElement.classList.contains('number-input-wrapper')) {
    return;
  }
  
  // Skip if already initialized dynamically
  if (input.dataset.numberInit) return;
  input.dataset.numberInit = 'true';
  
  // Create wrapper structure matching Blade output
  const wrapper = document.createElement('div');
  wrapper.className = 'number-input-wrapper';
  
  // Create decrement button
  const decrementBtn = document.createElement('button');
  decrementBtn.type = 'button';
  decrementBtn.className = 'number-input__button number-input__button--decrement';
  decrementBtn.dataset.action = 'decrement';
  decrementBtn.setAttribute('aria-label', window.translations?.form?.decrease || 'Decrease');
  decrementBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
  `;
  
  // Create increment button
  const incrementBtn = document.createElement('button');
  incrementBtn.type = 'button';
  incrementBtn.className = 'number-input__button number-input__button--increment';
  incrementBtn.dataset.action = 'increment';
  incrementBtn.setAttribute('aria-label', window.translations?.form?.increase || 'Increase');
  incrementBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="5" x2="12" y2="19"></line>
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
  `;
  
  // Wrap the input
  input.parentNode.insertBefore(wrapper, input);
  wrapper.appendChild(decrementBtn);
  wrapper.appendChild(input);
  wrapper.appendChild(incrementBtn);
}

function handleButtonClick(event) {
  // Check if clicked element is a number input button
  const button = event.target.closest('[data-action]');
  if (!button) return;
  
  const wrapper = button.closest('.number-input-wrapper');
  if (!wrapper) return;
  
  const input = wrapper.querySelector('input[type="number"]');
  if (!input) return;
  
  const action = button.dataset.action;
  const currentValue = parseFloat(input.value) || 0;
  const step = parseFloat(input.step) || 1;
  const min = input.hasAttribute('min') ? parseFloat(input.min) : -Infinity;
  const max = input.hasAttribute('max') ? parseFloat(input.max) : Infinity;
  
  let newValue;
  if (action === 'increment') {
    newValue = Math.min(currentValue + step, max);
  } else if (action === 'decrement') {
    newValue = Math.max(currentValue - step, min);
  }
  
  if (newValue !== undefined && newValue !== currentValue) {
    input.value = newValue;
    
    // Trigger events
    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }
  
  // Update button states
  updateButtonStates(wrapper, input);
}

function updateButtonStates(wrapper, input) {
  const value = parseFloat(input.value) || 0;
  const min = input.hasAttribute('min') ? parseFloat(input.min) : -Infinity;
  const max = input.hasAttribute('max') ? parseFloat(input.max) : Infinity;
  
  const decrementBtn = wrapper.querySelector('[data-action="decrement"]');
  const incrementBtn = wrapper.querySelector('[data-action="increment"]');
  
  if (decrementBtn) {
    decrementBtn.disabled = value <= min;
  }
  if (incrementBtn) {
    incrementBtn.disabled = value >= max;
  }
}

function observeNewInputs() {
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (node.nodeType === 1) { // Element node
          // Check if the node itself is a number input
          if (node.matches && node.matches('input[type="number"].form-input')) {
            setupNumberInput(node);
          }
          // Check for number inputs within the node
          const inputs = node.querySelectorAll?.('input[type="number"].form-input');
          inputs?.forEach(input => setupNumberInput(input));
        }
      });
    });
  });
  
  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
}

// Also listen for input changes to update button states
document.addEventListener('input', (event) => {
  if (event.target.matches('input[type="number"].form-input')) {
    const wrapper = event.target.closest('.number-input-wrapper');
    if (wrapper) {
      updateButtonStates(wrapper, event.target);
    }
  }
});