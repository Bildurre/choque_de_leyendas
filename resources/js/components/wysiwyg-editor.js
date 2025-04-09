/**
 * Initialize TinyMCE WYSIWYG editors
 */
import 'tinymce';

export function initWysiwygEditors() {
  document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
    const editorId = editor.id;
    const rows = parseInt(editor.getAttribute('rows') || 10);
    const height = rows * 24;
    
    // Get image list if provided
    let imageList = [];
    try {
      const imageListData = document.getElementById(`${editorId}-image-list`);
      if (imageListData) {
        imageList = JSON.parse(imageListData.textContent);
      }
    } catch (e) {
      console.error('Error parsing image list:', e);
    }
    
    // Initialize TinyMCE
    tinymce.init({
      selector: `#${editorId}`,
      height: height,
      menubar: true,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | image | help',
      content_style: 'body { font-family:Roboto,Arial,sans-serif; font-size:14px; color:#e0e0e0; background-color:#333333; }',
      image_list: imageList.length > 0 ? imageList : null,
      image_advtab: true,
      branding: false,
      promotion: false,
      
      // Configuración de ruta para recursos estáticos
      suffix: '.min',
      
      // Configuración específica de rutas
      base_url: '/build/tinymce', // Ruta base corregida
      skin: 'oxide',
      skin_url: '/build/tinymce/skins/ui/oxide',
      content_css: '/build/tinymce/skins/content/default/content.min.css',
      icons_url: '/build/tinymce/icons/default/icons.min.js',

      // License key
      license_key: 'gpl',

      // Populate textarea
      setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
      }
    });
  });
}