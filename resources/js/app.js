import './bootstrap';
import '../scss/app.scss';
import initHeaderScroll from './components/public-header';
import initThemeSwitcher from './components/theme-switcher';
import initRandomAccentColor from './components/random-accent-color';
import initSidebar from './components/admin-sidebar';

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebar) {
    initSidebar();
  }
  initThemeSwitcher();
  initRandomAccentColor();
});