// Manages statistics and cleanup actions
export class StatisticsManager {
  constructor() {
    this.isCleaningUp = false;
  }
  
  init() {
    this.bindEvents();
    this.startAutoRefresh();
  }
  
  bindEvents() {
    const cleanupBtn = document.querySelector('.cleanup-temp-pdfs');
    if (cleanupBtn) {
      cleanupBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.cleanupTemporaryPdfs(cleanupBtn);
      });
    }
  }
  
  async cleanupTemporaryPdfs(button) {
    if (this.isCleaningUp) {
      return;
    }
    
    if (!confirm('Clean up all expired temporary PDFs? This will permanently delete expired files.')) {
      return;
    }
    
    this.isCleaningUp = true;
    const originalContent = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = `
      <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
        <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor" opacity="0.75"></path>
      </svg>
      Cleaning up...
    `;
    
    try {
      const response = await fetch(button.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        window.showNotification(data.message || 'Cleanup completed successfully', 'success');
        
        // Reload to update statistics
        setTimeout(() => window.location.reload(), 1500);
      } else {
        window.showNotification(data.message || 'Cleanup failed', 'error');
      }
    } catch (error) {
      console.error('Error during cleanup:', error);
      window.showNotification('Error during cleanup', 'error');
    } finally {
      button.disabled = false;
      button.innerHTML = originalContent;
      this.isCleaningUp = false;
    }
  }
  
  startAutoRefresh() {
    // Auto-refresh statistics every 5 minutes
    setInterval(() => {
      if (document.querySelector('.pdf-statistics')) {
        this.refreshStatistics();
      }
    }, 5 * 60 * 1000);
  }
  
  async refreshStatistics() {
    try {
      const response = await fetch('/admin/pdf-export/statistics', {
        headers: {
          'Accept': 'application/json'
        }
      });
      
      if (response.ok) {
        const data = await response.json();
        this.updateStatisticsUI(data);
      }
    } catch (error) {
      console.error('Error refreshing statistics:', error);
    }
  }
  
  updateStatisticsUI(statistics) {
    // Update permanent PDFs
    const permanentValue = document.querySelector('.pdf-statistics__card:nth-child(1) .pdf-statistics__value');
    const permanentMeta = document.querySelector('.pdf-statistics__card:nth-child(1) .pdf-statistics__meta');
    if (permanentValue) permanentValue.textContent = statistics.permanent.count;
    if (permanentMeta) permanentMeta.textContent = statistics.permanent.formatted_size;
    
    // Update temporary PDFs
    const tempValue = document.querySelector('.pdf-statistics__card:nth-child(2) .pdf-statistics__value');
    const tempMeta = document.querySelector('.pdf-statistics__card:nth-child(2) .pdf-statistics__meta');
    if (tempValue) tempValue.textContent = statistics.temporary.count;
    if (tempMeta) tempMeta.textContent = statistics.temporary.formatted_size;
    
    // Update total
    const totalValue = document.querySelector('.pdf-statistics__card:nth-child(3) .pdf-statistics__value');
    const totalMeta = document.querySelector('.pdf-statistics__card:nth-child(3) .pdf-statistics__meta');
    if (totalValue) totalValue.textContent = statistics.total.count;
    if (totalMeta) totalMeta.textContent = statistics.total.formatted_size;
  }
}