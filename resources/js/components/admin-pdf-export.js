// Main module for admin PDF export functionality
import { PdfGenerator } from '../modules/admin-pdf-generator.js';
import { EntityManager } from '../modules/admin-entity-manager.js';
import { StatisticsManager } from '../modules/admin-statistics-manager.js';

export default function initAdminPdfExport() {
  const pdfGenerator = new PdfGenerator();
  const entityManager = new EntityManager(pdfGenerator);
  const statisticsManager = new StatisticsManager();
  
  // Initialize all managers
  pdfGenerator.init();
  entityManager.init();
  statisticsManager.init();
  
  // Global event listeners
  document.addEventListener('click', function(e) {
    // Delete PDF
    const deleteBtn = e.target.closest('.admin-pdf-card__action--delete');
    if (deleteBtn) {
      e.preventDefault();
      pdfGenerator.deletePdf(
        deleteBtn.dataset.pdfId,
        deleteBtn.dataset.url
      );
    }
  });
}

// Auto-initialize if on PDF export pages
if (document.querySelector('[class*="pdf-export"]')) {
  initAdminPdfExport();
}