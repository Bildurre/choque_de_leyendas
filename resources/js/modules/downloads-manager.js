// Manages downloads and PDF deletion
export class DownloadsManager {
  constructor() {
    this.deletingPdfs = new Set();
  }
  
  init() {
    // Any initialization logic
  }
  
  async deletePdf(pdfId) {
    // Prevent duplicate requests
    if (this.deletingPdfs.has(pdfId)) {
      return;
    }
    
    if (!confirm('Delete this PDF? This action cannot be undone.')) {
      return;
    }
    
    this.deletingPdfs.add(pdfId);
    const card = document.querySelector(`.download-card[data-pdf-id="${pdfId}"]`);
    
    if (card) {
      card.classList.add('download-card--deleting');
    }
    
    try {
      const response = await fetch(`/downloads/${pdfId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Animate and remove card
        if (card) {
          card.style.opacity = '0';
          card.style.transform = 'scale(0.9)';
          setTimeout(() => {
            card.remove();
            this.checkEmptyState();
          }, 300);
        }
        
        window.showNotification('PDF deleted successfully', 'success');
      } else {
        if (card) {
          card.classList.remove('download-card--deleting');
        }
        window.showNotification(data.message || 'Error deleting PDF', 'error');
      }
    } catch (error) {
      console.error('Error deleting PDF:', error);
      if (card) {
        card.classList.remove('download-card--deleting');
      }
      window.showNotification('Error deleting PDF', 'error');
    } finally {
      this.deletingPdfs.delete(pdfId);
    }
  }
  
  checkEmptyState() {
    const grid = document.querySelector('.downloads-grid');
    const cards = grid.querySelectorAll('.download-card');
    
    if (cards.length === 0) {
      const emptyHtml = `
        <div class="downloads-empty">
          <svg class="downloads-empty__icon" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12h6m-3-3v6m5.5-1.5L17 14l-3 3-3-3-.5-.5"/>
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2Z"/>
          </svg>
          <p class="downloads-empty__text">No downloads available</p>
        </div>
      `;
      
      grid.innerHTML = emptyHtml;
    }
  }
}