// This file will be loaded separately and early
(function() {
  // Check for saved theme preference or use OS preference
  const theme = localStorage.getItem('theme') || 
    (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
  document.documentElement.setAttribute('data-theme', theme);
})();