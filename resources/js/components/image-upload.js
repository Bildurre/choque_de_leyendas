export default function initImageUpload() {
  const imageUploads = document.querySelectorAll('.image-upload');
  
  if (!imageUploads.length) return;
  
  imageUploads.forEach(upload => {
    const input = upload.querySelector('.image-upload__input');
    const dropzone = upload.querySelector('.image-upload__dropzone');
    const preview = upload.querySelector('.image-upload__preview');
    const previewImage = upload.querySelector('.image-upload__image');
    const removeButton = upload.querySelector('.image-upload__remove');
    const removeFlag = upload.querySelector('.image-upload__remove-flag');
    
    // Inicializar estado
    if (previewImage && previewImage.getAttribute('src')) {
      showPreview(true);
    }
    
    // Asignar eventos
    setupDragAndDrop();
    setupClickBrowse();
    setupRemoveButton();
    
    function setupDragAndDrop() {
      if (!dropzone) return;
      
      // Prevenir el comportamiento predeterminado para permitir drop
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
      });
      
      // Resaltar drop zone cuando se arrastra sobre ella
      ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
      });
      
      ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
      });
      
      // Manejar el drop
      dropzone.addEventListener('drop', handleDrop, false);
    }
    
    function setupClickBrowse() {
      if (!dropzone || !input) return;
      
      dropzone.addEventListener('click', function(e) {
        // Si ya hay una imagen, no hacer nada
        if (upload.classList.contains('has-image')) return;
        
        // Evitar que se active si se hizo clic en el botón de eliminar
        if (e.target.closest('.image-upload__remove')) return;
        
        input.click();
      });
      
      // Permitir uso de teclado (accesibilidad)
      dropzone.addEventListener('keydown', function(e) {
        // Activar con Enter o Space
        if ((e.key === 'Enter' || e.key === ' ') && !upload.classList.contains('has-image')) {
          e.preventDefault();
          input.click();
        }
      });
      
      input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          handleFiles([this.files[0]]);
        }
      });
    }
    
    function setupRemoveButton() {
      if (!removeButton) return;
      
      removeButton.addEventListener('click', function(e) {
        e.stopPropagation(); // Evitar que el clic llegue al dropzone
        removeImage();
      });
    }
    
    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    function highlight() {
      if (upload.classList.contains('has-image')) return;
      dropzone.classList.add('is-dragover');
    }
    
    function unhighlight() {
      dropzone.classList.remove('is-dragover');
    }
    
    function handleDrop(e) {
      if (upload.classList.contains('has-image')) return;
      
      const dt = e.dataTransfer;
      const files = dt.files;
      
      if (files && files.length) {
        handleFiles(files);
      }
    }
    
    function handleFiles(files) {
      if (files && files[0]) {
        const file = files[0];
        
        // Verificar que sea una imagen
        if (!file.type.match('image.*')) {
          return;
        }
        
        // Crear URL de objeto para la vista previa
        const reader = new FileReader();
        reader.onload = function(e) {
          if (previewImage) {
            previewImage.src = e.target.result;
            showPreview(true);
          }
        };
        reader.readAsDataURL(file);
      }
    }
    
    function showPreview(show) {
      if (show) {
        upload.classList.add('has-image');
        if (preview) preview.style.display = 'flex';
      } else {
        upload.classList.remove('has-image');
        if (preview) preview.style.display = 'none';
      }
    }
    
    function removeImage() {
      if (input) {
        input.value = ''; // Limpiar el input file
      }
      
      if (previewImage) {
        previewImage.src = '';
      }
      
      // Establecer flag para eliminar imagen en el servidor
      if (removeFlag) {
        removeFlag.value = '1';
      }
      
      showPreview(false);
    }
  });
}