import createHamburgerButton from './hamburguer-button.js';

/**
 * Implementación específica para el sidebar del admin
 */
export default function initSidebar() {
  // El componente auto-detecta el contexto admin y aplica la configuración correcta
  const sidebarToggle = createHamburgerButton();

  if (!sidebarToggle) {
    console.warn('Could not initialize admin sidebar');
    return null;
  }

  return sidebarToggle;
}