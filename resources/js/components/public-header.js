export default function initHeaderScroll() {
  const header = document.querySelector('.public-header');
  let lastScrollY = 0;
  const scrollThreshold = 200; // Umbral de 200px para activar el comportamiento

  function handleScroll() {
    const currentScrollY = window.scrollY;
    
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
}