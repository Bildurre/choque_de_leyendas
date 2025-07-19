// resources/js/components/pdf-collection/index.js
import CollectionCounter from './modules/CollectionCounter';
import CollectionController from './modules/CollectionController';
import AddButtonHandler from './modules/AddButtonHandler';
import PdfGenerator from './modules/PdfGenerator';
import ApiService from './services/ApiService';
import NotificationService from './services/NotificationService';

export default function initPdfCollection() {
  // Initialize services
  const apiService = new ApiService();
  const notificationService = new NotificationService();
  
  // Initialize counter in header
  new CollectionCounter(apiService);
  
  // Initialize add buttons handler
  const addButtonHandler = new AddButtonHandler();
  
  // Initialize collection controller on collection page
  const collectionElement = document.querySelector('[data-collection-content]');
  if (collectionElement) {
    new CollectionController(apiService, notificationService);
    new PdfGenerator(apiService, notificationService);
  }
  
  // Global event listener for adding items
  document.addEventListener('collection:add', async (event) => {
    try {
      const result = await apiService.addToCollection(event.detail);
      
      if (result.success) {
        notificationService.success(result.message);
        
        // Update counter
        document.dispatchEvent(new CustomEvent('collection:countUpdated', {
          detail: { count: result.totalCards || result.count || 0 }
        }));
        
        // Update collection if on collection page
        if (collectionElement) {
          document.dispatchEvent(new CustomEvent('collection:updated'));
        }
      } else {
        notificationService.error(result.message || 'Error al añadir a la colección');
      }
    } catch (error) {
      console.error('Error adding to collection:', error);
      notificationService.error('Error al añadir a la colección');
    }
  });
}