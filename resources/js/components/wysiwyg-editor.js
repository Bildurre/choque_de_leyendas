import tinymce from 'tinymce';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
// Import required plugins
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
// ... more imports

/**
 * Initialize TinyMCE WYSIWYG editors
 */
export function initWysiwygEditors() {
  document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
    const editorId = editor.id;
    const rows = parseInt(editor.getAttribute('rows') || 10);
    const height = rows * 50;
    
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
    
    initEditor(`#${editorId}`, height, imageList);
  });
}

/**
 * Initialize a single editor instance
 */
function initEditor(selector, height, imageList = []) {
  tinymce.init({
    selector: selector,
    height: height,
    menubar: false,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
      'bold italic backcolor | alignleft aligncenter ' +
      'alignright alignjustify | bullist numlist outdent indent | ' +
      'removeformat | image | help',
    content_style: 'body { font-family:Roboto,Arial,sans-serif; font-size:14px }',
    image_list: imageList,
    image_advtab: true
  });
}