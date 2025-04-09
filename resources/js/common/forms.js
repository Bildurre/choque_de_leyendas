/**
 * Initialize common form components and validation
 */
export function setupFormComponents() {
  setupColorInputs();
  setupFileValidation();
}

/**
 * Setup color input synchronization
 */
export function setupColorInputs() {
  const colorInputs = document.querySelectorAll('input[type="color"]');
  
  colorInputs.forEach(colorInput => {
    const colorId = colorInput.id;
    const textInput = document.getElementById(`${colorId}_text`);
    
    if (textInput) {
      colorInput.addEventListener('input', function() {
        textInput.value = this.value;
      });
    }
  });
}


/**
 * Setup file validation before form submission
 */
export function setupFileValidation() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    const fileInputs = form.querySelectorAll('input[type="file"]');
    
    if (fileInputs.length > 0) {
      form.addEventListener('submit', function(event) {
        fileInputs.forEach(fileInput => {
          if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const fileSize = file.size / 1024 / 1024; // size in MB
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            
            // Validate size (2MB max)
            if (fileSize > 2) {
              event.preventDefault();
              alert('El archivo es demasiado grande. El tamaño máximo permitido es 2MB.');
              return;
            }
            
            // Validate format
            if (!allowedExtensions.includes(fileExtension)) {
              event.preventDefault();
              alert('Formato de archivo no válido. Los formatos permitidos son: ' + allowedExtensions.join(', '));
              return;
            }
          }
        });
      });
    }
  });
}
