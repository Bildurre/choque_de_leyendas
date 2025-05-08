import './bootstrap';
import '../scss/app.scss';
import initHeaderScroll from './components/public-header';
import initThemeSwitcher from './components/theme-switcher';
import initRandomAccentColor from './components/random-accent-color';
import initSidebar from './components/admin-sidebar';
import initConfirmActions from './components/confirm-action';
import initNotifications from './components/notifications';
import initLanguageTabs from './components/language-tabs';
import initWysiwygEditor from './components/wysiwyg-editor';
import initBlocksManager from './components/blocks-manager';

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebar) {
    initSidebar();
  }
  const blocksContainer = document.getElementById('blocks-container');
  if (blocksContainer) {
    initBlocksManager();
  }
  
  initThemeSwitcher();
  initRandomAccentColor();
  initConfirmActions();
  initNotifications();
  initLanguageTabs();
  initWysiwygEditor();
});