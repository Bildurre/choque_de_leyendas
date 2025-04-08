/**
 * Toggle cost section based on passive checkbox
 */
export function setupPassiveToggle() {
  const passiveCheckbox = document.getElementById('is_passive');
  const costSection = document.getElementById('cost-section');
  
  function toggleCostSection() {
    if (passiveCheckbox && costSection) {
      if (passiveCheckbox.checked) {
        costSection.style.display = 'none';
      } else {
        costSection.style.display = 'block';
      }
    }
  }
  
  if (passiveCheckbox && costSection) {
    passiveCheckbox.addEventListener('change', toggleCostSection);
    toggleCostSection(); // Initial state
  }
}

/**
 * Handle type and subtype relationship
 * @param {string|null} currentSubtypeId - The current subtype ID for edit pages
 */
export function setupTypeSubtypeRelationship(currentSubtypeId = null) {
  const typeSelect = document.getElementById('attack_type_id');
  const subtypeSelect = document.getElementById('attack_subtype_id');
  
  if (typeSelect && subtypeSelect) {
    typeSelect.addEventListener('change', function() {
      const typeId = this.value;
      
      if (typeId) {
        // Fetch subtypes for the selected type
        fetch(`/admin/attack-subtypes/by-type/${typeId}`)
          .then(response => response.json())
          .then(data => {
            // Clear current options
            subtypeSelect.innerHTML = '<option value="">Selecciona un subtipo</option>';
            
            // Add new options
            data.forEach(subtype => {
              const option = document.createElement('option');
              option.value = subtype.id;
              option.textContent = subtype.name;
              
              // Select the current subtype if it matches
              if (currentSubtypeId && subtype.id == currentSubtypeId) {
                option.selected = true;
              }
              
              subtypeSelect.appendChild(option);
            });
          })
          .catch(error => console.error('Error fetching subtypes:', error));
      } else {
        // Clear subtypes if no type is selected
        subtypeSelect.innerHTML = '<option value="">Selecciona un subtipo</option>';
      }
    });
    
    // Trigger change event if a type is already selected (for edit pages)
    if (typeSelect.value) {
      typeSelect.dispatchEvent(new Event('change'));
    }
  }
}