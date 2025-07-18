// resources/js/components/pdf-collection.js
import initPdfAddButton from './pdf-add-button';
import initPdfCollectionController from './pdf-collection-controller';
import initPdfCollectionCounter from './pdf-collection-counter';

export default function initPdfCollection() {
  // Inicializar el contador del header
  initPdfCollectionCounter();
  
  // Inicializar botones de añadir
  const addButtons = document.querySelectorAll('[data-collection-add]');
  if (addButtons.length) {
    initPdfAddButton();
  }
  
  // Inicializar controlador de la página de colección
  const pdfCollection = document.querySelector('[data-collection-content]');
  if (pdfCollection) {
    initPdfCollectionController();
  }
  
  // Listener global para añadir elementos
  document.addEventListener('collection:add', async (event) => {
    try {
      const response = await fetch('/pdf-collection/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(event.detail)
      });
      
      const result = await response.json();
      
      if (result.success) {
        // Mostrar notificación usando el sistema existente
        if (window.showNotification) {
          window.showNotification(result.message, 'success');
        }
        
        // Actualizar el contador del header
        document.dispatchEvent(new CustomEvent('collection:countUpdated', {
          detail: { count: result.totalCards || result.count || 0 }
        }));
        
        // Si estamos en la página de colección, actualizar
        if (document.querySelector('[data-collection-content]')) {
          document.dispatchEvent(new CustomEvent('collection:updated'));
        }
      } else {
        if (window.showNotification) {
          window.showNotification(result.message || 'Error al añadir a la colección', 'error');
        }
      }
    } catch (error) {
      console.error('Error adding to collection:', error);
      if (window.showNotification) {
        window.showNotification('Error al añadir a la colección', 'error');
      }
    }
  });
}