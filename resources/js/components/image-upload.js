export default function initImageUpload() {
  // Seleccionar todos los componentes de carga de imagen
  const imageUploads = document.querySelectorAll('.image-upload');
  
  if (!imageUploads.length) return;
  
  imageUploads.forEach(upload => {
    const input = upload.querySelector('.image-upload__input');
    const previewContainer = upload.querySelector('.image-upload__preview-container');
    const preview = upload.querySelector('.image-upload__preview');
    const removeBtn = upload.querySelector('.image-upload__remove-btn');
    const removeFlag = upload.querySelector('.image-upload__remove-flag');
    
    // Configurar evento para mostrar vista previa al seleccionar archivo
    if (input) {
      input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const file = this.files[0];
          
          // Crear URL para la vista previa
          const imageURL = URL.createObjectURL(file);
          
          // Actualizar imagen y mostrar contenedor
          if (preview) {
            preview.src = imageURL;
          }
          
          if (previewContainer) {
            previewContainer.style.display = 'flex';
          }
          
          // Marcar como que tiene imagen
          upload.classList.add('has-image');
          
          // Resetear flag de eliminación
          if (removeFlag) {
            removeFlag.value = '0';
          }
        }
      });
    }
    
    // Configurar evento para el botón de eliminar
    if (removeBtn && removeFlag) {
      removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Ocultar la vista previa
        if (previewContainer) {
          previewContainer.style.display = 'none';
        }
        
        // Quitar clase has-image
        upload.classList.remove('has-image');
        
        // Limpiar input de archivo
        if (input) {
          input.value = '';
        }
        
        // Marcar para eliminar en el servidor
        if (removeFlag) {
          removeFlag.value = '1';
        }
      });
    }
  });
}