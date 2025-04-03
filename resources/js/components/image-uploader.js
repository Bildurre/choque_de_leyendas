/**
 * Image Uploader Component
 * Handles drag and drop, file upload preview, and image removal
 */
export default class ImageUploader {
  /**
   * Initialize image uploader components
   */
  static init() {
    document.querySelectorAll('.image-uploader').forEach(uploader => {
      new ImageUploader(uploader);
    });
  }

  /**
   * Constructor for the image uploader
   * @param {HTMLElement} element - The uploader container element
   */
  constructor(element) {
    this.container = element;
    this.id = this.container.id.replace('-uploader', '');
    this.dropzone = this.container.querySelector('.dropzone');
    this.input = this.container.querySelector(`#${this.id}`);
    this.removeBtn = this.container.querySelector(`#${this.id}-remove`);
    this.preview = this.container.querySelector(`#${this.id}-preview`);
    this.placeholder = this.container.querySelector('.uploader-placeholder');
    this.removeInputField = document.querySelector(`#remove_${this.id}`);
    
    this.setupListeners();
  }

  /**
   * Setup event listeners for the uploader
   */
  setupListeners() {
    // Drag and drop events
    this.dropzone.addEventListener('dragover', this.handleDragOver.bind(this));
    this.dropzone.addEventListener('dragleave', this.handleDragLeave.bind(this));
    this.dropzone.addEventListener('drop', this.handleDrop.bind(this));
    
    // File input change
    this.input.addEventListener('change', this.handleFileChange.bind(this));
    
    // Remove button click
    if (this.removeBtn) {
      this.removeBtn.addEventListener('click', this.handleRemove.bind(this));
    }
  }

  /**
   * Handle file selection
   * @param {Event} event - The change event
   */
  handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
      this.previewFile(file);
      
      // Reset remove flag if a new file is selected
      if (this.removeInputField) {
        this.removeInputField.value = "0";
      }
    }
  }

  /**
   * Handle dragover event
   * @param {DragEvent} event - The dragover event
   */
  handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    this.dropzone.classList.add('drag-over');
  }

  /**
   * Handle dragleave event
   * @param {DragEvent} event - The dragleave event
   */
  handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    this.dropzone.classList.remove('drag-over');
  }

  /**
   * Handle drop event
   * @param {DragEvent} event - The drop event
   */
  handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    this.dropzone.classList.remove('drag-over');
    
    // Get the dropped files
    const files = event.dataTransfer.files;
    if (files.length) {
      this.input.files = files;
      this.previewFile(files[0]);
      
      // Reset remove flag if a new file is dropped
      if (this.removeInputField) {
        this.removeInputField.value = "0";
      }
    }
  }

  /**
   * Preview the selected file
   * @param {File} file - The file to preview
   */
  previewFile(file) {
    // Check if file is an image
    if (!file.type.match('image.*')) {
      alert('Solo se permiten imágenes');
      return;
    }
    
    // Check file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
      alert('El tamaño máximo permitido es 2MB');
      this.input.value = '';
      return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
      this.preview.src = e.target.result;
      this.preview.classList.add('active');
      this.placeholder.classList.add('hidden');
      this.removeBtn.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }

  /**
   * Handle image removal
   */
  handleRemove() {
    // Clear the file input
    this.input.value = '';
    
    // Reset the preview
    this.preview.src = '';
    this.preview.classList.remove('active');
    this.placeholder.classList.remove('hidden');
    this.removeBtn.classList.add('hidden');
    
    // Set the remove flag to true
    if (this.removeInputField) {
      this.removeInputField.value = "1";
    }
  }
}