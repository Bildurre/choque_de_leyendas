import tinymce from 'tinymce';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
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
 * Initialize TinyMCE WYSIWYG editor for specific textareas
 * @param {string} selector - CSS selector for editor elements
 * @param {number} height - Editor height in pixels
 * @param {Array} imageList - Available images for insertion
 */
export function initTinyMCE(selector, height, imageList = []) {
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
    images_upload_handler: function(blobInfo, success, failure) {
      // In a real implementation, you'd upload to the server
      // Here we just prevent upload but allow insertion from predefined list
      failure('Direct upload not allowed. Please use the predefined images.');
    },
    image_list: imageList,
    image_advtab: true,
    contextmenu: "link image inserttable | cell row column deletetable"
  });
}

/**
 * Initialize all WYSIWYG editors on the page
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
    
    // Initialize TinyMCE for this editor
    initTinyMCE(`#${editorId}`, height, imageList);
  });
}