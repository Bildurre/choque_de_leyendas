export default function initThemeSwitcher() {
  const themeToggleBtn = document.getElementById('theme-switcher');
  
  if (!themeToggleBtn) return;
  
  // Check for saved theme preference
  const getThemePreference = () => {
    if (localStorage.getItem('theme')) {
      return localStorage.getItem('theme');
    }
    return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
  };
  
  // Apply theme to document
  const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  };
  
  // Apply initial theme
  applyTheme(getThemePreference());
  
  // Toggle theme on button click
  themeToggleBtn.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Apply the new theme
    applyTheme(newTheme);
  });
  
  // Listen for OS theme changes
  window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', e => {
    // Solo cambia el tema si no hay una preferencia guardada
    if (!localStorage.getItem('theme')) {
      const newTheme = e.matches ? 'light' : 'dark';
      applyTheme(newTheme);
    }
  });
}