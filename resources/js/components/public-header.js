import createHamburgerButton from './hamburguer-button.js';

/**
 * Initialize header scroll behavior and mobile menu
 */
export default function initPublicHeader() {
  const header = document.querySelector('.public-header');
  
  if (!header) return null;

  // Inicializar el botón hamburguesa (auto-detecta contexto público)
  const mobileMenuToggle = createHamburgerButton();
  
  // Inicializar el comportamiento de scroll del header
  const scrollHandler = initHeaderScroll(header, mobileMenuToggle);
  
  return { 
    header, 
    mobileMenuToggle, 
    scrollHandler,
    destroy() {
      if (mobileMenuToggle) {
        mobileMenuToggle.destroy();
      }
    }
  };
}

/**
 * Inicializar el comportamiento de scroll del header
 */
function initHeaderScroll(header, mobileMenuToggle) {
  let lastScrollY = 0;
  const scrollThreshold = 200;

  function handleScroll() {
    const currentScrollY = window.scrollY;
    
    // No ocultar el header si el menú está abierto
    if (mobileMenuToggle && mobileMenuToggle.isOpen()) {
      header.classList.remove('header--hidden');
      lastScrollY = currentScrollY;
      return;
    }
    
    // Solo aplicar lógica de ocultado/mostrado si hemos pasado el umbral
    if (currentScrollY > scrollThreshold) {
      const shouldShowHeader = (currentScrollY <= scrollThreshold) || (currentScrollY < lastScrollY);
      
      if (shouldShowHeader) {
        header.classList.remove('header--hidden');
      } else {
        header.classList.add('header--hidden');
      }
    } else {
      // Si estamos por debajo del umbral, siempre mostrar el header
      header.classList.remove('header--hidden');
    }
    
    lastScrollY = currentScrollY;
  }

  // Añadir evento de scroll
  window.addEventListener('scroll', handleScroll);
  
  return handleScroll;
}