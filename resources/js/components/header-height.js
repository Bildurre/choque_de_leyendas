export default function initHeaderHeight() {
  const updateHeaderHeight = () => {
    const header = document.querySelector('.public-header');
    if (header) {
      const headerHeight = header.offsetHeight;
      document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
    }
  };

  updateHeaderHeight();

  window.addEventListener('resize', updateHeaderHeight);

  document.fonts.ready.then(updateHeaderHeight);
}