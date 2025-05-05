// resources/js/app.js
import './bootstrap';
import '../scss/app.scss';
import initHeaderScroll from './components/public-header';
import initThemeSwitcher from './components/theme-switcher';
import initRandomAccentColor from './components/random-accent-color';

// Initialize components when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  // Initialize header scroll behavior if header exists
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  
  // Initialize theme switcher
  initThemeSwitcher();
  
  // Initialize random accent color
  initRandomAccentColor();
  
  // Initialize other components as needed
});