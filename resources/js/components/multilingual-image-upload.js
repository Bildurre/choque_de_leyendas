export default function initMultilingualImageUpload() {
  // Select all multilingual image upload components
  const multilingualFields = document.querySelectorAll('.form-field--multilingual');
  
  multilingualFields.forEach(field => {
    // Check if this field contains image uploads
    const imageUploads = field.querySelectorAll('.image-upload');
    
    if (!imageUploads.length) return;
    
    imageUploads.forEach(upload => {
      const locale = upload.dataset.locale;
      const input = upload.querySelector('.image-upload__input');
      const previewContainer = upload.querySelector('.image-upload__preview-container');
      const preview = upload.querySelector('.image-upload__preview');
      const removeBtn = upload.querySelector('.image-upload__remove-btn');
      const removeFlag = upload.querySelector('.image-upload__remove-flag');
      const dropzone = upload.querySelector('.image-upload__dropzone');
      
      // Configure preview on file selection
      if (input) {
        input.addEventListener('change', function() {
          handleFileSelect(this.files, upload, preview, previewContainer, removeFlag);
        });
      }
      
      // Configure remove button
      if (removeBtn && removeFlag) {
        removeBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          // Hide preview
          if (previewContainer) {
            previewContainer.style.display = 'none';
          }
          
          // Remove has-image class
          upload.classList.remove('has-image');
          
          // Clear file input
          if (input) {
            input.value = '';
          }
          
          // Mark for deletion on server
          removeFlag.value = '1';
        });
      }
      
      // Configure drag and drop
      if (dropzone) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
          dropzone.addEventListener(eventName, preventDefaults, false);
          document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
          dropzone.addEventListener(eventName, () => {
            upload.classList.add('image-upload--drag-over');
          }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
          dropzone.addEventListener(eventName, () => {
            upload.classList.remove('image-upload--drag-over');
          }, false);
        });
        
        // Handle dropped files
        dropzone.addEventListener('drop', (e) => {
          const files = e.dataTransfer.files;
          
          // Only process if there's exactly one file and it's an image
          if (files.length === 1 && files[0].type.startsWith('image/')) {
            // Update the file input
            if (input) {
              // Create a new FileList containing the dropped file
              const dataTransfer = new DataTransfer();
              dataTransfer.items.add(files[0]);
              input.files = dataTransfer.files;
              
              // Trigger change event to handle preview
              input.dispatchEvent(new Event('change', { bubbles: true }));
            }
          }
        }, false);
        
        // Make the entire dropzone clickable
        dropzone.addEventListener('click', (e) => {
          // Don't trigger if clicking on remove button
          if (e.target.closest('.image-upload__remove-btn')) {
            return;
          }
          
          if (input) {
            input.click();
          }
        });
      }
    });
  });
  
  // Helper function to handle file selection
  function handleFileSelect(files, upload, preview, previewContainer, removeFlag) {
    if (files && files[0]) {
      const file = files[0];
      
      // Validate it's an image
      if (!file.type.startsWith('image/')) {
        alert('Please select an image file');
        return;
      }
      
      // Create URL for preview
      const imageURL = URL.createObjectURL(file);
      
      // Update image and show container
      if (preview) {
        preview.src = imageURL;
        
        // Clean up the URL when image loads
        preview.onload = () => {
          URL.revokeObjectURL(imageURL);
        };
      }
      
      if (previewContainer) {
        previewContainer.style.display = 'flex';
      }
      
      // Mark as having image
      upload.classList.add('has-image');
      
      // Reset deletion flag
      if (removeFlag) {
        removeFlag.value = '0';
      }
    }
  }
  
  // Helper function to prevent default drag behaviors
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
}