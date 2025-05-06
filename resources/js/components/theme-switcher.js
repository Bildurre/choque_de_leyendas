export default function initThemeSwitcher() {
  const themeToggleBtn = document.getElementById('theme-switcher');
  
  if (!themeToggleBtn) return;
  
  // Toggle theme on button click
  themeToggleBtn.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Apply the new theme
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    // Si hay editores TinyMCE en la página, recargamos para que se apliquen correctamente los estilos
    if (document.querySelector('.tox-tinymce')) {
      // Guardar la posición de desplazamiento actual
      const scrollPosition = window.scrollY;
      localStorage.setItem('scrollPosition', scrollPosition);
      
      // Recargar la página
      window.location.reload();
    }
  });
  
  // Restaurar la posición de desplazamiento después de recargar
  if (localStorage.getItem('scrollPosition')) {
    const scrollPosition = parseInt(localStorage.getItem('scrollPosition'), 10);
    window.scrollTo(0, scrollPosition);
    localStorage.removeItem('scrollPosition');
  }
}