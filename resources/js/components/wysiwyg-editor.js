// resources/js/components/wysiwyg-editor.js
export default function initWysiwygEditor() {
  // Verificar si el script de TinyMCE está cargado
  if (typeof tinymce === 'undefined') {
    console.error('TinyMCE not loaded');
    return;
  }
  
  // Encontrar todos los editores WYSIWYG en la página
  const editors = document.querySelectorAll('.wysiwyg-editor');
  if (!editors.length) return;
    
  // Inicializar cada editor
  editors.forEach(editor => {
    const uploadUrl = editor.dataset.uploadUrl || '';
    const imagesUrl = editor.dataset.imagesUrl || '';
    
    // Inicializar TinyMCE
    tinymce.init({
      selector: `#${editor.id}`,
      height: 300,
      menubar: false,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help'
      ],
      toolbar: 'undo redo | formatselect | ' +
        'bold italic underline | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'link image | removeformat code',
      content_css: [],
      // Configuración para subir imágenes
      images_upload_url: uploadUrl,
      automatic_uploads: true,
      file_picker_types: 'image',
      // Selector de imágenes personalizadas
      file_picker_callback: function(cb, value, meta) {
        if (meta.filetype === 'image' && imagesUrl) {
          // Hacer una solicitud AJAX para obtener las imágenes disponibles
          fetch(imagesUrl)
            .then(response => response.json())
            .then(data => {
              // Mostrar un diálogo de selección de imágenes
              showImagePickerDialog(data, cb);
            })
            .catch(error => console.error('Error loading images:', error));
        }
      },
      // Configuración para el tema oscuro/claro
      skin: localStorage.getItem('theme') === 'dark' ? 'oxide-dark' : 'oxide',
      content_css: localStorage.getItem('theme') === 'dark' ? 'dark' : 'default',

      license_key: 'gpl'
    });
  });
  
  // Función para mostrar el diálogo de selección de imágenes
  function showImagePickerDialog(images, callback) {
    // Crear un diálogo modal para seleccionar imágenes
    const dialog = document.createElement('div');
    dialog.className = 'image-picker-dialog';
    dialog.innerHTML = `
      <div class="image-picker-dialog__content">
        <div class="image-picker-dialog__header">
          <h3>Seleccionar imagen</h3>
          <button type="button" class="image-picker-dialog__close">&times;</button>
        </div>
        <div class="image-picker-dialog__body">
          <div class="image-picker-dialog__images">
            ${images.map(image => `
              <div class="image-picker-dialog__item" data-url="${image.url}">
                <img src="${image.url}" alt="${image.title}" title="${image.title}">
                <span>${image.title}</span>
              </div>
            `).join('')}
          </div>
          <div class="image-picker-dialog__upload">
            <h4>Subir nueva imagen</h4>
            <input type="file" class="image-picker-dialog__file" accept="image/*">
            <button type="button" class="btn btn--primary image-picker-dialog__upload-btn">Subir</button>
          </div>
        </div>
      </div>
    `;
    
    document.body.appendChild(dialog);
    
    // Manejar el cierre del diálogo
    const closeBtn = dialog.querySelector('.image-picker-dialog__close');
    closeBtn.addEventListener('click', () => {
      document.body.removeChild(dialog);
    });
    
    // Manejar la selección de una imagen existente
    const imageItems = dialog.querySelectorAll('.image-picker-dialog__item');
    imageItems.forEach(item => {
      item.addEventListener('click', () => {
        callback(item.dataset.url, { title: item.querySelector('span').textContent });
        document.body.removeChild(dialog);
      });
    });
    
    // Manejar la subida de una nueva imagen
    const fileInput = dialog.querySelector('.image-picker-dialog__file');
    const uploadBtn = dialog.querySelector('.image-picker-dialog__upload-btn');
    
    uploadBtn.addEventListener('click', () => {
      if (!fileInput.files || !fileInput.files[0]) {
        return;
      }
      
      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('image', file);
      
      // Obtener el token CSRF
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      fetch(uploadUrl, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': token
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.url) {
            callback(data.url, { title: data.title || file.name });
            document.body.removeChild(dialog);
          }
        })
        .catch(error => console.error('Error uploading image:', error));
    });
  }
}