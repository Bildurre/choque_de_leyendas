/**
 * PDF Progress Handler
 * Manages the PDF generation progress UI integrated in the PDF item
 */

export default function initPdfProgress() {
  const form = document.getElementById('generate-pdf-form');
  if (!form) return;
  
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Generate temporary ID and filename
    const tempId = `temp-${Date.now()}`;
    const now = new Date();
    const timestamp = `${now.getFullYear()}${(now.getMonth() + 1).toString().padStart(2, '0')}${now.getDate().toString().padStart(2, '0')}${now.getHours().toString().padStart(2, '0')}${now.getMinutes().toString().padStart(2, '0')}${now.getSeconds().toString().padStart(2, '0')}`;
    const locale = document.documentElement.lang || 'es';
    const filename = `${timestamp}_${locale}.pdf`;
    
    // Add item with progress bar immediately
    const pdfItem = createPdfItemWithProgress(tempId, filename);
    addPdfToList(pdfItem);
    
    // Get the progress bar element from the newly added item
    const itemElement = document.querySelector(`[data-pdf-temp-id="${tempId}"]`);
    const progressBar = itemElement.querySelector('.pdf-progress-inline__bar-fill');
    
    // Start animated progress
    let progress = 0;
    const progressInterval = setInterval(() => {
      progress += Math.random() * 15;
      if (progress > 90) progress = 90;
      progressBar.style.width = progress + '%';
    }, 300);
    
    try {
      // Make AJAX request to generate PDF
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({})
      });
      
      const data = await response.json();
      
      // Stop fake progress
      clearInterval(progressInterval);
      
      if (data.success) {
        // Complete the progress bar
        progressBar.style.width = '100%';
        
        // After a short delay, replace the item with the final version
        setTimeout(() => {
          const finalPdfItem = createPdfItem({
            id: data.pdf_id,
            display_name: filename,
            download_url: data.download_url,
            view_url: data.view_url,
            created_at: now,
            size: data.size || '---'  // Añadir el tamaño desde la respuesta
          });
          
          // Replace the temporary item with the final one
          itemElement.outerHTML = finalPdfItem;
          
          // Clear the collection
          document.dispatchEvent(new CustomEvent('collection:cleared'));
          
          // Show notification
          if (window.showNotification) {
            window.showNotification(window.translations?.pdf?.collection?.generation_complete || 'PDF generado correctamente', 'success');
          }
        }, 500);
        
      } else {
        // Show error in the item
        const progressElement = itemElement.querySelector('.pdf-progress-inline');
        progressElement.innerHTML = `
          <span class="pdf-progress-inline__error">
            ${data.message || window.translations?.pdf?.collection?.generation_failed || 'Error al generar el PDF'}
          </span>
        `;
        
        // Remove the item after 3 seconds
        setTimeout(() => {
          itemElement.remove();
        }, 3000);
      }
      
    } catch (error) {
      clearInterval(progressInterval);
      console.error('Error generating PDF:', error);
      
      // Show error in the item
      const progressElement = itemElement.querySelector('.pdf-progress-inline');
      progressElement.innerHTML = `
        <span class="pdf-progress-inline__error">
          ${window.translations?.pdf?.collection?.generation_failed || 'Error al generar el PDF'}
        </span>
      `;
      
      // Remove the item after 3 seconds
      setTimeout(() => {
        itemElement.remove();
      }, 3000);
    }
  });
  
  /**
   * Add PDF to the list
   */
  const addPdfToList = (pdfItemHtml) => {
    // Look for existing temporary PDFs section
    let temporarySection = null;
    const sections = document.querySelectorAll('.pdf-collection__section');
    
    // Find the section that contains "Tus PDFs Generados"
    sections.forEach(section => {
      const title = section.querySelector('.pdf-collection__section-title');
      if (title && (title.textContent.includes('Tus PDFs Generados') || title.textContent.includes('Your Generated PDFs'))) {
        temporarySection = section;
      }
    });
    
    if (!temporarySection) {
      // Create the section if it doesn't exist
      temporarySection = createTemporaryPdfsSection();
      
      // Insert before the collection section
      const collectionSection = document.querySelector('.pdf-collection__section--temporary');
      if (collectionSection && collectionSection.parentElement) {
        collectionSection.parentElement.insertBefore(temporarySection, collectionSection);
      }
    }
    
    // Find the list container
    const listContainer = temporarySection.querySelector('.pdf-list__items');
    
    if (listContainer) {
      // Add the new item at the beginning
      listContainer.insertAdjacentHTML('afterbegin', pdfItemHtml);
    }
    
    // Remove empty state if exists
    const emptyState = temporarySection.querySelector('.pdf-collection__empty');
    if (emptyState) {
      emptyState.remove();
    }
  };
  
  /**
   * Create temporary PDFs section HTML
   */
  const createTemporaryPdfsSection = () => {
    const section = document.createElement('div');
    section.className = 'pdf-collection__section';
    section.innerHTML = `
      <h2 class="pdf-collection__section-title">${window.translations?.pdf?.collection?.your_pdfs || 'Tus PDFs Generados'}</h2>
      <p class="pdf-collection__section-description">
        ${window.translations?.pdf?.collection?.temporary_description || 'Estos PDFs se eliminarán automáticamente después de 24 horas'}
      </p>
      <div class="pdf-list pdf-list--temporary">
        <div class="pdf-list__items"></div>
      </div>
    `;
    return section;
  };
  
  /**
   * Create a PDF item with progress bar
   */
  const createPdfItemWithProgress = (tempId, displayName) => {
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
  };
  
  /**
   * Create a final PDF item HTML
   */
  const createPdfItem = (pdf) => {
    const date = new Date(pdf.created_at);
    const formattedDate = `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
    
    // Generate the action URL with locale
    const locale = document.documentElement.lang || 'es';
    const downloadUrl = pdf.download_url.includes(locale) ? pdf.download_url : pdf.download_url.replace('/descargas/', `/${locale}/descargas/`);
    const viewUrl = pdf.view_url.includes(locale) ? pdf.view_url : pdf.view_url.replace('/descargas/', `/${locale}/descargas/`);
    const deleteUrl = `/${locale}/descargas/eliminar/${pdf.id}`;
    
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
          <a href="${downloadUrl}" 
             class="action-button action-button--primary action-button--sm"
             download="download">
            <span class="icon icon--pdf-download icon--sm action-button__icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <path d="M12 18v-6"></path>
                <polyline points="9 15 12 18 15 15"></polyline>
              </svg>
            </span>
            <span class="action-button__text"></span>
          </a>
          
          <a href="${viewUrl}" 
             class="action-button action-button--view action-button--sm"
             target="_blank">
            <span class="icon icon--eye icon--sm action-button__icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </span>
            <span class="action-button__text"></span>
          </a>
          
          <form action="${deleteUrl}" method="POST" class="action-button-form">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" 
                    class="action-button action-button--delete action-button--sm"
                    data-confirm-message="${window.translations?.pdf?.confirm_delete || '¿Estás seguro de que quieres eliminar este PDF?'}">
              <span class="icon icon--trash icon--sm action-button__icon">
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
        </div>
      </div>
    `;
  };
}