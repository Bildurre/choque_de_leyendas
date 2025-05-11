/**
 * Initialize the hero abilities selector component
 */
const initHeroAbilitiesSelector = () => {
  const selector = document.querySelector('.hero-abilities-selector');
  if (!selector) return;

  const abilityItems = selector.querySelectorAll('.hero-ability-item');
  const previewPlaceholder = selector.querySelector('.hero-abilities-selector__preview-placeholder');
  const previewContent = selector.querySelector('.hero-abilities-selector__preview-content');
  const previewTitle = selector.querySelector('.hero-abilities-selector__preview-title');
  const previewCost = selector.querySelector('.hero-abilities-selector__preview-cost');
  const previewAttackType = selector.querySelector('.hero-abilities-selector__preview-attack-type');
  const previewDescription = selector.querySelector('.hero-abilities-selector__preview-description');

  // Toggle the selection of an ability item
  const toggleAbilitySelection = (item, checkbox) => {
    if (checkbox.checked) {
      item.classList.add('is-selected');
    } else {
      item.classList.remove('is-selected');
    }
  };

  // Show the preview of an ability item
  const showAbilityPreview = (item) => {
    // Extract ability information
    const abilityName = item.querySelector('.hero-ability-item__label').textContent;
    const costHtml = item.querySelector('.hero-ability-item__cost-icons')?.innerHTML || '';
    const attackType = item.querySelector('.hero-ability-item__attack-type')?.innerHTML || '';
    const description = item.querySelector('.hero-ability-item__description')?.textContent || '';

    // Update preview content
    previewTitle.textContent = abilityName;
    previewCost.innerHTML = costHtml;
    previewAttackType.innerHTML = attackType;
    previewDescription.textContent = description;

    // Show preview content, hide placeholder
    previewPlaceholder.style.display = 'none';
    previewContent.style.display = 'block';
  };

  // Hide the preview
  const hideAbilityPreview = () => {
    previewPlaceholder.style.display = 'block';
    previewContent.style.display = 'none';
  };

  // Add event listeners to ability items
  abilityItems.forEach(item => {
    const checkbox = item.querySelector('input[type="checkbox"]');
    
    // Initialize selection state
    toggleAbilitySelection(item, checkbox);
    
    // Handle checkbox change
    checkbox.addEventListener('change', () => {
      toggleAbilitySelection(item, checkbox);
    });
    
    // Show preview on click
    item.addEventListener('click', (event) => {
      // Don't toggle checkbox if clicking on the checkbox itself
      if (event.target !== checkbox && event.target !== checkbox.parentNode) {
        showAbilityPreview(item);
      }
    });
    
    // Show preview on hover
    item.addEventListener('mouseenter', () => {
      showAbilityPreview(item);
    });
  });
  
  // Hide preview when mouse leaves the list
  selector.querySelector('.hero-abilities-selector__list').addEventListener('mouseleave', () => {
    hideAbilityPreview();
  });
};

export default initHeroAbilitiesSelector;