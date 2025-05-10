export default function initAttributeConfigForm() {
  const form = document.querySelector('form.hero-attributes-form');
  if (!form) return;
  
  // Validation for max_attribute_value and min_attribute_value
  const minAttributeInput = form.querySelector('input[name="min_attribute_value"]');
  const maxAttributeInput = form.querySelector('input[name="max_attribute_value"]');
  
  function validateAttributeRange() {
    const minValue = parseInt(minAttributeInput.value, 10);
    const maxValue = parseInt(maxAttributeInput.value, 10);
    
    if (maxValue < minValue) {
      maxAttributeInput.setCustomValidity('El valor máximo debe ser mayor o igual que el valor mínimo');
    } else {
      maxAttributeInput.setCustomValidity('');
    }
  }
  
  minAttributeInput.addEventListener('input', validateAttributeRange);
  maxAttributeInput.addEventListener('input', validateAttributeRange);
  
  // Validation for max_total_attributes and min_total_attributes
  const minTotalInput = form.querySelector('input[name="min_total_attributes"]');
  const maxTotalInput = form.querySelector('input[name="max_total_attributes"]');
  
  function validateTotalRange() {
    const minValue = parseInt(minTotalInput.value, 10);
    const maxValue = parseInt(maxTotalInput.value, 10);
    
    if (maxValue < minValue) {
      maxTotalInput.setCustomValidity('El total máximo debe ser mayor o igual que el total mínimo');
    } else {
      maxTotalInput.setCustomValidity('');
    }
  }
  
  minTotalInput.addEventListener('input', validateTotalRange);
  maxTotalInput.addEventListener('input', validateTotalRange);
  
  // Ejecutar la validación inicial
  validateAttributeRange();
  validateTotalRange();
}