import './bootstrap';
import '../scss/app.scss';
import initHeaderScroll from './components/public-header';
import initThemeSwitcher from './components/theme-switcher';
import initRandomAccentColor from './components/random-accent-color';

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  initThemeSwitcher();
  initRandomAccentColor();
});