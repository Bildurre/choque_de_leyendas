export default function initTextImageHeight() {
  // Function to check if we're on tablet viewport or larger
  function isTabletOrLarger() {
    return window.matchMedia('(min-width: 48rem)').matches; // 768px/16 = 48rem
  }
  
  // Function to adjust image heights for grid layouts only
  function adjustImageHeights() {
    // Only run on tablet or larger
    if (!isTabletOrLarger()) {
      resetImageHeights();
      return;
    }
    
    // Find all text blocks
    const textBlocks = document.querySelectorAll('.block--text');
    
    textBlocks.forEach(textBlock => {
      const contentWrapper = textBlock.querySelector('.block__content-wrapper');
      if (!contentWrapper) return;
      
      // Check if it has lateral images (grid layout)
      const hasImageLeft = contentWrapper.classList.contains('has-image-left');
      const hasImageRight = contentWrapper.classList.contains('has-image-right');
      
      // Skip if no lateral images or if it's clearfix layout
      if (!hasImageLeft && !hasImageRight) return;
      
      // Skip clearfix layouts - they handle their own sizing
      const isClearfixLeft = contentWrapper.classList.contains('has-image-clearfix-left');
      const isClearfixRight = contentWrapper.classList.contains('has-image-clearfix-right');
      
      if (isClearfixLeft || isClearfixRight) return;
      
      // Get content and image elements
      const content = contentWrapper.querySelector('.block__content');
      const imageContainer = contentWrapper.querySelector('.block__image');
      
      if (!content || !imageContainer) return;
      
      // Reset height to get real measurement
      imageContainer.style.maxHeight = '';
      
      // Wait a frame for browser to calculate heights
      requestAnimationFrame(() => {
        // Get content height
        const contentHeight = content.offsetHeight;
        
        // Apply max height to image container
        imageContainer.style.maxHeight = contentHeight + 'px';
      });
    });
  }
  
  // Function to reset heights on mobile
  function resetImageHeights() {
    // Only reset grid layout images, not clearfix
    const imageContainers = document.querySelectorAll(
      '.block--text .block__content-wrapper:is(.has-image-left, .has-image-right) .block__image'
    );
    
    imageContainers.forEach(imageContainer => {
      const wrapper = imageContainer.closest('.block__content-wrapper');
      // Skip clearfix layouts
      if (wrapper && (
        wrapper.classList.contains('has-image-clearfix-left') || 
        wrapper.classList.contains('has-image-clearfix-right')
      )) {
        return;
      }
      
      imageContainer.style.maxHeight = '';
    });
  }
  
  // Run on load
  adjustImageHeights();
  
  // Run on window resize
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(adjustImageHeights, 150);
  });
  
  // Run when images load
  const images = document.querySelectorAll(
    '.block--text .block__content-wrapper:is(.has-image-left, .has-image-right) .block__image img'
  );
  
  images.forEach(img => {
    if (img.complete) {
      adjustImageHeights();
    } else {
      img.addEventListener('load', adjustImageHeights);
    }
  });
  
  // Observer for dynamic DOM changes
  const observer = new MutationObserver(() => {
    adjustImageHeights();
  });
  
  // Observe body for dynamic content
  const body = document.querySelector('body');
  if (body) {
    observer.observe(body, { 
      childList: true, 
      subtree: true,
      attributes: true,
      attributeFilter: ['class', 'style']
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