/**
 * Set up auto-hiding for alert messages
 * @param {string} selector - CSS selector for alert elements
 * @param {number} timeout - Time in ms before hiding alerts
 */
export default function setupAlertDismissal(selector = '.alert', timeout = 5000) {
  document.addEventListener('DOMContentLoaded', function() {
    // Select all alert elements
    const alerts = document.querySelectorAll(selector);
    
    // Set up auto-dismissal
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 300); // Transition time
      }, timeout);
    });
    
    // Set up manual close buttons
    const closeButtons = document.querySelectorAll(`${selector} .btn-close`);
    closeButtons.forEach(button => {
      button.addEventListener('click', function() {
        const alert = this.closest('.alert');
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 300); // Transition time
      });
    });
  });
}