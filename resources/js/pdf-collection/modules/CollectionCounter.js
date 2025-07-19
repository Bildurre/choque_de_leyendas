// resources/js/components/pdf-collection/modules/CollectionCounter.js
export default class CollectionCounter {
  constructor(apiService) {
    this.apiService = apiService;
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
    
    // Check initial status
    this.checkInitialStatus();
  }
  
  updateCounter(count) {
    this.counter.textContent = count;
    this.counter.dataset.collectionCount = count;
    
    // Show/hide based on count
    this.counter.style.display = count > 0 ? '' : 'none';
  }
  
  async checkInitialStatus() {
    try {
      const data = await this.apiService.getCollectionStatus();
      const count = data.totalCards || data.count || 0;
      this.updateCounter(count);
    } catch (error) {
      console.error('Error checking collection status:', error);
    }
  }
}