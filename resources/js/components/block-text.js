export default function initTextImageHeight() {
  // Function to check if we're on tablet viewport or larger
  function isTabletOrLarger() {
    return window.matchMedia('(min-width: 48rem)').matches; // 768px/16 = 48rem
  }
  
  // Function to adjust image heights
  function adjustImageHeights() {
    // Only execute on tablet or larger
    if (!isTabletOrLarger()) {
      resetImageHeights();
      return;
    }
    
    // Find all text blocks with side images
    const textBlocks = document.querySelectorAll('.block--text');
    
    textBlocks.forEach(textBlock => {
      const contentWrapper = textBlock.querySelector('.block__content-wrapper');
      if (!contentWrapper) return;
      
      // Check if it has side image (not clearfix)
      const hasImageLeft = contentWrapper.classList.contains('has-image-left');
      const hasImageRight = contentWrapper.classList.contains('has-image-right');
      
      // Skip if no side image or if it's clearfix layout
      if (!hasImageLeft && !hasImageRight) return;
      if (contentWrapper.classList.contains('has-image-clearfix-left') || 
          contentWrapper.classList.contains('has-image-clearfix-right')) return;
      
      // Get content and image
      const content = contentWrapper.querySelector('.block__content');
      const imageContainer = contentWrapper.querySelector('.block__image');
      
      if (!content || !imageContainer) return;
      
      // Reset height to get real measurement
      imageContainer.style.maxHeight = '';
      
      // Wait a frame for browser to calculate heights
      requestAnimationFrame(() => {
        // Get content height
        const contentHeight = content.offsetHeight;
        
        // Apply max height to image
        imageContainer.style.maxHeight = contentHeight + 'px';
      });
    });
  }
  
  // Function to reset heights on mobile
  function resetImageHeights() {
    const imageContainers = document.querySelectorAll('.block--text .block__content-wrapper:not(.has-image-clearfix-left):not(.has-image-clearfix-right) .block__image');
    imageContainers.forEach(imageContainer => {
      imageContainer.style.maxHeight = '';
    });
  }
  
  // Execute on load
  adjustImageHeights();
  
  // Execute on window resize
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(adjustImageHeights, 150);
  });
  
  // Execute when images load
  const images = document.querySelectorAll('.block--text .block__content-wrapper:not(.has-image-clearfix-left):not(.has-image-clearfix-right) .block__image img');
  images.forEach(img => {
    if (img.complete) {
      adjustImageHeights();
    } else {
      img.addEventListener('load', adjustImageHeights);
    }
  });
  
  // Observer to detect dynamic DOM changes
  const observer = new MutationObserver(() => {
    adjustImageHeights();
  });
  
  // Observe body changes (in case text blocks are added dynamically)
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