export default function initCollapsibleSections() {
  // Get all collapsibles
  const collapsibleSections = document.querySelectorAll('.collapsible-section');
  
  if (!collapsibleSections.length) return;
  
  // Function to update icon based on state
  function updateIcon(section) {
    const icon = section.querySelector('.collapsible-section__icon');
    if (!icon) return;
    
    // Icon will be handled by CSS - we don't need to change classes here
  }
  
  // Function to update collapsible state
  function updateCollapsibleState(section, collapsed = null, enableAnimation = false) {
    // If we want to enable animations, remove the class that disables them
    if (enableAnimation) {
      section.classList.remove('collapsible-section--no-animation');
    }
    
    // If collapsed is null, toggle the state
    const newState = collapsed !== null ? collapsed : !section.classList.contains('is-collapsed');
    
    // Update class
    if (newState) {
      section.classList.add('is-collapsed');
    } else {
      section.classList.remove('is-collapsed');
    }
    
    // Update icon
    updateIcon(section);
    
    return newState;
  }
  
  // Setup event listener for each section
  function setupSectionListeners(section) {
    const header = section.querySelector('.collapsible-section__header');
    const sectionId = section.id;
    const accordion = section.closest('.accordion');
    const forceCollapse = section.getAttribute('data-force-collapse') === 'true';
    
    if (!header || !sectionId) return;
    
    header.addEventListener('click', (event) => {
      // Make sure the click wasn't on a link or other interactive element
      if (event.target.closest('a, input, textarea, select')) {
        return;
      }
      
      // Enable animations starting from the first click
      const enableAnimation = true;
      
      const isSidebarAccordion = accordion?.getAttribute('data-is-sidebar') === 'true';
      
      // Update this collapsible's state
      const isNowCollapsed = updateCollapsibleState(section, null, enableAnimation);
      
      // If not part of an accordion or not the sidebar, save state in localStorage
      // Don't save if forceCollapse is true
      if (!accordion || !isSidebarAccordion) {
        if (!forceCollapse) {
          localStorage.setItem(`section-${sectionId}`, isNowCollapsed ? 'collapsed' : 'expanded');
        }
      }
      
      // If in an accordion and expanded, trigger event
      if (accordion && !isNowCollapsed) {
        // Create and trigger custom event
        const event = new CustomEvent('section:opened', {
          detail: { 
            sectionId: sectionId,
            section: section
          }
        });
        accordion.dispatchEvent(event);
      }
    });
  }
  
  // Initialize independent sections (outside accordions)
  function initializeIndependentSections() {
    collapsibleSections.forEach(section => {
      const accordion = section.closest('.accordion');
      const sectionId = section.id;
      const forceCollapse = section.getAttribute('data-force-collapse') === 'true';
      
      if (!sectionId || accordion) return; // Ignore those in accordions
      
      // If forceCollapse is true, always start collapsed
      if (forceCollapse) {
        updateCollapsibleState(section, true, false);
        // Remove any saved state from localStorage
        localStorage.removeItem(`section-${sectionId}`);
      } else {
        // Outside accordion: use localStorage or collapsed by default
        const savedState = localStorage.getItem(`section-${sectionId}`);
        
        // If should be expanded according to localStorage, expand without animation
        if (savedState === 'expanded') {
          updateCollapsibleState(section, false, false);
        }
        // Otherwise, already collapsed by default, do nothing
      }
    });
  }
  
  // Setup all collapsible listeners
  collapsibleSections.forEach(section => {
    setupSectionListeners(section);
  });
  
  // Initialize independent sections state
  initializeIndependentSections();
  
  // Expose function for external use
  window.updateCollapsibleState = updateCollapsibleState;
}