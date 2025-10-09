export default function initWysiwygEditor() {
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
      // Plugins y configuración existente
      plugins: ['autolink', 'lists', 'link', 'visualblocks', 'code'],
      toolbar: 'bold italic underline | h2 h3 h4 h5 | bullist numlist outdent indent | link dice_image | removeformat code',
      
      content_style: `
        img[src*="dices/"] {
          width: 24px;
          height: 24px;
          vertical-align: middle;
        }
      `,

      // Importante: asegurar la sincronización automática con el textarea
      auto_focus: editor.id,
      setup: function(editor) {
        // Registrar un evento para actualizar el textarea cuando el contenido cambia
        editor.on('change', function() {
          editor.save(); // Guardar contenido en el textarea
        });
        
        // También sincronizar al perder el foco
        editor.on('blur', function() {
          editor.save();
        });
        
        // Añadir un botón personalizado para dados
        editor.ui.registry.addButton('dice_image', {
          icon: 'image',
          tooltip: 'Insertar dado',
          onAction: function() {
            showDiceImageDialog(editor);
          }
        });
      },

      // Configuración para el tema oscuro/claro
      skin: localStorage.getItem('theme') === 'dark' ? 'oxide-dark' : 'oxide',
      content_css: localStorage.getItem('theme') === 'dark' ? 'dark' : 'default',
      license_key: 'gpl'
    });
  });
  
  // Sincronizar todos los editores antes del envío del formulario
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      // Asegurarse de que todos los editores TinyMCE sincronicen su contenido
      if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
      }
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
        // editor.insertContent(`<img src="${item.dataset.url}" alt="${item.querySelector('span').textContent}" style="width: 24px; height: 24px;">`);
        editor.insertContent(`<img src="${item.dataset.url}" alt="${item.querySelector('span').textContent}">`);
        document.body.removeChild(dialog);
        
        // Asegurarse de guardar el contenido después de insertar la imagen
        editor.save();
      });
    });
  }
}