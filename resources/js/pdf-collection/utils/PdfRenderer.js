// resources/js/pdf-collection/utils/PdfRenderer.js
export default class PdfRenderer {
  createProgressItem(tempId, displayName) {
    return `
      <div class="pdf-item" data-pdf-temp-id="${tempId}">
        <div class="pdf-item__header">
          <h3 class="pdf-item__title">${displayName}</h3>
          
          <div class="pdf-item__info">
            <div class="pdf-progress-inline">
              <div class="pdf-progress-inline__bar">
                <div class="pdf-progress-inline__bar-fill"></div>
              </div>
              <span class="pdf-progress-inline__text">${window.translations?.pdf?.collection?.generating || 'Generando...'}</span>
            </div>
          </div>
        </div>
        
        <div class="pdf-item__actions">
          <!-- No actions while generating -->
        </div>
      </div>
    `;
  }
  
  createFinalItem(pdf) {
    const formattedDate = this.formatDate(pdf.created_at);
    const urls = this.generateUrls(pdf);
    
    return `
      <div class="pdf-item" data-pdf-id="${pdf.id}">
        <div class="pdf-item__header">
          <h3 class="pdf-item__title">${pdf.display_name}</h3>
          
          <div class="pdf-item__info">
            <span class="pdf-item__size">${pdf.size}</span>
            <span class="pdf-item__separator">•</span>
            <span class="pdf-item__date">${formattedDate}</span>
          </div>
        </div>
        
        <div class="pdf-item__actions">
          ${this.renderDownloadButton(urls.download)}
          ${this.renderViewButton(urls.view)}
          ${this.renderDeleteForm(urls.delete, pdf.id)}
        </div>
      </div>
    `;
  }
  
  formatDate(date) {
    const d = new Date(date);
    return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
  }
  
  generateUrls(pdf) {
    const locale = document.documentElement.lang || 'es';
    
    return {
      download: this.ensureLocaleInUrl(pdf.download_url, locale),
      view: this.ensureLocaleInUrl(pdf.view_url, locale),
      delete: `/${locale}/descargas/eliminar/${pdf.id}`
    };
  }
  
  ensureLocaleInUrl(url, locale) {
    return url.includes(locale) ? url : url.replace('/descargas/', `/${locale}/descargas/`);
  }
  
  renderDownloadButton(url) {
    return `
      <a href="${url}" 
         class="action-button action-button--download action-button--md"
         download="download">
        <span class="icon icon--pdf-download icon--md action-button__icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <path d="M12 18v-6"></path>
            <polyline points="9 15 12 18 15 15"></polyline>
          </svg>
        </span>
        <span class="action-button__text"></span>
      </a>
    `;
  }
  
  renderViewButton(url) {
    return `
      <a href="${url}" 
         class="action-button action-button--view action-button--md"
         target="_blank">
        <span class="icon icon--eye icon--md action-button__icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </span>
        <span class="action-button__text"></span>
      </a>
    `;
  }
  
  renderDeleteForm(url, pdfId) {
    return `
      <form action="${url}" method="POST" class="action-button-form">
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" 
                class="action-button action-button--delete action-button--md"
                data-confirm-message="${window.translations?.pdf?.confirm_delete || '¿Estás seguro de que quieres eliminar este PDF?'}">
          <span class="icon icon--trash icon--md action-button__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              <line x1="10" y1="11" x2="10" y2="17"></line>
              <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
          </span>
          <span class="action-button__text"></span>
        </button>
      </form>
    `;
  }
}