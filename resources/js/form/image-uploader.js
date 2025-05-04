/**
 * Image Uploader Component
 * Handles drag and drop, file upload preview, and image removal
 */
export function initImageUploaders() {
  // Select all image uploaders that haven't been initialized yet
  const uploaders = document.querySelectorAll('.image-uploader:not([data-initialized])');
  
  if (uploaders.length === 0) {
    return;
  }
  
  uploaders.forEach(uploader => {
    setupImageUploader(uploader);
    uploader.setAttribute('data-initialized', 'true');
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
  
  // Verify all required elements are present
  if (!dropzone || !input || !preview || !placeholder) {
    return;
  }
  
  // Drag and drop events
  dropzone.addEventListener('dragover', handleDragOver);
  dropzone.addEventListener('dragleave', handleDragLeave);
  dropzone.addEventListener('drop', handleDrop);
  
  // File input change
  input.addEventListener('change', handleFileChange);
  
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
      preview.src = e.target.result;
      preview.classList.add('active');
      placeholder.classList.add('hidden');
      
      if (removeBtn) {
        removeBtn.classList.remove('hidden');
      }
    };
    reader.readAsDataURL(file);
  }
  
  function handleRemove() {
    // Clear input
    input.value = '';
    
    // Reset preview
    preview.src = '';
    preview.classList.remove('active');
    placeholder.classList.remove('hidden');
    
    if (removeBtn) {
      removeBtn.classList.add('hidden');
    }
    
    // Set remove flag
    if (removeInputField) {
      removeInputField.value = "1";
    }
  }
}

// Add global event handlers for dynamic content
document.addEventListener('contentLoaded', initImageUploaders);
document.addEventListener('DOMContentLoaded', initImageUploaders);