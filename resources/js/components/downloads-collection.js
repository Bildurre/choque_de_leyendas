// Main module that coordinates all downloads and collection functionality
import { CollectionManager } from '../modules/collection-manager.js';
import { DownloadsManager } from '../modules/downloads-manager.js';
import { PdfGenerator } from '../modules/pdf-generator.js';
import { CollectionUI } from '../modules/collection-ui.js';
import { NotificationManager } from '../modules/notification-manager.js';

export default function initDownloadsCollection() {
  // Initialize managers
  const notificationManager = new NotificationManager();
  const collectionManager = new CollectionManager();
  const downloadsManager = new DownloadsManager();
  const pdfGenerator = new PdfGenerator(notificationManager);
  const collectionUI = new CollectionUI(collectionManager, notificationManager);
  
  // Initialize all components
  collectionManager.init();
  downloadsManager.init();
  collectionUI.init();
  
  // Set up PDF generator with collection manager
  pdfGenerator.setCollectionManager(collectionManager);
  
  // Update counter on page load
  collectionUI.updateCounter();
  
  // Global event listeners
  document.addEventListener('click', function(e) {
    // Handle add to collection from other pages
    const addBtn = e.target.closest('[data-collection-add]');
    if (addBtn && addBtn.dataset.entityType && addBtn.dataset.entityId) {
      e.preventDefault();
      collectionManager.addItem(
        addBtn.dataset.entityType,
        parseInt(addBtn.dataset.entityId)
      );
    }
    
    // Handle PDF deletion
    const deleteBtn = e.target.closest('.download-card__delete');
    if (deleteBtn && deleteBtn.dataset.pdfId) {
      e.preventDefault();
      downloadsManager.deletePdf(deleteBtn.dataset.pdfId);
    }
  });
  
  // Export to window for debugging
  if (window.DEBUG) {
    window.downloadsCollection = {
      collectionManager,
      downloadsManager,
      pdfGenerator,
      collectionUI,
      notificationManager
    };
  }
}