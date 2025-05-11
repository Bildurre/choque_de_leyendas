
/**
 * Initialize the hero form component
 */
const initHeroForm = () => {
  const heroForm = document.getElementById('hero-form');
  if (!heroForm) return;

  // Add API endpoint to fetch attribute configuration if needed
  const attributesFieldset = document.getElementById('attributes-fieldset');
  if (attributesFieldset && !attributesFieldset.dataset.configLoaded) {
    // Add multipliers as data attributes for the hero attributes component
    const attributesConfig = JSON.parse(heroForm.dataset.attributesConfig || '{}');
    
    if (Object.keys(attributesConfig).length > 0) {
      attributesFieldset.dataset.agilityMultiplier = attributesConfig.agility_multiplier;
      attributesFieldset.dataset.mentalMultiplier = attributesConfig.mental_multiplier;
      attributesFieldset.dataset.willMultiplier = attributesConfig.will_multiplier;
      attributesFieldset.dataset.strengthMultiplier = attributesConfig.strength_multiplier;
      attributesFieldset.dataset.armorMultiplier = attributesConfig.armor_multiplier;
      attributesFieldset.dataset.healthBase = attributesConfig.total_health_base;
      attributesFieldset.dataset.configLoaded = 'true';
    }
  }

  // Add validation before submit
  heroForm.addEventListener('submit', (event) => {
    const totalAttributesElement = document.getElementById('total-attributes');
    const pointsAvailableElement = document.getElementById('points-available');
    
    if (totalAttributesElement && pointsAvailableElement) {
      const totalAttributes = parseInt(totalAttributesElement.textContent);
      const minTotal = parseInt(attributesFieldset.dataset.minTotalAttributes || '0');
      const maxTotal = parseInt(attributesFieldset.dataset.maxTotalAttributes || '0');
      
      // Check if total attributes are within limits
      if (minTotal > 0 && totalAttributes < minTotal) {
        event.preventDefault();
        alert(`El total de atributos debe ser al menos ${minTotal}.`);
        return false;
      }
      
      if (maxTotal > 0 && totalAttributes > maxTotal) {
        event.preventDefault();
        alert(`El total de atributos no puede ser mayor que ${maxTotal}.`);
        return false;
      }
    }
    
    return true;
  });
};

export default initHeroForm;