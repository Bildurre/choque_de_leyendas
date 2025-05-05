import './bootstrap';
import '../scss/app.scss';
import initHeaderScroll from './components/public-header';

// Initialize components when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  // Initialize header scroll behavior if header exists
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  
  // Initialize other components as needed
});