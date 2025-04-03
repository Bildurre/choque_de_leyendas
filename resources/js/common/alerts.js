// resources/js/common/alerts.js
/**
 * Set up auto-hiding for alert messages
 * @param {string} selector - CSS selector for alert elements
 * @param {number} timeout - Time in ms before hiding alerts
 */
export function setupAlertDismissal(selector = '.alert', timeout = 5000) {
  document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll(selector);
    
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.style.display = 'none';
        }, 300);
      }, timeout);
    });
  });
}