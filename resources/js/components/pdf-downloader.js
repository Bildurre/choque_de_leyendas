// resources/js/components/pdf-downloader.js
export default function initPdfDownloader() {
  const buttons = document.querySelectorAll('[data-download-pdf]');
  if (!buttons.length) return;
  
  // Event delegation para manejar botones dinámicos
  document.addEventListener('click', handleDownloadClick);
  
  function handleDownloadClick(e) {
    const downloadBtn = e.target.closest('[data-download-pdf]');
    if (!downloadBtn) return;
    
    e.preventDefault();
    downloadPdf(downloadBtn);
  }
  
  async function downloadPdf(button) {
    const pdfId = button.dataset.pdfId;
    const entityName = button.dataset.entityName;
    const entityType = button.dataset.entityType;
    
    try {
      // Obtener el locale actual
      const locale = document.documentElement.lang || 'es';
      
      
      let downloadPath;
      if (locale === 'es') {
        downloadPath = 'descargas/descargar';
      } else {
        downloadPath = 'downloads/download';
      }
      
      const downloadUrl = `/${locale}/${downloadPath}/${pdfId}`;

      const link = document.createElement('a');
      link.href = downloadUrl;
      link.download = '';
      link.style.display = 'none';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      
      // Mostrar notificación de éxito
      const entityTypeTranslated = window.translations?.entities?.[entityType] || entityType;
      const message = window.translations?.pdf?.download?.success
        ?.replace(':name', entityName)
        ?.replace(':type', entityTypeTranslated)
        || `${entityTypeTranslated} ${entityName} descargado correctamente`;
      
      if (window.notificationService) {
        window.notificationService.success(message);
      } else {
        console.log('PDF descargado:', message);
      }
      
    } catch (error) {
      console.error('Error de descarga:', error);
      const errorMessage = window.translations?.pdf?.download?.error || 'Error al descargar el PDF';
      
      if (window.notificationService) {
        window.notificationService.error(errorMessage);
      } else {
        console.error(errorMessage);
      }
    }
  }
}