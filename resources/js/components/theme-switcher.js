export default function initThemeSwitcher() {
  const themeToggleBtn = document.getElementById('theme-switcher');
  
  if (!themeToggleBtn) return;
  
  // Theme has already been applied by the theme-detector script,
  // so we don't need to apply it again on initialization
  
  // Toggle theme on button click
  themeToggleBtn.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Apply the new theme
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
  
  // Listen for OS theme changes
  window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', e => {
    // Only change theme if there's no saved preference
    if (!localStorage.getItem('theme')) {
      const newTheme = e.matches ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', newTheme);
    }
  });
}