/**
 * Set up auto-dismiss for alert messages
 * @param {number} timeout - Time in ms before hiding alerts
 */
export function setupAlertDismissal(timeout = 5000) {
  const alerts = document.querySelectorAll('.alert');
  
  // Set up auto-dismissal
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => {
        alert.style.display = 'none';
      }, 300); // Transition time
    }, timeout);
    
    // Set up manual close buttons
    const closeButton = alert.querySelector('.btn-close');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 300);
      });
    }
  });
}