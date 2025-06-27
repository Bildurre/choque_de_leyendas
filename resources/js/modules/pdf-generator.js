// Handles PDF generation and status polling
export class PdfGenerator {
  constructor(notificationManager) {
    this.notificationManager = notificationManager;
    this.collectionManager = null;
    this.isGenerating = false;
    this.currentJobId = null;
  }
  
  setCollectionManager(collectionManager) {
    this.collectionManager = collectionManager;
    this.bindEvents();
  }
  
  bindEvents() {
    document.addEventListener('click', (e) => {
      if (e.target.closest('.collection-generate-pdf')) {
        e.preventDefault();
        this.generatePdf();
      }
    });
  }
  
  async generatePdf() {
    if (this.isGenerating) {
      window.showNotification('A PDF is already being generated', 'warning');
      return;
    }
    
    const reduceHeroesCheckbox = document.getElementById('reduce-heroes');
    const withGapCheckbox = document.getElementById('with-gap');
    const generateBtn = document.querySelector('.collection-generate-pdf');
    
    const reduceHeroes = reduceHeroesCheckbox?.checked || false;
    const withGap = withGapCheckbox?.checked || true;
    
    this.isGenerating = true;
    
    // Update button state
    generateBtn.disabled = true;
    const originalContent = generateBtn.innerHTML;
    generateBtn.innerHTML = `
      <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2v4m0 12v4m8-8h-4M8 12H4"/>
      </svg>
      Generating PDF...
    `;
    
    try {
      const response = await fetch('/collection/generate-pdf', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reduce_heroes: reduceHeroes,
          with_gap: withGap
        })
      });
      
      const data = await response.json();
      
      if (data.success && data.jobId) {
        this.currentJobId = data.jobId;
        this.notificationManager.showPdfGenerating();
        this.pollJobStatus(data.jobId);
      } else {
        this.resetButton(generateBtn, originalContent);
        window.showNotification(data.message || 'Error generating PDF', 'error');
      }
    } catch (error) {
      console.error('Error generating PDF:', error);
      this.resetButton(generateBtn, originalContent);
      window.showNotification('Error generating PDF', 'error');
    }
  }
  
  async pollJobStatus(jobId) {
    const maxAttempts = 60;
    let attempts = 0;
    
    const interval = setInterval(async () => {
      attempts++;
      
      try {
        const response = await fetch(`/collection/status/${jobId}`);
        const data = await response.json();
        
        if (data.status === 'completed' && data.pdf) {
          clearInterval(interval);
          this.handlePdfComplete();
        } else if (data.status === 'failed' || attempts >= maxAttempts) {
          clearInterval(interval);
          this.handlePdfFailed();
        }
      } catch (error) {
        clearInterval(interval);
        this.handlePdfFailed();
      }
    }, 1000);
  }
  
  handlePdfComplete() {
    this.isGenerating = false;
    this.currentJobId = null;
    this.notificationManager.hidePdfGenerating();
    
    const generateBtn = document.querySelector('.collection-generate-pdf');
    if (generateBtn) {
      generateBtn.disabled = false;
      generateBtn.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
        Generate PDF
      `;
    }
    
    // Reload page to show new PDF
    window.location.reload();
  }
  
  handlePdfFailed() {
    this.isGenerating = false;
    this.currentJobId = null;
    this.notificationManager.hidePdfGenerating();
    
    const generateBtn = document.querySelector('.collection-generate-pdf');
    if (generateBtn) {
      this.resetButton(generateBtn, `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
        Generate PDF
      `);
    }
    
    window.showNotification('PDF generation failed or timed out', 'error');
  }
  
  resetButton(button, content) {
    button.disabled = false;
    button.innerHTML = content;
  }
}