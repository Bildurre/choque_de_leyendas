// resources/js/components/pdf-collection/services/NotificationService.js
export default class NotificationService {
  success(message) {
    this.show(message, 'success');
  }
  
  error(message) {
    this.show(message, 'error');
  }
  
  info(message) {
    this.show(message, 'info');
  }
  
  show(message, type = 'info') {
    if (window.showNotification) {
      window.showNotification(message, type);
    } else {
      console.log(`[${type}] ${message}`);
    }
  }
}