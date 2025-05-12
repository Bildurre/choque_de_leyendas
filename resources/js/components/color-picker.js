export default function initColorPicker() {
  // Find all color pickers on the page
  const colorPickers = document.querySelectorAll('.color-picker');
  
  if (!colorPickers.length) return;
  
  colorPickers.forEach(pickerContainer => {
    const textInput = pickerContainer.querySelector('.color-picker__text');
    const colorInput = pickerContainer.querySelector('.color-picker__selector');
    
    if (!textInput || !colorInput) return;
    
    // Sync color picker with text input
    textInput.addEventListener('input', function() {
      let color = this.value;
      
      // Add # if missing
      if (color && !color.startsWith('#')) {
        color = '#' + color;
      }
      
      // Update color picker if it's a valid hex color
      if (/^#([0-9A-F]{3}){1,2}$/i.test(color)) {
        colorInput.value = color;
      }
    });
    
    // Sync text input with color picker
    colorInput.addEventListener('input', function() {
      textInput.value = this.value;
    });
  });
}