export default function initHeaderScroll() {
  const header = document.querySelector('.public-header');
  let lastScrollY = 0;

  function handleScroll() {
    const currentScrollY = window.scrollY;
    const shouldShowHeader = (currentScrollY <= 0) || (currentScrollY < lastScrollY);
    
    if (shouldShowHeader) {
      header.classList.remove('header--hidden');
    } else {
      header.classList.add('header--hidden');
    }
    
    lastScrollY = currentScrollY;
  }

  // Add scroll event listener
  window.addEventListener('scroll', handleScroll);
}