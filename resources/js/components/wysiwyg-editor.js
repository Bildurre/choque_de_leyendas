export default function initWysiwygEditor() {
  // Verificar si el script de TinyMCE está cargado
  if (typeof tinymce === 'undefined') {
    console.error('TinyMCE not loaded');
    return;
  }
  
  // Definir las imágenes de dados predefinidas (hardcodeadas)
  const predefinedDiceImages = [
    {
      title: 'Dado Rojo',
      url: '/storage/images/dices/dice-red.svg'
    },
    {
      title: 'Dado Verde',
      url: '/storage/images/dices/dice-green.svg'
    },
    {
      title: 'Dado Azul',
      url: '/storage/images/dices/dice-blue.svg'
    },
    {
      title: 'Dado Rojo-Verde',
      url: '/storage/images/dices/dice-red-green.svg'
    },
    {
      title: 'Dado Rojo-Azul',
      url: '/storage/images/dices/dice-red-blue.svg'
    },
    {
      title: 'Dado Verde-Azul',
      url: '/storage/images/dices/dice-green-blue.svg'
    },
    {
      title: 'Dado Rojo-Verde-Azul',
      url: '/storage/images/dices/dice-red-green-blue.svg'
    }
  ];
  
  // Encontrar todos los editores WYSIWYG en la página
  const editors = document.querySelectorAll('.wysiwyg-editor');
  if (!editors.length) return;
    
  // Inicializar cada editor
  editors.forEach(editor => {
    // Inicializar TinyMCE
    tinymce.init({
      selector: `#${editor.id}`,
      height: 300,
      menubar: false,
      // Eliminar el plugin de imagen estándar y añadir nuestro plugin personalizado
      plugins: ['autolink', 'lists', 'link', 'visualblocks', 'code'],
      // Cambiamos 'image' por 'dice_image' en la barra de herramientas
      toolbar: 'bold italic underline | bullist numlist outdent indent | link dice_image | removeformat code',
      content_css: [],
      
      // Configuración para el tema oscuro/claro
      skin: localStorage.getItem('theme') === 'dark' ? 'oxide-dark' : 'oxide',
      content_css: localStorage.getItem('theme') === 'dark' ? 'dark' : 'default',

      // Registrar nuestro botón personalizado
      setup: function(editor) {
        // Añadir un botón personalizado para dados
        editor.ui.registry.addButton('dice_image', {
          icon: 'image',
          tooltip: 'Insertar dado',
          onAction: function() {
            showDiceImageDialog(editor);
          }
        });
      },

      license_key: 'gpl'
    });
  });
  
  // Función para mostrar el diálogo de selección de dados
  function showDiceImageDialog(editor) {
    // Crear el diálogo
    const dialog = document.createElement('div');
    dialog.className = 'image-picker-dialog';
    
    // Construir el HTML con las imágenes predefinidas
    let imagesHtml = '';
    predefinedDiceImages.forEach(image => {
      imagesHtml += `
        <div class="image-picker-dialog__item" data-url="${image.url}">
          <img src="${image.url}" alt="${image.title}" title="${image.title}">
          <span>${image.title}</span>
        </div>
      `;
    });
    
    // Estructura del diálogo
    dialog.innerHTML = `
      <div class="image-picker-dialog__content">
        <div class="image-picker-dialog__header">
          <h3>Seleccionar dado</h3>
          <button type="button" class="image-picker-dialog__close">&times;</button>
        </div>
        <div class="image-picker-dialog__body">
          <div class="image-picker-dialog__images">
            ${imagesHtml}
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
    
    // Manejar la selección de una imagen
    const imageItems = dialog.querySelectorAll('.image-picker-dialog__item');
    imageItems.forEach(item => {
      item.addEventListener('click', () => {
        editor.insertContent(`<img src="${item.dataset.url}" alt="${item.querySelector('span').textContent}" style="width: 24px; height: 24px;">`);
        document.body.removeChild(dialog);
      });
    });
  }
}