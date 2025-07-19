// resources/js/components/pdf-collection/modules/PdfGenerator.js
import PdfListManager from './PdfListManager';
import PdfRenderer from '../utils/PdfRenderer';
import ProgressAnimator from '../utils/ProgressAnimator';

export default class PdfGenerator {
  constructor(apiService, notificationService) {
    this.apiService = apiService;
    this.notificationService = notificationService;
    this.listManager = new PdfListManager();
    this.renderer = new PdfRenderer();
    this.animator = new ProgressAnimator();
    
    this.init();
  }
  
  init() {
    const form = document.getElementById('generate-pdf-form');
    if (!form) return;
    
    form.addEventListener('submit', (e) => this.handleFormSubmit(e));
  }
  
  async handleFormSubmit(e) {
    e.preventDefault();
    
    const { tempId, filename } = this.generateIdentifiers();
    
    // Add item with progress immediately
    const progressItem = this.renderer.createProgressItem(tempId, filename);
    this.listManager.addPdfToList(progressItem);
    
    // Get the item element and start progress
    const itemElement = document.querySelector(`[data-pdf-temp-id="${tempId}"]`);
    const progressBar = itemElement.querySelector('.pdf-progress-inline__bar-fill');
    
    // Start progress animation
    const stopProgress = this.animator.startProgress(progressBar);
    
    try {
      const data = await this.apiService.generatePdf();
      
      stopProgress();
      
      if (data.success) {
        await this.handleSuccess(data, itemElement, filename);
      } else {
        this.handleError(itemElement, data.message);
      }
    } catch (error) {
      stopProgress();
      console.error('Error generating PDF:', error);
      this.handleError(itemElement);
    }
  }
  
  generateIdentifiers() {
    const tempId = `temp-${Date.now()}`;
    const now = new Date();
    const timestamp = this.formatTimestamp(now);
    const locale = document.documentElement.lang || 'es';
    const filename = `${timestamp}_${locale}.pdf`;
    
    return { tempId, filename, now };
  }
  
  formatTimestamp(date) {
    return `${date.getFullYear()}${(date.getMonth() + 1).toString().padStart(2, '0')}${date.getDate().toString().padStart(2, '0')}${date.getHours().toString().padStart(2, '0')}${date.getMinutes().toString().padStart(2, '0')}${date.getSeconds().toString().padStart(2, '0')}`;
  }
  
  async handleSuccess(data, itemElement, filename) {
    const progressBar = itemElement.querySelector('.pdf-progress-inline__bar-fill');
    progressBar.style.width = '100%';
    
    setTimeout(() => {
      const finalItem = this.renderer.createFinalItem({
        id: data.pdf_id,
        display_name: filename,
        download_url: data.download_url,
        view_url: data.view_url,
        created_at: new Date(),
        size: data.size || '---'
      });
      
      itemElement.outerHTML = finalItem;
      
      // Clear collection
      document.dispatchEvent(new CustomEvent('collection:cleared'));
      
      // Show notification
      this.notificationService.success(
        window.translations?.pdf?.collection?.generation_complete || 'PDF generado correctamente'
      );
    }, 500);
  }
  
  handleError(itemElement, message) {
    const progressElement = itemElement.querySelector('.pdf-progress-inline');
    progressElement.innerHTML = `
      <span class="pdf-progress-inline__error">
        ${message || window.translations?.pdf?.collection?.generation_failed || 'Error al generar el PDF'}
      </span>
    `;
    
    setTimeout(() => {
      itemElement.remove();
    }, 3000);
  }
}