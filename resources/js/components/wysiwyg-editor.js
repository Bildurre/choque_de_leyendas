 /**
 * Initialize TinyMCE WYSIWYG editors
 */
import 'tinymce';

export function initWysiwygEditors() {
  document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
    const editorId = editor.id;
    if (tinymce.get(editorId)) {
      return;
    }

    const rows = parseInt(editor.getAttribute('rows') || 10);
    const height = rows * 24;

    // Obtener configuración del editor
    let isAdvanced = false;
    const configElements = document.querySelectorAll(`script[id$="-config"]`);
    configElements.forEach(element => {
      if (element.id.startsWith(editorId)) {
        try {
          const config = JSON.parse(element.textContent);
          if (config.advanced) {
            isAdvanced = true;
          }
        } catch (e) {
          console.error('Error parsing editor config:', e);
        }
      }
    });
    
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
    
    // Configuración base según si es avanzado o simple
    const plugins = isAdvanced 
      ? 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount'
      : 'autolink link image code';
      
    const toolbar = isAdvanced
      ? 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | help'
      : 'bold italic | image | code';

    // Initialize TinyMCE
    tinymce.init({
      selector: `#${editorId}`,
      height: height,
      menubar: isAdvanced,
      statusbar: isAdvanced,
      plugins: plugins,
      toolbar: toolbar,

      content_style: `
        body { 
          font-family: 'Roboto', sans-serif; 
          font-size: 14px; 
          color: #e0e0e0; 
          background-color: #333333;
          padding: 10px;
        }
        p { margin: 0 0 10px 0; }
        h1, h2, h3, h4, h5, h6 { color: #ffffff; }
        a { color: #9575cd; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid #4a4a4a; padding: 5px; }
      `,

      skin: 'oxide-dark',
      content_css: 'dark',

      image_list: imageList.length > 0 ? imageList : null,

      suffix: '.min',
      base_url: '/build/tinymce',
      skin_url: '/build/tinymce/skins/ui/oxide-dark',
      content_css: '/build/tinymce/skins/content/dark/content.min.css',
      icons_url: '/build/tinymce/icons/default/icons.min.js',

      branding: false,
      promotion: false,

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