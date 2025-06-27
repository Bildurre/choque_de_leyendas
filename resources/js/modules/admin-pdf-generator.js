// Handles PDF generation for admin
export class PdfGenerator {
  constructor() {
    this.activeRequests = new Map();
  }
  
  init() {
    this.bindEvents();
  }
  
  bindEvents() {
    // Individual entity generation
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.generate-pdf-btn');
      if (btn) {
        e.preventDefault();
        this.generateEntityPdf(btn);
      }
    });
    
    // Generate all buttons
    document.addEventListener('click', (e) => {
      if (e.target.closest('.generate-all-factions')) {
        e.preventDefault();
        this.generateAllFactions(e.target.closest('.generate-all-factions'));
      }
      
      if (e.target.closest('.generate-all-decks')) {
        e.preventDefault();
        this.generateAllDecks(e.target.closest('.generate-all-decks'));
      }
    });
    
    // Custom PDF generation
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.generate-custom-pdf');
      if (btn) {
        e.preventDefault();
        this.generateCustomPdf(btn);
      }
    });
  }
  
  getOptions() {
    return {
      reduce_heroes: document.getElementById('pdf-reduce-heroes')?.checked || false,
      with_gap: document.getElementById('pdf-with-gap')?.checked || true
    };
  }
  
  async generateEntityPdf(button) {
    const entityId = button.dataset.entityId;
    const entityType = button.dataset.entityType;
    const url = button.dataset.url;
    
    // Prevent duplicate requests
    const key = `${entityType}-${entityId}`;
    if (this.activeRequests.has(key)) {
      return;
    }
    
    this.activeRequests.set(key, true);
    
    // Get card element
    const card = button.closest('.pdf-entity-card');
    this.showGenerating(card);
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(this.getOptions())
      });
      
      const data = await response.json();
      
      if (data.success && data.jobId) {
        // Start polling for job status
        this.pollJobStatus(data.jobId, card);
      } else {
        this.hideStatus(card);
        window.showNotification(data.message || 'Error generating PDF', 'error');
      }
    } catch (error) {
      console.error('Error generating PDF:', error);
      this.hideStatus(card);
      window.showNotification('Error generating PDF', 'error');
    } finally {
      this.activeRequests.delete(key);
    }
  }
  
  async pollJobStatus(jobId, card) {
    const maxAttempts = 60;
    let attempts = 0;
    
    const interval = setInterval(async () => {
      attempts++;
      
      try {
        const response = await fetch(`/admin/pdf-export/status/${jobId}`);
        const data = await response.json();
        
        if (data.status === 'completed' && data.pdf) {
          clearInterval(interval);
          this.showSuccess(card);
          window.showNotification('PDF generated successfully', 'success');
        } else if (data.status === 'failed' || attempts >= maxAttempts) {
          clearInterval(interval);
          this.hideStatus(card);
          window.showNotification('PDF generation failed or timed out', 'error');
        }
      } catch (error) {
        clearInterval(interval);
        this.hideStatus(card);
        window.showNotification('Error checking PDF status', 'error');
      }
    }, 1000);
  }
  
  async generateAllFactions(button) {
    if (!confirm('Generate PDFs for all factions? This may take several minutes.')) {
      return;
    }
    
    await this.generateBatch(button, button.dataset.url, 'factions');
  }
  
  async generateAllDecks(button) {
    if (!confirm('Generate PDFs for all decks? This may take several minutes.')) {
      return;
    }
    
    await this.generateBatch(button, button.dataset.url, 'decks');
  }
  
  async generateBatch(button, url, type) {
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
      <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
        <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor" opacity="0.75"></path>
      </svg>
      Queueing...
    `;
    
    // Show all cards as generating
    const cards = document.querySelectorAll(`.pdf-entity-card[data-entity-type="${type.slice(0, -1)}"]`);
    cards.forEach(card => {
      this.showGenerating(card);
    });
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(this.getOptions())
      });
      
      const data = await response.json();
      
      if (data.success && data.jobIds) {
        window.showNotification(data.message, 'success');
        
        // Start polling for all jobs
        this.pollBatchJobs(data.jobIds, cards);
      } else {
        // Hide status on all cards
        cards.forEach(card => {
          this.hideStatus(card);
        });
        
        window.showNotification(data.message || 'Error generating PDFs', 'error');
      }
    } catch (error) {
      console.error('Error generating batch:', error);
      
      cards.forEach(card => {
        this.hideStatus(card);
      });
      
      window.showNotification('Error generating PDFs', 'error');
    } finally {
      button.disabled = false;
      button.innerHTML = originalContent;
    }
  }
  
  async pollBatchJobs(jobIds, cards) {
    const maxAttempts = 120; // 2 minutes for batch
    let attempts = 0;
    const completedJobs = new Set();
    
    const interval = setInterval(async () => {
      attempts++;
      
      // Check all jobs
      for (let i = 0; i < jobIds.length; i++) {
        const jobId = jobIds[i];
        
        if (completedJobs.has(jobId)) {
          continue;
        }
        
        try {
          const response = await fetch(`/admin/pdf-export/status/${jobId}`);
          const data = await response.json();
          
          if (data.status === 'completed') {
            completedJobs.add(jobId);
            if (cards[i]) {
              this.showSuccess(cards[i]);
            }
          } else if (data.status === 'failed') {
            completedJobs.add(jobId);
            if (cards[i]) {
              this.hideStatus(cards[i]);
            }
          }
        } catch (error) {
          console.error('Error checking job status:', error);
        }
      }
      
      // Check if all jobs are completed or timeout
      if (completedJobs.size === jobIds.length || attempts >= maxAttempts) {
        clearInterval(interval);
        
        if (completedJobs.size === jobIds.length) {
          window.showNotification('All PDFs generated successfully', 'success');
        } else {
          window.showNotification('Some PDFs failed to generate or timed out', 'warning');
        }
      }
    }, 2000); // Check every 2 seconds for batch
  }
  
  async generateCustomPdf(button) {
    const template = button.dataset.template;
    const url = button.dataset.url;
    
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
      <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
        <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor" opacity="0.75"></path>
      </svg>
      Generating...
    `;
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          template: template,
          options: this.getOptions()
        })
      });
      
      const data = await response.json();
      
      if (data.success && data.jobId) {
        window.showNotification(data.message || 'PDF generation started', 'success');
        
        // Poll for custom PDF completion
        this.pollCustomJob(data.jobId, button, originalContent);
      } else {
        button.disabled = false;
        button.innerHTML = originalContent;
        window.showNotification(data.message || 'Error generating PDF', 'error');
      }
    } catch (error) {
      console.error('Error generating custom PDF:', error);
      button.disabled = false;
      button.innerHTML = originalContent;
      window.showNotification('Error generating PDF', 'error');
    }
  }
  
  async pollCustomJob(jobId, button, originalContent) {
    const maxAttempts = 60;
    let attempts = 0;
    
    const interval = setInterval(async () => {
      attempts++;
      
      try {
        const response = await fetch(`/admin/pdf-export/status/${jobId}`);
        const data = await response.json();
        
        if (data.status === 'completed' && data.pdf) {
          clearInterval(interval);
          button.disabled = false;
          button.innerHTML = originalContent;
          window.showNotification('PDF generated successfully', 'success');
          
          // Reload to show new PDF
          setTimeout(() => window.location.reload(), 1500);
        } else if (data.status === 'failed' || attempts >= maxAttempts) {
          clearInterval(interval);
          button.disabled = false;
          button.innerHTML = originalContent;
          window.showNotification('PDF generation failed or timed out', 'error');
        }
      } catch (error) {
        clearInterval(interval);
        button.disabled = false;
        button.innerHTML = originalContent;
        window.showNotification('Error checking PDF status', 'error');
      }
    }, 1000);
  }
  
  async deletePdf(pdfId, url) {
    if (!confirm('Delete this PDF? This action cannot be undone.')) {
      return;
    }
    
    const card = document.querySelector(`.admin-pdf-card[data-pdf-id="${pdfId}"]`);
    if (card) {
      card.classList.add('admin-pdf-card--deleting');
    }
    
    try {
      const response = await fetch(url, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        }
      });
      
      if (response.redirected) {
        window.location.href = response.url;
        return;
      }
      
      const data = await response.json();
      
      if (data.success || response.ok) {
        if (card) {
          card.style.opacity = '0';
          card.style.transform = 'scale(0.9)';
          setTimeout(() => card.remove(), 300);
        }
        
        window.showNotification('PDF deleted successfully', 'success');
      } else {
        if (card) {
          card.classList.remove('admin-pdf-card--deleting');
        }
        window.showNotification(data.message || 'Error deleting PDF', 'error');
      }
    } catch (error) {
      console.error('Error deleting PDF:', error);
      if (card) {
        card.classList.remove('admin-pdf-card--deleting');
      }
      window.showNotification('Error deleting PDF', 'error');
    }
  }
  
  showGenerating(card) {
    const status = card.querySelector('.pdf-entity-card__status');
    const actions = card.querySelector('.pdf-entity-card__actions');
    
    if (status) status.style.display = 'flex';
    if (actions) actions.style.display = 'none';
  }
  
  showSuccess(card) {
    const status = card.querySelector('.pdf-entity-card__status');
    const statusText = card.querySelector('.pdf-entity-card__status-text');
    const spinner = card.querySelector('.pdf-entity-card__spinner');
    
    if (spinner) spinner.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>';
    if (statusText) statusText.textContent = 'Generated!';
    
    setTimeout(() => this.hideStatus(card), 2000);
  }
  
  hideStatus(card) {
    const status = card.querySelector('.pdf-entity-card__status');
    const actions = card.querySelector('.pdf-entity-card__actions');
    
    if (status) status.style.display = 'none';
    if (actions) actions.style.display = 'flex';
  }
}