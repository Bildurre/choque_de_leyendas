/**
 * Image Uploader Component
 * Handles drag and drop, file upload preview, and image removal
 */
export function initImageUploaders() {
  document.querySelectorAll('.image-uploader').forEach(uploader => {
    setupImageUploader(uploader);
  });
}

/**
 * Setup a single image uploader component
 * @param {HTMLElement} uploader - The uploader container
 */
function setupImageUploader(uploader) {
  const id = uploader.id.replace('-uploader', '');
  const dropzone = uploader.querySelector('.dropzone');
  const input = uploader.querySelector(`#${id}`);
  const removeBtn = uploader.querySelector(`#${id}-remove`);
  const preview = uploader.querySelector(`#${id}-preview`);
  const placeholder = uploader.querySelector('.uploader-placeholder');
  const removeInputField = document.querySelector(`#remove_${id}`);
  
  // Drag and drop events
  if (dropzone) {
    dropzone.addEventListener('dragover', handleDragOver);
    dropzone.addEventListener('dragleave', handleDragLeave);
    dropzone.addEventListener('drop', handleDrop);
  }
  
  // File input change
  if (input) {
    input.addEventListener('change', handleFileChange);
  }
  
  // Remove button click
  if (removeBtn) {
    removeBtn.addEventListener('click', handleRemove);
  }
  
  function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    dropzone.classList.add('drag-over');
  }
  
  function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    dropzone.classList.remove('drag-over');
  }
  
  function handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    dropzone.classList.remove('drag-over');
    
    const files = event.dataTransfer.files;
    if (files.length) {
      input.files = files;
      previewFile(files[0]);
      
      // Reset remove flag
      if (removeInputField) {
        removeInputField.value = "0";
      }
    }
  }
  
  function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
      previewFile(file);
      
      // Reset remove flag
      if (removeInputField) {
        removeInputField.value = "0";
      }
    }
  }
  
  function previewFile(file) {
    // Check if file is an image
    if (!file.type.match('image.*')) {
      alert('Solo se permiten imágenes');
      return;
    }
    
    // Check file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
      alert('El tamaño máximo permitido es 2MB');
      input.value = '';
      return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
      if (preview) {
        preview.src = e.target.result;
        preview.classList.add('active');
      }
      
      if (placeholder) {
        placeholder.classList.add('hidden');
      }
      
      if (removeBtn) {
        removeBtn.classList.remove('hidden');
      }
    };
    reader.readAsDataURL(file);
  }
  
  function handleRemove() {
    // Clear input
    if (input) {
      input.value = '';
    }
    
    // Reset preview
    if (preview) {
      preview.src = '';
      preview.classList.remove('active');
    }
    
    if (placeholder) {
      placeholder.classList.remove('hidden');
    }
    
    if (removeBtn) {
      removeBtn.classList.add('hidden');
    }
    
    // Set remove flag
    if (removeInputField) {
      removeInputField.value = "1";
    }
  }
}