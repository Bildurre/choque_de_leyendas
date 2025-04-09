import 'tinymce/tinymce';
import 'tinymce/skins/content/default/content.js';
import 'tinymce/skins/ui/oxide/content.js';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';
import 'tinymce/icons/default/icons';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';

// Import required plugins
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';

/**
 * Initialize TinyMCE WYSIWYG editors
 */
export function initWysiwygEditors() {
  document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
    const editorId = editor.id;
    const rows = parseInt(editor.getAttribute('rows') || 10);
    const height = rows * 24; // Ajustado para ser más realista
    
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
      skin_url: 'default',
      content_css: 'default',
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | image | help',
      // content_style: 'body { font-family:Roboto,Arial,sans-serif; font-size:14px; color:#e0e0e0; background-color:#333333; }',
      image_list: imageList.length > 0 ? imageList : null,
      image_advtab: true,
      branding: false,
      promotion: false,
      // Agregar configuración para el path de los recursos estáticos
      base_url: '/tinymce',
      suffix: '.min',
      license_key: 'gpl'
    });
  });
}