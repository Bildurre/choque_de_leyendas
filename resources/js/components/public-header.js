/**
 * Initialize header scroll behavior and mobile menu
 */
export default function initPublicHeader() {
  const header = document.querySelector('.public-header');
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const headerNav = document.querySelector('.header-nav');
  const navLinks = document.querySelectorAll('.nav-link, .nav-dropdown__link');
  
  if (!header) return;
  
  // Header scroll behavior
  let lastScrollY = 0;
  let isMenuOpen = false;
  const scrollThreshold = 200; // Umbral de 200px para activar el comportamiento

  function handleScroll() {
    const currentScrollY = window.scrollY;
    
    // No ocultar el header si el menú está abierto
    if (isMenuOpen) {
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
  
  // Mobile menu functionality
  if (mobileMenuToggle && headerNav) {
    
    // Toggle menu function
    function toggleMobileMenu() {
      isMenuOpen = !isMenuOpen;
      
      if (isMenuOpen) {
        openMobileMenu();
      } else {
        closeMobileMenu();
      }
    }
    
    // Open menu
    function openMobileMenu() {
      mobileMenuToggle.setAttribute('aria-expanded', 'true');
      headerNav.classList.add('is-open');
      
      // Ensure header is visible when menu is open
      header.classList.remove('header--hidden');
    }
    
    // Close menu
    function closeMobileMenu() {
      mobileMenuToggle.setAttribute('aria-expanded', 'false');
      headerNav.classList.remove('is-open');
    }
    
    // Event listeners
    mobileMenuToggle.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      toggleMobileMenu();
    });
    
    // Close menu when a link is clicked (only on mobile)
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        if (isMenuOpen && window.innerWidth < 768) {
          closeMobileMenu();
        }
      });
    });
    
    // Handle escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && isMenuOpen) {
        closeMobileMenu();
      }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        // Close menu if window is resized to tablet or larger
        if (window.innerWidth >= 768 && isMenuOpen) {
          closeMobileMenu();
        }
      }, 250);
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (isMenuOpen && !headerNav.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
        closeMobileMenu();
      }
    });
  }
}