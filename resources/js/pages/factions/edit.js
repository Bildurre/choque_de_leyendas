/**
 * Faction edit page functionality
 * Handles form interactions for faction editing
 */
document.addEventListener('DOMContentLoaded', function() {
  // Actualizar el campo de texto del color cuando se cambia el color
  const colorInput = document.getElementById('color');
  const colorTextInput = document.getElementById('color_text');
  
  if (colorInput && colorTextInput) {
    colorInput.addEventListener('input', function() {
      colorTextInput.value = this.value;
    });
  }
  
  // Mostrar el nombre del archivo seleccionado
  const fileInput = document.getElementById('icon');
  const fileName = document.getElementById('file-name');
  const removeIconCheckbox = document.getElementById('remove_icon');
  
  if (fileInput && fileName) {
    fileInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        fileName.textContent = this.files[0].name;
        
        // Si se selecciona un nuevo archivo, deseleccionar la opción de eliminar
        if (removeIconCheckbox) {
          removeIconCheckbox.checked = false;
        }
      } else {
        fileName.textContent = 'Ningún archivo seleccionado';
      }
    });
  }
  
  // Si se marca eliminar icono, limpiar el input de archivo
  if (removeIconCheckbox && fileInput) {
    removeIconCheckbox.addEventListener('change', function() {
      if (this.checked) {
        // Limpiar el input de archivo
        fileInput.value = '';
        if (fileName) {
          fileName.textContent = 'Ningún archivo seleccionado';
        }
      }
    });
  }
  
  // Validar tamaño y formato de imagen antes de enviar
  const factionForm = document.querySelector('.faction-form');
  
  if (factionForm) {
    factionForm.addEventListener('submit', function(event) {
      if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const fileSize = file.size / 1024 / 1024; // tamaño en MB
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        // Validar tamaño (2MB máximo)
        if (fileSize > 2) {
          event.preventDefault();
          alert('El archivo es demasiado grande. El tamaño máximo permitido es 2MB.');
          return;
        }
        
        // Validar formato
        if (!allowedExtensions.includes(fileExtension)) {
          event.preventDefault();
          alert('Formato de archivo no válido. Los formatos permitidos son: ' + allowedExtensions.join(', '));
          return;
        }
      }
    });
  }
});