/**
 * Initialize conditional field visibility
 * @param {string} triggerId - ID of the trigger field
 * @param {string|Array} targetIds - ID(s) of target field(s) to show/hide
 * @param {string|Array} checkValues - Optional value(s) to check for (for select/multiselect)
 */
export default function initConditionalField(triggerId, targetIds, checkValues = null) {
  const trigger = document.getElementById(triggerId);
  if (!trigger) return;
  
  const targets = Array.isArray(targetIds) ? targetIds : [targetIds];
  const targetElements = targets
    .map(id => document.getElementById(id))
    .filter(el => el !== null);
  
  if (targetElements.length === 0) return;
  
  // Store original display values for each target
  const originalDisplays = new Map();
  
  // Check if this is a Choices.js element
  const isChoicesElement = trigger.hasAttribute('data-choices');
  
  // Store original display values and set initial visibility
  targetElements.forEach(element => {
    const targetContainer = element.hasAttribute('data-choices') 
      ? element.closest('.filter-select') || element.closest('.choices')
      : element;
      
    if (targetContainer) {
      // Get computed display value from CSS
      const computedStyle = window.getComputedStyle(targetContainer);
      const originalDisplay = computedStyle.display;
      originalDisplays.set(targetContainer, originalDisplay);
    }
  });
  
  // Set initial visibility
  updateVisibility();
  
  if (isChoicesElement) {
    // For Choices.js, we need to listen to the parent element
    const choicesElement = trigger.closest('.choices');
    if (choicesElement) {
      // Listen for Choices.js events
      choicesElement.addEventListener('change', updateVisibility);
      choicesElement.addEventListener('addItem', updateVisibility);
      choicesElement.addEventListener('removeItem', updateVisibility);
    }
  } else {
    // Regular element
    trigger.addEventListener('change', updateVisibility);
  }
  
  function updateVisibility() {
    let shouldShow = false;
    
    if (trigger.type === 'checkbox') {
      // For checkboxes, check if checked
      shouldShow = trigger.checked;
    } else if (trigger.multiple) {
      // For multiselect, check if value is included
      const selectedValues = Array.from(trigger.selectedOptions).map(opt => opt.value);
      if (checkValues) {
        const valuesToCheck = Array.isArray(checkValues) ? checkValues : [checkValues];
        shouldShow = selectedValues.some(val => valuesToCheck.includes(val));
      } else {
        shouldShow = selectedValues.length > 0;
      }
    } else {
      // For regular select or input, check exact value
      if (checkValues) {
        const valuesToCheck = Array.isArray(checkValues) ? checkValues : [checkValues];
        shouldShow = valuesToCheck.includes(trigger.value);
      } else {
        shouldShow = trigger.value !== '';
      }
    }
    
    targetElements.forEach(element => {
      // If the target is also a Choices element, show/hide its container
      const targetContainer = element.hasAttribute('data-choices') 
        ? element.closest('.filter-select') || element.closest('.choices')
        : element;
        
      if (targetContainer) {
        if (shouldShow) {
          // Restore original display value
          const originalDisplay = originalDisplays.get(targetContainer);
          targetContainer.style.display = originalDisplay === 'none' ? '' : originalDisplay;
        } else {
          // Hide element
          targetContainer.style.display = 'none';
        }
      }
    });
  }
}