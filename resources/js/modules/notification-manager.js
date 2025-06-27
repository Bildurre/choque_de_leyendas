// Manages PDF generation notifications
export class NotificationManager {
  constructor() {
    this.currentNotification = null;
  }
  
  showPdfGenerating() {
    // Remove existing notification if any
    this.hidePdfGenerating();
    
    const notificationHtml = `
      <div class="pdf-generating-notification">
        <div class="pdf-generating-notification__content">
          <div class="pdf-generating-notification__spinner">
            <svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
          <span class="pdf-generating-notification__text">Generating PDF...</span>
        </div>
      </div>
    `;
    
    const downloadsSection = document.querySelector('.downloads-section');
    if (downloadsSection) {
      downloadsSection.insertAdjacentHTML('afterbegin', notificationHtml);
      this.currentNotification = downloadsSection.querySelector('.pdf-generating-notification');
      
      // Animate in
      requestAnimationFrame(() => {
        if (this.currentNotification) {
          this.currentNotification.classList.add('pdf-generating-notification--visible');
        }
      });
    }
  }
  
  hidePdfGenerating() {
    if (this.currentNotification) {
      this.currentNotification.classList.remove('pdf-generating-notification--visible');
      setTimeout(() => {
        this.currentNotification?.remove();
        this.currentNotification = null;
      }, 300);
    }
  }
}