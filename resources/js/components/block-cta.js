export default function initCtaImageHeight() {
  // Función para verificar si estamos en viewport de tablet o superior
  function isTabletOrLarger() {
    return window.matchMedia('(min-width: 48rem)').matches; // 768px/16 = 48rem
  }
  
  // Función para ajustar la altura de las imágenes
  function adjustImageHeights() {
    // Solo ejecutar en tablet o superior
    if (!isTabletOrLarger()) {
      resetImageHeights();
      return;
    }
    
    // Buscar todos los CTAs con imágenes laterales
    const ctaBlocks = document.querySelectorAll('.block--cta');
    
    ctaBlocks.forEach(ctaBlock => {
      const contentWrapper = ctaBlock.querySelector('.block__content-wrapper');
      if (!contentWrapper) return;
      
      // Verificar si tiene imagen lateral
      const hasImageLeft = contentWrapper.classList.contains('has-image-left');
      const hasImageRight = contentWrapper.classList.contains('has-image-right');
      
      if (!hasImageLeft && !hasImageRight) return;
      
      // Obtener el contenido y la imagen
      const content = contentWrapper.querySelector('.block__content');
      const imageContainer = contentWrapper.querySelector('.block__image');
      
      if (!content || !imageContainer) return;
      
      // Resetear altura para obtener medida real
      imageContainer.style.maxHeight = '';
      
      // Esperar un frame para que el navegador calcule las alturas
      requestAnimationFrame(() => {
        // Obtener la altura del contenido
        const contentHeight = content.offsetHeight;
        
        // Aplicar la altura máxima a la imagen
        imageContainer.style.maxHeight = contentHeight + 'px';
      });
    });
  }
  
  // Función para resetear las alturas en móvil
  function resetImageHeights() {
    const imageContainers = document.querySelectorAll('.block--cta .block__image');
    imageContainers.forEach(imageContainer => {
      imageContainer.style.maxHeight = '';
    });
  }
  
  // Ejecutar al cargar
  adjustImageHeights();
  
  // Ejecutar cuando cambie el tamaño de la ventana
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(adjustImageHeights, 150);
  });
  
  // Ejecutar cuando las imágenes se carguen
  const images = document.querySelectorAll('.block--cta .block__image img');
  images.forEach(img => {
    if (img.complete) {
      adjustImageHeights();
    } else {
      img.addEventListener('load', adjustImageHeights);
    }
  });
  
  // Observer para detectar cambios dinámicos en el DOM
  const observer = new MutationObserver(() => {
    adjustImageHeights();
  });
  
  // Observar cambios en el body (por si se añaden CTAs dinámicamente)
  const body = document.querySelector('body');
  if (body) {
    observer.observe(body, { 
      childList: true, 
      subtree: true,
      attributes: true,
      attributeFilter: ['class']
    });
  }
  
  // Cleanup function
  return () => {
    window.removeEventListener('resize', adjustImageHeights);
    images.forEach(img => {
      img.removeEventListener('load', adjustImageHeights);
    });
    observer.disconnect();
  };
}