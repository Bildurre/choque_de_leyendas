// resources/js/services/notification-service.js
class NotificationService {
  constructor() {
    // Singleton pattern para asegurar una única instancia
    if (window.notificationService) {
      return window.notificationService;
    }
    
    window.notificationService = this;
  }
  
  success(message) {
    this.show(message, 'success');
  }
  
  error(message) {
    this.show(message, 'error');
  }
  
  info(message) {
    this.show(message, 'info');
  }
  
  warning(message) {
    this.show(message, 'warning');
  }
  
  show(message, type = 'info') {
    if (window.showNotification) {
      window.showNotification(message, type);
    } else {
      // Fallback: crear notificación temporal si showNotification no está disponible
      this.createTemporaryNotification(message, type);
    }
  }
  
  createTemporaryNotification(message, type) {
    // Crear contenedor si no existe
    let container = document.querySelector('.notifications-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'notifications-container';
      document.body.appendChild(container);
    }
    
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `notification notification--${type} notification--temporary`;
    notification.setAttribute('role', 'alert');
    
    const iconMap = {
      success: 'check-circle',
      error: 'x-circle',
      warning: 'alert-triangle',
      info: 'info'
    };
    
    const iconName = iconMap[type] || 'info';
    
    notification.innerHTML = `
      <div class="notification__content">
        <div class="notification__icon">
          <svg class="icon icon--${iconName}">
            <use href="#icon-${iconName}"></use>
          </svg>
        </div>
        <div class="notification__message">${message}</div>
      </div>
      <button type="button" class="notification__close" aria-label="${window.translations?.notification?.close || 'Cerrar'}">
        <svg class="icon icon--x icon--sm">
          <use href="#icon-x"></use>
        </svg>
      </button>
    `;
    
    // Añadir al contenedor
    container.appendChild(notification);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
      notification.classList.add('notification--dismissing');
      setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    // Botón de cerrar
    const closeBtn = notification.querySelector('.notification__close');
    closeBtn.addEventListener('click', () => {
      notification.classList.add('notification--dismissing');
      setTimeout(() => notification.remove(), 300);
    });
  }
}

// Exportar función de inicialización
export default function initNotificationService() {
  return new NotificationService();
}