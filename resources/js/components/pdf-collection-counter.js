/**
 * PDF Collection Counter
 * Manages the collection counter in the header
 */

class PdfCollectionCounter {
  constructor() {
    this.counter = document.querySelector('.collection-counter');
    if (!this.counter) return;
    
    this.init();
  }
  
  init() {
    // Listen for counter updates
    document.addEventListener('collection:countUpdated', (event) => {
      const count = event.detail.count || 0;
      this.updateCounter(count);
    });
    
    // Check initial status on page load
    this.checkInitialStatus();
  }
  
  updateCounter(count) {
    this.counter.textContent = count;
    this.counter.dataset.collectionCount = count;
    
    // Show/hide based on count
    if (count > 0) {
      this.counter.style.display = '';
    } else {
      this.counter.style.display = 'none';
    }
  }
  
  async checkInitialStatus() {
    try {
      const response = await fetch('/pdf-collection/status', {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        }
      });
      
      const data = await response.json();
      const count = data.totalCards || data.count || 0;
      this.updateCounter(count);
    } catch (error) {
      console.error('Error checking collection status:', error);
    }
  }
}

export default function initPdfCollectionCounter() {
  new PdfCollectionCounter();
}