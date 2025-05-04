/**
 * Sidebar functionality handler
 * Manages sidebar state changes based on window size and user interactions
 */
export function initSidebar() {
  // Create overlay for closing sidebar on mobile
  createOverlay();
  
  // Initial state based on window size
  syncSidebarState();
  
  // Handle window resize events
  window.addEventListener('resize', debounce(syncSidebarState, 100));
}

/**
 * Create overlay for closing sidebar
 */
function createOverlay() {
  if (!document.querySelector('.sidebar-overlay')) {
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    
    const container = document.querySelector('.admin-main-container');
    if (container) {
      container.appendChild(overlay);
      
      // Add event to close sidebar when overlay is clicked
      overlay.addEventListener('click', () => {
        document.body.classList.remove('sidebar-open');
        updateAlpineState(false);
      });
    }
  }
}

/**
 * Synchronize sidebar state with window size
 */
function syncSidebarState() {
  const isMobile = window.innerWidth <= 767;
  document.body.classList.toggle('sidebar-open', !isMobile);
  updateAlpineState(!isMobile);
}

/**
 * Update Alpine.js state if available
 */
function updateAlpineState(isOpen) {
  if (window.Alpine) {
    const bodyData = Alpine.raw(document.body);
    if (bodyData && typeof bodyData.sidebarOpen !== 'undefined') {
      bodyData.sidebarOpen = isOpen;
    }
  }
}

/**
 * Simple debounce function to limit execution frequency
 */
function debounce(func, wait) {
  let timeout;
  return function() {
    const context = this;
    const args = arguments;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
}