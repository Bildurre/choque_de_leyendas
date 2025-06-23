export default function initNotifications() {
  // Create notifications container if it doesn't exist
  let container = document.getElementById('notifications-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'notifications-container';
    container.className = 'notifications-container';
    document.body.appendChild(container);
  }

  // Process existing notifications from server (session messages)
  const existingNotifications = document.querySelectorAll('.notification');
  existingNotifications.forEach(notification => {
    // Move to container if not already there
    if (notification.parentElement !== container) {
      container.appendChild(notification);
    }
    setupNotification(notification);
  });

  // Setup individual notification
  function setupNotification(notification) {
    // Add show animation
    setTimeout(() => notification.classList.add('notification--show'), 10);

    // Setup close button
    const closeButton = notification.querySelector('.notification__close');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        closeNotification(notification);
      });
    }

    // Auto-close success notifications after 5 seconds
    if (notification.classList.contains('notification--success')) {
      setTimeout(() => {
        if (document.body.contains(notification)) {
          closeNotification(notification);
        }
      }, 5000); // 5000ms = 5 segundos
    }
  }

  // Close notification with animation
  function closeNotification(notification) {
    notification.classList.remove('notification--show');
    notification.classList.add('notification--closing');
    
    setTimeout(() => {
      notification.remove();
    }, 300);
  }

  // Public API for creating notifications from JavaScript
  window.showNotification = function(message, type = 'success', options = {}) {
    const notification = createNotification(message, type, options);
    container.appendChild(notification);
    setupNotification(notification, options);
    return notification;
  };

  // Create notification element
  function createNotification(message, type, options = {}) {
    const dismissible = options.dismissible !== false;
    const notification = document.createElement('div');
    notification.className = `notification notification--${type}`;
    notification.setAttribute('role', 'alert');

    const iconMap = {
      success: 'check-circle',
      error: 'x-circle',
      warning: 'alert-triangle',
      info: 'info'
    };

    notification.innerHTML = `
      <div class="notification__content">
        <div class="notification__icon">
          ${getIconSvg(iconMap[type] || 'info')}
        </div>
        <div class="notification__message">
          ${message}
        </div>
      </div>
      ${dismissible ? `
        <button type="button" class="notification__close" aria-label="Cerrar notificación">
          ${getIconSvg('x')}
        </button>
      ` : ''}
    `;

    return notification;
  }

  // Get SVG icon
  function getIconSvg(name) {
    const icons = {
      'check-circle': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
      'x-circle': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
      'alert-triangle': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
      'info': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
      'x': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>'
    };

    return icons[name] || icons.info;
  }
}